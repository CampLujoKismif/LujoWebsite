<template>
  <div class="rental-form bg-white rounded-lg shadow-lg p-6">
    <!-- Header -->
    <div class="mb-6">
      <h3 class="text-2xl font-bold text-gray-900 mb-2">Complete Your Reservation</h3>
      <p class="text-gray-600">Fill out the form below to complete your rental reservation</p>
    </div>

    <!-- Selected Dates Display -->
    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
      <h4 class="font-semibold text-blue-900 mb-2">Selected Dates:</h4>
      <div class="text-blue-800 font-medium">
        {{ formattedSelectedDates }}
      </div>
      <div class="mt-2 text-sm text-blue-700">
        {{ selectedDates.length }} day{{ selectedDates.length > 1 ? 's' : '' }} selected
        <span v-if="selectedDates.length > 1"> (consecutive)</span>
      </div>
    </div>

    <!-- Form -->
    <form @submit.prevent="submitReservation" class="space-y-6">
      <!-- Contact Information -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">
            Full Name *
          </label>
          <input
            type="text"
            id="contact_name"
            v-model="form.contact_name"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Enter your full name"
          />
        </div>

        <div>
          <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
            Email Address *
          </label>
          <input
            type="email"
            id="contact_email"
            v-model="form.contact_email"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Enter your email address"
          />
        </div>

        <div>
          <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
            Phone Number *
          </label>
          <input
            type="tel"
            id="contact_phone"
            v-model="form.contact_phone"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Enter your phone number"
          />
        </div>

        <div>
          <label for="number_of_people" class="block text-sm font-medium text-gray-700 mb-2">
            Number of People *
          </label>
          <select
            id="number_of_people"
            v-model="form.number_of_people"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Select number of people</option>
            <option v-for="n in 50" :key="n" :value="n">{{ n }} {{ n === 1 ? 'person' : 'people' }}</option>
          </select>
        </div>
      </div>

      <!-- Rental Purpose -->
      <div>
        <label for="rental_purpose" class="block text-sm font-medium text-gray-700 mb-2">
          Rental Purpose *
        </label>
        <textarea
          id="rental_purpose"
          v-model="form.rental_purpose"
          required
          rows="3"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          placeholder="Please describe the purpose of your rental (e.g., family reunion, corporate retreat, church event, etc.)"
        ></textarea>
      </div>

      <!-- Discount Code -->
      <div>
        <label for="discount_code" class="block text-sm font-medium text-gray-700 mb-2">
          Discount Code (Optional)
        </label>
        <div class="flex gap-2">
          <input
            type="text"
            id="discount_code"
            v-model="form.discount_code"
            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Enter discount code"
          />
          <button
            type="button"
            @click="validateDiscountCode"
            :disabled="!form.discount_code || validatingDiscount"
            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ validatingDiscount ? 'Validating...' : 'Apply' }}
          </button>
        </div>
        <div v-if="discountMessage" class="mt-2 text-sm" :class="discountValid ? 'text-green-600' : 'text-red-600'">
          {{ discountMessage }}
        </div>
      </div>

      <!-- Pricing Summary -->
      <div class="bg-gray-50 p-4 rounded-lg">
        <h4 class="font-semibold text-gray-900 mb-3">Pricing Summary</h4>
        <div v-if="!pricing" class="text-gray-500 text-center py-4">
          Loading pricing information...
        </div>
        <div v-else class="space-y-2">
          <div class="flex justify-between">
            <span>{{ form.number_of_people || 0 }} people × {{ selectedDates.length }} days</span>
            <span>${{ pricing.price_per_person_per_day }} per person per day</span>
          </div>
          <div class="flex justify-between font-semibold">
            <span>Subtotal:</span>
            <span>${{ subtotal.toFixed(2) }}</span>
          </div>
          <div v-if="discountAmount > 0" class="flex justify-between text-green-600">
            <span>Discount:</span>
            <span>-${{ discountAmount.toFixed(2) }}</span>
          </div>
          <div v-if="pricing.deposit_amount" class="flex justify-between">
            <span>Deposit Required:</span>
            <span>${{ pricing.deposit_amount.toFixed(2) }}</span>
          </div>
          <hr class="border-gray-300">
          <div class="flex justify-between text-lg font-bold">
            <span>Total Amount:</span>
            <span>${{ finalAmount.toFixed(2) }}</span>
          </div>
        </div>
      </div>

      <!-- Consent Checkbox -->
      <div class="flex items-start">
        <input
          type="checkbox"
          id="consent"
          v-model="form.consent"
          required
          class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
        />
        <label for="consent" class="ml-2 text-sm text-gray-700">
          <span class="font-semibold">MY PARTY AGREES TO FOLLOW ALL THE RULES OF CAMP LUJO KISMIF AND UNDERSTANDS THAT ANY VIOLATION CAN RESULT IN FORFEITURE OF YOUR DEPOSIT</span> *
        </label>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-between">
        <button
          type="button"
          @click="goBackToCalendar"
          class="px-6 py-2 text-gray-600 hover:text-gray-800 transition-colors"
        >
          ← Back to Calendar
        </button>
        <button
          type="button"
          @click="proceedToPayment"
          :disabled="!isFormValid"
          class="px-8 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Continue to Payment
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'RentalForm',
  props: {
    selectedDates: {
      type: Array,
      required: true
    },
    pricing: {
      type: Object,
      required: false,
      default: null
    }
  },
  data() {
    return {
      form: {
        contact_name: '',
        contact_email: '',
        contact_phone: '',
        number_of_people: '',
        rental_purpose: '',
        discount_code: '',
        consent: false
      },
      discountValid: false,
      discountAmount: 0,
      discountCodeId: null,
      discountMessage: '',
      validatingDiscount: false,
      submitting: false
    }
  },
  computed: {
    formattedSelectedDates() {
      if (this.selectedDates.length === 0) return ''
      
      const dates = this.selectedDates.map(date => {
        const d = this.parseDateString(date)
        const month = d.toLocaleDateString('en-US', { month: 'short' })
        const day = d.getDate()
        return `${month} ${day}`
      })
      
      if (dates.length === 1) {
        return dates[0]
      } else if (dates.length === 2) {
        return `${dates[0]} - ${dates[1]}`
      } else {
        return `${dates[0]} - ${dates[dates.length - 1]}`
      }
    },
    subtotal() {
      if (!this.form.number_of_people || this.selectedDates.length === 0 || !this.pricing) return 0
      return this.form.number_of_people * this.selectedDates.length * this.pricing.price_per_person_per_day
    },
    finalAmount() {
      return Math.max(0, this.subtotal - this.discountAmount)
    },
    isFormValid() {
      return this.form.contact_name && 
             this.form.contact_email && 
             this.form.contact_phone && 
             this.form.number_of_people && 
             this.form.rental_purpose && 
             this.form.consent
    }
  },
  methods: {
    parseDateString(dateString) {
      const [year, month, day] = dateString.split('-').map(Number)
      return new Date(year, month - 1, day)
    },
    async validateDiscountCode() {
      if (!this.form.discount_code) return
      
      this.validatingDiscount = true
      this.discountMessage = ''
      
      try {
        const response = await fetch('/api/rental/validate-discount', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            code: this.form.discount_code,
            total: this.subtotal
          })
        })
        
        const data = await response.json()
        
        if (data.valid) {
          this.discountValid = true
          this.discountAmount = data.discount_amount
          this.discountCodeId = data.discount_code_id
          this.discountMessage = `Discount applied: ${data.discount_type === 'percentage' ? data.discount_value + '%' : '$' + data.discount_value} off`
        } else {
          this.discountValid = false
          this.discountAmount = 0
          this.discountCodeId = null
          this.discountMessage = data.message || 'Invalid discount code'
        }
      } catch (error) {
        this.discountValid = false
        this.discountAmount = 0
        this.discountMessage = 'Error validating discount code'
        console.error('Error validating discount:', error)
      } finally {
        this.validatingDiscount = false
      }
    },
    async submitReservation() {
      this.submitting = true
      
      try {
        const response = await fetch('/api/rental/reservation', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            start_date: this.selectedDates[0],
            end_date: this.selectedDates[this.selectedDates.length - 1],
            contact_name: this.form.contact_name,
            contact_email: this.form.contact_email,
            contact_phone: this.form.contact_phone,
            rental_purpose: this.form.rental_purpose,
            number_of_people: parseInt(this.form.number_of_people),
            discount_code: this.form.discount_code || null,
            total_amount: this.subtotal,
            final_amount: this.finalAmount
          })
        })
        
        const data = await response.json()
        
        if (response.ok) {
          // Redirect to payment or success page
          this.$emit('reservation-created', data)
        } else {
          alert('Error creating reservation: ' + (data.message || 'Unknown error'))
        }
      } catch (error) {
        console.error('Error submitting reservation:', error)
        alert('Error creating reservation. Please try again.')
      } finally {
        this.submitting = false
      }
    },
    goBackToCalendar() {
      this.$emit('go-back-to-calendar')
    },
    proceedToPayment() {
      // Emit form data to parent component
      this.$emit('form-data-updated', {
        contact_name: this.form.contact_name,
        contact_email: this.form.contact_email,
        contact_phone: this.form.contact_phone,
        rental_purpose: this.form.rental_purpose,
        number_of_people: this.form.number_of_people,
        discount_code: this.form.discount_code,
        discount_amount: this.discountAmount,
        discount_code_id: this.discountCodeId
      })
      this.$emit('proceed-to-payment')
    }
  }
}
</script>
