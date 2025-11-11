<template>
  <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <div :class="['bg-gradient-to-r', gradientClass, 'text-white p-6']">
      <h3 class="text-2xl font-bold mb-2">{{ session.campName }}</h3>
      <span
        v-if="gradeText"
        class="inline-block bg-white px-3 py-1 rounded-full text-sm font-medium text-gray-900"
      >
        {{ gradeText }}
      </span>
      <span
        v-else-if="ageText"
        class="inline-block bg-white px-3 py-1 rounded-full text-sm font-medium text-gray-900"
      >
        {{ ageText }}
      </span>
    </div>

    <div class="p-6">
      <div class="mb-4">
        <div class="flex items-center text-gray-600 mb-2">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
            />
          </svg>
          <span class="font-medium">Dates</span>
        </div>
        <div class="text-lg font-semibold text-gray-900">
          {{ dateText }}
        </div>
      </div>

      <div v-if="hasPrice" class="mb-4">
        <div class="flex items-center text-gray-600 mb-2">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
            />
          </svg>
          <span class="font-medium">Price</span>
        </div>
        <div class="text-3xl font-bold text-gray-900">
          {{ formattedPrice }}
        </div>
        <div class="text-sm text-gray-600">per camper</div>
      </div>

      <div v-if="truncatedDescription" class="mb-4">
        <p class="text-gray-600 text-sm leading-relaxed">
          {{ truncatedDescription }}
        </p>
      </div>

      <div class="mb-4">
        <span
          v-if="session.isRegistrationOpen"
          class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800"
        >
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path
              fill-rule="evenodd"
              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
              clip-rule="evenodd"
            />
          </svg>
          Registration Open
        </span>
        <span
          v-else
          class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800"
        >
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path
              fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"
            />
          </svg>
          Registration Closed
        </span>
      </div>

      <div v-if="session.maxCapacity" class="mb-4 text-sm text-gray-600">
        <span class="font-medium">{{ session.availableSpots }}</span> spots available
      </div>

      <div class="flex space-x-3">
        <a
          v-if="session.themeDescription && session.detailsUrl"
          :href="session.detailsUrl"
          class="flex-1 bg-blue-100 hover:bg-blue-200 text-gray-900 text-center py-2 px-4 rounded-md font-medium transition duration-300"
        >
          Learn More
        </a>

        <button
          v-if="session.isRegistrationOpen"
          type="button"
          @click="handleRegister"
          class="flex-1 bg-green-100 hover:bg-green-200 text-gray-900 text-center py-2 px-4 rounded-md font-medium transition duration-300"
        >
          Register Now
        </button>
        <button
          v-else
          type="button"
          disabled
          class="flex-1 bg-gray-200 text-gray-600 text-center py-2 px-4 rounded-md font-medium cursor-not-allowed"
        >
          Registration Closed
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CampSessionCard',
  props: {
    session: {
      type: Object,
      required: true
    },
    gradientClass: {
      type: String,
      default: 'from-blue-500 to-blue-600'
    },
    descriptionLimit: {
      type: Number,
      default: 150
    }
  },
  computed: {
    gradeText() {
      const { gradeFrom, gradeTo } = this.session
      if (!gradeFrom || !gradeTo) {
        return ''
      }

      const start = `${gradeFrom}${this.gradeSuffix(gradeFrom)} Grade`
      if (gradeFrom === gradeTo) {
        return start
      }

      const end = `${gradeTo}${this.gradeSuffix(gradeTo)} Grade`
      return `${start} - ${end}`
    },
    ageText() {
      const { ageFrom, ageTo } = this.session
      if (!ageFrom || !ageTo) {
        return ''
      }
      return `Ages ${ageFrom} - ${ageTo}`
    },
    hasPrice() {
      const price = Number(this.session.price ?? 0)
      return Boolean(price)
    },
    formattedPrice() {
      if (!this.hasPrice) {
        return ''
      }
      const price = Number(this.session.price)
      return price.toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).replace('$', '$')
    },
    truncatedDescription() {
      const description = this.session.description || ''
      if (!description) {
        return ''
      }
      if (description.length <= this.descriptionLimit) {
        return description
      }
      return `${description.slice(0, this.descriptionLimit - 3)}...`
    },
    dateText() {
      const { startDate, endDate } = this.session
      if (startDate && endDate) {
        const start = this.formatDate(startDate, { month: 'short', day: 'numeric' })
        const end = this.formatDate(endDate, { month: 'short', day: 'numeric', year: 'numeric' })
        return `${start} - ${end}`
      }
      if (startDate) {
        return this.formatDate(startDate, { month: 'short', day: 'numeric', year: 'numeric' })
      }
      return 'TBD'
    }
  },
  methods: {
    gradeSuffix(num) {
      if (num === 1) return 'st'
      if (num === 2) return 'nd'
      if (num === 3) return 'rd'
      return 'th'
    },
    formatDate(dateString, options) {
      try {
        const date = new Date(dateString)
        if (Number.isNaN(date.getTime())) {
          return 'TBD'
        }
        return date.toLocaleDateString('en-US', options)
      } catch (error) {
        console.warn('Failed to format date', dateString, error)
        return 'TBD'
      }
    },
    handleRegister() {
      if (typeof window.openRegistrationModal === 'function') {
        window.openRegistrationModal(this.session.id)
      } else if (typeof window.openCampRegistrationModal === 'function') {
        window.openCampRegistrationModal(this.session.id)
      }
    }
  }
}
</script>

