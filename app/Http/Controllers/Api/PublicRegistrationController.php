<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Family;
use App\Models\FamilyContact;
use App\Models\Camper;
use App\Models\CampInstance;
use App\Models\Enrollment;
use App\Models\ParentAgreementSignature;
use App\Models\DiscountCode;
use App\Models\CamperInformationSnapshot;
use App\Models\CamperMedicalSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;

class PublicRegistrationController extends Controller
{
    /**
     * Check if user is authenticated
     */
    public function checkAuth()
    {
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]
            ]);
        }

        return response()->json(['authenticated' => false]);
    }

    /**
     * Login user (without redirect)
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', true);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Register new user (without redirect)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create default family
        $family = Family::create([
            'name' => $request->name . ' Family',
            'owner_user_id' => $user->id,
        ]);

        $family->users()->attach($user->id, ['role_in_family' => 'parent']);

        // Log the user in
        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Get camp instance details
     */
    public function getCampInstance($id)
    {
        $instance = CampInstance::with('camp')
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'id' => $instance->id,
            'name' => $instance->name,
            'camp_name' => $instance->camp->display_name ?? $instance->camp->name,
            'year' => $instance->year,
            'description' => $instance->description,
            'start_date' => $instance->start_date ? $instance->start_date->format('Y-m-d') : null,
            'end_date' => $instance->end_date ? $instance->end_date->format('Y-m-d') : null,
            'price' => $instance->price ? (float) $instance->price : null,
            'max_capacity' => $instance->max_capacity,
            'available_spots' => $instance->available_spots,
        ]);
    }

    /**
     * Validate a discount code for camper registration.
     */
    public function validateDiscountCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50',
            'camp_instance_id' => 'required|exists:camp_instances,id',
            'camper_ids' => 'required|array|min:1',
            'camper_ids.*' => 'integer|exists:campers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid discount request.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        $family = $user->defaultFamily();

        $campInstance = CampInstance::findOrFail($request->input('camp_instance_id'));

        $camperIds = collect($request->input('camper_ids', []))
            ->filter()
            ->unique()
            ->values();

        $campers = Camper::whereIn('id', $camperIds)
            ->where('family_id', $family->id)
            ->get();

        if ($campers->count() !== $camperIds->count()) {
            return response()->json([
                'valid' => false,
                'message' => 'One or more campers are not available for this registration.',
            ], 403);
        }

        if (!$campInstance->price || (float) $campInstance->price <= 0) {
            return response()->json([
                'valid' => false,
                'message' => 'This camp session does not have pricing configured yet.',
            ]);
        }

        $code = strtoupper(trim($request->input('code')));

        $discountCode = DiscountCode::where('code', $code)
            ->where('type', 'camper')
            ->first();

        if (!$discountCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid discount code.',
            ]);
        }

        if (!$discountCode->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'This discount code is no longer active.',
            ]);
        }

        $subtotal = $campers->count() * (float) $campInstance->price;
        $discountAmount = round($discountCode->calculateDiscount($subtotal), 2);

        if ($discountAmount <= 0) {
            return response()->json([
                'valid' => false,
                'message' => 'This discount code does not apply to the selected registration.',
            ]);
        }

        $discountAmount = min($discountAmount, $subtotal);
        $finalAmount = max(0, $subtotal - $discountAmount);

        return response()->json([
            'valid' => true,
            'discount_code_id' => $discountCode->id,
            'discount_amount' => round($discountAmount, 2),
            'final_amount' => round($finalAmount, 2),
            'discount_type' => $discountCode->discount_type,
            'discount_value' => (float) $discountCode->discount_value,
            'message' => 'Discount applied successfully.',
            'discount_code' => [
                'id' => $discountCode->id,
                'code' => $discountCode->code,
                'type' => $discountCode->type,
                'discount_type' => $discountCode->discount_type,
                'discount_value' => (float) $discountCode->discount_value,
                'description' => $discountCode->description,
            ],
            'subtotal' => round($subtotal, 2),
        ]);
    }

    /**
     * Get user data (family and campers)
     */
    public function getUserData()
    {
        $user = Auth::user();
        $family = $user->defaultFamily();

        $family->load(['contacts' => function ($query) {
            $query->orderByDesc('is_primary')->orderBy('name');
        }]);

        return response()->json([
            'family' => [
                ...$this->formatFamily($family),
            ],
            'campers' => $family->campers()
                ->get()
                ->map(fn (Camper $camper) => $this->formatCamper($camper))
                ->values(),
            'archived_campers' => $family->campers()
                ->onlyTrashed()
                ->get()
                ->map(fn (Camper $camper) => $this->formatCamper($camper))
                ->values(),
        ]);
    }

    /**
     * Update family information
     */
    public function updateFamily(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'contacts' => 'nullable|array',
            'contacts.*.id' => 'nullable|integer|exists:family_contacts,id',
            'contacts.*.name' => 'required_with:contacts|string|max:255',
            'contacts.*.relation' => 'nullable|string|max:100',
            'contacts.*.relationship' => 'nullable|string|max:100',
            'contacts.*.home_phone' => 'nullable|string|max:20',
            'contacts.*.cell_phone' => 'nullable|string|max:20',
            'contacts.*.work_phone' => 'nullable|string|max:20',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.address' => 'nullable|string|max:255',
            'contacts.*.city' => 'nullable|string|max:255',
            'contacts.*.state' => 'nullable|string|max:100',
            'contacts.*.zip' => 'nullable|string|max:20',
            'contacts.*.authorized_pickup' => 'sometimes|boolean',
            'contacts.*.is_primary' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $family = $user->defaultFamily();
        $hasContactsKey = $request->has('contacts');
        $contactsInput = $hasContactsKey
            ? collect($request->input('contacts', []))
                ->map(fn ($contact) => $this->sanitizeFamilyContactInput((array) $contact))
                ->filter(fn ($contact) => $contact['name'] !== '' || $contact['home_phone'] !== '' || $contact['cell_phone'] !== '' || $contact['email'] !== '' || $contact['address'] !== '')
                ->values()
            : collect();

        DB::transaction(function () use ($family, $request, $hasContactsKey, $contactsInput) {
            $family->update($request->only([
                'name', 'phone', 'address', 'city', 'state', 'zip_code',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'
            ]));

            if (! $hasContactsKey) {
                return;
            }

            if ($contactsInput->isEmpty()) {
                $family->contacts()->delete();
                $family->update([
                    'emergency_contact_name' => null,
                    'emergency_contact_phone' => null,
                    'emergency_contact_relationship' => null,
                ]);

                return;
            }

            $updatedContacts = collect();
            $idsToKeep = [];

            foreach ($contactsInput as $index => $contactData) {
                $contactId = $contactData['id'];
                $payload = $contactData;
                unset($payload['id']);
                $payload['family_id'] = $family->id;

                if ($contactId) {
                    $contact = $family->contacts()->where('id', $contactId)->first();
                    if (! $contact) {
                        throw ValidationException::withMessages([
                            "contacts.$index.id" => 'Invalid contact reference.',
                        ]);
                    }
                    $contact->update($payload);
                } else {
                    $contact = $family->contacts()->create($payload);
                }

                $updatedContacts->push($contact);
                $idsToKeep[] = $contact->id;
            }

            if ($idsToKeep) {
                $family->contacts()->whereNotIn('id', $idsToKeep)->delete();
            } else {
                $family->contacts()->delete();
            }

            if ($updatedContacts->isEmpty()) {
                $family->update([
                    'emergency_contact_name' => null,
                    'emergency_contact_phone' => null,
                    'emergency_contact_relationship' => null,
                ]);

                return;
            }

            $primaryContact = $updatedContacts->firstWhere('is_primary', true) ?? $updatedContacts->first();

            foreach ($updatedContacts as $contact) {
                $contact->is_primary = $contact->id === $primaryContact->id;
                $contact->save();
            }

            $family->update([
                'emergency_contact_name' => $primaryContact->name,
                'emergency_contact_phone' => $primaryContact->home_phone,
                'emergency_contact_relationship' => $primaryContact->relation,
            ]);
        });

        $family->refresh()->load(['contacts' => function ($query) {
            $query->orderByDesc('is_primary')->orderBy('name');
        }]);

        return response()->json([
            'message' => 'Family information updated successfully',
            'family' => [
                ...$this->formatFamily($family),
            ]
        ]);
    }

    /**
     * Create or update camper
     */
    public function saveCamper(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:campers,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'grade' => 'nullable|integer|min:1|max:12',
            't_shirt_size' => 'nullable|string|max:50',
            'biological_gender' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $family = $user->defaultFamily();

        $year = $this->defaultYear();
        $camperData = $request->only(['first_name', 'last_name', 'date_of_birth', 'biological_gender']);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('camper-photos', 'public');
            $camperData['photo_path'] = $photoPath;
        }

        if ($request->has('id')) {
            $camper = Camper::findOrFail($request->id);
            if ($camper->family_id !== $family->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $camper->update($camperData);
        } else {
            $camperData['family_id'] = $family->id;
            $camper = Camper::create($camperData);
        }

        $snapshot = $this->syncCamperInformationSnapshot(
            $camper,
            [
                'grade' => $request->input('grade'),
                't_shirt_size' => $request->input('t_shirt_size'),
            ],
            $year
        );

        return response()->json([
            'message' => 'Camper saved successfully',
            'camper' => array_merge(
                $this->formatCamper($camper),
                [
                    'grade' => Arr::get($snapshot->data, 'camper.grade'),
                    't_shirt_size' => Arr::get($snapshot->data, 'camper.t_shirt_size'),
                ]
            ),
        ]);
    }

    /**
     * Soft delete camper
     */
    public function deleteCamper($id)
    {
        $user = Auth::user();
        $family = $user->defaultFamily();

        $camper = Camper::where('id', $id)
            ->where('family_id', $family->id)
            ->firstOrFail();

        $hasUpcomingEnrollment = $camper->enrollments()
            ->whereNull('deleted_at')
            ->whereNotIn('status', ['cancelled'])
            ->whereHas('campInstance', function ($query) {
                $query->where('start_date', '>=', now()->toDateString());
            })
            ->exists();

        if ($hasUpcomingEnrollment) {
            return response()->json([
                'error' => 'This camper is registered for an upcoming camp and cannot be removed.',
            ], 422);
        }

        $camper->delete();

        return response()->json([
            'message' => 'Camper removed successfully',
        ]);
    }

    /**
     * Restore soft deleted camper
     */
    public function restoreCamper($id)
    {
        $user = Auth::user();
        $family = $user->defaultFamily();

        $camper = Camper::onlyTrashed()
            ->where('id', $id)
            ->where('family_id', $family->id)
            ->firstOrFail();

        $camper->restore();
        $camper->refresh();

        return response()->json([
            'message' => 'Camper restored successfully',
            'camper' => $this->formatCamper($camper),
        ]);
    }

    /**
     * Create enrollments
     */
    public function createEnrollments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enrollments' => 'required|array|min:1',
            'enrollments.*.camper_id' => 'required|exists:campers,id',
            'enrollments.*.camp_instance_id' => 'required|exists:camp_instances,id',
            'payment_method' => 'required|in:stripe,cash_check',
            'discount' => 'nullable|array',
            'discount.code' => 'required_with:discount|string|max:50',
            'discount.discount_code_id' => 'required_with:discount|integer|exists:discount_codes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $family = $user->defaultFamily();
        $discountInput = $request->input('discount');

        DB::beginTransaction();
        try {
            $enrollments = [];
            $totalAmount = 0;

            $parentSignatureCache = [];
            $informationSnapshotCache = [];
            $medicalSnapshotCache = [];

            foreach ($request->enrollments as $enrollmentData) {
                $camper = Camper::findOrFail($enrollmentData['camper_id']);
                if ($camper->family_id !== $family->id) {
                    throw new \Exception('Unauthorized camper access');
                }

                $campInstance = CampInstance::findOrFail($enrollmentData['camp_instance_id']);
                $campYear = $campInstance->year ?? (int) now()->year;

                $parentSignature = $parentSignatureCache[$campYear] ?? ParentAgreementSignature::where('user_id', $user->id)
                    ->where('year', $campYear)
                    ->orderByDesc('signed_at')
                    ->first();

                if (!$parentSignature) {
                    throw new \Exception('Annual parent agreement not completed for ' . $campYear . '. Please review and sign the required forms.');
                }

                $parentSignatureCache[$campYear] = $parentSignature;

                $snapshotKey = "{$camper->id}-{$campYear}";
                $informationSnapshot = $informationSnapshotCache[$snapshotKey] ?? CamperInformationSnapshot::where('camper_id', $camper->id)
                    ->where('year', $campYear)
                    ->first();
                $medicalSnapshot = $medicalSnapshotCache[$snapshotKey] ?? CamperMedicalSnapshot::where('camper_id', $camper->id)
                    ->where('year', $campYear)
                    ->first();

                if (!$informationSnapshot || !$medicalSnapshot) {
                    throw new \Exception("Annual information is incomplete for {$camper->first_name} {$camper->last_name}. Please review and confirm their details.");
                }

                $informationSnapshotCache[$snapshotKey] = $informationSnapshot;
                $medicalSnapshotCache[$snapshotKey] = $medicalSnapshot;

                // Check if already enrolled
                $existing = Enrollment::where('camper_id', $camper->id)
                    ->where('camp_instance_id', $campInstance->id)
                    ->first();

                // Calculate price in cents
                $price = $campInstance->price ? (int)round($campInstance->price * 100) : 0;

                if ($existing) {
                    $existing->update([
                        'information_snapshot_id' => $informationSnapshot->id,
                        'medical_snapshot_id' => $medicalSnapshot->id,
                        'parent_signature_id' => $parentSignature->id,
                    ]);

                    $enrollments[] = $existing;
                    continue;
                }

                // Determine status based on payment method
                $status = $request->payment_method === 'cash_check' 
                    ? 'registered_awaiting_payment' 
                    : 'pending';

                $enrollment = Enrollment::create([
                    'camper_id' => $camper->id,
                    'camp_instance_id' => $campInstance->id,
                    'information_snapshot_id' => $informationSnapshot->id,
                    'medical_snapshot_id' => $medicalSnapshot->id,
                    'parent_signature_id' => $parentSignature->id,
                    'status' => $status,
                    'balance_cents' => $price,
                    'amount_paid_cents' => 0,
                    'enrolled_at' => now(),
                ]);

                $enrollments[] = $enrollment;
                $totalAmount += $price;
            }

            // Ensure we have at least one enrollment
            if (empty($enrollments)) {
                throw new \Exception('No enrollments were created. Please check that campers are selected and the camp instance is valid.');
            }

            $discountCents = 0;

            if (!empty($discountInput)) {
                $discountCode = DiscountCode::where('id', $discountInput['discount_code_id'] ?? null)
                    ->where('code', strtoupper(trim($discountInput['code'] ?? '')))
                    ->where('type', 'camper')
                    ->first();

                if (!$discountCode) {
                    throw new \Exception('Invalid discount code provided.');
                }

                if (!$discountCode->isValid()) {
                    throw new \Exception('The discount code is no longer active.');
                }

                $subtotalCents = collect($enrollments)->sum('balance_cents');

                if ($subtotalCents <= 0) {
                    throw new \Exception('Unable to apply discount to zero-total registration.');
                }

                $calculatedDiscount = $discountCode->calculateDiscount($subtotalCents / 100);
                $discountCents = (int) round($calculatedDiscount * 100);

                if ($discountCents <= 0) {
                    throw new \Exception('Discount amount is zero for this registration.');
                }

                if ($discountCents > $subtotalCents) {
                    $discountCents = $subtotalCents;
                }

                $perEnrollmentDiscount = intdiv($discountCents, count($enrollments));
                $remainder = $discountCents % count($enrollments);

                foreach ($enrollments as $index => $enrollment) {
                    $deduction = $perEnrollmentDiscount + ($index < $remainder ? 1 : 0);
                    $newBalance = max(0, $enrollment->balance_cents - $deduction);

                    $enrollment->update([
                        'balance_cents' => $newBalance,
                        'discount_code_id' => $discountCode->id,
                        'discount_cents' => $deduction,
                    ]);

                    $enrollment->balance_cents = $newBalance;
                    $enrollment->discount_code_id = $discountCode->id;
                    $enrollment->discount_cents = $deduction;
                }

                $discountCode->incrementUsage();
            }

            $totalAmount = collect($enrollments)->sum('balance_cents');

            DB::commit();

            return response()->json([
                'message' => 'Enrollments created successfully',
                'enrollment_ids' => array_map(fn($e) => $e->id, $enrollments),
                'total_amount_cents' => $totalAmount,
                'discount_cents' => $discountCents,
                'discount_message' => $discountCents > 0 ? 'Discount applied successfully.' : null,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create payment intent for enrollments
     */
    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'enrollment_ids' => 'required|array',
            'enrollment_ids.*' => 'exists:enrollments,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            \Log::warning('Payment intent validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'error' => 'Validation failed',
                'message' => 'Please check your input and try again.',
                'errors' => $validator->errors()
            ], 400);
        }

        // Check if Stripe secret key is configured
        $stripeSecret = config('services.stripe.secret');
        if (!$stripeSecret) {
            \Log::error('Stripe secret key not configured');
            return response()->json(['error' => 'Payment processing is not configured. Please contact support.'], 500);
        }

        try {
            Stripe::setApiKey($stripeSecret);

            $amount = $request->input('amount');
            $amountCents = (int) ($amount * 100);

            $user = Auth::user();
            $family = $user->defaultFamily();

            // Get enrollment details to extract camp session information
            $enrollments = Enrollment::whereIn('id', $request->enrollment_ids)
                ->whereHas('camper', function ($query) use ($family) {
                    $query->where('family_id', $family->id);
                })
                ->with(['campInstance.camp', 'camper'])
                ->get();

            if ($enrollments->count() !== count($request->enrollment_ids)) {
                return response()->json([
                    'error' => 'Invalid enrollment selection.',
                ], 403);
            }

            $expectedAmountCents = (int) $enrollments->sum('balance_cents');

            if ($expectedAmountCents <= 0) {
                return response()->json([
                    'error' => 'There is no payment due for the selected enrollments.',
                ], 422);
            }

            if ($amountCents !== $expectedAmountCents) {
                return response()->json([
                    'error' => 'Payment amount mismatch. Please refresh and try again.',
                ], 422);
            }

            // Get camp instance info from first enrollment (assuming all enrollments are for same camp)
            $campInstance = $enrollments->first()?->campInstance;
            $campName = $campInstance?->camp->display_name ?? $campInstance?->camp->name ?? 'Unknown Camp';
            $sessionName = $campInstance?->display_name ?? $campInstance?->name ?? 'Unknown Session';
            
            // Get camper names
            $camperNames = $enrollments->map(function ($enrollment) {
                return $enrollment->camper->first_name . ' ' . $enrollment->camper->last_name;
            })->implode(', ');

            $metadata = [
                'type' => 'camp_enrollment',
                'enrollment_ids' => implode(',', $request->enrollment_ids),
                'camp_name' => $campName,
                'session_name' => $sessionName,
                'camp_instance_id' => $campInstance?->id,
                'camper_names' => $camperNames,
            ];

            // Add customer info to metadata
            if ($request->input('customer_name')) {
                $metadata['customer_name'] = $request->input('customer_name');
            }
            if ($request->input('customer_email')) {
                $metadata['customer_email'] = $request->input('customer_email');
            }
            if ($request->input('customer_phone')) {
                $metadata['customer_phone'] = $request->input('customer_phone');
            }

            // Create Stripe Customer
            $stripeCustomer = null;
            if ($request->input('customer_email')) {
                $customerData = [
                    'email' => $request->input('customer_email'),
                    'metadata' => [
                        'source' => 'camp_enrollment',
                    ],
                ];

                if ($request->input('customer_name')) {
                    $customerData['name'] = $request->input('customer_name');
                }

                if ($request->input('customer_phone')) {
                    $customerData['phone'] = $request->input('customer_phone');
                }

                $stripeCustomer = Customer::create($customerData);
            }

            $paymentIntentData = [
                'amount' => $amountCents,
                'currency' => 'usd',
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ];

            // Attach customer to payment intent
            if ($stripeCustomer) {
                $paymentIntentData['customer'] = $stripeCustomer->id;
                $paymentIntentData['receipt_email'] = $request->input('customer_email');
            }

            // Add description with camp session info
            $description = 'Camp Registration: ' . $sessionName;
            if ($camperNames) {
                $description .= ' - ' . $camperNames;
            }
            $paymentIntentData['description'] = $description;

            $paymentIntent = PaymentIntent::create($paymentIntentData);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API error', [
                'error' => $e->getMessage(),
                'type' => get_class($e),
                'stripe_error' => $e->getStripeError() ? $e->getStripeError()->toArray() : null,
                'amount' => $request->input('amount'),
            ]);

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Payment processing failed. Please try again or contact support.'
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Payment intent creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'amount' => $request->input('amount'),
            ]);

            return response()->json([
                'error' => 'Failed to create payment intent',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm payment for enrollments
     */
    public function confirmPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_intent_id' => 'required|string',
            'enrollment_ids' => 'required|array',
            'enrollment_ids.*' => 'exists:enrollments,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json(['error' => 'Payment not completed'], 400);
            }

            $user = Auth::user();
            $family = $user->defaultFamily();

            DB::beginTransaction();

            $enrollments = Enrollment::whereIn('id', $request->enrollment_ids)
                ->whereHas('camper', function ($query) use ($family) {
                    $query->where('family_id', $family->id);
                })
                ->get();

            $paymentAmount = $paymentIntent->amount;

            foreach ($enrollments as $enrollment) {
                $enrollment->update([
                    'status' => 'registered_fully_paid',
                    'amount_paid_cents' => $enrollment->balance_cents,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Payment confirmed and enrollments updated',
                'enrollments' => $enrollments->map(function ($e) {
                    return [
                        'id' => $e->id,
                        'status' => $e->status,
                    ];
                }),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment confirmation failed', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $request->payment_intent_id,
            ]);

            return response()->json([
                'error' => 'Failed to confirm payment'
            ], 500);
        }
    }

    /**
     * Format camper data for API responses.
     */
    protected function formatFamily(Family $family): array
    {
        $contacts = $family->contacts
            ->map(fn (FamilyContact $contact) => $this->formatFamilyContactData($contact))
            ->values();

        if ($contacts->isEmpty() && $family->emergency_contact_name) {
            $contacts = collect([
                $this->formatFamilyContactData([
                    'name' => $family->emergency_contact_name,
                    'relation' => $family->emergency_contact_relationship,
                    'home_phone' => $family->emergency_contact_phone,
                    'authorized_pickup' => false,
                    'is_primary' => true,
                ]),
            ]);
        }

        return [
            'id' => $family->id,
            'name' => $family->name,
            'phone' => $family->phone,
            'address' => $family->address,
            'city' => $family->city,
            'state' => $family->state,
            'zip_code' => $family->zip_code,
            'emergency_contact_name' => $family->emergency_contact_name,
            'emergency_contact_phone' => $family->emergency_contact_phone,
            'emergency_contact_relationship' => $family->emergency_contact_relationship,
            'emergency_contacts' => $contacts->values()->all(),
        ];
    }

    protected function formatFamilyContactData($contact): array
    {
        if ($contact instanceof FamilyContact) {
            return [
                'id' => $contact->id,
                'name' => $contact->name ?? '',
                'relation' => $contact->relation ?? '',
                'home_phone' => $contact->home_phone ?? '',
                'cell_phone' => $contact->cell_phone ?? '',
                'work_phone' => $contact->work_phone ?? '',
                'email' => $contact->email ?? '',
                'address' => $contact->address ?? '',
                'city' => $contact->city ?? '',
                'state' => $contact->state ?? '',
                'zip' => $contact->zip ?? '',
                'authorized_pickup' => (bool) $contact->authorized_pickup,
                'is_primary' => (bool) $contact->is_primary,
                'created_at' => optional($contact->created_at)->toIso8601String(),
                'updated_at' => optional($contact->updated_at)->toIso8601String(),
            ];
        }

        $data = is_array($contact) ? $contact : [];

        return [
            'id' => $data['id'] ?? null,
            'name' => $data['name'] ?? '',
            'relation' => $data['relation'] ?? ($data['relationship'] ?? ''),
            'home_phone' => $data['home_phone'] ?? ($data['phone'] ?? ''),
            'cell_phone' => $data['cell_phone'] ?? ($data['mobile_phone'] ?? ''),
            'work_phone' => $data['work_phone'] ?? '',
            'email' => $data['email'] ?? '',
            'address' => $data['address'] ?? '',
            'city' => $data['city'] ?? '',
            'state' => $data['state'] ?? '',
            'zip' => $data['zip'] ?? '',
            'authorized_pickup' => (bool) ($data['authorized_pickup'] ?? false),
            'is_primary' => (bool) ($data['is_primary'] ?? true),
            'created_at' => $data['created_at'] ?? null,
            'updated_at' => $data['updated_at'] ?? null,
        ];
    }

    protected function sanitizeFamilyContactInput(array $contact): array
    {
        $sanitizeString = fn ($value) => trim((string) ($value ?? ''));

        return [
            'id' => $contact['id'] ?? null,
            'name' => $sanitizeString($contact['name'] ?? ''),
            'relation' => $sanitizeString($contact['relation'] ?? ($contact['relationship'] ?? '')),
            'home_phone' => $sanitizeString($contact['home_phone'] ?? ''),
            'cell_phone' => $sanitizeString($contact['cell_phone'] ?? ''),
            'work_phone' => $sanitizeString($contact['work_phone'] ?? ''),
            'email' => $sanitizeString($contact['email'] ?? ''),
            'address' => $sanitizeString($contact['address'] ?? ''),
            'city' => $sanitizeString($contact['city'] ?? ''),
            'state' => $sanitizeString($contact['state'] ?? ''),
            'zip' => $sanitizeString($contact['zip'] ?? ''),
            'authorized_pickup' => (bool) ($contact['authorized_pickup'] ?? false),
            'is_primary' => (bool) ($contact['is_primary'] ?? false),
        ];
    }

    protected function formatCamper(Camper $camper): array
    {
        $hasUpcomingEnrollment = $camper->enrollments()
            ->whereNull('deleted_at')
            ->whereNotIn('status', ['cancelled'])
            ->whereHas('campInstance', function ($query) {
                $query->where('start_date', '>=', now()->toDateString());
            })
            ->exists();

        $year = $this->defaultYear();

        return [
            'id' => $camper->id,
            'first_name' => $camper->first_name,
            'last_name' => $camper->last_name,
            'date_of_birth' => $camper->date_of_birth ? $camper->date_of_birth->format('Y-m-d') : null,
            'grade' => $camper->gradeForYear($year),
            't_shirt_size' => $camper->tShirtSizeForYear($year),
            'photo_url' => $camper->photo_url,
            'has_upcoming_enrollment' => $hasUpcomingEnrollment,
            'deleted_at' => optional($camper->deleted_at)?->toDateTimeString(),
            'biological_gender' => $camper->biological_gender,
        ];
    }

    protected function defaultYear(): int
    {
        return (int) (config('annual_forms.default_year') ?? now()->year);
    }

    protected function syncCamperInformationSnapshot(Camper $camper, array $attributes, int $year): CamperInformationSnapshot
    {
        $snapshot = CamperInformationSnapshot::firstOrNew([
            'camper_id' => $camper->id,
            'year' => $year,
        ]);

        $existing = $snapshot->data ?? [];
        $camperData = array_merge(
            [
                'first_name' => $camper->first_name,
                'last_name' => $camper->last_name,
                'date_of_birth' => optional($camper->date_of_birth)->format('Y-m-d'),
            ],
            Arr::get($existing, 'camper', [])
        );

        if (array_key_exists('grade', $attributes)) {
            $camperData['grade'] = $attributes['grade'];
        }

        if (array_key_exists('t_shirt_size', $attributes)) {
            $camperData['t_shirt_size'] = $attributes['t_shirt_size'];
        }

        Arr::set($existing, 'camper', $camperData);

        $snapshot->fill([
            'form_version' => $snapshot->form_version ?? "{$year}.1",
            'data' => $existing,
            'data_hash' => hash('sha256', json_encode($existing)),
        ]);

        $snapshot->captured_at = now();
        $snapshot->captured_by_user_id = Auth::id();
        $snapshot->save();

        return $snapshot;
    }
}

