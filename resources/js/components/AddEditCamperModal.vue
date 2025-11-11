<template>
  <div
    v-if="visible"
    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8 bg-black/60 backdrop-blur-sm"
    @click.self="$emit('close')"
  >
    <div class="w-full max-w-2xl bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-full">
      <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
          {{ editingCamper ? 'Edit Camper' : 'Add Camper' }}
        </h3>
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

      <div v-if="archivedCampers.length && !editingCamper" class="px-5 pt-4 pb-2 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800">
        <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-200">
          Previously Removed Campers
        </h4>
        <p class="mt-1 text-xs text-amber-700 dark:text-amber-300">
          Restore a camper to reuse their information instead of creating a new record.
        </p>
        <ul class="mt-3 space-y-2">
          <li
            v-for="archived in archivedCampers"
            :key="archived.id"
            class="flex items-start justify-between rounded-lg bg-white/70 dark:bg-zinc-800/60 px-3 py-2 shadow-sm"
          >
            <div class="text-sm">
              <p class="font-medium text-gray-900 dark:text-white">
                {{ archived.first_name }} {{ archived.last_name }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                <span v-if="archived.date_of_birth">DOB: {{ formatDate(archived.date_of_birth) }}</span>
                <span v-if="archived.has_upcoming_enrollment" class="ml-2 text-amber-700 dark:text-amber-300">
                  Upcoming registration
                </span>
              </p>
            </div>
            <button
              type="button"
              class="ml-3 inline-flex items-center rounded-md border border-indigo-200 dark:border-indigo-500 px-3 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 disabled:opacity-60 disabled:cursor-not-allowed"
              :disabled="restoringCamperId === archived.id"
              @click="$emit('restore-archived', archived.id)"
            >
              {{ restoringCamperId === archived.id ? 'Restoring…' : 'Restore' }}
            </button>
          </li>
        </ul>
      </div>

      <form @submit.prevent="$emit('submit')" class="flex-1 overflow-y-auto px-5 py-4 space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Camper Photo</label>
          <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4 gap-3">
            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 dark:bg-zinc-800 flex items-center justify-center">
              <template v-if="camperPhotoPreview">
                <img
                  :src="camperPhotoPreview"
                  alt="Camper photo preview"
                  class="w-full h-full object-contain"
                >
              </template>
              <template v-else-if="editingCamper?.photo_url">
                <img
                  :src="editingCamper.photo_url"
                  alt="Existing camper photo"
                  class="w-full h-full object-contain"
                >
              </template>
              <span v-else class="text-lg font-semibold text-gray-500 dark:text-gray-400">
                {{ (camperForm.first_name?.[0] || '') + (camperForm.last_name?.[0] || '') || 'C' }}
              </span>
            </div>
            <div class="flex-1 space-y-2">
              <input
                ref="photoInput"
                type="file"
                accept="image/*"
                @change="onPhotoChange"
                class="block w-full text-sm text-gray-600 dark:text-gray-300
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0
                       file:text-sm file:font-medium
                       file:bg-indigo-50 file:text-indigo-700
                       hover:file:bg-indigo-100"
              >
              <div class="flex items-center gap-2" v-if="camperPhotoFileName">
                <span class="text-xs text-gray-600 dark:text-gray-300">Selected: {{ camperPhotoFileName }}</span>
                <button
                  type="button"
                  class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                  @click="$emit('clear-photo')"
                >
                  Remove
                </button>
              </div>
              <p v-if="camperPhotoError" class="text-xs text-red-600 dark:text-red-400">{{ camperPhotoError }}</p>
              <p v-else class="text-xs text-gray-500 dark:text-gray-400">Optional. JPG, PNG, GIF, or WebP (max 5 MB).</p>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">First Name *</label>
            <input
              :value="camperForm.first_name"
              required
              @input="updateField('first_name', $event.target.value)"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Last Name *</label>
            <input
              :value="camperForm.last_name"
              required
              @input="updateField('last_name', $event.target.value)"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth *</label>
            <input
              :value="camperForm.date_of_birth"
              type="date"
              required
              @input="updateField('date_of_birth', $event.target.value)"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gender</label>
            <select
              :value="camperForm.gender"
              @change="updateField('gender', $event.target.value)"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
            >
              <option value="">Select</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
        </div>

        <div class="space-y-3 sm:space-y-0 sm:flex sm:items-center sm:justify-between">
          <div v-if="editingCamper" class="space-y-2">
            <button
              type="button"
              class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-300 rounded-md border border-red-200 dark:border-red-900/40 hover:bg-red-100 dark:hover:bg-red-900/40 disabled:opacity-60 disabled:cursor-not-allowed"
              :disabled="deletingCamper || camperHasUpcomingEnrollment || processing"
              @click="$emit('delete-editing')"
            >
              {{ deletingCamper ? 'Removing…' : 'Remove Camper' }}
            </button>
            <p
              v-if="camperHasUpcomingEnrollment"
              class="text-xs font-medium text-amber-700 dark:text-amber-300"
            >
              This camper is registered for an upcoming camp and cannot be removed.
            </p>
          </div>
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700"
              :disabled="processing || deletingCamper"
              @click="$emit('close')"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="processing || deletingCamper"
              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ processing ? 'Saving...' : editingCamper ? 'Save Changes' : 'Add Camper' }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AddEditCamperModal',
  props: {
    visible: {
      type: Boolean,
      default: false,
    },
    editingCamper: {
      type: Object,
      default: null,
    },
    archivedCampers: {
      type: Array,
      default: () => [],
    },
    restoringCamperId: {
      type: [Number, String],
      default: null,
    },
    camperForm: {
      type: Object,
      required: true,
    },
    processing: {
      type: Boolean,
      default: false,
    },
    deletingCamper: {
      type: Boolean,
      default: false,
    },
    camperPhotoPreview: {
      type: String,
      default: null,
    },
    camperPhotoFileName: {
      type: String,
      default: '',
    },
    camperPhotoError: {
      type: String,
      default: '',
    },
    camperHasUpcomingEnrollment: {
      type: Boolean,
      default: false,
    },
  },
  emits: [
    'close',
    'submit',
    'restore-archived',
    'photo-change',
    'clear-photo',
    'delete-editing',
    'update:camperForm',
  ],
  watch: {
    visible(newValue) {
      if (!newValue) {
        this.resetPhotoInput()
      }
    },
    camperPhotoFileName(newValue) {
      if (!newValue) {
        this.resetPhotoInput()
      }
    },
  },
  methods: {
    updateField(field, value) {
      this.$emit('update:camperForm', {
        ...this.camperForm,
        [field]: value,
      })
    },
    onPhotoChange(event) {
      this.$emit('photo-change', event)
    },
    resetPhotoInput() {
      if (this.$refs.photoInput) {
        this.$refs.photoInput.value = ''
      }
    },
    formatDate(dateString) {
      if (!dateString) {
        return ''
      }
      const date = new Date(dateString)
      if (Number.isNaN(date.getTime())) {
        return dateString
      }
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      })
    },
  },
}
</script>

