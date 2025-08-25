<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Payment Processing</h2>
                </div>

                @if($error)
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $error }}
                    </div>
                @endif

                @if($success)
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ $success }}
                    </div>
                @endif

                @if($enrollments->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500">No enrollments requiring payment found.</p>
                    </div>
                @else
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Select Enrollments to Pay</h3>
                        
                        <div class="space-y-4">
                            @foreach($enrollments as $enrollment)
                                <div class="border rounded-lg p-4 {{ in_array($enrollment->id, $selectedEnrollments) ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <input 
                                                type="checkbox" 
                                                id="enrollment_{{ $enrollment->id }}"
                                                wire:click="handleEnrollmentSelection({{ $enrollment->id }})"
                                                {{ in_array($enrollment->id, $selectedEnrollments) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <div>
                                                <h4 class="font-semibold">{{ $enrollment->camper->full_name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $enrollment->campInstance->camp->name }}</p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $enrollment->campInstance->start_date->format('M j, Y') }} - 
                                                    {{ $enrollment->campInstance->end_date->format('M j, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-lg">${{ number_format($enrollment->outstanding_balance, 2) }}</p>
                                            <p class="text-sm text-gray-500">Outstanding Balance</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if(!empty($selectedEnrollments))
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold">Selected Enrollments</h4>
                                    <p class="text-sm text-gray-600">{{ count($selectedEnrollments) }} enrollment(s) selected</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-green-600">${{ number_format($this->totalAmount, 2) }}</p>
                                    <p class="text-sm text-gray-500">Total Amount</p>
                                </div>
                            </div>
                        </div>

                        @if(!$showPaymentForm)
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <input 
                                        type="radio" 
                                        id="pay_now" 
                                        wire:model="payAtCheckin" 
                                        value="false"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    >
                                    <label for="pay_now" class="text-sm font-medium text-gray-700">Pay Now</label>
                                    
                                    <input 
                                        type="radio" 
                                        id="pay_at_checkin" 
                                        wire:model="payAtCheckin" 
                                        value="true"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    >
                                    <label for="pay_at_checkin" class="text-sm font-medium text-gray-700">Pay at Check-in</label>
                                </div>

                                <button 
                                    wire:click="proceedToPayment"
                                    wire:loading.attr="disabled"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50"
                                >
                                    <span wire:loading.remove>Proceed to Payment</span>
                                    <span wire:loading>Processing...</span>
                                </button>
                            </div>
                        @endif

                        @if($showPaymentForm)
                            <div class="border rounded-lg p-6 bg-white">
                                <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Payment Method
                                        </label>
                                        <select wire:model="paymentMethod" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="stripe">Credit/Debit Card</option>
                                        </select>
                                    </div>

                                    @if($paymentMethod === 'stripe')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Card Information
                                            </label>
                                            <div id="card-element" class="w-full border border-gray-300 rounded-md p-3">
                                                <!-- Stripe Elements will be inserted here -->
                                            </div>
                                            <div id="card-errors" class="text-red-600 text-sm mt-2"></div>
                                        </div>
                                    @endif

                                    <div class="flex space-x-4">
                                        <button 
                                            wire:click="processPayment"
                                            wire:loading.attr="disabled"
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50"
                                        >
                                            <span wire:loading.remove>Process Payment</span>
                                            <span wire:loading>Processing...</span>
                                        </button>
                                        
                                        <button 
                                            wire:click="cancelPayment"
                                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe
    const stripe = Stripe('{{ config("cashier.key") }}');
    const elements = stripe.elements();

    // Create card element
    const card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });

    // Mount the card element
    card.mount('#card-element');

    // Handle real-time validation errors
    card.addEventListener('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission
    document.addEventListener('livewire:init', () => {
        Livewire.on('process-payment', () => {
            stripe.createPaymentMethod({
                type: 'card',
                card: card,
            }).then(function(result) {
                if (result.error) {
                    // Show error to customer
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send payment method ID to server
                    @this.set('paymentMethodId', result.paymentMethod.id);
                    @this.processPayment();
                }
            });
        });
    });
</script>
@endpush
