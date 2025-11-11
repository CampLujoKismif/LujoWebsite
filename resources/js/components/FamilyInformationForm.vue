<template>
  <div class="max-w-2xl mx-auto">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Family Information</h3>
    <p class="text-gray-600 dark:text-gray-400 mb-6">
      Please provide or confirm your family's contact and emergency information.
    </p>

    <form @submit.prevent="$emit('submit')" class="space-y-6">
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
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
          <input
            v-model="familyForm.phone"
            type="tel"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
          <input
            v-model="familyForm.address"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">City</label>
          <input
            v-model="familyForm.city"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">State</label>
          <input
            v-model="familyForm.state"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ZIP Code</label>
          <input
            v-model="familyForm.zip_code"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
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
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input
                  v-model="contact.name"
                  @input="contact.is_primary && $emit('sync-legacy')"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relationship</label>
                <input
                  v-model="contact.relation"
                  @input="contact.is_primary && $emit('sync-legacy')"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Phone</label>
                <input
                  v-model="contact.home_phone"
                  type="tel"
                  @input="contact.is_primary && $emit('sync-legacy')"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cell Phone</label>
                <input
                  v-model="contact.cell_phone"
                  type="tel"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Work Phone</label>
                <input
                  v-model="contact.work_phone"
                  type="tel"
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
          class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hoverbg-zinc-700"
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
  computed: {
    contacts() {
      return Array.isArray(this.familyForm?.emergency_contacts)
        ? this.familyForm.emergency_contacts
        : []
    },
  },
}
</script>

