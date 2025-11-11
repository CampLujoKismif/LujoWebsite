<template>
  <div
    v-if="visible"
    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8 bg-black/60 backdrop-blur-sm"
    @click.self="$emit('close')"
  >
    <div class="w-full max-w-4xl bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-full">
      <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Complete Camper Forms
          </h3>
          <p v-if="camper" class="text-sm text-gray-500 dark:text-gray-400">
            {{ camper.first_name }} {{ camper.last_name }}
          </p>
        </div>
        <button
          type="button"
          class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
          @click="$emit('close')"
        >
          <span class="sr-only">Close</span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto px-5 py-4">
        <camper-registration-form
          v-if="camper && formsData"
          :camper="camper"
          :active-year="activeYear"
          :forms-data="formsData"
          :annual-status="annualStatus"
          @forms-updated="$emit('forms-updated', $event)"
          @agreements-updated="$emit('agreements-updated', $event)"
          @close="$emit('close')"
        />
      </div>
    </div>
  </div>
</template>

<script>
import CamperRegistrationForm from './CamperRegistrationForm.vue'

export default {
  name: 'CamperFormsModal',
  components: {
    CamperRegistrationForm,
  },
  props: {
    visible: {
      type: Boolean,
      default: false,
    },
    camper: {
      type: Object,
      default: null,
    },
    formsData: {
      type: Object,
      default: null,
    },
    annualStatus: {
      type: Object,
      default: null,
    },
    activeYear: {
      type: [String, Number],
      default: null,
    },
  },
  emits: [
    'close',
    'forms-updated',
    'agreements-updated',
  ],
}
</script>

