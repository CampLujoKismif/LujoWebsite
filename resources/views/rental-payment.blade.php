<x-layouts.public title="Pay for Your Rental Reservation">
    @push('head')
        <!-- Stripe.js -->
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            window.stripePublishableKey = '{{ config('services.stripe.key') }}';
        </script>
    @endpush

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-900 to-blue-700 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Complete Your Payment
            </h1>
            <p class="text-lg md:text-xl text-blue-100">
                Pay for your rental reservation at Camp LUJO-KISMIF
            </p>
        </div>
    </section>

    <!-- Payment Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8">
                @if($reservation)
                    <!-- Reservation Details -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Reservation Details</h2>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-600">Contact Name:</span>
                                    <p class="font-semibold text-gray-900">{{ $reservation->contact_name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Email:</span>
                                    <p class="font-semibold text-gray-900">{{ $reservation->contact_email }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Dates:</span>
                                    <p class="font-semibold text-gray-900">
                                        {{ $reservation->start_date->format('F j, Y') }} - {{ $reservation->end_date->format('F j, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Number of People:</span>
                                    <p class="font-semibold text-gray-900">{{ $reservation->number_of_people }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Payment Summary</h2>
                        <div class="bg-blue-50 rounded-lg p-6 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Total Amount:</span>
                                <span class="text-xl font-bold text-gray-900">${{ number_format($reservation->final_amount, 2) }}</span>
                            </div>
                            @if($reservation->amount_paid > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Amount Paid:</span>
                                    <span class="text-lg font-semibold text-green-600">${{ number_format($reservation->amount_paid, 2) }}</span>
                                </div>
                                <div class="border-t border-blue-200 pt-3 mt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-900">Amount Due:</span>
                                        <span class="text-2xl font-bold text-blue-600">${{ number_format($reservation->remaining_balance, 2) }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="border-t border-blue-200 pt-3 mt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-900">Amount Due:</span>
                                        <span class="text-2xl font-bold text-blue-600">${{ number_format($reservation->final_amount, 2) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($reservation->isFullyPaid())
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                            <div class="text-green-600 text-4xl mb-4">✓</div>
                            <h3 class="text-xl font-bold text-green-900 mb-2">Payment Complete</h3>
                            <p class="text-green-700">This reservation has already been paid in full. Thank you!</p>
                        </div>
                    @else
                        <!-- Stripe Payment Form -->
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Payment Information</h2>
                            <p class="text-gray-600 mb-6">Please enter your payment details below to complete your reservation payment.</p>
                            
                            <x-vue-component 
                                component="RentalPaymentPage" 
                                :amount="$reservation->remaining_balance"
                                :reservation-id="$reservation->id"
                                :customer-name="$reservation->contact_name"
                                :customer-email="$reservation->contact_email"
                                :customer-phone="$reservation->contact_phone"
                                rentals-url="{{ route('rentals') }}"
                            />
                        </div>
                    @endif
                @else
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <div class="text-red-600 text-4xl mb-4">✗</div>
                        <h3 class="text-xl font-bold text-red-900 mb-2">Reservation Not Found</h3>
                        <p class="text-red-700">We couldn't find the reservation you're looking for. Please check your payment link or contact us for assistance.</p>
                        <a href="{{ route('rentals') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Return to Rentals
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts.public>

