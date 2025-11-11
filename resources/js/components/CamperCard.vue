<template>
  <div
    :class="[
      'camper-card flex flex-col bg-white dark:bg-zinc-900 rounded-xl border transition-all duration-200 shadow-sm hover:shadow-md w-full max-w-[400px] h-[405px] overflow-hidden',
      selected
        ? 'border-indigo-500 ring-2 ring-indigo-400'
        : alreadyRegistered
          ? 'border-emerald-500 ring-2 ring-emerald-400'
          : 'border-gray-200 dark:border-gray-700',
      disabled ? 'opacity-60 pointer-events-none' : '',
    ]"
  >
    <!-- Photo / Header -->
    <div class="relative h-40 bg-gray-100 dark:bg-zinc-800">
      <div
        v-if="photoUrl"
        class="w-full h-full flex items-center justify-center bg-white"
      >
        <img
          :src="photoUrl"
          :alt="`${camperName} photo`"
          class="max-w-full max-h-full object-contain"
        >
      </div>
      <div
        v-else
        class="flex items-center justify-center w-full h-full text-3xl font-semibold text-white bg-gradient-to-br from-indigo-500 to-purple-500"
      >
        {{ initials }}
      </div>

      <div class="absolute top-3 left-3 space-y-1">
        <span
          v-if="selected"
          class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-indigo-600 rounded-full shadow-sm"
        >
          Selected
        </span>
        <span
          v-else-if="alreadyRegistered"
          class="inline-flex items-center px-2 py-1 text-xs font-medium text-emerald-800 bg-emerald-100 rounded-full shadow-sm dark:bg-emerald-900/40 dark:text-emerald-200"
        >
          Registered
        </span>
        <span
          v-if="formsComplete"
          class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full shadow-sm dark:bg-green-900/40 dark:text-green-200"
        >
          ✓ Forms Complete
        </span>
        <span
          v-else
          class="inline-flex items-center px-2 py-1 text-xs font-medium text-amber-800 bg-amber-100 rounded-full shadow-sm dark:bg-amber-900/40 dark:text-amber-200"
        >
          Forms Needed
        </span>
      </div>

      <button
        type="button"
        class="absolute top-3 right-3 inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 bg-white/90 rounded-full shadow hover:bg-white"
        @click="$emit('edit-camper', camper)"
      >
        Edit
      </button>
    </div>

    <!-- Content -->
    <div class="flex-1 p-4 flex flex-col overflow-hidden">
      <div class="flex-1 overflow-y-auto space-y-3">
        <div>
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white leading-tight">
            {{ camperName }}
          </h3>
          <p v-if="camper.nickname" class="text-sm text-gray-500 dark:text-gray-400">
            "{{ camper.nickname }}"
          </p>
        </div>

        <div class="grid grid-cols-1 gap-2 text-sm">
          <div class="flex items-center justify-between">
            <span class="text-gray-500 dark:text-gray-400">Birthday</span>
            <span class="font-medium text-gray-900 dark:text-white">{{ formattedBirthday }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-gray-500 dark:text-gray-400">Age</span>
            <span class="font-medium text-gray-900 dark:text-white">{{ ageDisplay }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-gray-500 dark:text-gray-400">Gender</span>
            <span class="font-medium text-gray-900 dark:text-white">{{ genderDisplay }}</span>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="pt-4 mt-auto">
        <div class="flex flex-wrap items-center gap-2">
          <template v-if="showSelectionActions && !alreadyRegistered">
            <button
              v-if="selected"
              type="button"
              class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-300 rounded-md hover:bg-red-100 dark:hover:bg-red-900/40"
              @click="$emit('deselect', camper)"
            >
              Deselect
            </button>
            <button
              v-else
              type="button"
              class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
              @click="handleSelect"
            >
              Select Camper
            </button>
          </template>
          <div
            v-else-if="alreadyRegistered"
            class="w-full text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-200 px-4 py-2 rounded-md text-center"
          >
            Already registered for this session
          </div>
          <button
            v-if="allowFormsAccess"
            type="button"
            class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-amber-700 bg-amber-100 rounded-md hover:bg-amber-200 dark:bg-amber-900/30 dark:text-amber-200"
            @click="$emit('request-forms', camper)"
          >
            {{ formsComplete ? 'View / Update Forms' : 'Complete Forms' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const SHIRT_LABELS = {
  XS: 'XS',
  S: 'S',
  M: 'M',
  L: 'L',
  XL: 'XL',
  XXL: 'XXL',
}

export default {
  name: 'CamperCard',
  props: {
    camper: {
      type: Object,
      required: true,
    },
    selected: {
      type: Boolean,
      default: false,
    },
    formsComplete: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    showSelectionActions: {
      type: Boolean,
      default: true,
    },
    allowFormsAccess: {
      type: Boolean,
      default: false,
    },
    alreadyRegistered: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['select', 'deselect', 'request-forms', 'edit-camper'],
  computed: {
    camperName() {
      const first = this.camper.first_name || ''
      const last = this.camper.last_name || ''
      return `${first} ${last}`.trim() || 'Unnamed Camper'
    },
    initials() {
      const first = this.camper.first_name?.[0] || ''
      const last = this.camper.last_name?.[0] || ''
      return `${first}${last}`.toUpperCase() || 'C'
    },
    photoUrl() {
      return this.camper.photo_url || this.camper.photo || null
    },
    formattedBirthday() {
      if (!this.camper.date_of_birth) return '—'
      const date = new Date(this.camper.date_of_birth)
      if (Number.isNaN(date.getTime())) return this.camper.date_of_birth
      return date.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      })
    },
    ageDisplay() {
      const age = this.calculateAge(this.camper.date_of_birth)
      return age >= 0 ? `${age} years` : '—'
    },
    gradeDisplay() {
      const grade = this.camper.grade
      if (grade === undefined || grade === null || grade === '') {
        return '—'
      }
      if (grade === 'K') {
        return 'Kindergarten'
      }
      return `Grade ${grade}`
    },
    genderDisplay() {
      return this.camper.biological_gender || '—'
    },
  },
  methods: {
    calculateAge(dateString) {
      if (!dateString) return -1
      const dob = new Date(dateString)
      if (Number.isNaN(dob.getTime())) return -1
      const today = new Date()
      let age = today.getFullYear() - dob.getFullYear()
      const monthDiff = today.getMonth() - dob.getMonth()
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        age -= 1
      }
      return age
    },
    handleSelect() {
      if (this.alreadyRegistered) {
        return
      }
      if (this.selected) {
        this.$emit('deselect', this.camper)
        return
      }

      if (this.formsComplete) {
        this.$emit('select', this.camper)
      } else {
        this.$emit('request-forms', this.camper)
      }
    },
  },
}
</script>

