<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Camper;
use App\Models\CampInstance;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ParentOnboardingController extends Controller
{
    /**
     * Get initial data for onboarding
     */
    public function getInitialData()
    {
        $user = Auth::user();
        $family = $user->defaultFamily();
        
        return response()->json([
            'family' => [
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
                'insurance_provider' => $family->insurance_provider,
                'insurance_policy_number' => $family->insurance_policy_number,
            ],
            'campers' => $family->campers->map(function ($camper) {
                return [
                    'id' => $camper->id,
                    'first_name' => $camper->first_name,
                    'last_name' => $camper->last_name,
                    'date_of_birth' => $camper->date_of_birth ? $camper->date_of_birth->format('Y-m-d') : null,
                    'grade' => $camper->grade,
                    't_shirt_size' => $camper->t_shirt_size,
                    'photo_url' => $camper->photo_url,
                ];
            }),
            'campSessions' => CampInstance::where('is_active', true)
                ->where('start_date', '>=', now()->toDateString())
                ->with('camp')
                ->orderBy('start_date')
                ->get()
                ->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'name' => $session->name,
                        'camp_name' => $session->camp->name,
                        'start_date' => $session->start_date->format('Y-m-d'),
                        'end_date' => $session->end_date->format('Y-m-d'),
                        'price' => $session->price ? (float) $session->price : null,
                        'max_capacity' => $session->max_capacity,
                    ];
                }),
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
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $family = $user->defaultFamily();

        $family->update($request->only([
            'name', 'phone', 'address', 'city', 'state', 'zip_code',
            'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
            'insurance_provider', 'insurance_policy_number'
        ]));

        return response()->json(['message' => 'Family information updated successfully', 'family' => $family]);
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
            'grade' => 'required|integer|min:1|max:12',
            't_shirt_size' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $family = $user->defaultFamily();

        $data = $request->only(['first_name', 'last_name', 'date_of_birth', 'grade', 't_shirt_size']);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('camper-photos', 'public');
            $data['photo_path'] = $photoPath;
        }

        if ($request->has('id')) {
            $camper = Camper::findOrFail($request->id);
            if ($camper->family_id !== $family->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $camper->update($data);
        } else {
            $data['family_id'] = $family->id;
            $camper = Camper::create($data);
        }

        return response()->json([
            'message' => 'Camper saved successfully',
            'camper' => [
                'id' => $camper->id,
                'first_name' => $camper->first_name,
                'last_name' => $camper->last_name,
                'date_of_birth' => $camper->date_of_birth ? $camper->date_of_birth->format('Y-m-d') : null,
                'grade' => $camper->grade,
                't_shirt_size' => $camper->t_shirt_size,
                'photo_url' => $camper->photo_url,
            ]
        ]);
    }

    /**
     * Delete camper
     */
    public function deleteCamper($id)
    {
        $user = Auth::user();
        $family = $user->defaultFamily();
        
        $camper = Camper::findOrFail($id);
        if ($camper->family_id !== $family->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $camper->delete();
        return response()->json(['message' => 'Camper deleted successfully']);
    }

    /**
     * Create enrollments and process payment
     */
    public function createEnrollments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enrollments' => 'required|array|min:1',
            'enrollments.*.camper_id' => 'required|exists:campers,id',
            'enrollments.*.camp_instance_id' => 'required|exists:camp_instances,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $family = $user->defaultFamily();

        DB::beginTransaction();
        try {
            $enrollments = [];
            $totalAmount = 0;

            foreach ($request->enrollments as $enrollmentData) {
                $camper = Camper::findOrFail($enrollmentData['camper_id']);
                if ($camper->family_id !== $family->id) {
                    throw new \Exception('Unauthorized camper access');
                }

                $campInstance = CampInstance::findOrFail($enrollmentData['camp_instance_id']);
                
                // Check if already enrolled
                $existing = Enrollment::where('camper_id', $camper->id)
                    ->where('camp_instance_id', $campInstance->id)
                    ->first();

                if ($existing) {
                    continue; // Skip if already enrolled
                }

                // Calculate price in cents (handle both decimal and integer formats)
                $price = $campInstance->price ? (int)round($campInstance->price * 100) : 0;

                $enrollment = Enrollment::create([
                    'camper_id' => $camper->id,
                    'camp_instance_id' => $campInstance->id,
                    'status' => 'pending',
                    'balance_cents' => $price,
                    'amount_paid_cents' => 0,
                    'enrolled_at' => now(),
                ]);

                $enrollments[] = $enrollment;
                $totalAmount += $price;
            }

            DB::commit();

            // Return enrollment IDs for payment processing
            return response()->json([
                'message' => 'Enrollments created successfully',
                'enrollments' => array_map(fn($e) => $e->id, $enrollments),
                'total_amount_cents' => $totalAmount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Complete onboarding
     */
    public function completeOnboarding()
    {
        $user = Auth::user();
        $user->update(['onboarding_complete' => true]);

        return response()->json(['message' => 'Onboarding completed successfully']);
    }
}
