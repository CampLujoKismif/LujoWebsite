<template>
  <div class="rental-calendar bg-white rounded-lg shadow-lg p-3 sm:p-6">
    <!-- Step Indicator -->
    <div class="mb-6 sm:mb-8">
      <div class="flex items-center justify-center space-x-2 sm:space-x-8">
        <!-- Step 1: Date Selection -->
        <div class="flex items-center space-x-1 sm:space-x-3">
          <div class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 transition-all duration-300"
               :class="currentStep >= 1 ? 'bg-blue-600 border-blue-600 text-white' : 'bg-gray-100 border-gray-300 text-gray-500'">
            <span class="text-xs sm:text-sm font-semibold">1</span>
          </div>
          <span class="text-xs sm:text-sm font-medium transition-colors duration-300 hidden sm:inline"
                :class="currentStep >= 1 ? 'text-blue-600' : 'text-gray-500'">
            Select Dates
          </span>
        </div>
        
        <!-- Arrow -->
        <div class="w-4 sm:w-8 h-0.5 bg-gray-300"></div>
        
        <!-- Step 2: Form Info -->
        <div class="flex items-center space-x-1 sm:space-x-3">
          <div class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 transition-all duration-300"
               :class="currentStep >= 2 ? 'bg-blue-600 border-blue-600 text-white' : 'bg-gray-100 border-gray-300 text-gray-500'">
            <span class="text-xs sm:text-sm font-semibold">2</span>
          </div>
          <span class="text-xs sm:text-sm font-medium transition-colors duration-300 hidden sm:inline"
                :class="currentStep >= 2 ? 'text-blue-600' : 'text-gray-500'">
            Reservation Info
          </span>
        </div>
        
        <!-- Arrow -->
        <div class="w-4 sm:w-8 h-0.5 bg-gray-300"></div>
        
        <!-- Step 3: Payment -->
        <div class="flex items-center space-x-1 sm:space-x-3">
          <div class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 transition-all duration-300"
               :class="currentStep >= 3 ? 'bg-blue-600 border-blue-600 text-white' : 'bg-gray-100 border-gray-300 text-gray-500'">
            <span class="text-xs sm:text-sm font-semibold">3</span>
          </div>
          <span class="text-xs sm:text-sm font-medium transition-colors duration-300 hidden sm:inline"
                :class="currentStep >= 3 ? 'text-blue-600' : 'text-gray-500'">
            Payment
          </span>
        </div>
      </div>
    </div>

    <!-- Step Content Container -->
    <div class="relative overflow-hidden">
      <!-- Step 1: Calendar Selection -->
      <div class="transition-all duration-500 ease-in-out"
           :class="currentStep === 1 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'">
        
        <!-- Calendar Header -->
        <div class="mb-4">
          <div class="mb-3">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Select Your Dates</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">Click two dates to select a range</p>
          </div>
          <div class="flex items-center justify-between">
            <button 
              @click="previousMonth"
              class="p-2 hover:bg-gray-100 rounded-lg transition-colors flex-shrink-0"
              :disabled="loading"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            
            <h4 class="text-base sm:text-lg font-semibold text-gray-700 text-center px-2">
              {{ currentMonthName }} {{ currentYear }}
            </h4>
            
            <button 
              @click="nextMonth"
              class="p-2 hover:bg-gray-100 rounded-lg transition-colors flex-shrink-0"
              :disabled="loading"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </button>
          </div>
        </div>

        <!-- Calendar Grid -->
        <div class="mb-6">
          <!-- Day Headers -->
          <div class="grid grid-cols-7 gap-0.5 sm:gap-1 mb-2">
            <div v-for="day in dayHeaders" :key="day" class="text-center text-xs sm:text-sm font-semibold text-gray-500 py-1 sm:py-2">
              {{ day }}
            </div>
          </div>
          
          <!-- Calendar Days -->
          <div v-if="loading" class="grid grid-cols-7 gap-0.5 sm:gap-1">
            <div v-for="i in 42" :key="i" class="aspect-square flex items-center justify-center text-xs sm:text-sm font-medium rounded-md sm:rounded-lg bg-gray-100 animate-pulse">
              <div class="w-3 h-3 sm:w-4 sm:h-4 bg-gray-300 rounded"></div>
            </div>
          </div>
          <div v-else class="grid grid-cols-7 gap-0.5 sm:gap-1">
            <div 
              v-for="day in calendarDays" 
              :key="day.date"
              @click="selectDate(day)"
              class="aspect-square flex items-center justify-center text-xs sm:text-sm font-medium rounded-md sm:rounded-lg cursor-pointer transition-all duration-200 hover:scale-105"
              :class="getDayClasses(day)"
              :disabled="!day.available"
            >
              {{ day.day }}
            </div>
          </div>
        </div>

        <!-- Selected Dates Display -->
        <div v-if="selectedDates.length > 0" class="mb-6 p-4 bg-blue-50 rounded-lg">
          <h4 class="font-semibold text-blue-900 mb-2">Selected Dates:</h4>
          <div class="text-blue-800 font-medium">
            {{ formattedSelectedDates }}
          </div>
          <div class="mt-2 text-sm text-blue-700">
            {{ selectedDates.length }} day{{ selectedDates.length > 1 ? 's' : '' }} selected
            <span v-if="selectedDates.length > 1"> (consecutive)</span>
          </div>
          <div v-if="selectedDates.length === 1" class="mt-1 text-xs text-blue-600">
            Click another date to create a range
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between">
          <button 
            @click="clearSelection"
            v-if="selectedDates.length > 0"
            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors"
          >
            Clear Selection
          </button>
          
          <button 
            @click="proceedToForm"
            v-if="selectedDates.length > 0"
            :disabled="loading"
            class="ml-auto bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
          >
            {{ loading ? 'Loading...' : 'Continue to Reservation' }}
          </button>
        </div>
      </div>

      <!-- Step 2: Rental Form -->
      <div class="transition-all duration-500 ease-in-out"
           :class="currentStep === 2 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'">
        <rental-form 
          v-if="pricing"
          :selected-dates="selectedDates"
          :pricing="pricing"
          @go-back-to-calendar="goBackToCalendar"
          @reservation-created="handleReservationCreated"
          @proceed-to-payment="proceedToPayment"
          @form-data-updated="updateFormData"
        />
        <div v-else class="text-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
          <p class="text-gray-600">Loading pricing information...</p>
        </div>
      </div>

      <!-- Step 3: Payment -->
      <div class="transition-all duration-500 ease-in-out"
           :class="currentStep === 3 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'">
        <div class="max-w-2xl mx-auto">
          <h3 class="text-2xl font-bold text-gray-900 mb-6">Payment Information</h3>
          
          <!-- Selected Dates Summary -->
          <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold text-blue-900 mb-2">Reservation Summary:</h4>
            <div class="text-blue-800 font-medium">
              {{ formattedSelectedDates }}
            </div>
            <div class="mt-2 text-sm text-blue-700">
              {{ selectedDates.length }} day{{ selectedDates.length > 1 ? 's' : '' }} selected
            </div>
          </div>

          <!-- Pricing Summary -->
          <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold text-gray-900 mb-3">Pricing Summary</h4>
            <div v-if="!pricing" class="text-gray-500 text-center py-4">
              Loading pricing information...
            </div>
            <div v-else class="space-y-2">
              <div class="flex justify-between">
                <span>{{ formData.number_of_people || 0 }} people × {{ selectedDates.length }} days</span>
                <span>${{ parseFloat(pricing.price_per_person_per_day).toFixed(2) }} per person per day</span>
              </div>
              <div class="flex justify-between font-semibold">
                <span>Subtotal:</span>
                <span>${{ calculateSubtotal().toFixed(2) }}</span>
              </div>
              <div v-if="discountAmount > 0" class="flex justify-between text-green-600">
                <span>Discount:</span>
                <span>-${{ discountAmount.toFixed(2) }}</span>
              </div>
              <div v-if="pricing.deposit_amount" class="flex justify-between">
                <span>Deposit Required:</span>
                <span>${{ parseFloat(pricing.deposit_amount).toFixed(2) }}</span>
              </div>
              <hr class="border-gray-300">
              <div class="flex justify-between text-lg font-bold">
                <span>Total Amount:</span>
                <span>${{ calculateFinalAmount().toFixed(2) }}</span>
              </div>
            </div>
          </div>

          <!-- Payment Options -->
          <div class="mb-6">
            <h4 class="font-semibold text-gray-900 mb-4">Choose Payment Method:</h4>
            <div class="space-y-3">
              <!-- Credit Card Option -->
              <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                     :class="selectedPaymentMethod === 'credit_card' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'">
                <input 
                  type="radio" 
                  v-model="selectedPaymentMethod" 
                  value="credit_card"
                  class="mr-3 text-blue-600 focus:ring-blue-500"
                />
                <div class="flex items-center space-x-3">
                  <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                  </svg>
                  <div>
                    <div class="font-medium text-gray-900">Credit/Debit Card</div>
                    <div class="text-sm text-gray-600">Pay securely online with Stripe</div>
                  </div>
                </div>
              </label>

              <!-- Mail Check Option -->
              <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                     :class="selectedPaymentMethod === 'mail_check' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'">
                <input 
                  type="radio" 
                  v-model="selectedPaymentMethod" 
                  value="mail_check"
                  class="mr-3 text-blue-600 focus:ring-blue-500"
                />
                <div class="flex items-center space-x-3">
                  <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                  </svg>
                  <div>
                    <div class="font-medium text-gray-900">Mail a Check</div>
                    <div class="text-sm text-gray-600">Send payment by mail (instructions will be provided)</div>
                  </div>
                </div>
              </label>
            </div>
          </div>

          <!-- Show Stripe Form After Reservation Created -->
          <div v-if="selectedPaymentMethod === 'credit_card' && reservationCreated" class="mb-6">
            <stripe-payment-form 
              :amount="calculateFinalAmount()"
              :reservation-id="createdReservationId"
              @payment-success="handlePaymentSuccess"
            />
          </div>

          <!-- Payment Instructions for Mail Check -->
          <div v-if="selectedPaymentMethod === 'mail_check'" class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h5 class="font-semibold text-yellow-800 mb-2">Payment Instructions:</h5>
            <div class="text-sm text-yellow-700 space-y-1">
              <p>• Make check payable to: <strong>Camp LUJO-KISMIF</strong></p>
              <p>• Amount: <strong>${{ calculateFinalAmount().toFixed(2) }}</strong></p>
              <p>• Mail to: <strong>Camp LUJO-KISMIF, [Address Here]</strong></p>
              <p>• Include reservation details in the memo line</p>
              <p>• Your reservation will be confirmed once payment is received</p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div v-if="!reservationCreated" class="flex justify-between">
            <button 
              @click="goBackToForm"
              class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
            >
              ← Back to Form
            </button>
            <button 
              @click="completeReservation"
              :disabled="!selectedPaymentMethod || processingReservation"
              class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
            >
              <span v-if="!processingReservation">
                {{ selectedPaymentMethod === 'mail_check' ? 'Complete Reservation' : 'Continue to Payment' }}
              </span>
              <span v-else>Processing...</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Legend -->
    <div class="mt-6 pt-6 border-t border-gray-200">
      <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-6 text-xs sm:text-sm">
        <div class="flex items-center space-x-1.5 sm:space-x-2">
          <div class="w-3 h-3 sm:w-4 sm:h-4 bg-green-100 border border-green-300 rounded"></div>
          <span class="text-gray-600">Available</span>
        </div>
        <div class="flex items-center space-x-1.5 sm:space-x-2">
          <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-100 border border-red-300 rounded"></div>
          <span class="text-gray-600">Unavailable</span>
        </div>
        <div class="flex items-center space-x-1.5 sm:space-x-2">
          <div class="w-3 h-3 sm:w-4 sm:h-4 bg-gray-100 border border-gray-300 rounded"></div>
          <span class="text-gray-600">Past Date</span>
        </div>
        <div class="flex items-center space-x-1.5 sm:space-x-2">
          <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-100 border border-blue-300 rounded"></div>
          <span class="text-gray-600">Selected</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RentalCalendar',
  data() {
    return {
      currentYear: new Date().getFullYear(),
      currentMonth: new Date().getMonth() + 1,
      selectedDates: [],
      availability: {},
      loading: false,
      dayHeaders: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      currentStep: 1,
      pricing: null,
      selectedPaymentMethod: null,
      processingReservation: false,
      reservationCreated: false,
      createdReservationId: null,
      formData: {
        number_of_people: 0,
        discount_code: '',
        discount_amount: 0
      }
    }
  },
  computed: {
    currentMonthName() {
      return new Date(this.currentYear, this.currentMonth - 1).toLocaleString('default', { month: 'long' })
    },
    formattedSelectedDates() {
      if (this.selectedDates.length === 0) return ''
      
      const dates = this.selectedDates.map(date => {
        const d = this.parseDateString(date)
        // Use a more reliable formatting method
        const month = d.toLocaleDateString('en-US', { month: 'short' })
        const day = d.getDate()
        const formatted = `${month} ${day}`
        return formatted
      })
      
      if (dates.length === 1) {
        return dates[0]
      } else if (dates.length === 2) {
        return `${dates[0]} - ${dates[1]}`
      } else {
        return `${dates[0]} - ${dates[dates.length - 1]}`
      }
    },
    calendarDays() {
      // Return empty array if still loading or no availability data
      if (this.loading || !this.availability || Object.keys(this.availability).length === 0) {
        return []
      }
      
      const days = []
      const firstDay = new Date(this.currentYear, this.currentMonth - 1, 1)
      const lastDay = new Date(this.currentYear, this.currentMonth, 0)
      const startDate = new Date(firstDay)
      startDate.setDate(startDate.getDate() - firstDay.getDay())
      
      for (let i = 0; i < 42; i++) {
        const date = new Date(startDate)
        date.setDate(startDate.getDate() + i)
        
        // Create date string safely to avoid timezone issues
        const year = date.getFullYear()
        const month = String(date.getMonth() + 1).padStart(2, '0')
        const day = String(date.getDate()).padStart(2, '0')
        const dateString = `${year}-${month}-${day}`
        
        const dayData = this.availability[dateString] || { available: false, is_past: date < new Date() }
        
        days.push({
          date: dateString,
          day: date.getDate(),
          month: date.getMonth() + 1,
          year: date.getFullYear(),
          available: dayData.available,
          is_past: dayData.is_past,
          is_current_month: date.getMonth() === this.currentMonth - 1
        })
      }
      
      return days
    },
    discountAmount() {
      return this.formData.discount_amount || 0
    }
  },
  mounted() {
    console.log('RentalCalendar mounted, loading availability and pricing...')
    console.log('Current pricing value:', this.pricing)
    this.loadAvailability()
    this.loadPricing()
  },
  methods: {
    async loadAvailability() {
      this.loading = true
      this.availability = {} // Clear previous data
      try {
        const response = await fetch(`/api/rental/availability/${this.currentYear}/${this.currentMonth}`)
        const data = await response.json()
        this.availability = data.availability || {}
      } catch (error) {
        console.error('Error loading availability:', error)
        this.availability = {}
      } finally {
        this.loading = false
      }
    },
    parseDateString(dateString) {
      // Parse date string safely to avoid timezone issues
      const [year, month, day] = dateString.split('-').map(Number)
      return new Date(year, month - 1, day) // month is 0-indexed in Date constructor
    },
    async loadAvailabilityForRange(startDate, endDate) {
      // Get all unique months in the range
      const monthsToLoad = new Set()
      const currentDate = new Date(startDate)
      
      while (currentDate <= endDate) {
        const year = currentDate.getFullYear()
        const month = currentDate.getMonth() + 1
        monthsToLoad.add(`${year}-${month}`)
        currentDate.setMonth(currentDate.getMonth() + 1)
      }
      
      // Load availability for each month
      const allAvailabilityData = {}
      for (const yearMonth of monthsToLoad) {
        const [year, month] = yearMonth.split('-')
        
        try {
          const response = await fetch(`/api/rental/availability/${year}/${month}`)
          const data = await response.json()
          
          // Collect the availability data
          if (data.availability) {
            Object.assign(allAvailabilityData, data.availability)
          }
        } catch (error) {
          console.error('Error loading availability for', year, month, ':', error)
        }
      }
      
      // Merge all collected data at once to avoid multiple reactive updates
      if (Object.keys(allAvailabilityData).length > 0) {
        this.availability = { ...this.availability, ...allAvailabilityData }
      }
    },
    async previousMonth() {
      if (this.currentMonth === 1) {
        this.currentMonth = 12
        this.currentYear--
      } else {
        this.currentMonth--
      }
      await this.loadAvailability()
    },
    async nextMonth() {
      if (this.currentMonth === 12) {
        this.currentMonth = 1
        this.currentYear++
      } else {
        this.currentMonth++
      }
      await this.loadAvailability()
    },
    async selectDate(day) {
      if (!day.available || day.is_past) return
      
      const dateString = day.date
      
      if (this.selectedDates.length === 0) {
        // First selection - just add the date
        this.selectedDates = [dateString]
      } else if (this.selectedDates.length === 1) {
        // Second selection - create range between first and second date
        // Parse dates safely to avoid timezone issues
        const firstDate = this.parseDateString(this.selectedDates[0])
        const secondDate = this.parseDateString(dateString)
        
        
        // Determine start and end dates
        const startDate = firstDate < secondDate ? firstDate : secondDate
        const endDate = firstDate < secondDate ? secondDate : firstDate
        
        // Store the clicked dates before clearing
        const firstClickedDate = this.selectedDates[0]
        const secondClickedDate = dateString
        
        // Load availability for all months in the range
        await this.loadAvailabilityForRange(startDate, endDate)
        
        // Generate all dates in the range (inclusive of both start and end dates)
        const newSelectedDates = []
        const currentDate = new Date(startDate.getTime()) // Create a copy to avoid mutation
        
        while (currentDate <= endDate) {
          const dateStr = currentDate.toISOString().split('T')[0]
          // Include the date if it's available and not in the past, OR if it's one of the clicked dates
          const dayData = this.availability[dateStr]
          const isClickedDate = dateStr === firstClickedDate || dateStr === secondClickedDate
          
          if ((dayData && dayData.available && !dayData.is_past) || isClickedDate) {
            newSelectedDates.push(dateStr)
          }
          currentDate.setDate(currentDate.getDate() + 1)
        }
        
        // Update selectedDates all at once to avoid multiple reactive updates
        this.selectedDates = newSelectedDates
        
      } else {
        // Clear selection and start new one
        this.selectedDates = [dateString]
      }
    },
    getDayClasses(day) {
      const classes = []
      
      if (!day.is_current_month) {
        classes.push('text-gray-300')
      } else if (day.is_past) {
        classes.push('text-gray-400 bg-gray-100 border border-gray-300')
      } else if (this.selectedDates.includes(day.date)) {
        classes.push('text-white bg-blue-600 border border-blue-700 font-semibold')
      } else if (day.available) {
        classes.push('text-green-700 bg-green-100 border border-green-300 hover:bg-green-200')
      } else {
        classes.push('text-red-700 bg-red-100 border border-red-300')
      }
      
      return classes.join(' ')
    },
    clearSelection() {
      this.selectedDates = []
    },
    async loadPricing() {
      console.log('Starting to load pricing...')
      
      // Test if fetch is available
      if (typeof fetch === 'undefined') {
        console.error('Fetch is not available!')
        return
      }
      
      try {
        console.log('Fetching /api/rental/pricing...')
        const response = await fetch('/api/rental/pricing', {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          }
        })
        console.log('Response received:', response.status, response.statusText)
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`)
        }
        
        const data = await response.json()
        console.log('Pricing data received:', data)
        this.pricing = data
        console.log('Pricing set to:', this.pricing)
      } catch (error) {
        console.error('Error loading pricing:', error)
        // Fallback to default pricing if API fails
        this.pricing = {
          price_per_person_per_day: 20.00,
          deposit_amount: null,
          description: 'Default pricing - $20 per person per day'
        }
        console.log('Using fallback pricing:', this.pricing)
      }
    },
    async proceedToForm() {
      if (this.selectedDates.length === 0) return
      
      this.loading = true
      try {
        // Load pricing if not already loaded
        if (!this.pricing) {
          await this.loadPricing()
        }
        this.currentStep = 2
      } catch (error) {
        console.error('Error loading pricing:', error)
      } finally {
        this.loading = false
      }
    },
    goBackToCalendar() {
      this.currentStep = 1
    },
    proceedToPayment() {
      this.currentStep = 3
    },
    updateFormData(formData) {
      this.formData = { ...this.formData, ...formData }
    },
    goBackToForm() {
      this.currentStep = 2
    },
    calculateSubtotal() {
      if (!this.pricing || !this.formData.number_of_people) return 0
      return this.formData.number_of_people * this.selectedDates.length * parseFloat(this.pricing.price_per_person_per_day)
    },
    calculateFinalAmount() {
      const subtotal = this.calculateSubtotal()
      const discount = this.discountAmount
      const deposit = parseFloat(this.pricing?.deposit_amount || 0)
      return Math.max(0, subtotal - discount + deposit)
    },
    async completeReservation() {
      console.log('Completing reservation...')
      console.log('Payment method:', this.selectedPaymentMethod)
      console.log('Final amount:', this.calculateFinalAmount())
      
      this.processingReservation = true
      
      try {
        // Prepare reservation data
        const reservationData = {
          start_date: this.selectedDates[0],
          end_date: this.selectedDates[this.selectedDates.length - 1],
          contact_name: this.formData.contact_name || '',
          contact_email: this.formData.contact_email || '',
          contact_phone: this.formData.contact_phone || '',
          rental_purpose: this.formData.rental_purpose || '',
          number_of_people: this.formData.number_of_people || 0,
          total_amount: this.calculateSubtotal(),
          deposit_amount: this.pricing?.deposit_amount || null,
          discount_code_id: this.formData.discount_code_id || null,
          final_amount: this.calculateFinalAmount(),
          payment_method: this.selectedPaymentMethod,
          consent_given: true, // User has reached payment step, so consent is given
          notes: this.selectedPaymentMethod === 'mail_check' ? 'Payment by mail check' : 'Online payment'
        }
        
        console.log('Sending reservation data:', reservationData)
        
        const response = await fetch('/api/rental/reservation', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(reservationData)
        })
        
        if (!response.ok) {
          const errorData = await response.json()
          throw new Error(errorData.error || errorData.message || `HTTP error! status: ${response.status}`)
        }
        
        const result = await response.json()
        console.log('Reservation created successfully:', result)
        
        if (this.selectedPaymentMethod === 'mail_check') {
          alert('Reservation completed! Please mail your check as instructed. You will receive a confirmation email shortly.')
          this.resetForm()
        } else if (this.selectedPaymentMethod === 'credit_card') {
          // For credit card, show Stripe payment form
          this.reservationCreated = true
          this.createdReservationId = result.reservation.id
          console.log('Showing Stripe payment form for reservation ID:', this.createdReservationId)
        }
        
      } catch (error) {
        console.error('Error creating reservation:', error)
        alert('Error creating reservation: ' + error.message)
      } finally {
        this.processingReservation = false
      }
    },
    async handlePaymentSuccess(paymentData) {
      console.log('Payment successful:', paymentData)
      
      try {
        // Confirm payment on backend
        const response = await fetch('/api/rental/confirm-payment', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            payment_intent_id: paymentData.paymentIntentId,
            reservation_id: this.createdReservationId
          })
        })
        
        if (!response.ok) {
          throw new Error('Failed to confirm payment')
        }
        
        const result = await response.json()
        console.log('Payment confirmed:', result)
        
        alert('Payment successful! Your reservation is confirmed. You will receive a confirmation email shortly.')
        
        // Reset form and return to calendar
        this.resetForm()
        
      } catch (error) {
        console.error('Error confirming payment:', error)
        alert('Payment was processed but there was an error confirming your reservation. Please contact support with your confirmation number.')
      }
    },
    resetForm() {
      this.currentStep = 1
      this.selectedDates = []
      this.formData = {
        number_of_people: 0,
        discount_code: '',
        discount_amount: 0
      }
      this.selectedPaymentMethod = null
      this.reservationCreated = false
      this.createdReservationId = null
      this.processingReservation = false
    },
    handleReservationCreated(reservation) {
      // Handle successful reservation creation
      console.log('Reservation created:', reservation)
      // You can redirect to a success page or show a success message
      alert('Reservation created successfully! You will receive a confirmation email shortly.')
    }
  }
}
</script>
