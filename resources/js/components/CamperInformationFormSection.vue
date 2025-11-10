<template>
  <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-zinc-900">
    <div class="mb-4">
      <div class="flex items-baseline justify-between gap-3 flex-wrap">
        <div>
          <h6 class="text-lg font-semibold text-gray-900 dark:text-white">Camper Information</h6>
          <span class="text-xs text-gray-500 dark:text-gray-400">* Required fields</span>
        </div>
        <div class="flex flex-wrap gap-2 text-sm">
          <InfoChip label="First Name" :value="localForms.information.camper.first_name" />
          <InfoChip label="Last Name" :value="localForms.information.camper.last_name" />
          <InfoChip label="Birthday" :value="localForms.information.camper.date_of_birth" />
          <InfoChip label="Gender" :value="localForms.information.camper.sex" />
          <InfoChip label="T-Shirt Size" :value="formatTShirtSize(localForms.information.camper.t_shirt_size)" />
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grade *</label>
        <select v-model="localForms.information.camper.grade" required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
          <option value="">Select Grade</option>
          <option value="K">Kindergarten</option>
          <option v-for="grade in 12" :key="grade" :value="grade">{{ grade }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">T-Shirt Size</label>
        <select v-model="localForms.information.camper.t_shirt_size"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
          <option value="">Select Size</option>
          <optgroup label="Youth">
            <option value="YXS">Youth XS</option>
            <option value="YS">Youth S</option>
            <option value="YM">Youth M</option>
            <option value="YL">Youth L</option>
            <option value="YXL">Youth XL</option>
          </optgroup>
          <optgroup label="Adult">
            <option value="XS">Adult XS</option>
            <option value="S">Adult S</option>
            <option value="M">Adult M</option>
            <option value="L">Adult L</option>
            <option value="XL">Adult XL</option>
            <option value="XXL">Adult XXL</option>
          </optgroup>
        </select>
      </div>
    </div>

    <!-- More fields would go here -->
  </div>
</template>

<script>
export default {
  name: 'CamperInformationFormSection',
  props: {
    localForms: {
      type: Object,
      required: true
    }
  },
  components: {
    InfoChip: {
      props: {
        label: {
          type: String,
          required: true
        },
        value: {
          type: [String, Number],
          default: ''
        }
      },
      template: `
        <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-zinc-800 px-3 py-1.5">
          <span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ label }}</span>
          <span class="font-medium text-gray-900 dark:text-white">{{ value || '—' }}</span>
        </div>
      `
    }
  },
  methods: {
    formatTShirtSize(value) {
      if (!value) {
        return ''
      }
      const sizeMap = {
        YXS: 'Youth XS',
        YS: 'Youth S',
        YM: 'Youth M',
        YL: 'Youth L',
        YXL: 'Youth XL',
        XS: 'Adult XS',
        S: 'Adult S',
        M: 'Adult M',
        L: 'Adult L',
        XL: 'Adult XL',
        XXL: 'Adult XXL'
      }
      return sizeMap[value] || value
    }
  }
}
</script>

