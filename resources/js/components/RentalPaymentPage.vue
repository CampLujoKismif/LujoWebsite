<template>
  <div>
    <div id="payment-form-container">
      <stripe-payment-form 
        :amount="amount"
        :reservation-id="reservationId"
        :customer-name="customerName"
        :customer-email="customerEmail"
        :customer-phone="customerPhone"
        @payment-success="handlePaymentSuccess"
      />
    </div>
    
    <!-- Payment Success Message (hidden initially) -->
    <div v-if="paymentComplete" class="mt-6 bg-green-50 border border-green-200 rounded-lg p-6 text-center">
      <div class="text-green-600 text-4xl mb-4">✓</div>
      <h3 class="text-xl font-bold text-green-900 mb-2">Payment Successful!</h3>
      <p class="text-green-700 mb-4">Your payment has been processed successfully. You will receive a confirmation email shortly.</p>
      <a :href="rentalsUrl" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
        Return to Rentals
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RentalPaymentPage',
  props: {
    amount: {
      type: Number,
      required: true
    },
    reservationId: {
      type: Number,
      required: true
    },
    customerName: {
      type: String,
      default: ''
    },
    customerEmail: {
      type: String,
      default: ''
    },
    customerPhone: {
      type: String,
      default: ''
    },
    rentalsUrl: {
      type: String,
      default: '/rentals'
    }
  },
  data() {
    return {
      paymentComplete: false
    }
  },
  methods: {
    async handlePaymentSuccess(paymentData) {
      // Hide payment form
      document.getElementById('payment-form-container').style.display = 'none'
      
      // Show success message
      this.paymentComplete = true
      
      // Confirm payment on backend
      try {
        const response = await fetch('/api/rental/confirm-payment', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            payment_intent_id: paymentData.paymentIntentId,
            reservation_id: this.reservationId
          })
        })
        
        if (!response.ok) {
          console.error('Failed to confirm payment')
        }
      } catch (error) {
        console.error('Error confirming payment:', error)
      }
    }
  }
}
</script>

