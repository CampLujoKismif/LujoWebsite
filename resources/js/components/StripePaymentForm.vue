<template>
  <div class="stripe-payment-form">
    <div v-if="loading" class="text-center py-4">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <p class="mt-2 text-gray-600">Processing payment...</p>
    </div>
    
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
      <p class="text-red-800">{{ error }}</p>
    </div>
    
    <div v-if="!loading && !paymentComplete">
      <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Payment Details</h3>
        <p class="text-gray-600">Amount to pay: <span class="font-bold text-green-600">${{ amount.toFixed(2) }}</span></p>
      </div>

      <!-- Stripe Payment Element -->
      <div id="payment-element" class="mb-4"></div>
      
      <button 
        @click="handleSubmit" 
        :disabled="processing"
        class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center"
      >
        <span v-if="!processing">Pay ${{ amount.toFixed(2) }}</span>
        <span v-else class="flex items-center">
          <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Processing...
        </span>
      </button>
    </div>

    <div v-if="paymentComplete" class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
      <svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
      </svg>
      <h3 class="text-2xl font-bold text-green-800 mb-2">Payment Successful!</h3>
      <p class="text-green-700">Your reservation has been confirmed.</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StripePaymentForm',
  props: {
    amount: {
      type: Number,
      required: true
    },
    reservationId: {
      type: Number,
      required: false,
      default: null
    },
    customerName: {
      type: String,
      required: false,
      default: null
    },
    customerEmail: {
      type: String,
      required: false,
      default: null
    },
    customerPhone: {
      type: String,
      required: false,
      default: null
    }
  },
  data() {
    return {
      stripe: null,
      elements: null,
      paymentElement: null,
      loading: true,
      processing: false,
      error: null,
      clientSecret: null,
      paymentIntentId: null,
      paymentComplete: false
    }
  },
  async mounted() {
    await this.initializeStripe()
  },
  methods: {
    async initializeStripe() {
      try {
        // Initialize Stripe
        if (!window.Stripe) {
          throw new Error('Stripe.js failed to load')
        }

        this.stripe = window.Stripe(window.stripePublishableKey)

        // Create payment intent
        const response = await fetch('/api/rental/create-payment-intent', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            amount: this.amount,
            reservation_id: this.reservationId,
            customer_name: this.customerName,
            customer_email: this.customerEmail,
            customer_phone: this.customerPhone
          })
        })

        if (!response.ok) {
          throw new Error('Failed to create payment intent')
        }

        const data = await response.json()
        this.clientSecret = data.client_secret
        this.paymentIntentId = data.payment_intent_id

        // Create Stripe Elements
        const appearance = {
          theme: 'stripe',
          variables: {
            colorPrimary: '#2563eb',
          }
        }

        this.elements = this.stripe.elements({ 
          clientSecret: this.clientSecret,
          appearance 
        })

        // Create the Payment Element
        this.paymentElement = this.elements.create('payment')
        
        // Set loading to false first
        this.loading = false

        // Wait for Vue to render the DOM, then mount
        await this.$nextTick()
        this.paymentElement.mount('#payment-element')

      } catch (err) {
        console.error('Error initializing Stripe:', err)
        this.error = 'Failed to initialize payment form. Please try again.'
        this.loading = false
      }
    },
    async handleSubmit() {
      if (this.processing) return

      this.processing = true
      this.error = null

      try {
        // Confirm the payment with billing details
        const confirmParams = {
          return_url: window.location.href, // This won't be used since we're not redirecting
        }
        
        // Add billing details if we have customer info
        if (this.customerName || this.customerEmail || this.customerPhone) {
          confirmParams.payment_method_data = {
            billing_details: {}
          }
          
          if (this.customerName) {
            confirmParams.payment_method_data.billing_details.name = this.customerName
          }
          
          if (this.customerEmail) {
            confirmParams.payment_method_data.billing_details.email = this.customerEmail
          }
          
          if (this.customerPhone) {
            confirmParams.payment_method_data.billing_details.phone = this.customerPhone
          }
        }
        
        // Confirm the payment
        const { error, paymentIntent } = await this.stripe.confirmPayment({
          elements: this.elements,
          confirmParams: confirmParams,
          redirect: 'if_required'
        })

        if (error) {
          // Payment failed
          this.error = error.message
          this.processing = false
        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
          // Payment successful
          this.paymentComplete = true
          this.$emit('payment-success', {
            paymentIntentId: paymentIntent.id,
            amount: this.amount
          })
        }

      } catch (err) {
        console.error('Error processing payment:', err)
        this.error = 'Payment failed. Please try again.'
        this.processing = false
      }
    }
  }
}
</script>

<style scoped>
#payment-element {
  margin-bottom: 24px;
}
</style>

