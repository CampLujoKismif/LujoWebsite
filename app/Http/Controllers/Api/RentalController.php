<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalReservation;
use App\Models\RentalPricing;
use App\Models\RentalBlackoutDate;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\PaymentMethod;
use App\Mail\RentalConfirmed;
use App\Mail\RentalAdminNotification;
use App\Mail\RentalSubmissionAdminNotification;
use App\Models\User;

class RentalController extends Controller
{
    /**
     * Get availability for a specific month.
     */
    public function getAvailability(Request $request, $year, $month): JsonResponse
    {
        $validator = Validator::make(['year' => $year, 'month' => $month], [
            'year' => 'required|integer|min:2024|max:2030',
            'month' => 'required|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        // Get the first and last day of the month
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
        
        // Get all active reservations that overlap with this month
        $reservations = RentalReservation::active()
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                      ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                          $q->where('start_date', '<=', $startOfMonth)
                            ->where('end_date', '>=', $endOfMonth);
                      });
            })
            ->get();

        // Get all active camp sessions that overlap with this month
        $campSessions = \App\Models\CampInstance::where('is_active', true)
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                      ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                          $q->where('start_date', '<=', $startOfMonth)
                            ->where('end_date', '>=', $endOfMonth);
                      });
            })
            ->get();

        // Get all active blackout dates that overlap with this month
        $blackoutDates = RentalBlackoutDate::where('is_active', true)
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                      ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                          $q->where('start_date', '<=', $startOfMonth)
                            ->where('end_date', '>=', $endOfMonth);
                      });
            })
            ->get();

        // Create availability array for the month
        $availability = [];
        $currentDate = $startOfMonth->copy();
        
        while ($currentDate->lte($endOfMonth)) {
            $dateString = $currentDate->toDateString();
            $isAvailable = true;
            $isPast = $currentDate->isPast();
            $blockedReason = null;
            
            // Check if this date is blocked by any reservation
            foreach ($reservations as $reservation) {
                if ($currentDate->between($reservation->start_date, $reservation->end_date)) {
                    $isAvailable = false;
                    $blockedReason = 'reserved';
                    break;
                }
            }
            
            // Check if this date is blocked by any camp session
            if ($isAvailable) {
                foreach ($campSessions as $campSession) {
                    if ($currentDate->between($campSession->start_date, $campSession->end_date)) {
                        $isAvailable = false;
                        $blockedReason = 'camp_session';
                        break;
                    }
                }
            }
            
            // Check if this date is blocked by any blackout date
            if ($isAvailable) {
                foreach ($blackoutDates as $blackout) {
                    if ($currentDate->between($blackout->start_date, $blackout->end_date)) {
                        $isAvailable = false;
                        $blockedReason = 'blackout';
                        break;
                    }
                }
            }
            
            $availability[$dateString] = [
                'date' => $dateString,
                'available' => $isAvailable && !$isPast,
                'is_past' => $isPast,
                'blocked_reason' => $blockedReason,
            ];
            
            $currentDate->addDay();
        }

        return response()->json([
            'year' => $year,
            'month' => $month,
            'availability' => $availability,
        ]);
    }

    /**
     * Validate a discount code.
     */
    public function validateDiscountCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50',
            'type' => 'required|in:rental,camper',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $code = strtoupper(trim($request->input('code')));
        $type = $request->input('type');

        $discountCode = DiscountCode::where('code', $code)
            ->where('type', $type)
            ->first();

        if (!$discountCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid discount code',
            ]);
        }

        if (!$discountCode->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Discount code is expired or inactive',
            ]);
        }

        // Calculate discount amount
        $total = $request->input('total', 0);
        $discountAmount = $discountCode->calculateDiscount($total);

        return response()->json([
            'valid' => true,
            'discount_code_id' => $discountCode->id,
            'discount_amount' => $discountAmount,
            'discount_type' => $discountCode->discount_type,
            'discount_value' => $discountCode->discount_value,
            'discount_code' => [
                'id' => $discountCode->id,
                'code' => $discountCode->code,
                'type' => $discountCode->type,
                'discount_type' => $discountCode->discount_type,
                'discount_value' => $discountCode->discount_value,
                'description' => $discountCode->description,
            ],
        ]);
    }

    /**
     * Calculate pricing for a reservation.
     */
    public function calculatePricing(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'number_of_people' => 'required|integer|min:1|max:100',
            'discount_code_id' => 'nullable|exists:discount_codes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));
        $numberOfPeople = $request->input('number_of_people');
        $discountCodeId = $request->input('discount_code_id');

        // Get current pricing
        $pricing = RentalPricing::current();
        if (!$pricing) {
            return response()->json(['error' => 'No active pricing found'], 500);
        }

        // Calculate number of days
        $numberOfDays = $startDate->diffInDays($endDate) + 1;

        // Calculate base total
        $baseTotal = $pricing->calculateTotal($numberOfPeople, $numberOfDays);

        // Apply discount if provided
        $discountAmount = 0;
        $discountCode = null;
        
        if ($discountCodeId) {
            $discountCode = DiscountCode::find($discountCodeId);
            if ($discountCode && $discountCode->isValid()) {
                $discountAmount = $discountCode->calculateDiscount($baseTotal);
            }
        }

        $finalAmount = $baseTotal - $discountAmount;

        return response()->json([
            'base_total' => $baseTotal,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'deposit_amount' => $pricing->deposit_amount,
            'number_of_days' => $numberOfDays,
            'pricing_per_day' => $pricing->price_per_person_per_day,
            'discount_code' => $discountCode ? [
                'id' => $discountCode->id,
                'code' => $discountCode->code,
                'discount_type' => $discountCode->discount_type,
                'discount_value' => $discountCode->discount_value,
            ] : null,
        ]);
    }

    /**
     * Create a new rental reservation.
     */
    public function createReservation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'rental_purpose' => 'required|string|max:1000',
            'number_of_people' => 'required|integer|min:1|max:100',
            'discount_code_id' => 'nullable|exists:discount_codes,id',
            'payment_method' => 'required|in:credit_card,mail_check',
            'consent_given' => 'required|boolean|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if (!$request->input('consent_given')) {
            return response()->json(['error' => 'Consent is required'], 400);
        }

        try {
            DB::beginTransaction();

            // Check availability
            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'));
            
            $conflictingReservations = RentalReservation::active()
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                          });
                })
                ->exists();

            if ($conflictingReservations) {
                return response()->json(['error' => 'Selected dates are no longer available'], 409);
            }

            // Check for conflicts with camp sessions
            $conflictingCampSessions = \App\Models\CampInstance::where('is_active', true)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                          });
                })
                ->exists();

            if ($conflictingCampSessions) {
                return response()->json(['error' => 'Selected dates conflict with camp sessions'], 409);
            }

            // Check for blackout dates
            if (RentalBlackoutDate::hasConflict($startDate, $endDate)) {
                $conflicts = RentalBlackoutDate::getConflicts($startDate, $endDate);
                $conflictMessages = $conflicts->map(function ($blackout) {
                    return $blackout->reason . ' (' . $blackout->start_date->format('M j, Y') . ' - ' . $blackout->end_date->format('M j, Y') . ')';
                })->implode(', ');
                
                return response()->json([
                    'error' => 'Selected dates conflict with blackout period(s): ' . $conflictMessages
                ], 409);
            }

            // Calculate pricing
            $pricing = RentalPricing::current();
            if (!$pricing) {
                return response()->json(['error' => 'No active pricing found. Please contact support.'], 500);
            }
            
            $numberOfDays = $startDate->diffInDays($endDate) + 1;
            $baseTotal = $pricing->calculateTotal($request->input('number_of_people'), $numberOfDays);

            $discountAmount = 0;
            if ($request->input('discount_code_id')) {
                $discountCode = DiscountCode::find($request->input('discount_code_id'));
                if ($discountCode && $discountCode->isValid()) {
                    $discountAmount = $discountCode->calculateDiscount($baseTotal);
                }
            }

            $finalAmount = $baseTotal - $discountAmount;

            // Determine flow based on payment method
            $paymentMethod = $request->input('payment_method');

            if ($paymentMethod === 'mail_check') {
                // Create reservation immediately for check payments
                $reservation = RentalReservation::create([
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'contact_name' => $request->input('contact_name'),
                    'contact_email' => $request->input('contact_email'),
                    'contact_phone' => $request->input('contact_phone'),
                    'rental_purpose' => $request->input('rental_purpose'),
                    'number_of_people' => $request->input('number_of_people'),
                    'total_amount' => $baseTotal,
                    'deposit_amount' => $pricing->deposit_amount,
                    'discount_code_id' => $request->input('discount_code_id'),
                    'final_amount' => $finalAmount,
                    'status' => 'pending',
                    'notes' => $request->input('notes', 'Payment method: mail_check'),
                ]);

                // Increment discount code usage if used
                if ($request->input('discount_code_id')) {
                    $discountCode->incrementUsage();
                }

                DB::commit();

                // Notify admins of submission (pending check)
                try {
                    $admins = User::role(['rental-admin', 'system-admin', 'super_admin'])->get();
                    $submission = [
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString(),
                        'contact_name' => $reservation->contact_name,
                        'contact_email' => $reservation->contact_email,
                        'contact_phone' => $reservation->contact_phone,
                        'rental_purpose' => $reservation->rental_purpose,
                        'number_of_people' => $reservation->number_of_people,
                        'total_amount' => $baseTotal,
                        'discount_amount' => $discountAmount,
                        'final_amount' => $finalAmount,
                        'deposit_amount' => $pricing->deposit_amount,
                        'payment_method' => 'mail_check',
                        'notes' => $request->input('notes'),
                    ];
                    foreach ($admins as $admin) {
                        Mail::to($admin->email)->send(new RentalSubmissionAdminNotification($submission));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send rental submission emails to admins', [
                        'reservation_id' => $reservation->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'reservation' => [
                        'id' => $reservation->id,
                        'start_date' => $reservation->start_date->toDateString(),
                        'end_date' => $reservation->end_date->toDateString(),
                        'contact_name' => $reservation->contact_name,
                        'contact_email' => $reservation->contact_email,
                        'number_of_people' => $reservation->number_of_people,
                        'final_amount' => $reservation->final_amount,
                        'status' => $reservation->status,
                    ],
                ], 201);
            }

            // For credit cards: do NOT create reservation yet
            DB::commit();

            // Notify admins of submission (awaiting online payment)
            try {
                $admins = User::role(['rental-admin', 'system-admin', 'super_admin'])->get();
                $submission = [
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'contact_name' => $request->input('contact_name'),
                    'contact_email' => $request->input('contact_email'),
                    'contact_phone' => $request->input('contact_phone'),
                    'rental_purpose' => $request->input('rental_purpose'),
                    'number_of_people' => $request->input('number_of_people'),
                    'total_amount' => $baseTotal,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                    'deposit_amount' => $pricing->deposit_amount,
                    'payment_method' => 'credit_card',
                    'notes' => $request->input('notes'),
                ];
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new RentalSubmissionAdminNotification($submission));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send rental submission emails to admins (card)', [
                    'error' => $e->getMessage(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Submission received. Proceed to payment to finalize your reservation.',
                'pricing' => [
                    'total_amount' => $baseTotal,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                    'deposit_amount' => $pricing->deposit_amount,
                ],
            ], 202);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create reservation'], 500);
        }
    }

    /**
     * Get current pricing information.
     */
    public function getPricing(): JsonResponse
    {
        $pricing = RentalPricing::current();
        
        if (!$pricing) {
            return response()->json(['error' => 'No active pricing found'], 500);
        }

        return response()->json([
            'price_per_person_per_day' => $pricing->price_per_person_per_day,
            'deposit_amount' => $pricing->deposit_amount,
            'description' => $pricing->description,
        ]);
    }

    /**
     * Get all active blackout dates.
     */
    public function getBlackoutDates(): JsonResponse
    {
        $blackoutDates = RentalBlackoutDate::getActive();
        
        return response()->json([
            'blackout_dates' => $blackoutDates->map(function ($blackout) {
                return [
                    'id' => $blackout->id,
                    'start_date' => $blackout->start_date->toDateString(),
                    'end_date' => $blackout->end_date->toDateString(),
                    'reason' => $blackout->reason,
                    'notes' => $blackout->notes,
                ];
            }),
        ]);
    }

    /**
     * Create a payment intent for a rental reservation.
     */
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'reservation_id' => 'nullable|exists:rental_reservations,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $amount = $request->input('amount');
            $amountCents = (int) ($amount * 100);

            $metadata = [
                'type' => 'rental_reservation',
            ];

            if ($request->input('reservation_id')) {
                $metadata['reservation_id'] = $request->input('reservation_id');
            }

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

            // Create Stripe Customer with name, email, and phone
            $stripeCustomer = null;
            if ($request->input('customer_email')) {
                $customerData = [
                    'email' => $request->input('customer_email'),
                    'metadata' => [
                        'source' => 'rental_reservation',
                    ],
                ];
                
                if ($request->input('customer_name')) {
                    $customerData['name'] = $request->input('customer_name');
                }
                
                if ($request->input('customer_phone')) {
                    $customerData['phone'] = $request->input('customer_phone');
                }
                
                $stripeCustomer = Customer::create($customerData);
                
                Log::info('Stripe customer created for rental', [
                    'customer_id' => $stripeCustomer->id,
                    'name' => $request->input('customer_name'),
                    'email' => $request->input('customer_email'),
                    'phone' => $request->input('customer_phone'),
                ]);
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

            // Add description with customer name if provided
            if ($request->input('customer_name')) {
                $paymentIntentData['description'] = 'Rental reservation for ' . $request->input('customer_name');
            }

            $paymentIntent = PaymentIntent::create($paymentIntentData);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe payment intent creation failed', [
                'error' => $e->getMessage(),
                'amount' => $request->input('amount'),
            ]);

            return response()->json([
                'error' => 'Failed to create payment intent'
            ], 500);
        }
    }

    /**
     * Confirm payment for a rental reservation.
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_intent_id' => 'required|string',
            'reservation_id' => 'required|exists:rental_reservations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::retrieve($request->input('payment_intent_id'));

            if ($paymentIntent->status === 'succeeded') {
                $reservation = RentalReservation::find($request->input('reservation_id'));
                
                if ($reservation) {
                    // Attach payment method to customer for future use
                    if ($paymentIntent->payment_method && $paymentIntent->customer) {
                        try {
                            $paymentMethod = PaymentMethod::retrieve($paymentIntent->payment_method);
                            
                            // Attach payment method to customer if not already attached
                            if (!$paymentMethod->customer) {
                                $paymentMethod->attach(['customer' => $paymentIntent->customer]);
                                
                                Log::info('Payment method attached to customer', [
                                    'payment_method_id' => $paymentMethod->id,
                                    'customer_id' => $paymentIntent->customer,
                                ]);
                            }
                            
                            // Set as default payment method for this customer
                            Customer::update(
                                $paymentIntent->customer,
                                ['invoice_settings' => ['default_payment_method' => $paymentMethod->id]]
                            );
                            
                            Log::info('Default payment method set for customer', [
                                'payment_method_id' => $paymentMethod->id,
                                'customer_id' => $paymentIntent->customer,
                            ]);
                        } catch (\Exception $e) {
                            Log::warning('Failed to attach payment method to customer', [
                                'error' => $e->getMessage(),
                                'payment_intent_id' => $paymentIntent->id,
                            ]);
                        }
                    }
                    
                    $reservation->update([
                        'stripe_payment_intent_id' => $paymentIntent->id,
                        'payment_status' => 'paid',
                        'payment_method' => 'credit_card',
                        'payment_date' => now(),
                        'amount_paid' => $paymentIntent->amount / 100,
                        'status' => 'confirmed',
                    ]);

                    Log::info('Rental payment confirmed', [
                        'reservation_id' => $reservation->id,
                        'payment_intent_id' => $paymentIntent->id,
                        'amount' => $paymentIntent->amount / 100,
                        'payment_method_id' => $paymentIntent->payment_method,
                        'customer_id' => $paymentIntent->customer,
                    ]);

                    // Send confirmation email to customer
                    try {
                        Mail::to($reservation->contact_email)
                            ->send(new RentalConfirmed($reservation));
                        
                        Log::info('Rental confirmation email sent to customer', [
                            'reservation_id' => $reservation->id,
                            'email' => $reservation->contact_email,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send rental confirmation email to customer', [
                            'reservation_id' => $reservation->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    // Send notification emails to rental admins
                    try {
                        $rentalAdmins = User::role(['rental-admin', 'system-admin', 'super_admin'])->get();
                        
                        foreach ($rentalAdmins as $admin) {
                            Mail::to($admin->email)
                                ->send(new RentalAdminNotification($reservation));
                        }
                        
                        Log::info('Rental notification emails sent to admins', [
                            'reservation_id' => $reservation->id,
                            'admin_count' => $rentalAdmins->count(),
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send rental notification emails to admins', [
                            'reservation_id' => $reservation->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    return response()->json([
                        'success' => true,
                        'reservation' => [
                            'id' => $reservation->id,
                            'status' => $reservation->status,
                            'payment_status' => $reservation->payment_status,
                        ],
                    ]);
                }
            }

            return response()->json([
                'error' => 'Payment not successful'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Rental payment confirmation failed', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $request->input('payment_intent_id'),
                'reservation_id' => $request->input('reservation_id'),
            ]);

            return response()->json([
                'error' => 'Failed to confirm payment'
            ], 500);
        }
    }
}
