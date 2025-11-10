<template>
  <div class="camper-registration-form">
    <!-- Forms Section -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-4">
        <h6 class="text-lg font-semibold text-gray-900 dark:text-white">Information Forms</h6>
        <div class="flex items-center space-x-2">
          <span v-if="formsComplete" class="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-2 py-1 rounded">
            ✓ Forms Complete
          </span>
          <span v-else-if="formsOnFile" class="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded">
            Forms on File
          </span>
          <button v-if="formsOnFile && !editingForms"
                  @click="editingForms = true"
                  class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
            Edit
          </button>
        </div>
      </div>

      <!-- Forms Content -->
      <div v-if="!formsOnFile || editingForms">
        <CamperInformationFormSection :local-forms="localForms" />

        <!-- Emergency Contacts -->
        <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-zinc-900">
          <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <h6 class="text-lg font-semibold text-gray-900 dark:text-white">Emergency Contacts</h6>
            <div class="flex items-center gap-2">
              <button type="button" @click="importEmergencyContactsFromFamily"
                      :disabled="importingFamilyContacts"
                      class="text-sm px-3 py-1 border border-indigo-200 dark:border-indigo-700 rounded-md text-indigo-600 dark:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 disabled:opacity-50 disabled:cursor-not-allowed">
                {{ importingFamilyContacts ? 'Importing Contacts...' : 'Import Emergency Contacts from Family' }}
              </button>
              <button type="button" @click="addEmergencyContact"
                      class="text-sm px-3 py-1 border border-indigo-200 dark:border-indigo-700 rounded-md text-indigo-600 dark:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30">
                Add Contact
              </button>
              <button type="button" @click="clearAllEmergencyContacts"
                      class="text-sm px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800">
                Clear All Contacts
              </button>
              <button type="button" @click="copyEmergencyToAll"
                      class="text-sm px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800">
                Copy Primary to All Campers
              </button>
            </div>
          </div>

          <div v-if="localForms.information.emergency_contacts.length" class="space-y-4">
            <div v-for="(contact, index) in localForms.information.emergency_contacts"
                 :key="`emergency-contact-${index}`"
                 class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-zinc-900/60">
              <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-3">
                <div>
                  <div class="text-sm font-semibold text-gray-900 dark:text-white">
                    Emergency Contact {{ index + 1 }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">
                    Provide contact details in case we cannot reach a guardian.
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" v-model="contact.authorized_pickup"
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    Authorized for pickup
                  </label>
                  <button type="button"
                          @click="removeEmergencyContact(index)"
                          :disabled="localForms.information.emergency_contacts.length === 1"
                          class="text-sm text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 disabled:opacity-40 disabled:cursor-not-allowed">
                    Remove
                  </button>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                  <input v-model="contact.name" type="text" required
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relationship *</label>
                  <input v-model="contact.relation" type="text" required
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Phone *</label>
                  <input v-model="contact.home_phone" type="tel" required
                         @input="contact.home_phone = formatPhoneInput(contact.home_phone)"
                         @blur="contact.home_phone = formatPhone(contact.home_phone)"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cell Phone</label>
                  <input v-model="contact.cell_phone" type="tel"
                         @input="contact.cell_phone = formatPhoneInput(contact.cell_phone)"
                         @blur="contact.cell_phone = formatPhone(contact.cell_phone)"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Work Phone</label>
                  <input v-model="contact.work_phone" type="tel"
                         @input="contact.work_phone = formatPhoneInput(contact.work_phone)"
                         @blur="contact.work_phone = formatPhone(contact.work_phone)"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                  <input v-model="contact.email" type="email"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                  <input v-model="contact.address" type="text"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                    <input v-model="contact.city" type="text"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">State</label>
                    <input v-model="contact.state" type="text"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ZIP</label>
                    <input v-model="contact.zip" type="text"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="rounded-md border border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-zinc-900/40 px-4 py-6 text-center text-sm text-gray-600 dark:text-gray-400">
            No emergency contacts yet. Use “Add Contact” to include at least one trusted adult.
          </div>
        </div>

        <!-- Medical Information Form -->
        <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-zinc-900">
          <h6 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Medical Information</h6>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Life or health issues</label>
              <textarea v-model="localForms.medical.medical.life_health_issues" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current medications</label>
              <textarea v-model="localForms.medical.medical.medications" rows="4"
                        placeholder="Medication name - Dosage&#10;Medication name - Dosage"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Allergies</label>
              <textarea v-model="localForms.medical.medical.allergies" rows="4"
                        placeholder="Allergy&#10;Allergy"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Physician Name</label>
              <input v-model="localForms.medical.medical.physician_name" type="text"
                     class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Physician Phone</label>
              <input v-model="localForms.medical.medical.physician_phone" type="tel"
                     @input="localForms.medical.medical.physician_phone = formatPhoneInput(localForms.medical.medical.physician_phone)"
                     @blur="localForms.medical.medical.physician_phone = formatPhone(localForms.medical.medical.physician_phone)"
                     class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="md:col-span-2 mt-4">
              <div class="mb-3">
                <h6 class="text-sm font-semibold text-gray-900 dark:text-white">Over-the-counter permissions</h6>
                <p class="text-xs text-gray-500 dark:text-gray-400">Select the treatments we can provide if needed.</p>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-start space-x-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-zinc-800 px-3 py-2 text-sm text-gray-700 dark:text-gray-300">
                  <input type="checkbox" v-model="localForms.medical.otc_permissions.pain"
                         class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                  <span>Pain &amp; fever relief (e.g., acetaminophen, ibuprofen)</span>
                </label>
                <label class="flex items-start space-x-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-zinc-800 px-3 py-2 text-sm text-gray-700 dark:text-gray-300">
                  <input type="checkbox" v-model="localForms.medical.otc_permissions.skin"
                         class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                  <span>Skin care (e.g., hydrocortisone, calamine)</span>
                </label>
                <label class="flex items-start space-x-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-zinc-800 px-3 py-2 text-sm text-gray-700 dark:text-gray-300">
                  <input type="checkbox" v-model="localForms.medical.otc_permissions.cuts"
                         class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                  <span>Cuts &amp; scrapes (e.g., antibiotic ointment, bandages)</span>
                </label>
                <label class="flex items-start space-x-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-zinc-800 px-3 py-2 text-sm text-gray-700 dark:text-gray-300">
                  <input type="checkbox" v-model="localForms.medical.otc_permissions.stomach"
                         class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                  <span>Stomach relief (e.g., antacids, anti-nausea)</span>
                </label>
                <label class="flex items-start space-x-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-zinc-800 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 sm:col-span-2">
                  <input type="checkbox" v-model="localForms.medical.otc_permissions.allergies"
                         class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                  <span>Allergy relief (e.g., antihistamines, eye drops)</span>
                </label>
              </div>
            </div>
          </div>
        </div>
<!-- Camper Agreement -->
<div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-zinc-900">
        <h6 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Camper Agreement</h6>
        <div class="mb-4 p-3 bg-gray-50 dark:bg-zinc-800 rounded text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
          {{ annualStatus?.camper_agreement?.content || 'Agreement content not available.' }}
        </div>

        <div v-if="!camperAgreementSigned" class="space-y-3">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Camper typed name *</label>
            <input v-model="camperSignature.typed_name"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
          </div>
          <label class="flex items-start space-x-2 text-sm text-gray-700 dark:text-gray-300">
            <input type="checkbox" v-model="camperSignature.confirmed"
                   class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <span>I confirm that {{ camper.first_name }} {{ camper.last_name }} has read and agrees to the camper agreement for {{ activeYear }}.</span>
          </label>
          <button @click="signCamperAgreement" :disabled="signingCamper || !camperSignatureReady"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
            {{ signingCamper ? 'Signing...' : 'Sign Camper Agreement' }}
          </button>
        </div>

        <div v-else class="flex items-center space-x-2 text-green-700 dark:text-green-300">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span>Camper agreement signed</span>
        </div>
      </div>
        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
          <button v-if="editingForms" @click="cancelFormEdit"
                  class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700">
            Cancel
          </button>
          <button @click="saveForms" :disabled="savingForms"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
            {{ savingForms ? 'Saving...' : 'Save Forms' }}
          </button>
        </div>
      </div>

      <!-- Forms Summary when not editing -->
      <div v-else class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-center space-x-2 mb-2">
          <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span class="font-medium text-green-800 dark:text-green-200">Forms completed for {{ activeYear }}</span>
        </div>
        <p class="text-sm text-green-700 dark:text-green-300">
          Information for {{ camper.first_name }} {{ camper.last_name }} has been saved and can be updated annually.
        </p>
      </div>
    </div>

    <!-- Agreements Section -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-4">
        <h6 class="text-lg font-semibold text-gray-900 dark:text-white">Agreements</h6>
        <span v-if="agreementsComplete" class="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-2 py-1 rounded">
          ✓ Agreements Signed
        </span>
      </div>

      <!-- Parent Agreement -->
      <div v-if="!parentAgreementSigned" class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-zinc-900">
        <h6 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Parent / Guardian Agreement</h6>
        <div class="mb-4 p-3 bg-gray-50 dark:bg-zinc-800 rounded text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
          {{ annualStatus?.parent?.agreement?.content || 'Agreement content not available.' }}
        </div>
        <div class="space-y-3">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type your full name *</label>
            <input v-model="parentSignature.typed_name"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
          </div>
          <label class="flex items-start space-x-2 text-sm text-gray-700 dark:text-gray-300">
            <input type="checkbox" v-model="parentSignature.confirmed"
                   class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <span>I confirm that I have read and agree to the Parent / Guardian Agreement for {{ activeYear }}.</span>
          </label>
          <button @click="signParentAgreement" :disabled="signingParent || !parentSignatureReady"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
            {{ signingParent ? 'Signing...' : 'Sign Parent Agreement' }}
          </button>
        </div>
      </div>

      <!-- Parent Agreement Signed -->
      <div v-else class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-center space-x-2">
          <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span class="font-medium text-green-800 dark:text-green-200">Parent agreement signed</span>
        </div>
      </div>

      
    </div>
  </div>
</template>

<script>
import CamperInformationFormSection from './CamperInformationFormSection.vue'

export default {
  name: 'CamperRegistrationForm',
  components: {
    CamperInformationFormSection
  },
  props: {
    camper: {
      type: Object,
      required: true
    },
    activeYear: {
      type: Number,
      required: true
    },
    formsData: {
      type: Object,
      default: null
    },
    annualStatus: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      editingForms: false,
      savingForms: false,
      signingParent: false,
      signingCamper: false,
      importingFamilyContacts: false,
      localForms: this.initializeFormsData(),
      parentSignature: {
        typed_name: '',
        confirmed: false
      },
      camperSignature: {
        typed_name: '',
        confirmed: false
      }
    }
  },
  computed: {
    formsOnFile() {
      return !!(this.formsData?.information && this.formsData?.medical)
    },
    formsComplete() {
      return this.formsOnFile && !this.editingForms
    },
    parentAgreementSigned() {
      return !!this.annualStatus?.parent?.signed
    },
    camperAgreementSigned() {
      const camperStatus = this.annualStatus?.campers?.find(c => c.camper_id === this.camper.id)
      return !!camperStatus?.signed
    },
    agreementsComplete() {
      return this.parentAgreementSigned && this.camperAgreementSigned
    },
    parentSignatureReady() {
      return this.parentSignature.typed_name?.trim().length > 0 && this.parentSignature.confirmed
    },
    camperSignatureReady() {
      return this.camperSignature.typed_name?.trim().length > 0 && this.camperSignature.confirmed
    }
  },
  watch: {
    formsData: {
      handler(newData) {
        if (newData) {
          this.localForms = this.normalizeFormData(newData)
        }
      },
      immediate: true,
      deep: true
    }
  },
  methods: {
    initializeFormsData() {
      const primaryEmergencyContact = this.createEmergencyContact({
        name: '',
        relation: '',
        home_phone: '',
        cell_phone: '',
        work_phone: '',
        email: '',
        address: '',
        city: '',
        state: '',
        zip: '',
        authorized_pickup: false
      })

      return {
        information: {
          camper: {
            first_name: this.camper.first_name || '',
            last_name: this.camper.last_name || '',
            date_of_birth: this.camper.date_of_birth || '',
            grade: this.camper.grade || '',
            sex: '',
            t_shirt_size: this.camper.t_shirt_size || '',
            address: '',
            city: '',
            state: '',
            zip: '',
            home_phone: '',
            alternate_phone: '',
            email: '',
            alternate_email: '',
            home_church: '',
            parent_marital_status: '',
            lives_with: ''
          },
          emergency_contact: { ...primaryEmergencyContact },
          emergency_contacts: [{ ...primaryEmergencyContact }]
        },
        medical: {
          medical: {
            life_health_issues: '',
            medications_prescribed: '',
            medications: '',
            allergies: '',
            physician_name: '',
            physician_phone: '',
            tetanus_date: ''
          },
          otc_permissions: {
            pain: false,
            skin: false,
            cuts: false,
            stomach: false,
            allergies: false
          },
          insurance: {
            insured_name: '',
            company: '',
            policy_number: '',
            phone: ''
          }
        }
      }
    },
    normalizeFormData(rawForm) {
      const information = rawForm?.information || {}
      const medical = rawForm?.medical || {}
      const camperHomePhone = this.formatPhone(information?.camper?.home_phone || '')
      const camperAlternatePhone = this.formatPhone(information?.camper?.alternate_phone || '')
      const physicianPhone = this.formatPhone(medical?.medical?.physician_phone || '')

      const rawEmergencyContacts = Array.isArray(information?.emergency_contacts)
        ? information.emergency_contacts
        : []

      let emergencyContacts = rawEmergencyContacts.map(contact => this.createEmergencyContact(contact))

      if (!emergencyContacts.length) {
        emergencyContacts = [this.createEmergencyContact(information?.emergency_contact)]
      }

      const primaryEmergencyContact = emergencyContacts[0] || this.createEmergencyContact()

      return {
        information: {
          camper: {
            first_name: information?.camper?.first_name || '',
            last_name: information?.camper?.last_name || '',
            date_of_birth: information?.camper?.date_of_birth || '',
            grade: information?.camper?.grade || '',
            sex: information?.camper?.sex || '',
            t_shirt_size: information?.camper?.t_shirt_size || '',
            address: information?.camper?.address || '',
            city: information?.camper?.city || '',
            state: information?.camper?.state || '',
            zip: information?.camper?.zip || '',
            home_phone: camperHomePhone,
            alternate_phone: camperAlternatePhone,
            email: information?.camper?.email || '',
            alternate_email: information?.camper?.alternate_email || '',
            home_church: information?.camper?.home_church || '',
            parent_marital_status: information?.camper?.parent_marital_status || '',
            lives_with: information?.camper?.lives_with || ''
          },
          emergency_contact: { ...primaryEmergencyContact },
          emergency_contacts: emergencyContacts
        },
        medical: {
          medical: {
            life_health_issues: medical?.medical?.life_health_issues || '',
            medications_prescribed: medical?.medical?.medications_prescribed || '',
            medications: medical?.medical?.medications || '',
            allergies: medical?.medical?.allergies || '',
            physician_name: medical?.medical?.physician_name || '',
            physician_phone: physicianPhone,
            tetanus_date: medical?.medical?.tetanus_date || ''
          },
          otc_permissions: {
            pain: medical?.otc_permissions?.pain || false,
            skin: medical?.otc_permissions?.skin || false,
            cuts: medical?.otc_permissions?.cuts || false,
            stomach: medical?.otc_permissions?.stomach || false,
            allergies: medical?.otc_permissions?.allergies || false
          },
          insurance: {
            insured_name: medical?.insurance?.insured_name || '',
            company: medical?.insurance?.company || '',
            policy_number: medical?.insurance?.policy_number || '',
            phone: medical?.insurance?.phone || ''
          }
        }
      }
    },
    async saveForms() {
      this.savingForms = true
      try {
        const payload = {
          campers: [this.prepareFormForSubmit()]
        }

        const response = await fetch('/api/public-registration/forms', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          },
          credentials: 'same-origin',
          body: JSON.stringify(payload)
        })

        if (!response.ok) {
          throw new Error('Failed to save forms')
        }

        this.editingForms = false
        this.$emit('forms-updated', { camperId: this.camper.id })
      } catch (error) {
        console.error('Error saving forms:', error)
        // Handle error - could emit error event
      } finally {
        this.savingForms = false
      }
    },
    cancelFormEdit() {
      // Reset local forms to original data
      if (this.formsData) {
        this.localForms = this.normalizeFormData(this.formsData)
      }
      this.editingForms = false
    },
    prepareFormForSubmit() {
      const information = JSON.parse(JSON.stringify(this.localForms.information || {}))
      const medical = JSON.parse(JSON.stringify(this.localForms.medical || {}))

      // Convert grade to number if needed
      if (information?.camper?.grade && information.camper.grade !== '') {
        const numericGrade = Number(information.camper.grade)
        information.camper.grade = Number.isNaN(numericGrade) ? information.camper.grade : numericGrade
      }

      if (information?.camper) {
        information.camper.home_phone = this.formatPhone(information.camper.home_phone)
        information.camper.alternate_phone = this.formatPhone(information.camper.alternate_phone)
      }

      // Ensure emergency contacts are normalized
      const emergencyContacts = Array.isArray(information?.emergency_contacts)
        ? information.emergency_contacts
        : []
      information.emergency_contacts = emergencyContacts.map(contact => this.createEmergencyContact(contact))

      if (!information.emergency_contacts.length) {
        information.emergency_contacts = [this.createEmergencyContact(information?.emergency_contact)]
      }

      const primaryEmergencyContact = information.emergency_contacts[0] || this.createEmergencyContact()
      information.emergency_contact = { ...primaryEmergencyContact }

      // Convert medications and allergies to arrays
      if (medical?.medical?.medications) {
        medical.medical.medications = this.stringToArray(medical.medical.medications)
      }
      if (medical?.medical?.allergies) {
        medical.medical.allergies = this.stringToArray(medical.medical.allergies)
      }

      if (medical?.medical) {
        medical.medical.physician_phone = this.formatPhone(medical.medical.physician_phone)
      }

      return {
        camper_id: this.camper.id,
        information,
        medical
      }
    },
    stringToArray(value) {
      if (Array.isArray(value)) {
        return value
      }
      if (typeof value !== 'string') {
        return []
      }
      return value
        .split(/\r?\n/)
        .map(entry => entry.trim())
        .filter(entry => entry.length > 0)
    },
    formatPhone(value) {
      return this.formatPhoneInput(value)
    },
    formatPhoneInput(value) {
      const digits = this.extractPhoneDigits(value)
      return this.formatPhoneFromDigits(digits)
    },
    extractPhoneDigits(value) {
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
    addEmergencyContact() {
      this.localForms.information.emergency_contacts.push(this.createEmergencyContact())
    },
    removeEmergencyContact(index) {
      if (!Array.isArray(this.localForms.information.emergency_contacts)) {
        return
      }
      if (index < 0 || index >= this.localForms.information.emergency_contacts.length) {
        return
      }
      this.localForms.information.emergency_contacts.splice(index, 1)
      if (!this.localForms.information.emergency_contacts.length) {
        this.localForms.information.emergency_contacts.push(this.createEmergencyContact())
      }
    },
    clearAllEmergencyContacts() {
      if (!this.localForms?.information) {
        return
      }
      const blankContact = this.createEmergencyContact()
      this.localForms.information.emergency_contacts = [blankContact]
      this.localForms.information.emergency_contact = { ...blankContact }
    },
    async importEmergencyContactsFromFamily() {
      if (this.importingFamilyContacts) {
        return
      }
      this.importingFamilyContacts = true
      try {
        const response = await fetch('/api/public-registration/user-data', {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          credentials: 'same-origin'
        })

        if (!response.ok) {
          throw new Error('Failed to load family emergency contacts')
        }

        const data = await response.json()
        const family = data?.family || {}
        let rawContacts = Array.isArray(family.emergency_contacts) ? [...family.emergency_contacts] : []

        if (rawContacts.length > 1) {
          rawContacts.sort((a, b) => Number(!!b?.is_primary) - Number(!!a?.is_primary))
        }

        if (!rawContacts.length) {
          const legacy = this.createEmergencyContact({
            name: family.emergency_contact_name || '',
            relation: family.emergency_contact_relationship || '',
            phone: family.emergency_contact_phone || ''
          })

          const hasLegacyData = Object.values({
            name: legacy.name,
            relation: legacy.relation,
            home_phone: legacy.home_phone,
            cell_phone: legacy.cell_phone,
            email: legacy.email,
            address: legacy.address
          }).some(value => typeof value === 'string' ? value.trim().length > 0 : !!value)

          if (hasLegacyData) {
            rawContacts = [{ ...legacy }]
          }
        }

        let normalized = rawContacts.map(contact => this.createEmergencyContact(contact))

        if (!normalized.length) {
          normalized = [this.createEmergencyContact()]
        }

        this.localForms.information.emergency_contacts = normalized
        this.localForms.information.emergency_contact = { ...normalized[0] }
      } catch (error) {
        console.error('Error importing emergency contacts from family:', error)
        window.alert('We could not import your family emergency contacts. Please try again or enter them manually.')
      } finally {
        this.importingFamilyContacts = false
      }
    },
    copyEmergencyToAll() {
      const contacts = this.localForms?.information?.emergency_contacts || []
      const primaryContact = contacts[0] ? { ...contacts[0] } : this.createEmergencyContact()
      this.$emit('copy-emergency-contact', primaryContact)
    },
    createEmergencyContact(overrides = {}) {
      const rawHomePhone = overrides?.home_phone ?? overrides?.phone ?? ''
      const rawCellPhone = overrides?.cell_phone ?? overrides?.mobile_phone ?? ''
      const rawWorkPhone = overrides?.work_phone ?? ''

      return {
        name: overrides?.name || '',
        relation: overrides?.relation || overrides?.relationship || '',
        home_phone: this.formatPhone(rawHomePhone),
        cell_phone: this.formatPhone(rawCellPhone),
        work_phone: this.formatPhone(rawWorkPhone),
        email: overrides?.email || '',
        address: overrides?.address || '',
        city: overrides?.city || '',
        state: overrides?.state || '',
        zip: overrides?.zip || '',
        authorized_pickup: !!(overrides?.authorized_pickup ?? overrides?.authorizedPickup ?? false)
      }
    },
    async signParentAgreement() {
      this.signingParent = true
      try {
        const response = await fetch('/api/public-registration/annual-confirmation', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          },
          credentials: 'same-origin',
          body: JSON.stringify({
            year: this.activeYear,
            parent_signature_name: this.parentSignature.typed_name,
            parent_signature_agreed: true,
            campers: [] // Empty since we're only signing parent agreement
          })
        })

        if (!response.ok) {
          throw new Error('Failed to sign parent agreement')
        }

        this.$emit('agreements-updated', { type: 'parent' })
      } catch (error) {
        console.error('Error signing parent agreement:', error)
      } finally {
        this.signingParent = false
      }
    },
    async signCamperAgreement() {
      this.signingCamper = true
      try {
        const response = await fetch('/api/public-registration/annual-confirmation', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          },
          credentials: 'same-origin',
          body: JSON.stringify({
            year: this.activeYear,
            parent_signature_name: this.parentSignature.typed_name || this.annualStatus?.parent?.typed_name || '',
            parent_signature_agreed: true,
            campers: [{
              camper_id: this.camper.id,
              signature_name: this.camperSignature.typed_name
            }]
          })
        })

        if (!response.ok) {
          throw new Error('Failed to sign camper agreement')
        }

        this.$emit('agreements-updated', { type: 'camper', camperId: this.camper.id })
      } catch (error) {
        console.error('Error signing camper agreement:', error)
      } finally {
        this.signingCamper = false
      }
    }
  }
}
</script>

