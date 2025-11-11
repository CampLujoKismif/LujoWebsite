<template>
  <div class="camper-registration-form">
    <!-- Forms Section -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-2">
        <h6 class="text-lg font-semibold text-gray-900 dark:text-white">Information Forms</h6>
        <div class="flex items-center space-x-2">
          <span v-if="formsComplete" class="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-2 py-1 rounded">
            ✓ Forms Complete
          </span>
          <span v-else-if="formsOnFile" class="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded">
            Forms on File
          </span>
        </div>
      </div>
      <div v-if="missingRequirements.length"
           class="mb-4 text-xs text-amber-700 dark:text-amber-300 leading-snug">
        <span class="font-medium">Required:</span>
        {{ missingRequirements.join(', ') }}
      </div>

      <!-- Forms Content -->
      <div>
        <CamperInformationFormSection
          :local-forms="localForms"
          :validation-errors="validationErrors"
          @field-updated="clearFieldError"
        />

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
                         @input="handleFieldInput('emergency.name', index === 0)"
                         :class="[
                           'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500',
                           index === 0 && hasFieldError('emergency.name') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-500' : ''
                         ]">
                  <p v-if="index === 0 && hasFieldError('emergency.name')"
                     class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                    Primary emergency contact name is required.
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relationship *</label>
                  <input v-model="contact.relation" type="text" required
                         @input="handleFieldInput('emergency.relation', index === 0)"
                         :class="[
                           'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500',
                           index === 0 && hasFieldError('emergency.relation') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-500' : ''
                         ]">
                  <p v-if="index === 0 && hasFieldError('emergency.relation')"
                     class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                    Primary emergency contact relationship is required.
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Phone *</label>
                  <input v-model="contact.home_phone" type="tel" required
                         @input="
                           contact.home_phone = formatPhoneInput(contact.home_phone);
                           handleFieldInput('emergency.home_phone', index === 0);
                         "
                         @blur="
                           contact.home_phone = formatPhone(contact.home_phone);
                           handleFieldInput('emergency.home_phone', index === 0);
                         "
                         :class="[
                           'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500',
                           index === 0 && hasFieldError('emergency.home_phone') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-500' : ''
                         ]">
                  <p v-if="index === 0 && hasFieldError('emergency.home_phone')"
                     class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                    Primary emergency contact phone is required.
                  </p>
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
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Insured</label>
                <input v-model="localForms.medical.insurance.insured_name" type="text"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Group Number</label>
                <input v-model="localForms.medical.insurance.group_number" type="text"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Policy Number</label>
                <input v-model="localForms.medical.insurance.policy_number" type="text" required
                       @input="handleFieldInput('insurance.policy_number')"
                       :class="[
                         'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500',
                         hasFieldError('insurance.policy_number') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-500' : ''
                       ]">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Insurance Company</label>
                <input v-model="localForms.medical.insurance.company" type="text" required
                       @input="handleFieldInput('insurance.company')"
                       :class="[
                         'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500',
                         hasFieldError('insurance.company') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-500' : ''
                       ]">
              </div>
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
          <p v-if="hasFieldError('agreement.camper')"
             class="text-sm text-rose-600 dark:text-rose-400">
            Please have the camper sign this agreement before saving forms.
          </p>
        </div>

        <div v-else class="flex items-center space-x-2 text-green-700 dark:text-green-300">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span>Camper agreement signed</span>
        </div>
      </div>
        <!-- Parent Agreement -->
        <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-zinc-900">
          <h6 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Parent / Guardian Agreement</h6>
          <div class="mb-4 p-3 bg-gray-50 dark:bg-zinc-800 rounded text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
            {{ annualStatus?.parent?.agreement?.content || 'Agreement content not available.' }}
          </div>
          <div v-if="!parentAgreementSigned" class="space-y-3">
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
            <p v-if="hasFieldError('agreement.parent')"
               class="text-sm text-rose-600 dark:text-rose-400">
              A parent or guardian must sign this agreement before saving forms.
            </p>
          </div>
          <div v-else class="space-y-3">
            <div class="flex items-center space-x-2 text-green-700 dark:text-green-300">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              <span>Parent agreement signed</span>
            </div>
            <p v-if="annualStatus?.parent?.typed_name" class="text-sm text-gray-600 dark:text-gray-400">
              Signed by {{ annualStatus.parent.typed_name }}.
            </p>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end">
          <button @click="saveForms" :disabled="savingForms"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
            {{ savingForms ? 'Saving...' : 'Save Forms' }}
          </button>
        </div>
        <div v-if="saveErrors.length"
             class="mt-4 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-200">
          <p class="text-sm font-medium">We still need:</p>
          <ul class="mt-1 list-disc list-inside space-y-1 text-xs sm:text-sm">
            <li v-for="error in saveErrors" :key="error">{{ error }}</li>
          </ul>
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
    const initialForms = this.formsData
      ? this.normalizeFormData(this.formsData)
      : this.initializeFormsData()

    return {
      savingForms: false,
      signingParent: false,
      signingCamper: false,
      importingFamilyContacts: false,
      localForms: initialForms,
      parentSignature: {
        typed_name: '',
        confirmed: false
      },
      camperSignature: {
        typed_name: '',
        confirmed: false
      },
      saveErrors: [],
      validationErrors: []
    }
  },
  computed: {
    formsOnFile() {
      return !!(this.formsData?.information && this.formsData?.medical)
    },
    formsComplete() {
      return this.formsOnFile && this.camperAgreementSigned && this.parentAgreementSigned
    },
    missingRequirements() {
      const requirements = []

      if (!this.formsOnFile) {
        requirements.push('Camper information & medical forms')
      }
      if (!this.camperAgreementSigned) {
        requirements.push('Camper agreement')
      }
      if (!this.parentAgreementSigned) {
        requirements.push('Parent / Guardian agreement')
      }

      return requirements
    },
    parentAgreementSigned() {
      return !!this.annualStatus?.parent?.signed
    },
    camperAgreementSigned() {
      const camperStatus = this.annualStatus?.campers?.find(c => c.camper_id === this.camper.id)
      return !!camperStatus?.signed
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
        } else {
          this.localForms = this.initializeFormsData()
        }
      },
      immediate: true,
      deep: true
    },
    camperAgreementSigned(newVal) {
      if (newVal) {
        this.clearFieldError('agreement.camper')
      }
    },
    camperSignatureReady(newVal) {
      if (newVal) {
        this.clearFieldError('agreement.camper')
      }
    },
    parentAgreementSigned(newVal) {
      if (newVal) {
        this.clearFieldError('agreement.parent')
      }
    },
    parentSignatureReady(newVal) {
      if (newVal) {
        this.clearFieldError('agreement.parent')
      }
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
          insurance: this.normalizeInsuranceData()
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

      const insurance = this.normalizeInsuranceData(medical?.insurance)

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
            medications_prescribed: this.toTextareaString(medical?.medical?.medications_prescribed),
            medications: this.toTextareaString(medical?.medical?.medications),
            allergies: this.toTextareaString(medical?.medical?.allergies),
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
          insurance
        }
      }
    },
    async saveForms() {
      if (this.savingForms) {
        return
      }
      this.savingForms = true
      this.saveErrors = []
      try {
        const { normalizedForm, errors, errorFields } = this.validateBeforeSave()

        if (errors.length) {
          this.saveErrors = errors
          this.validationErrors = errorFields
          return
        }

        const payload = {
          year: this.activeYear,
          campers: [normalizedForm]
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

        this.$emit('forms-updated', { camperId: this.camper.id })
        this.saveErrors = []
        this.validationErrors = []
        this.$emit('close')
      } catch (error) {
        console.error('Error saving forms:', error)
        if (!this.saveErrors.length) {
          this.saveErrors = ['We could not save your forms. Please try again.']
        }
      } finally {
        this.savingForms = false
      }
    },
    validateBeforeSave() {
      const normalizedForm = this.prepareFormForSubmit()
      const errors = []
      const errorFields = []

      const camper = normalizedForm.information?.camper ?? {}
      const emergency = normalizedForm.information?.emergency_contact ?? {}
      const insurance = normalizedForm.medical?.insurance ?? {}

      const requiredFields = [
        { key: 'camper.t_shirt_size', value: camper.t_shirt_size, label: 'Camper t-shirt size' },
        { key: 'camper.first_name', value: camper.first_name, label: 'Camper first name' },
        { key: 'camper.last_name', value: camper.last_name, label: 'Camper last name' },
        { key: 'camper.date_of_birth', value: camper.date_of_birth, label: 'Camper birthday' },
        { key: 'camper.grade', value: camper.grade, label: 'Camper grade' },
        { key: 'emergency.name', value: emergency.name, label: 'Primary emergency contact name' },
        { key: 'emergency.relation', value: emergency.relation, label: 'Primary emergency contact relationship' },
        { key: 'emergency.home_phone', value: emergency.home_phone, label: 'Primary emergency contact phone' },
       // { key: 'insurance.company', value: insurance.company, label: 'Insurance company' },
        //{ key: 'insurance.policy_number', value: insurance.policy_number, label: 'Insurance policy number' },
      ]

      requiredFields.forEach(field => {
        if (
          field.value === null ||
          field.value === undefined ||
          String(field.value).trim().length === 0
        ) {
          errors.push(field.label)
          errorFields.push(field.key)
        }
      })

      if (!this.camperAgreementSigned) {
        errors.push('Camper agreement')
        errorFields.push('agreement.camper')
      }

      if (!this.parentAgreementSigned) {
        errors.push('Parent / Guardian agreement')
        errorFields.push('agreement.parent')
      }

      const uniqueErrors = [...new Set(errors)]
      const uniqueErrorFields = [...new Set(errorFields)]

      return { normalizedForm, errors: uniqueErrors, errorFields: uniqueErrorFields }
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

      // Convert medical text areas to arrays for persistence
      medical.medical.medications = this.stringToArray(medical?.medical?.medications)
      medical.medical.allergies = this.stringToArray(medical?.medical?.allergies)
      medical.medical.medications_prescribed = this.stringToArray(medical?.medical?.medications_prescribed)

      if (medical?.medical) {
        medical.medical.physician_phone = this.formatPhone(medical.medical.physician_phone)
      }

      const insurance = this.normalizeInsuranceData(medical?.insurance)

      return {
        camper_id: this.camper.id,
        information,
        medical: {
          ...medical,
          insurance
        }
      }
    },
    normalizeInsuranceData(raw = {}) {
      const source = raw || {}

      const insuredName =
        source.insured_name ??
        source.primary_insured ??
        source.insuredName ??
        ''

      const company =
        source.company ??
        source.company_name ??
        source.provider ??
        source.insurance_provider ??
        ''

      const policyNumber =
        source.policy_number ??
        source.policy ??
        source.policyNumber ??
        source.insurance_policy_number ??
        ''

      const groupNumber =
        source.group_number ??
        source.group ??
        source.groupNumber ??
        source.insurance_group_number ??
        ''

      const phone =
        source.phone ??
        source.contact_phone ??
        source.insurance_phone ??
        ''

      return {
        insured_name: insuredName,
        company,
        policy_number: policyNumber,
        group_number: groupNumber,
        phone,
      }
    },
    toTextareaString(value) {
      if (Array.isArray(value)) {
        return value.join('\n')
      }
      if (value === null || value === undefined) {
        return ''
      }
      return String(value)
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
    hasFieldError(fieldKey) {
      if (!fieldKey) return false
      return this.validationErrors.includes(fieldKey)
    },
    handleFieldInput(fieldKey, condition = true) {
      if (!condition) {
        return
      }
      this.clearFieldError(fieldKey)
    },
    clearFieldError(fieldKey) {
      if (!fieldKey || !this.validationErrors.length) {
        return
      }
      if (this.validationErrors.includes(fieldKey)) {
        this.validationErrors = this.validationErrors.filter(key => key !== fieldKey)
      }
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
        const parentTypedName = this.getParentTypedNameForSubmission()
        if (!parentTypedName) {
          this.addValidationError('agreement.parent')
          window.alert('Please enter and confirm your full name before signing the parent agreement.')
          return
        }

        const camperSignature = this.buildCurrentCamperSignatureForSubmission()
        if (!camperSignature) {
          this.addValidationError('agreement.camper')
          window.alert('Please have the camper sign the agreement before the parent signs.')
          return
        }

        await this.submitAnnualConfirmationRequest({
          parentTypedName,
          campers: [camperSignature],
        })

        this.clearFieldError('agreement.parent')
        this.$emit('agreements-updated', { type: 'parent' })
      } catch (error) {
        console.error('Error signing parent agreement:', error)
        if (error?.message) {
          window.alert(error.message)
        } else {
          window.alert('We could not sign the parent agreement. Please try again.')
        }
      } finally {
        this.signingParent = false
      }
    },
    async signCamperAgreement() {
      this.signingCamper = true
      try {
        const parentTypedName = this.getParentTypedNameForSubmission()
        if (!parentTypedName) {
          this.addValidationError('agreement.parent')
          window.alert('A parent or guardian must provide their signature before the camper agreement can be submitted.')
          return
        }

        const camperSignature = this.buildCurrentCamperSignatureForSubmission()
        if (!camperSignature) {
          this.addValidationError('agreement.camper')
          window.alert('Please type the camper name and confirm the agreement before signing.')
          return
        }

        await this.submitAnnualConfirmationRequest({
          parentTypedName,
          campers: [camperSignature],
        })

        this.clearFieldError('agreement.camper')
        this.$emit('agreements-updated', { type: 'camper', camperId: this.camper.id })
      } catch (error) {
        console.error('Error signing camper agreement:', error)
        if (error?.message) {
          window.alert(error.message)
        } else {
          window.alert('We could not sign the camper agreement. Please try again.')
        }
      } finally {
        this.signingCamper = false
      }
    },
    addValidationError(fieldKey) {
      if (!fieldKey) {
        return
      }
      if (!this.validationErrors.includes(fieldKey)) {
        this.validationErrors = [...this.validationErrors, fieldKey]
      }
    },
    getParentTypedNameForSubmission() {
      if (this.annualStatus?.parent?.signed && this.annualStatus.parent.typed_name) {
        return this.annualStatus.parent.typed_name
      }
      if (this.parentSignature.confirmed) {
        return this.parentSignature.typed_name?.trim() || ''
      }
      return ''
    },
    buildCurrentCamperSignatureForSubmission() {
      const status = this.annualStatus?.campers?.find(c => c.camper_id === this.camper.id)
      if (status?.typed_name) {
        return {
          camper_id: this.camper.id,
          typed_name: status.typed_name,
          affirmations: Array.isArray(status?.affirmations) ? status.affirmations : [],
        }
      }

      const typedName = this.camperSignature.confirmed ? this.camperSignature.typed_name?.trim() : ''
      if (typedName) {
        return {
          camper_id: this.camper.id,
          typed_name: typedName,
          affirmations: [],
        }
      }

      return null
    },
    async submitAnnualConfirmationRequest({ parentTypedName, campers }) {
      if (!parentTypedName) {
        throw new Error('Parent signature is required before submitting the annual confirmation.')
      }
      if (!Array.isArray(campers) || campers.length === 0) {
        throw new Error('At least one camper signature is required before submitting the annual confirmation.')
      }

      const payload = {
        year: this.activeYear,
        parent: {
          typed_name: parentTypedName,
          affirmations: [],
        },
        campers: campers.map(signature => ({
          camper_id: signature.camper_id,
          typed_name: signature.typed_name,
          affirmations: Array.isArray(signature?.affirmations) ? signature.affirmations : [],
        })),
      }

      const response = await fetch('/api/public-registration/annual-confirmation', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify(payload),
      })

      if (!response.ok) {
        let message = 'Failed to submit annual confirmation.'
        try {
          const errorBody = await response.json()
          if (errorBody?.message) {
            message = errorBody.message
          }
        } catch (err) {
          // ignore parse errors
        }
        throw new Error(message)
      }
    }
  }
}
</script>

