<template>
  <div class="max-w-2xl mx-auto">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Family Information</h3>
    <p class="text-gray-600 dark:text-gray-400 mb-6">
      Please provide or confirm your family's contact and emergency information.
    </p>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <div
        v-if="validationMessages.length"
        class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-200"
      >
        <p class="text-sm font-semibold">We still need:</p>
        <ul class="mt-2 list-disc list-inside space-y-1 text-xs sm:text-sm">
          <li
            v-for="message in validationMessages"
            :key="message"
          >
            {{ message }}
          </li>
        </ul>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Family Name *</label>
          <input
            v-model="familyForm.name"
            required
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone *</label>
          <input
            v-model="familyForm.phone"
            type="tel"
            required
            @input="handlePhoneInput($event, familyForm, 'phone')"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address *</label>
            <input
              v-model="familyForm.address"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">City *</label>
            <input
              v-model="familyForm.city"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
            >
          </div>
        </div>

        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">State *</label>
            <input
              v-model="familyForm.state"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ZIP Code *</label>
            <input
              v-model="familyForm.zip_code"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
            >
          </div>
        </div>
      </div>

      <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
          <div>
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Emergency Contacts</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Provide one or more trusted adults we can reach in an emergency.
            </p>
          </div>
          <button
            type="button"
            @click="$emit('add-contact')"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-700 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/20"
          >
            + Add Contact
          </button>
        </div>

        <div v-if="contacts.length" class="mt-6 space-y-4">
          <div
            v-for="(contact, index) in contacts"
            :key="`family-contact-${index}-${contact.id || 'new'}`"
            class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-zinc-900/50"
          >
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
              <div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                  Emergency Contact {{ index + 1 }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                  Mark one contact as primary for quick reference.
                </div>
              </div>
              <div class="flex flex-wrap items-center gap-3">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                  <input
                    type="radio"
                    :name="`family-primary-contact`"
                    :checked="contact.is_primary"
                    @change="$emit('set-primary-contact', index)"
                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                  >
                  Primary contact
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                  <input
                    type="checkbox"
                    v-model="contact.authorized_pickup"
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                  >
                  Authorized for pickup
                </label>
                <button
                  type="button"
                  @click="$emit('remove-contact', index)"
                  :disabled="contacts.length === 1"
                  class="text-sm text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 disabled:opacity-40 disabled:cursor-not-allowed"
                >
                  Remove
                </button>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                <input
                  v-model="contact.name"
                  required
                  @input="contact.is_primary && $emit('sync-legacy')"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relationship *</label>
                <input
                  v-model="contact.relation"
                  required
                  @input="contact.is_primary && $emit('sync-legacy')"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Phone *</label>
                <input
                  v-model="contact.home_phone"
                  type="tel"
                  required
                  @input="handlePhoneInput($event, contact, 'home_phone', { syncLegacy: contact.is_primary })"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cell Phone</label>
                <input
                  v-model="contact.cell_phone"
                  type="tel"
                  @input="handlePhoneInput($event, contact, 'cell_phone')"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Work Phone</label>
                <input
                  v-model="contact.work_phone"
                  type="tel"
                  @input="handlePhoneInput($event, contact, 'work_phone')"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input
                  v-model="contact.email"
                  type="email"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                <input
                  v-model="contact.address"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:col-span-2">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                  <input
                    v-model="contact.city"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">State</label>
                  <input
                    v-model="contact.state"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ZIP</label>
                  <input
                    v-model="contact.zip"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-end space-x-3 pt-6">
        <button
          type="button"
          @click="$emit('go-back')"
          class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700"
        >
          ← Back
        </button>
        <button
          type="submit"
          :disabled="processing"
          class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
        >
          {{ processing ? 'Saving...' : 'Continue' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'FamilyInformationForm',
  props: {
    familyForm: {
      type: Object,
      required: true,
    },
    processing: {
      type: Boolean,
      default: false,
    },
  },
  emits: [
    'submit',
    'add-contact',
    'remove-contact',
    'set-primary-contact',
    'sync-legacy',
    'go-back',
  ],
  data() {
    return {
      validationMessages: [],
    }
  },
  computed: {
    contacts() {
      return Array.isArray(this.familyForm?.emergency_contacts)
        ? this.familyForm.emergency_contacts
        : []
    },
  },
  mounted() {
    this.formatInitialPhones()
  },
  methods: {
    handleSubmit() {
      const errors = this.validateForm()
      if (errors.length) {
        this.validationMessages = errors
        return
      }
      this.validationMessages = []
      this.$emit('submit')
    },
    validateForm() {
      const errors = []

      if (!this.hasValue(this.familyForm?.name)) {
        errors.push('Family name')
      }
      if (!this.hasValue(this.familyForm?.phone)) {
        errors.push('Family phone')
      } else if (this.extractDigits(this.familyForm.phone).length !== 10) {
        errors.push('Family phone must be 10 digits')
      }
      if (!this.hasValue(this.familyForm?.address)) {
        errors.push('Family address')
      }
      if (!this.hasValue(this.familyForm?.city)) {
        errors.push('Family city')
      }
      if (!this.hasValue(this.familyForm?.state)) {
        errors.push('Family state')
      }
      if (!this.hasValue(this.familyForm?.zip_code)) {
        errors.push('Family ZIP code')
      }

      if (!this.contacts.length) {
        errors.push('At least one emergency contact')
      }

      this.contacts.forEach((contact, index) => {
        const contactLabel = `Emergency contact ${index + 1}`
        if (!this.hasValue(contact?.name)) {
          errors.push(`${contactLabel}: name`)
        }
        if (!this.hasValue(contact?.relation)) {
          errors.push(`${contactLabel}: relationship`)
        }
        if (!this.hasValue(contact?.home_phone)) {
          errors.push(`${contactLabel}: primary phone`)
        } else if (this.extractDigits(contact.home_phone).length !== 10) {
          errors.push(`${contactLabel}: primary phone must be 10 digits`)
        }
      })

      return [...new Set(errors)]
    },
    hasValue(value) {
      return value !== null && value !== undefined && String(value).trim().length > 0
    },
    handlePhoneInput(event, target, field, options = {}) {
      if (!target || !field) {
        return
      }
      const rawValue = event?.target?.value ?? target[field]
      const formatted = this.formatPhone(rawValue)

      if (event?.target && formatted !== event.target.value) {
        event.target.value = formatted
      }

      if (formatted !== target[field]) {
        target[field] = formatted
      }

      if (options.syncLegacy) {
        this.$emit('sync-legacy')
      }
    },
    formatPhone(value) {
      const digits = this.extractDigits(value)
      return this.formatPhoneFromDigits(digits)
    },
    extractDigits(value) {
      if (value === null || value === undefined) {
        return ''
      }
      return String(value).replace(/\D/g, '').slice(0, 10)
    },
    formatPhoneFromDigits(digits) {
      if (!digits.length) {
        return ''
      }
      if (digits.length <= 3) {
        return digits
      }
      if (digits.length <= 6) {
        return `(${digits.slice(0, 3)}) ${digits.slice(3)}`
      }
      const area = digits.slice(0, 3)
      const prefix = digits.slice(3, 6)
      const line = digits.slice(6)
      return `(${area}) ${prefix}-${line}`
    },
    formatInitialPhones() {
      if (this.familyForm) {
        this.familyForm.phone = this.formatPhone(this.familyForm.phone)
      }
      this.contacts.forEach(contact => {
        if (!contact) {
          return
        }
        contact.home_phone = this.formatPhone(contact.home_phone)
        contact.cell_phone = this.formatPhone(contact.cell_phone)
        contact.work_phone = this.formatPhone(contact.work_phone)
      })
    },
  },
}
</script>

