<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" @click.self="handleBackdropClick">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Backdrop -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="handleBackdropClick"></div>

      <!-- Modal Panel -->
      <div class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <div class="bg-white dark:bg-zinc-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <!-- Header -->
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ campInstance ? `Register for ${campInstance.camp_name}` : 'Camp Registration' }}
            </h3>
            <button @click="close" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Step Indicator -->
          <div class="mb-8">
            <div class="flex items-center justify-center space-x-4 sm:space-x-8">
              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 transition-all duration-300"
                     :class="currentStep >= 1 ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-gray-100 dark:bg-zinc-800 border-gray-300 dark:border-zinc-600 text-gray-500 dark:text-gray-400'">
                  <span class="text-sm sm:text-base font-semibold">1</span>
                </div>
                <span class="text-sm sm:text-base font-medium transition-colors duration-300 hidden sm:inline"
                      :class="currentStep >= 1 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'">
                  Account
                </span>
              </div>
              
              <div class="w-6 sm:w-8 h-0.5 bg-gray-300 dark:bg-zinc-600"></div>
              
              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 transition-all duration-300"
                     :class="currentStep >= 2 ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-gray-100 dark:bg-zinc-800 border-gray-300 dark:border-zinc-600 text-gray-500 dark:text-gray-400'">
                  <span class="text-sm sm:text-base font-semibold">2</span>
                </div>
                <span class="text-sm sm:text-base font-medium transition-colors duration-300 hidden sm:inline"
                      :class="currentStep >= 2 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'">
                  Family Info
                </span>
              </div>
              
              <div class="w-6 sm:w-8 h-0.5 bg-gray-300 dark:bg-zinc-600"></div>
              
              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 transition-all duration-300"
                     :class="currentStep >= 3 ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-gray-100 dark:bg-zinc-800 border-gray-300 dark:border-zinc-600 text-gray-500 dark:text-gray-400'">
                  <span class="text-sm sm:text-base font-semibold">3</span>
                </div>
                <span class="text-sm sm:text-base font-medium transition-colors duration-300 hidden sm:inline"
                      :class="currentStep >= 3 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'">
                  Camper
                </span>
              </div>
              
              <div class="w-6 sm:w-8 h-0.5 bg-gray-300 dark:bg-zinc-600"></div>
              
              
              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 transition-all duration-300"
                     :class="currentStep >= 4 ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-gray-100 dark:bg-zinc-800 border-gray-300 dark:border-zinc-600 text-gray-500 dark:text-gray-400'">
                  <span class="text-sm sm:text-base font-semibold">4</span>
                </div>
                <span class="text-sm sm:text-base font-medium transition-colors duration-300 hidden sm:inline"
                      :class="currentStep >= 4 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'">
                  Payment
                </span>
              </div>
            </div>
          </div>

          <!-- Step Content -->
          <div class="relative overflow-hidden min-h-[400px]">
            <!-- Step 1: Login/Register -->
            <div class="transition-all duration-500 ease-in-out"
                 :class="currentStep === 1 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'">
              <div class="max-w-md mx-auto">
                <login-controller
                  :is-authenticated="isAuthenticated"
                  :user-name="userName"
                  :user-email="userEmail"
                  :error="error"
                  :processing="processing"
                  v-model:show-register-form="showRegisterForm"
                  v-model:login-form="loginForm"
                  v-model:register-form="registerForm"
                  @continue="goToStep(2)"
                  @logout="handleLogout"
                  @login="handleLogin"
                  @register="handleRegister"
                />
              </div>
            </div>

            <!-- Step 2: Family Information -->
            <div
              class="transition-all duration-500 ease-in-out"
              :class="currentStep === 2 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'"
            >
              <family-information-form
                :family-form="familyForm"
                :processing="processing"
                @submit="saveFamilyInfo"
                @add-contact="addFamilyContact"
                @remove-contact="removeFamilyContact"
                @set-primary-contact="setFamilyPrimaryContact"
                @sync-legacy="syncFamilyLegacyContactFields()"
                @go-back="goToStep(1)"
              />
            </div>

            <!-- Step 3: Camper Selection & Forms -->
            <div class="transition-all duration-500 ease-in-out"
                 :class="currentStep === 3 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'">
              <div class="max-w-4xl mx-auto">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Camp Registration</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Select campers and complete required forms for {{ activeComplianceYear }}.</p>
                
                <!-- Available Campers -->
                <div v-if="campers.length > 0" class="mb-6">
                  <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Available Campers</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    <camper-card
                      v-for="camper in campers"
                      :key="camper.id"
                      :camper="camper"
                      :selected="isCamperSelected(camper.id)"
                      :forms-complete="camperHasCompleteForms(camper.id)"
                      :allow-forms-access="true"
                      @select="handleCamperCardSelect"
                      @deselect="handleCamperCardDeselect"
                      @edit-camper="handleCamperEditFromCard"
                      @request-forms="handleCardRequestForms"
                    />
                  </div>
                </div>

                <!-- No Campers Message -->
                <div v-if="campers.length === 0" class="mb-6 p-4 bg-gray-50 dark:bg-zinc-800 rounded-lg text-center">
                  <p class="text-gray-600 dark:text-gray-400">No campers added yet. Please add a camper to continue.</p>
                </div>

                <!-- Add Camper Button -->
                <div class="mb-6">
                  <button @click="openNewCamperModal"
                          class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    + Add Camper
                  </button>
                </div>
                
                <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                  <button @click="goToStep(2)"
                          class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700">
                    ← Back
                  </button>
                  <div class="flex items-center space-x-4">
                    <div v-if="selectedCampers.length === 0" class="text-sm text-amber-600 dark:text-amber-400">
                      Please select at least one camper to continue
                    </div>
                    <div v-else-if="!allCampersReady" class="text-sm text-amber-600 dark:text-amber-400">
                      Please complete forms and agreements for all selected campers
                    </div>
                    <button @click="goToStep(4)" :disabled="selectedCampers.length === 0 || !allCampersReady"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2">
                      <span>Continue to Payment</span>
                      <span v-if="selectedCampers.length > 0" class="bg-white/20 px-2 py-1 rounded text-xs">
                        {{ selectedCampers.length }} selected
                      </span>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Step 4: Payment -->
            <div class="transition-all duration-500 ease-in-out"
                 :class="currentStep === 4 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'">
              <div class="max-w-2xl mx-auto">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Complete Registration</h3>
                
                <!-- Registration Summary -->
                <div class="mb-6 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                  <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Registration Summary</h4>
                  <div class="space-y-2">
                    <div v-for="camper in selectedCampers" :key="camper.id" class="flex justify-between text-sm">
                      <span class="text-gray-700 dark:text-gray-300">
                        {{ camper.first_name }} {{ camper.last_name }} - {{ campInstance?.camp_name }}
                      </span>
                      <span class="font-semibold text-gray-900 dark:text-white">
                        ${{ campInstance?.price ? parseFloat(campInstance.price).toFixed(2) : '0.00' }}
                      </span>
                    </div>
                    <div class="border-t border-indigo-200 dark:border-indigo-800 pt-2 space-y-2">
                      <div v-if="hasDiscount" class="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                        <span>Subtotal</span>
                        <span>${{ subtotalAmount.toFixed(2) }}</span>
                      </div>
                      <div v-if="hasDiscount" class="flex justify-between text-sm text-emerald-600 dark:text-emerald-400">
                        <span>Discount ({{ discount.code }})</span>
                        <span>- ${{ discountAmountDisplay }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="font-semibold text-gray-900 dark:text-white">Total Due:</span>
                        <span class="font-bold text-lg text-indigo-600 dark:text-indigo-400">
                          ${{ totalAmount.toFixed(2) }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Discount Code Entry -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Discount Code</label>
                  <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <input
                      v-model.trim="discountCodeInput"
                      type="text"
                      placeholder="Enter discount code"
                      class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                      :disabled="discount.validating"
                    >
                    <div class="flex items-center gap-2">
                      <button
                        type="button"
                        @click="applyDiscountCode"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!discountCodeInput || discount.validating"
                      >
                        {{ discount.validating ? 'Applying...' : hasDiscount ? 'Reapply' : 'Apply' }}
                      </button>
                      <button
                        v-if="hasDiscount"
                        type="button"
                        @click="removeDiscount"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-800"
                      >
                        Remove
                      </button>
                    </div>
                  </div>
                  <p
                    v-if="discount.message"
                    class="mt-2 text-sm"
                    :class="discount.valid ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'"
                  >
                    {{ discount.message }}
                  </p>
                </div>

                <!-- Payment Method Selection -->
                <div class="mb-6">
                  <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Payment Method</h4>
                  <div class="space-y-3">
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors"
                           :class="paymentMethod === 'stripe' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/30' : 'border-gray-300 dark:border-zinc-600'">
                      <input type="radio" v-model="paymentMethod" value="stripe" class="mr-3 text-indigo-600">
                      <div>
                        <div class="font-medium text-gray-900 dark:text-white">Pay with Credit/Debit Card</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Secure payment via Stripe</div>
                      </div>
                    </label>
                    
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors"
                           :class="paymentMethod === 'cash_check' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/30' : 'border-gray-300 dark:border-zinc-600'">
                      <input type="radio" v-model="paymentMethod" value="cash_check" class="mr-3 text-indigo-600">
                      <div>
                        <div class="font-medium text-gray-900 dark:text-white">Pay with Cash or Check on Arrival</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Payment will be collected at camp check-in</div>
                      </div>
                    </label>
                  </div>
                </div>

                <!-- Stripe Payment Form -->
                <div v-if="paymentMethod === 'stripe'" class="mb-6">
                  <div v-if="!enrollmentCreated" class="text-center py-4">
                    <button @click="createEnrollments" :disabled="processing"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                      {{ processing ? 'Creating Enrollment...' : 'Create Enrollment & Continue to Payment' }}
                    </button>
                  </div>
                  <stripe-payment-form 
                    v-else-if="enrollmentIds && enrollmentIds.length > 0"
                    :amount="totalAmount"
                    :enrollment-ids="enrollmentIds"
                    :customer-name="userName"
                    :customer-email="userEmail"
                    @payment-success="handlePaymentSuccess"
                  />
                  <div v-else class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-red-800 dark:text-red-200">No enrollment IDs found. Please create enrollments first.</p>
                  </div>
                </div>

                <!-- Cash/Check Instructions -->
                <div v-if="paymentMethod === 'cash_check' && enrollmentCreated" class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                  <h4 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Payment Instructions</h4>
                  <p class="text-sm text-yellow-700 dark:text-yellow-300">
                    Your registration has been confirmed. Please bring payment (cash or check) when you arrive at camp for check-in.
                  </p>
                </div>

                <div class="flex justify-between pt-6">
                  <button @click="goToStep(3)"
                          class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700">
                    ← Back
                  </button>
                  <button v-if="paymentMethod === 'cash_check' && !enrollmentCreated" @click="createEnrollments"
                          :disabled="processing"
                          class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                    {{ processing ? 'Processing...' : 'Complete Registration' }}
                  </button>
                </div>
              </div>
            </div>
            </div>

            <!-- Success Step -->
            <div v-if="registrationComplete" class="transition-all duration-500 ease-in-out opacity-100 translate-x-0">
              <div class="max-w-md mx-auto text-center py-8">
                <div class="mb-6">
                  <svg class="w-16 h-16 text-green-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Registration Complete!</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                  Your registration has been successfully submitted. You will receive a confirmation email shortly.
                </p>
                <button @click="close" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  <add-edit-camper-modal
    :visible="showNewCamperModal"
    :editing-camper="editingCamper"
    :archived-campers="archivedCampers"
    :restoring-camper-id="restoringCamperId"
    :processing="processing"
    :deleting-camper="deletingCamper"
    :camper-photo-preview="camperPhotoPreview"
    :camper-photo-file-name="camperPhotoFileName"
    :camper-photo-error="camperPhotoError"
    :camper-has-upcoming-enrollment="camperHasUpcomingEnrollment"
    v-model:camper-form="camperForm"
    @close="closeNewCamperModal"
    @submit="saveCamper"
    @restore-archived="restoreArchivedCamper"
    @photo-change="handleCamperPhotoChange"
    @clear-photo="clearCamperPhoto"
    @delete-editing="deleteEditingCamper"
  />

  <camper-forms-modal
    :visible="showCamperFormsModal"
    :camper="activeCamperForForms"
    :forms-data="camperFormsModalData"
    :annual-status="annualStatus"
    :active-year="activeComplianceYear"
    @close="closeCamperFormsModal"
    @forms-updated="onCamperFormsUpdated"
    @agreements-updated="onCamperAgreementsUpdated"
  />
</template>

<script>
import StripePaymentForm from './StripePaymentForm.vue'
import CamperFormsModal from './CamperFormsModal.vue'
import CamperCard from './CamperCard.vue'
import FamilyInformationForm from './FamilyInformationForm.vue'
import AddEditCamperModal from './AddEditCamperModal.vue'
import LoginController from './LoginController.vue'

const getCsrfToken = () => {
  const meta = document.querySelector('meta[name="csrf-token"]')
  return meta ? meta.getAttribute('content') : ''
}

const createParentAgreementForm = () => ({
  typed_name: '',
  confirmed: false,
})

const createLoginForm = () => ({
  email: '',
  password: '',
  remember: true,
})

const createRegisterForm = () => ({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const createFamilyContact = (overrides = {}) => ({
  id: overrides.id ?? null,
  name: overrides.name ?? '',
  relation: overrides.relation ?? overrides.relationship ?? '',
  home_phone: overrides.home_phone ?? overrides.phone ?? '',
  cell_phone: overrides.cell_phone ?? overrides.mobile_phone ?? '',
  work_phone: overrides.work_phone ?? '',
  email: overrides.email ?? '',
  address: overrides.address ?? '',
  city: overrides.city ?? '',
  state: overrides.state ?? '',
  zip: overrides.zip ?? '',
  authorized_pickup: !!(overrides.authorized_pickup ?? overrides.authorizedPickup ?? false),
  is_primary: !!overrides.is_primary,
})

const createFamilyForm = () => ({
  name: '',
  phone: '',
  address: '',
  city: '',
  state: '',
  zip_code: '',
  emergency_contact_name: '',
  emergency_contact_phone: '',
  emergency_contact_relationship: '',
  emergency_contacts: [createFamilyContact({ is_primary: true })],
})

const createCamperForm = () => ({
  first_name: '',
  last_name: '',
  date_of_birth: '',
  gender: '',
})

const createDefaultState = () => ({
  currentStep: 1,
  processing: false,
  error: null,
  showRegisterForm: false,
  isAuthenticated: false,
  userName: '',
  userEmail: '',
  campInstance: null,
  family: null,
  campers: [],
  selectedCampers: [],
  annualStatus: null,
  annualProcessing: false,
  annualError: null,
  parentAgreementForm: createParentAgreementForm(),
  camperAgreementForms: [],
  camperForms: [],
  currentFormIndex: 0,
  formsLoading: false,
  formsError: null,
  formsSubmitting: false,
  formsSubmitted: false,
  copyEmergencyAcross: true,
  copyInsuranceAcross: true,
  editingForm: false,
  enrollmentIds: [],
  enrollmentCreated: false,
  registrationComplete: false,
  paymentMethod: 'stripe',
  discountCodeInput: '',
  discount: {
    validating: false,
    valid: false,
    code: '',
    codeId: null,
    amount: 0,
    finalAmount: null,
    message: '',
  },
  sanctumAvailable: true,
  loginForm: createLoginForm(),
  registerForm: createRegisterForm(),
  familyForm: createFamilyForm(),
  showNewCamperModal: false,
  editingCamper: null,
  camperForm: createCamperForm(),
  showCamperFormsModal: false,
  activeCamperForForms: null,
  camperFormsModalData: null,
  pendingSelectionCamperId: null,
  archivedCampers: [],
  restoringCamperId: null,
  camperPhotoFile: null,
  camperPhotoFileName: '',
  camperPhotoPreview: null,
  camperPhotoError: '',
  camperHasUpcomingEnrollment: false,
  deletingCamper: false,
})

export default {
  name: 'CampRegistrationModal',
  components: {
    StripePaymentForm,
    CamperFormsModal,
    CamperCard,
    FamilyInformationForm,
    AddEditCamperModal,
    LoginController
  },
  props: {
    campInstanceId: {
      type: Number,
      required: true
    },
    show: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return createDefaultState()
  },
  computed: {
    subtotalAmount() {
      if (!this.campInstance || !this.campInstance.price) {
        return 0
      }
      const price = parseFloat(this.campInstance.price)
      if (Number.isNaN(price)) {
        return 0
      }
      return this.selectedCampers.length * price
    },
    discountAmount() {
      if (!this.discount.valid) {
        return 0
      }
      const amount = Number(this.discount.amount || 0)
      return Math.min(amount, this.subtotalAmount)
    },
    hasDiscount() {
      return this.discount.valid && this.discountAmount > 0
    },
    discountAmountDisplay() {
      return this.discountAmount.toFixed(2)
    },
    totalAmount() {
      const total = this.subtotalAmount - this.discountAmount
      return total > 0 ? total : 0
    },
    currentCalendarYear() {
      return new Date().getFullYear()
    },
    activeComplianceYear() {
      if (this.campInstance && this.campInstance.year) {
        return Number(this.campInstance.year)
      }
      return this.currentCalendarYear
    },
    selectedCamperIds() {
      return this.selectedCampers.map(camper => camper.id)
    },
    currentForm() {
      if (!this.camperForms.length) return null
      const index = Math.min(this.currentFormIndex, this.camperForms.length - 1)
      return this.camperForms[index]
    },
    formsAlreadyOnFile() {
      if (!this.annualStatus) return false
      const parentSigned = !!this.annualStatus.parent?.signed
      if (!parentSigned) return false
      const statusMap = new Map(
        (this.annualStatus.campers || []).map(status => [status.camper_id, status])
      )
      return this.selectedCamperIds.length > 0 && this.selectedCamperIds.every(id => {
        const status = statusMap.get(id)
        return status?.information_snapshot_id && status?.medical_snapshot_id && status?.signed
      })
    },
    currentCamperHasForms() {
      if (!this.annualStatus || !this.currentForm) return false
      const status = this.annualStatus.campers?.find(c => c.camper_id === this.currentForm.camper_id)
      return !!(status?.information_snapshot_id && status?.medical_snapshot_id)
    },
    canSubmitForms() {
      if (!this.camperForms.length || this.formsSubmitting) {
        return false
      }
      return this.camperForms.every(form => this.validateForm(form))
    },
    annualStatusComplete() {
      if (!this.annualStatus) return false

      const parentSigned = !!this.annualStatus.parent?.signed
      if (!parentSigned) {
        return false
      }

      if (this.selectedCamperIds.length === 0) {
        return false
      }

      const camperStatuses = this.annualStatus.campers || []
      const statusMap = new Map(camperStatuses.map(status => [status.camper_id, status]))

      return this.selectedCamperIds.every(id => {
        const status = statusMap.get(id)
        return status?.signed
      })
    },
    canSubmitAnnual() {
      if (!this.isAuthenticated) {
        return false
      }

      const parentSigned = !!this.annualStatus?.parent?.signed
      const parentReady = parentSigned || (
        this.parentAgreementForm.typed_name?.trim().length > 0 &&
        this.parentAgreementForm.confirmed
      )

      if (this.camperAgreementForms.length === 0) {
        return false
      }

      const campersReady = this.camperAgreementForms.every(form => {
        const status = this.annualStatus?.campers?.find(c => c.camper_id === form.camper_id)
        if (status?.signed) {
          return true
        }
        return form.typed_name?.trim().length > 0 && form.confirmed
      })

      return parentReady && campersReady && !this.annualProcessing
    },
    allCampersReady() {
      if (this.selectedCampers.length === 0) return false

      return this.selectedCampers.every(camper => this.camperHasCompleteForms(camper.id))
    }
  },
  watch: {
    show(newVal) {
      if (newVal && this.campInstanceId) {
        this.initialize()
      } else if (!newVal) {
        this.reset()
      }
    },
    campInstanceId(newVal) {
      if (newVal && this.show) {
        this.loadCampInstance()
      }
    },
    camperForms: {
      handler() {
        if (this.formsLoading) {
          return
        }
        this.formsSubmitted = false
      },
      deep: true
    },
    'familyForm.emergency_contacts': {
      handler() {
        this.syncFamilyLegacyContactFields()
      },
      deep: true
    },
    currentFormIndex() {
      // Reset editing mode when switching between campers
      this.editingForm = false
    }
  },
  mounted() {
    // Only initialize if both show is true AND campInstanceId is set
    if (this.show && this.campInstanceId) {
      this.initialize()
    }
  },
  methods: {
    async callApi(
      url,
      {
        method = 'GET',
        body,
        headers = {},
        retries = 2,
        requireAuth = false,
        expectJson = true,
        allowUnauthenticated = false,
      } = {}
    ) {
      let attempt = 0
      const options = {
        method,
        credentials: 'same-origin',
        headers: {
          Accept: 'application/json',
          ...headers,
  },
      }

      const csrfToken = getCsrfToken()
      if (csrfToken && !options.headers['X-CSRF-TOKEN']) {
        options.headers['X-CSRF-TOKEN'] = csrfToken
      }

      if (body !== undefined && body !== null) {
        if (body instanceof FormData) {
          options.body = body
        } else if (typeof body === 'string') {
          options.body = body
          if (!options.headers['Content-Type']) {
            options.headers['Content-Type'] = 'application/json'
          }
        } else {
          options.body = JSON.stringify(body)
          if (!options.headers['Content-Type']) {
            options.headers['Content-Type'] = 'application/json'
          }
        }
      }

      while (attempt <= retries) {
        const response = await fetch(url, options)
        const contentType = response.headers.get('content-type') || ''
        const unauthorized = response.status === 401 || response.status === 419
        const htmlResponse = contentType.includes('text/html')
        const shouldRetry = (unauthorized || htmlResponse) && attempt < retries

        if (shouldRetry) {
          await this.refreshSession()
          if (requireAuth) {
            const authed = await this.ensureAuthenticatedState()
            if (!authed) {
              throw new Error('Your session has expired. Please sign in again.')
            }
          }
          attempt += 1
          continue
        }

        if (unauthorized) {
          if (allowUnauthenticated) {
            return null
          }
          throw new Error('Authentication error. Please try again.')
        }

        if (htmlResponse) {
          if (allowUnauthenticated) {
            return null
          }
          throw new Error('Authentication error. Please try again.')
        }

        if (!expectJson) {
          if (!response.ok) {
            throw new Error(`Request failed with status ${response.status}`)
          }
          return response
        }

        const data = await response.json().catch(() => null)

        if (!response.ok) {
          const validationMessage = data?.errors
            ? Object.values(data.errors)
                .flat()
                .join(', ')
            : ''
          const fallbackMessage = data?.message || data?.error || `Request failed with status ${response.status}`
          throw new Error(validationMessage || fallbackMessage)
        }

        return data
      }

      throw new Error('Request failed after multiple attempts. Please try again.')
    },
    resetDiscountState({ message = '' } = {}) {
      this.discount.validating = false
      this.discount.valid = false
      this.discount.code = ''
      this.discount.codeId = null
      this.discount.amount = 0
      this.discount.finalAmount = null
      this.discount.message = message
      if (!message) {
        this.discount.message = ''
      }
    },
    handleDiscountContextChange({ soft = false } = {}) {
      if (this.hasDiscount || (!soft && this.discount.message)) {
        this.resetDiscountState()
      }
    },
    async applyDiscountCode() {
      if (!this.discountCodeInput) {
        return
      }

      if (!this.campInstanceId || this.selectedCampers.length === 0) {
        this.discount.valid = false
        this.discount.amount = 0
        this.discount.code = ''
        this.discount.codeId = null
        this.discount.finalAmount = null
        this.discount.message = 'Select at least one camper before applying a discount code.'
        return
      }

      this.discount.validating = true
      try {
        const response = await fetch('/api/public-registration/discounts/validate', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            Accept: 'application/json',
          },
          credentials: 'same-origin',
          body: JSON.stringify({
            code: this.discountCodeInput,
            camp_instance_id: this.campInstanceId,
            camper_ids: this.selectedCamperIds,
          }),
        })

        let data
        try {
          data = await response.json()
        } catch (parseError) {
          console.error('Failed to parse discount validation response:', parseError)
          data = null
        }

        if (!response.ok) {
          const message = data?.message || 'Failed to validate discount code. Please try again.'
          throw new Error(message)
        }

        if (data?.valid) {
          this.discount.valid = true
          this.discount.code = data?.discount_code?.code || this.discountCodeInput.trim().toUpperCase()
          this.discount.codeId = data?.discount_code_id || null
          this.discount.amount = Number(data?.discount_amount || 0)
          this.discount.finalAmount = data?.final_amount !== undefined ? Number(data.final_amount) : null
          this.discount.message = data?.message || 'Discount applied successfully.'
          this.discountCodeInput = ''
        } else {
          this.discount.valid = false
          this.discount.code = ''
          this.discount.codeId = null
          this.discount.amount = 0
          this.discount.finalAmount = null
          this.discount.message = data?.message || 'Invalid discount code.'
        }
      } catch (error) {
        console.error('Error validating discount code:', error)
        this.discount.valid = false
        this.discount.code = ''
        this.discount.codeId = null
        this.discount.amount = 0
        this.discount.finalAmount = null
        this.discount.message = error?.message || 'Unable to validate discount code. Please try again.'
      } finally {
        this.discount.validating = false
      }
    },
    removeDiscount() {
      if (!this.hasDiscount && !this.discount.message) {
        return
      }
      this.resetDiscountState()
      this.discountCodeInput = ''
    },
    async initialize() {
      this.reset()
      await this.checkAuth({ autoAdvance: true })
      await this.loadCampInstance()
      if (this.isAuthenticated) {
        await this.loadUserData()
        await this.loadAnnualStatus()
        // Load forms data for any selected campers
        if (this.selectedCampers.length > 0) {
          await this.fetchCamperForms()
        }
      }
    },
    reset() {
      Object.assign(this, createDefaultState())
    },
    async checkAuth({ autoAdvance = false } = {}) {
      try {
        const data = await this.callApi('/api/public-registration/check-auth', {
          retries: 1,
          allowUnauthenticated: true,
        })

        if (!data) {
          this.isAuthenticated = false
          this.userName = ''
          this.userEmail = ''
          return false
        }

        this.isAuthenticated = !!data.authenticated

        if (this.isAuthenticated && data.user) {
          this.userName = data.user.name
          this.userEmail = data.user.email

          if (autoAdvance && this.currentStep === 1) {
            this.currentStep = 2
          }
        } else {
          this.userName = ''
          this.userEmail = ''
        }

        return this.isAuthenticated
      } catch (err) {
        console.error('Error checking auth:', err)
        return false
      }
    },
    async loadCampInstance() {
      try {
        this.resetDiscountState()
        this.campInstance = await this.callApi(`/api/public-registration/camp-instance/${this.campInstanceId}`, {
          retries: 1,
        })
      } catch (err) {
        console.error('Error loading camp instance:', err)
        this.error = 'Failed to load camp information'
      }
    },
    async handleLogin() {
      this.processing = true
      this.error = null
      
      try {
        const data = await this.callApi('/api/public-registration/login', {
          method: 'POST',
          body: this.loginForm,
        })

        this.isAuthenticated = true
        this.userName = data.user.name
        this.userEmail = data.user.email
        await this.loadUserData()
        await this.loadAnnualStatus()
        this.goToStep(2)
      } catch (err) {
        this.error = err.message
      } finally {
        this.processing = false
      }
    },
    async ensureAuthenticatedState() {
      const loggedIn = await this.checkAuth()
      if (!loggedIn) {
        this.error = 'Your session has expired. Please sign in again to continue.'
        this.currentStep = 1
        return false
      }

      if (!this.family) {
        await this.loadUserData()
      }

      return true
    },
    async refreshSession() {
      if (!this.sanctumAvailable) {
        return
      }

      try {
        const response = await fetch('/sanctum/csrf-cookie', {
          credentials: 'same-origin',
          headers: {
            'Accept': 'application/json'
          }
        })

        if (response.status === 404) {
          this.sanctumAvailable = false
          return
        }

        if (!response.ok) {
          throw new Error(`Unexpected response: ${response.status}`)
        }
      } catch (err) {
        console.warn('Failed to refresh CSRF cookie:', err)
      }
    },
    async handleRegister() {
      if (this.registerForm.password !== this.registerForm.password_confirmation) {
        this.error = 'Passwords do not match'
        return
      }
      
      this.processing = true
      this.error = null
      
      try {
        const data = await this.callApi('/api/public-registration/register', {
          method: 'POST',
          body: this.registerForm,
        })

        this.isAuthenticated = true
        this.userName = data.user.name
        this.userEmail = data.user.email
        await this.loadAnnualStatus()
        this.goToStep(2)
      } catch (err) {
        this.error = err.message
      } finally {
        this.processing = false
      }
    },
    async handleLogout() {
      this.processing = true
      this.error = null

      try {
        await this.callApi('/logout', {
          method: 'POST',
          expectJson: false,
        })
        this.reset()
      } catch (err) {
        this.error = err.message || 'Failed to logout. Please try again.'
      } finally {
        this.processing = false
      }
    },
    async loadUserData() {
      try {
        const data = await this.callApi('/api/public-registration/user-data', {
          requireAuth: true,
        })

        if (!data) {
          return
        }

        if (data.family) {
          this.family = data.family
          const contacts = this.normalizeFamilyContacts(
            data.family.emergency_contacts,
            {
              name: data.family.emergency_contact_name,
              relation: data.family.emergency_contact_relationship,
              phone: data.family.emergency_contact_phone,
            }
          )

          Object.assign(this.familyForm, {
            name: data.family.name ?? '',
            phone: data.family.phone ?? '',
            address: data.family.address ?? '',
            city: data.family.city ?? '',
            state: data.family.state ?? '',
            zip_code: data.family.zip_code ?? '',
            emergency_contact_name: data.family.emergency_contact_name ?? '',
            emergency_contact_phone: data.family.emergency_contact_phone ?? '',
            emergency_contact_relationship: data.family.emergency_contact_relationship ?? '',
            emergency_contacts: contacts,
          })

          this.syncFamilyLegacyContactFields(contacts)
        } else {
          // ensure at least one contact exists
          const contacts = this.normalizeFamilyContacts()
          this.familyForm.emergency_contacts = contacts
          this.syncFamilyLegacyContactFields(contacts)
        }
        
        if (data.campers) {
          this.campers = data.campers
          this.syncSelectedCampers()
        }

        this.archivedCampers = Array.isArray(data.archived_campers) ? data.archived_campers : []
        if (this.archivedCampers.length === 0) {
          this.restoringCamperId = null
        }

      } catch (err) {
        console.error('Error loading user data:', err)
      }
    },
    async loadAnnualStatus() {
      if (!this.isAuthenticated) {
        this.annualStatus = null
        return
      }

      try {
        const params = new URLSearchParams()
        if (this.activeComplianceYear) {
          params.append('year', this.activeComplianceYear)
        }
        const queryString = params.toString()
        const url = queryString
          ? `/api/public-registration/annual-status?${queryString}`
          : '/api/public-registration/annual-status'

        const data = await this.callApi(url, {
          requireAuth: true,
        })

        if (!data) {
          this.annualStatus = null
          return
        }

        this.annualStatus = data
        this.parentAgreementForm = {
          typed_name: data.parent?.signed ? data.parent.typed_name : (this.userName || ''),
          confirmed: false,
        }

        this.syncCamperAgreementForms()
        this.updateFormsSubmittedFlag()
      } catch (err) {
        console.error('Error loading annual compliance status:', err)
        this.annualStatus = null
        if (!this.isAuthenticated) {
          this.currentStep = 1
        }
      }
    },
    syncCamperAgreementForms() {
      const statusMap = new Map(
        (this.annualStatus?.campers || []).map(status => [status.camper_id, status])
      )

      const selectedSet = new Set(this.selectedCamperIds)
      if (selectedSet.size === 0) {
        this.camperAgreementForms = []
        return
      }

      this.camperAgreementForms = (this.campers || [])
        .filter(camper => selectedSet.has(camper.id))
        .map(camper => {
          const status = statusMap.get(camper.id)
          const fullName = `${camper.first_name} ${camper.last_name}`.trim()
          return {
            camper_id: camper.id,
            camper_name: fullName,
            typed_name: status?.signed ? status.typed_name : fullName,
            confirmed: !!status?.signed,
          }
        })
    },
    async saveFamilyInfo() {
      this.processing = true
      this.error = null
      const payload = this.prepareFamilyPayload()
      const validationError = this.validateFamilyPayload(payload)
      if (validationError) {
        this.error = validationError
        this.processing = false
        return
      }

      try {

        const data = await this.callApi('/api/public-registration/family', {
          method: 'POST',
          body: payload,
          requireAuth: true,
        })

        if (data?.family) {
          this.family = data.family
          const contacts = this.normalizeFamilyContacts(
            data.family.emergency_contacts,
            {
              name: data.family.emergency_contact_name,
              relation: data.family.emergency_contact_relationship,
              phone: data.family.emergency_contact_phone,
            }
          )

          Object.assign(this.familyForm, {
            name: data.family.name ?? this.familyForm.name,
            phone: data.family.phone ?? '',
            address: data.family.address ?? '',
            city: data.family.city ?? '',
            state: data.family.state ?? '',
            zip_code: data.family.zip_code ?? '',
            emergency_contact_name: data.family.emergency_contact_name ?? '',
            emergency_contact_phone: data.family.emergency_contact_phone ?? '',
            emergency_contact_relationship: data.family.emergency_contact_relationship ?? '',
            emergency_contacts: contacts,
          })

          this.syncFamilyLegacyContactFields(contacts)
        }

        await this.loadAnnualStatus()
        this.currentStep = 3
        this.error = null
      } catch (err) {
        this.error = err.message
      } finally {
        this.processing = false
      }
    },
    async continueToForms() {
      if (this.selectedCampers.length === 0) {
        this.error = 'Please select at least one camper to continue.'
        return
      }

      const authenticated = await this.ensureAuthenticatedState()
      if (!authenticated) {
        this.formsError = this.error || 'Please sign in before completing camper forms.'
        return
      }

      this.error = null
      this.formsError = null
      this.formsSubmitted = false

      try {
        await this.loadAnnualStatus()
        await this.fetchCamperForms()
        this.syncCamperAgreementForms()

        if (!this.camperForms.length) {
          this.formsError = 'No camper forms were found for the selected campers.'
          return
        }

        this.currentFormIndex = 0
        this.currentStep = 4
      } catch (err) {
        console.error('Error preparing camper forms:', err)
        this.formsError = err.message || 'Failed to load camper forms. Please try again.'
      }
    },
    async fetchCamperForms(camperIds = this.selectedCamperIds, { updateState = true } = {}) {
      if (!Array.isArray(camperIds) || camperIds.length === 0) {
        if (updateState) {
          this.camperForms = []
        }
        return []
      }

      if (updateState) {
        this.formsLoading = true
        this.formsError = null
      }

      try {
        const params = new URLSearchParams()
        camperIds.forEach(id => params.append('camper_ids[]', id))
        if (this.activeComplianceYear) {
          params.append('year', this.activeComplianceYear)
        }

        const data = await this.callApi(`/api/public-registration/forms?${params.toString()}`, {
          requireAuth: true,
        })

        const forms = Array.isArray(data.campers) ? data.campers : []
        const normalized = forms.map(form => this.normalizeFormData(form))

        if (updateState) {
          this.camperForms = normalized
          this.formsSubmitted = false
        }

        return normalized
      } catch (err) {
        if (updateState) {
          this.camperForms = []
        }
        throw err
      } finally {
        if (updateState) {
          this.formsLoading = false
        }
      }
    },
    normalizeFormData(rawForm) {
      const information = rawForm?.information || {}
      const medical = rawForm?.medical || {}

      const camperInfo = {
        camper: {
          first_name: information?.camper?.first_name ?? '',
          last_name: information?.camper?.last_name ?? '',
          date_of_birth: information?.camper?.date_of_birth ?? '',
          age: information?.camper?.age ?? '',
          grade: information?.camper?.grade ?? '',
          sex: information?.camper?.sex ?? '',
          address: information?.camper?.address ?? '',
          city: information?.camper?.city ?? '',
          state: information?.camper?.state ?? '',
          zip: information?.camper?.zip ?? '',
          home_phone: information?.camper?.home_phone ?? '',
          alternate_phone: information?.camper?.alternate_phone ?? '',
          email: information?.camper?.email ?? '',
          alternate_email: information?.camper?.alternate_email ?? '',
          home_church: information?.camper?.home_church ?? '',
          parent_marital_status: information?.camper?.parent_marital_status ?? '',
          lives_with: information?.camper?.lives_with ?? '',
          t_shirt_size: information?.camper?.t_shirt_size ?? '',
        },
        emergency_contact: {
          name: information?.emergency_contact?.name ?? '',
          relation: information?.emergency_contact?.relation ?? '',
          address: information?.emergency_contact?.address ?? '',
          city: information?.emergency_contact?.city ?? '',
          state: information?.emergency_contact?.state ?? '',
          zip: information?.emergency_contact?.zip ?? '',
          home_phone: information?.emergency_contact?.home_phone ?? '',
          work_phone: information?.emergency_contact?.work_phone ?? '',
          cell_phone: information?.emergency_contact?.cell_phone ?? '',
          authorized_pickup: information?.emergency_contact?.authorized_pickup ?? false,
        },
      }

      const medicalInfo = {
        medical: {
          life_health_issues: medical?.medical?.life_health_issues ?? '',
          medications_prescribed: medical?.medical?.medications_prescribed ?? '',
          otc_medications: medical?.medical?.otc_medications ?? '',
          conditions: this.arrayToMultiline(medical?.medical?.conditions),
          notes: medical?.medical?.notes ?? '',
          medications: this.arrayToMultiline(medical?.medical?.medications),
          allergies: this.arrayToMultiline(medical?.medical?.allergies),
          life_changes: medical?.medical?.life_changes ?? '',
          physician_name: medical?.medical?.physician_name ?? '',
          physician_phone: medical?.medical?.physician_phone ?? '',
          tetanus_date: medical?.medical?.tetanus_date ?? '',
        },
        otc_permissions: {
          pain: !!medical?.otc_permissions?.pain,
          skin: !!medical?.otc_permissions?.skin,
          cuts: !!medical?.otc_permissions?.cuts,
          stomach: !!medical?.otc_permissions?.stomach,
          allergies: !!medical?.otc_permissions?.allergies,
        },
        emergency_contact: {
          name: medical?.emergency_contact?.name ?? '',
          relationship: medical?.emergency_contact?.relationship ?? '',
          address: medical?.emergency_contact?.address ?? '',
          city: medical?.emergency_contact?.city ?? '',
          state: medical?.emergency_contact?.state ?? '',
          zip: medical?.emergency_contact?.zip ?? '',
          phone: medical?.emergency_contact?.phone ?? '',
          all_hours_phone: medical?.emergency_contact?.all_hours_phone ?? '',
        },
        insurance: {
          insured_name: medical?.insurance?.insured_name
            ?? medical?.insurance?.primary_insured
            ?? medical?.insurance?.insuredName
            ?? '',
          company: medical?.insurance?.company
            ?? medical?.insurance?.company_name
            ?? medical?.insurance?.provider
            ?? medical?.insurance?.insurance_provider
            ?? '',
          address: medical?.insurance?.address ?? '',
          city: medical?.insurance?.city ?? '',
          state: medical?.insurance?.state ?? '',
          zip: medical?.insurance?.zip ?? '',
          phone: medical?.insurance?.phone
            ?? medical?.insurance?.contact_phone
            ?? '',
          policy_number: medical?.insurance?.policy_number
            ?? medical?.insurance?.policy
            ?? medical?.insurance?.policyNumber
            ?? medical?.insurance?.insurance_policy_number
            ?? '',
          group_number: medical?.insurance?.group_number
            ?? medical?.insurance?.group
            ?? medical?.insurance?.groupNumber
            ?? medical?.insurance?.insurance_group_number
            ?? '',
        },
      }

      return {
        camper_id: rawForm?.camper_id,
        camper_name: rawForm?.camper_name ?? '',
        information: camperInfo,
        medical: medicalInfo,
      }
    },
    normalizeFamilyContacts(rawContacts = [], legacyContact = null) {
      const contactsArray = Array.isArray(rawContacts) ? rawContacts : []
      let contacts = contactsArray.map(contact => this.sanitizeFamilyContact(contact))

      if (!contacts.length && legacyContact) {
        const fallback = this.sanitizeFamilyContact({
          ...legacyContact,
          is_primary: true,
        })
        if (
          fallback.name.length > 0 ||
          fallback.home_phone.length > 0 ||
          fallback.cell_phone.length > 0 ||
          fallback.email.length > 0
        ) {
          contacts = [fallback]
        }
      }

      if (!contacts.length) {
        contacts = [createFamilyContact({ is_primary: true })]
      }

      return this.ensurePrimaryFamilyContact(contacts)
    },
    ensurePrimaryFamilyContact(contacts) {
      if (!Array.isArray(contacts) || !contacts.length) {
        return [createFamilyContact({ is_primary: true })]
      }

      let primaryIndex = contacts.findIndex(contact => contact.is_primary)
      if (primaryIndex === -1) {
        primaryIndex = 0
      }

      return contacts.map((contact, index) => ({
        ...contact,
        is_primary: index === primaryIndex,
        authorized_pickup: !!contact.authorized_pickup,
      }))
    },
    sanitizeFamilyContact(contact = {}) {
      const sanitized = createFamilyContact(contact)
      sanitized.name = (sanitized.name || '').trim()
      sanitized.relation = (sanitized.relation || '').trim()
      sanitized.home_phone = (sanitized.home_phone || '').trim()
      sanitized.cell_phone = (sanitized.cell_phone || '').trim()
      sanitized.work_phone = (sanitized.work_phone || '').trim()
      sanitized.email = (sanitized.email || '').trim()
      sanitized.address = (sanitized.address || '').trim()
      sanitized.city = (sanitized.city || '').trim()
      sanitized.state = (sanitized.state || '').trim()
      sanitized.zip = (sanitized.zip || '').trim()
      sanitized.authorized_pickup = !!sanitized.authorized_pickup
      sanitized.is_primary = !!sanitized.is_primary
      return sanitized
    },
    addFamilyContact() {
      const current = Array.isArray(this.familyForm.emergency_contacts)
        ? [...this.familyForm.emergency_contacts]
        : []
      current.push(createFamilyContact())
      this.familyForm.emergency_contacts = this.ensurePrimaryFamilyContact(current)
      this.syncFamilyLegacyContactFields()
    },
    removeFamilyContact(index) {
      const current = Array.isArray(this.familyForm.emergency_contacts)
        ? [...this.familyForm.emergency_contacts]
        : []
      if (current.length <= 1 || index < 0 || index >= current.length) {
        return
      }
      current.splice(index, 1)
      this.familyForm.emergency_contacts = this.ensurePrimaryFamilyContact(current)
      this.syncFamilyLegacyContactFields()
    },
    setFamilyPrimaryContact(index) {
      if (!Array.isArray(this.familyForm.emergency_contacts)) {
        return
      }
      const current = this.familyForm.emergency_contacts.map((contact, idx) => ({
        ...contact,
        is_primary: idx === index,
      }))
      this.familyForm.emergency_contacts = this.ensurePrimaryFamilyContact(current)
      this.syncFamilyLegacyContactFields()
    },
    syncFamilyLegacyContactFields(contacts = null) {
      const list = Array.isArray(contacts)
        ? contacts
        : Array.isArray(this.familyForm.emergency_contacts)
          ? this.familyForm.emergency_contacts
          : []

      const primary = list.find(contact => contact.is_primary) || list[0] || null

      this.familyForm.emergency_contact_name = primary?.name ?? ''
      this.familyForm.emergency_contact_phone = primary?.home_phone ?? ''
      this.familyForm.emergency_contact_relationship = primary?.relation ?? ''
    },
    validateFamilyPayload(payload) {
      if (!payload || typeof payload !== 'object') {
        return 'Please complete the family information before continuing.'
      }

      if (!payload.name || !payload.name.trim()) {
        return 'Family name is required.'
      }

      if (Array.isArray(payload.contacts) && payload.contacts.length > 0) {
        const contactMissingName = payload.contacts.find(contact => !contact?.name || !contact.name.trim())
        if (contactMissingName) {
          return 'Please provide a name for each emergency contact.'
        }
      }

      return null
    },
    prepareFamilyPayload() {
      const contacts = Array.isArray(this.familyForm.emergency_contacts)
        ? this.familyForm.emergency_contacts.map(contact => this.sanitizeFamilyContact(contact))
        : []

      const populated = contacts.filter(contact => {
        return (
          contact.name.length > 0 ||
          contact.home_phone.length > 0 ||
          contact.cell_phone.length > 0 ||
          contact.email.length > 0 ||
          contact.address.length > 0
        )
      })

      if (!populated.length) {
        this.syncFamilyLegacyContactFields([])
        return {
          name: (this.familyForm.name || '').trim(),
          phone: (this.familyForm.phone || '').trim(),
          address: (this.familyForm.address || '').trim(),
          city: (this.familyForm.city || '').trim(),
          state: (this.familyForm.state || '').trim(),
          zip_code: (this.familyForm.zip_code || '').trim(),
          emergency_contact_name: '',
          emergency_contact_phone: '',
          emergency_contact_relationship: '',
          contacts: [],
        }
      }

      const normalized = this.ensurePrimaryFamilyContact(populated)
      this.syncFamilyLegacyContactFields(normalized)

      return {
        name: (this.familyForm.name || '').trim(),
        phone: (this.familyForm.phone || '').trim(),
        address: (this.familyForm.address || '').trim(),
        city: (this.familyForm.city || '').trim(),
        state: (this.familyForm.state || '').trim(),
        zip_code: (this.familyForm.zip_code || '').trim(),
        emergency_contact_name: this.familyForm.emergency_contact_name,
        emergency_contact_phone: this.familyForm.emergency_contact_phone,
        emergency_contact_relationship: this.familyForm.emergency_contact_relationship,
        contacts: normalized.map(contact => ({
          id: contact.id,
          name: contact.name,
          relation: contact.relation,
          home_phone: contact.home_phone,
          cell_phone: contact.cell_phone,
          work_phone: contact.work_phone,
          email: contact.email,
          address: contact.address,
          city: contact.city,
          state: contact.state,
          zip: contact.zip,
          authorized_pickup: contact.authorized_pickup,
          is_primary: contact.is_primary,
        })),
      }
    },
    createBlankFormForCamper(camper) {
      return this.normalizeFormData({
        camper_id: camper?.id,
        camper_name: `${camper?.first_name || ''} ${camper?.last_name || ''}`.trim(),
        information: {},
        medical: {},
      })
    },
    arrayToMultiline(value) {
      if (Array.isArray(value)) {
        return value.join('\n')
      }
      if (typeof value === 'string') {
        return value
      }
      return ''
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
    nextForm() {
      if (this.currentFormIndex < this.camperForms.length - 1) {
        this.currentFormIndex += 1
      }
    },
    previousForm() {
      if (this.currentFormIndex > 0) {
        this.currentFormIndex -= 1
      }
    },
    applyEmergencyContactToAll() {
      if (!this.currentForm) {
        return
      }

      const source = JSON.parse(JSON.stringify(this.currentForm.information?.emergency_contact ?? {}))
      this.camperForms = this.camperForms.map((form, index) => {
        if (index === this.currentFormIndex) {
          return form
        }
        return {
          ...form,
          information: {
            ...form.information,
            emergency_contact: {
              ...form.information?.emergency_contact,
              ...source,
            },
          },
        }
      })
    },
    applyMedicalEmergencyToAll() {
      if (!this.currentForm) {
        return
      }

      const source = JSON.parse(JSON.stringify(this.currentForm.medical?.emergency_contact ?? {}))
      this.camperForms = this.camperForms.map((form, index) => {
        if (index === this.currentFormIndex) {
          return form
        }
        return {
          ...form,
          medical: {
            ...form.medical,
            emergency_contact: {
              ...form.medical?.emergency_contact,
              ...source,
            },
          },
        }
      })
    },
    applyInsuranceToAll() {
      if (!this.currentForm) {
        return
      }

      const source = JSON.parse(JSON.stringify(this.currentForm.medical?.insurance ?? {}))
      this.camperForms = this.camperForms.map((form, index) => {
        if (index === this.currentFormIndex) {
          return form
        }
        return {
          ...form,
          medical: {
            ...form.medical,
            insurance: {
              ...form.medical?.insurance,
              ...source,
            },
          },
        }
      })
    },
    validateForm(form) {
      if (!form) {
        return false
      }

      const camper = form.information?.camper ?? {}
      const emergency = form.information?.emergency_contact ?? {}
      const insurance = form.medical?.insurance ?? {}

      const requiredStrings = [
        camper.first_name,
        camper.last_name,
        camper.date_of_birth,
        camper.grade,
        emergency.name,
        emergency.home_phone,
        insurance.company,
        insurance.policy_number,
      ]

      if (requiredStrings.some(value => !value || String(value).trim().length === 0)) {
        return false
      }

      return true
    },
    prepareFormForSubmit(form) {
      const information = JSON.parse(JSON.stringify(form.information || {}))
      const medical = JSON.parse(JSON.stringify(form.medical || {}))

      if (information?.camper) {
        if (information.camper.grade !== null && information.camper.grade !== undefined && information.camper.grade !== '') {
          const numericGrade = Number(information.camper.grade)
          information.camper.grade = Number.isNaN(numericGrade) ? information.camper.grade : numericGrade
        }
        information.camper.date_of_birth = information.camper.date_of_birth || null
        information.camper.home_phone = information.camper.home_phone || ''
      }

      if (information?.emergency_contact) {
        information.emergency_contact.authorized_pickup = !!information.emergency_contact.authorized_pickup
      }

      if (medical?.medical) {
        medical.medical.medications = this.stringToArray(medical.medical.medications)
        medical.medical.allergies = this.stringToArray(medical.medical.allergies)
        medical.medical.conditions = this.stringToArray(medical.medical.conditions)
      }

      if (medical?.otc_permissions) {
        Object.keys(medical.otc_permissions).forEach(key => {
          medical.otc_permissions[key] = !!medical.otc_permissions[key]
        })
      }

      return {
        camper_id: form.camper_id,
        information,
        medical,
      }
    },
    async skipForms() {
      if (!this.formsAlreadyOnFile) {
        return
      }

      this.formsSubmitted = true
      await this.loadAnnualStatus()
      this.syncCamperAgreementForms()
      this.currentStep = 5
    },
    async submitCamperForms() {
      if (this.formsSubmitting) {
        return
      }

      if (!this.canSubmitForms) {
        this.formsError = 'Please complete all required fields for each camper before continuing.'
        return
      }

      this.formsSubmitting = true
      this.formsError = null

      try {
        const payload = {
          year: this.activeComplianceYear,
          campers: this.camperForms.map(form => this.prepareFormForSubmit(form))
        }

        await this.callApi('/api/public-registration/forms', {
          method: 'POST',
          body: payload,
          requireAuth: true,
        })

        this.formsSubmitted = true
        await this.loadAnnualStatus()
        this.syncCamperAgreementForms()
        this.currentStep = 5
      } catch (err) {
        console.error('Error saving camper forms:', err)
        this.formsError = err.message || 'Failed to save camper forms. Please try again.'
      } finally {
        this.formsSubmitting = false
      }
    },
    async submitAnnualCompliance() {
      if (!this.canSubmitAnnual) {
        return
      }

      this.annualProcessing = true
      this.annualError = null

      const payload = {
        year: this.activeComplianceYear,
        parent: {
          typed_name: this.annualStatus?.parent?.signed
            ? this.annualStatus.parent.typed_name
            : this.parentAgreementForm.typed_name,
          affirmations: [],
        },
        campers: this.camperAgreementForms.map(form => {
          const status = this.annualStatus?.campers?.find(c => c.camper_id === form.camper_id)
          return {
            camper_id: form.camper_id,
            typed_name: status?.signed ? status.typed_name : form.typed_name,
            affirmations: [],
          }
        }),
      }

      try {
        await this.callApi('/api/public-registration/annual-confirmation', {
          method: 'POST',
          body: payload,
          requireAuth: true,
        })

        await this.loadAnnualStatus()
        this.syncCamperAgreementForms()
        this.currentStep = 5
        this.error = null
      } catch (err) {
        console.error('Error submitting annual compliance:', err)
        this.annualError = err.message || 'Failed to submit annual confirmation. Please try again.'
      } finally {
        this.annualProcessing = false
      }
    },
    syncSelectedCampers() {
      if (!Array.isArray(this.selectedCampers) || this.selectedCampers.length === 0) {
        return
      }

      const camperMap = new Map((this.campers || []).map(c => [c.id, c]))
      this.selectedCampers = this.selectedCampers
        .map(selected => camperMap.get(selected.id) || selected)
        .filter(Boolean)
    },
    updateFormsSubmittedFlag() {
      this.formsSubmitted = this.selectedCampers.length > 0
        ? this.allCampersReady || this.formsAlreadyOnFile
        : false
    },
    async selectCamper(camper) {
      if (!this.selectedCampers.find(c => c.id === camper.id)) {
        this.handleDiscountContextChange()
        this.selectedCampers.push(camper)
        // Load forms data for the newly selected camper
        await this.fetchCamperForms(this.selectedCamperIds, { updateState: true })
        this.updateFormsSubmittedFlag()
      }
    },
    deselectCamper(camper) {
      const index = this.selectedCampers.findIndex(c => c.id === camper.id)
      if (index > -1) {
        this.handleDiscountContextChange()
        this.selectedCampers.splice(index, 1)
        this.fetchCamperForms(this.selectedCamperIds, { updateState: true })
        this.updateFormsSubmittedFlag()
      }
    },
    isCamperSelected(camperId) {
      return this.selectedCampers.some(c => c.id === camperId)
    },
    formatDate(dateString) {
      if (!dateString) return ''
      const date = new Date(dateString)
      return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
    },
    formatDateTime(dateString) {
      if (!dateString) return ''
      const date = new Date(dateString)
      if (Number.isNaN(date.getTime())) {
        return dateString
      }
      return date.toLocaleString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
      })
    },
    openNewCamperModal() {
      this.editingCamper = null
      this.camperForm = {
        first_name: '',
        last_name: '',
        date_of_birth: '',
        gender: '',
      }
      this.camperPhotoFile = null
      this.camperPhotoFileName = ''
      this.camperPhotoPreview = null
      this.camperPhotoError = ''
      this.camperHasUpcomingEnrollment = false
      this.deletingCamper = false
      this.showNewCamperModal = true
    },
    closeNewCamperModal() {
      this.showNewCamperModal = false
      this.editingCamper = null
      this.camperForm = {
        first_name: '',
        last_name: '',
        date_of_birth: '',
        gender: '',
      }
      this.camperPhotoFile = null
      this.camperPhotoFileName = ''
      this.camperPhotoPreview = null
      this.camperPhotoError = ''
      this.camperHasUpcomingEnrollment = false
      this.deletingCamper = false
    },
    async saveCamper() {
      this.processing = true
      this.error = null
      this.camperPhotoError = ''
      
      try {
        const formData = new FormData()

        const isEditing = !!this.editingCamper?.id

        if (isEditing) {
          formData.append('id', this.editingCamper.id)
        }

        const payload = {
          first_name: this.camperForm.first_name?.trim() || '',
          last_name: this.camperForm.last_name?.trim() || '',
          date_of_birth: this.camperForm.date_of_birth || '',
          biological_gender: this.camperForm.gender || '',
        }

        Object.entries(payload).forEach(([key, value]) => {
          if (value !== '' && value !== null && value !== undefined) {
            formData.append(key, value)
          }
        })

        if (this.camperPhotoFile) {
          formData.append('photo', this.camperPhotoFile)
        }
        
        const data = await this.callApi('/api/public-registration/camper', {
          method: 'POST',
          body: formData,
          requireAuth: true,
        })
        
        await this.loadUserData()
        await this.loadAnnualStatus()
        this.closeNewCamperModal()

        if (!isEditing && data.camper && !this.selectedCampers.find(c => c.id === data.camper.id)) {
          this.selectedCampers.push(data.camper)
        }
      } catch (err) {
        this.error = err.message || 'Failed to save camper. Please try again.'
      } finally {
        this.processing = false
        if (this.camperPhotoPreview) {
          URL.revokeObjectURL(this.camperPhotoPreview)
          this.camperPhotoPreview = null
        }
      }
    },
    async upsertCamper(payload) {
      const formData = new FormData()
      Object.entries(payload).forEach(([key, value]) => {
        if (value !== undefined && value !== null && value !== '') {
          formData.append(key, value)
        }
      })

      const data = await this.callApi('/api/public-registration/camper', {
        method: 'POST',
        body: formData,
        requireAuth: true,
      })

      await this.loadUserData()
      return data?.camper
    },
    async restoreArchivedCamper(camperId) {
      if (this.restoringCamperId) {
        return
      }

      const authenticated = await this.ensureAuthenticatedState()
      if (!authenticated) {
        return
      }

      this.restoringCamperId = camperId
      try {
        await this.callApi(`/api/public-registration/camper/${camperId}/restore`, {
          method: 'PATCH',
          requireAuth: true,
        })

        await this.loadUserData()
        await this.loadAnnualStatus()
      } catch (err) {
        console.error('Error restoring camper:', err)
        this.error = err.message || 'Failed to restore camper. Please try again.'
        window.alert(this.error)
      } finally {
        this.restoringCamperId = null
      }
    },
    async handleCamperCardSelect(camper) {
      if (this.camperHasCompleteForms(camper.id)) {
        await this.selectCamper(camper)
        return
      }

      const authenticated = await this.ensureAuthenticatedState()
      if (!authenticated) {
        return
      }

      this.pendingSelectionCamperId = camper.id
      await this.openCamperFormsModal(camper)
    },
    handleCamperCardDeselect(camper) {
      this.deselectCamper(camper)
    },
    async handleCamperUpdateFromCard(payload, done) {
      try {
      const authenticated = await this.ensureAuthenticatedState()
      if (!authenticated) {
          done?.(false)
          return
        }

        const updatedCamper = await this.upsertCamper(payload)
        await this.loadAnnualStatus()
        done?.(true, updatedCamper)
      } catch (err) {
        this.error = err.message || 'Failed to update camper. Please try again.'
        done?.(false)
      }
    },
    async handleCamperDeleteFromCard(camper, done) {
      try {
        const authenticated = await this.ensureAuthenticatedState()
        if (!authenticated) {
          done?.(false, 'Authentication required.')
          return
        }

        await this.callApi(`/api/public-registration/camper/${camper.id}`, {
          method: 'DELETE',
          requireAuth: true,
        })

        this.selectedCampers = this.selectedCampers.filter(c => c.id !== camper.id)
        if (this.pendingSelectionCamperId === camper.id) {
          this.pendingSelectionCamperId = null
        }
        if (this.activeCamperForForms?.id === camper.id) {
          this.closeCamperFormsModal()
        }
        this.camperForms = this.camperForms.filter(form => form.camper_id !== camper.id)
        if (this.currentFormIndex >= this.camperForms.length) {
          this.currentFormIndex = Math.max(0, this.camperForms.length - 1)
        }

        await this.loadUserData()
        await this.loadAnnualStatus()
        done?.(true)
      } catch (err) {
        this.error = err.message || 'Failed to remove camper. Please try again.'
        done?.(false, this.error)
      }
    },
    async handleCardRequestForms(camper) {
      const authenticated = await this.ensureAuthenticatedState()
      if (!authenticated) {
        return
      }

      this.pendingSelectionCamperId = null
      await this.openCamperFormsModal(camper)
    },
    async openCamperFormsModal(camper) {
      try {
        const forms = await this.fetchCamperForms([camper.id], { updateState: false })
        this.activeCamperForForms = camper
        this.camperFormsModalData = forms[0] || this.createBlankFormForCamper(camper)
        this.showCamperFormsModal = true
      } catch (err) {
        console.error('Error loading camper forms:', err)
        this.error = err.message || 'Failed to load camper forms. Please try again.'
        this.pendingSelectionCamperId = null
      }
    },
    closeCamperFormsModal() {
      this.showCamperFormsModal = false
      this.activeCamperForForms = null
      this.camperFormsModalData = null
      if (this.pendingSelectionCamperId && !this.camperHasCompleteForms(this.pendingSelectionCamperId)) {
        this.pendingSelectionCamperId = null
      }
    },
    evaluatePendingSelection() {
      if (!this.pendingSelectionCamperId) {
        return
      }

      if (this.camperHasCompleteForms(this.pendingSelectionCamperId)) {
        const camper = (this.campers || []).find(c => c.id === this.pendingSelectionCamperId)
        if (camper) {
          this.selectCamper(camper)
        }
        this.pendingSelectionCamperId = null
        this.closeCamperFormsModal()
      }
    },
    async createEnrollments() {
      if (this.selectedCampers.length === 0) {
        this.error = 'Please select at least one camper'
        return
      }

      if (!this.formsSubmitted) {
        this.error = 'Please complete and save camper forms before registering for a camp.'
        await this.continueToForms()
        return
      }

      if (!this.annualStatusComplete) {
        this.error = 'Please complete the annual agreements before registering for a camp.'
        this.currentStep = 5
        return
      }

      const authenticated = await this.ensureAuthenticatedState()
      if (!authenticated) {
        return
      }
      
      this.processing = true
      this.error = null
      
      try {
        const enrollments = this.selectedCampers.map(camper => ({
          camper_id: camper.id,
          camp_instance_id: this.campInstanceId
        }))

        const payload = {
          enrollments,
          payment_method: this.paymentMethod,
        }

        if (this.hasDiscount && this.discount.codeId) {
          payload.discount = {
            code: this.discount.code,
            discount_code_id: this.discount.codeId,
          }
        }

        const data = await this.callApi('/api/public-registration/enrollments', {
          method: 'POST',
          body: payload,
          requireAuth: true,
        })

        if (!data?.enrollment_ids || !Array.isArray(data.enrollment_ids) || data.enrollment_ids.length === 0) {
          throw new Error('No enrollment IDs were returned. Please try again.')
        }

        this.enrollmentIds = data.enrollment_ids
        this.enrollmentCreated = true
        if (typeof data?.discount_cents === 'number') {
          const discountDollars = data.discount_cents / 100
          if (discountDollars > 0) {
            this.discount.valid = true
            this.discount.amount = discountDollars
            this.discount.finalAmount = data?.total_amount_cents
              ? data.total_amount_cents / 100
              : this.totalAmount
            if (typeof data?.discount_message === 'string' && data.discount_message.length > 0) {
              this.discount.message = data.discount_message
            }
          }
        }

        if (this.paymentMethod === 'cash_check') {
          this.registrationComplete = true
        }

        // Force Vue to re-render the StripePaymentForm component when using Stripe
        await this.$nextTick()
      } catch (err) {
        console.error('Error creating enrollments:', err)
        this.error = err.message || 'Failed to create enrollments. Please try again.'
        this.enrollmentCreated = false
        this.enrollmentIds = []
      } finally {
        this.processing = false
      }
    },
    async handlePaymentSuccess(paymentData) {
      this.processing = true
      
      try {
        await this.callApi('/api/public-registration/confirm-payment', {
          method: 'POST',
          body: {
            payment_intent_id: paymentData.paymentIntentId,
            enrollment_ids: this.enrollmentIds
          },
          requireAuth: true,
        })

        this.registrationComplete = true
      } catch (err) {
        this.error = err.message
      } finally {
        this.processing = false
      }
    },
    goToStep(step) {
      if (step === 4) {
        if (!this.formsSubmitted) {
          this.error = 'Please complete and save camper forms before continuing to payment.'
          return
        }
        if (!this.annualStatusComplete) {
          this.error = 'Please complete the annual agreements before proceeding to payment.'
          return
        }
      }

      this.error = null
      this.currentStep = step
    },
    close() {
      if (window.closeCampRegistrationModal) {
        window.closeCampRegistrationModal()
      }
      this.$emit('close')
    },
    handleBackdropClick() {
      // Only allow closing if not in a critical step
      if (this.registrationComplete || this.currentStep === 1) {
        this.close()
      }
    },
    camperHasCompleteForms(camperId) {
      if (!this.annualStatus) return false
      const parentSigned = !!this.annualStatus.parent?.signed
      if (!parentSigned) return false

      const status = this.annualStatus.campers?.find(c => c.camper_id === camperId)
      return !!(status?.information_snapshot_id && status?.medical_snapshot_id && status?.signed)
    },
    getCamperFormsData(camperId) {
      if (!this.camperForms.length) return null

      return this.camperForms.find(form => form.camper_id === camperId) || null
    },
    async onCamperFormsUpdated() {
      await this.loadAnnualStatus()
      await this.fetchCamperForms(this.selectedCamperIds, { updateState: true })
      this.evaluatePendingSelection()
      this.updateFormsSubmittedFlag()
    },
    async onCamperAgreementsUpdated() {
      await this.loadAnnualStatus()
      await this.fetchCamperForms(this.selectedCamperIds, { updateState: true })
      this.updateFormsSubmittedFlag()
    },
    handleCamperEditFromCard(camper) {
      this.editingCamper = camper
      this.camperForm = {
        first_name: camper.first_name || '',
        last_name: camper.last_name || '',
        date_of_birth: camper.date_of_birth || '',
        gender: camper.biological_gender || '',
      }
      this.camperPhotoFile = null
      this.camperPhotoFileName = ''
      this.camperPhotoPreview = null
      this.camperPhotoError = ''
      this.camperHasUpcomingEnrollment = !!camper.has_upcoming_enrollment
      this.deletingCamper = false
      this.showNewCamperModal = true
    },
    handleCamperPhotoChange(event) {
      const [file] = event.target.files || []
      this.camperPhotoError = ''

      if (!file) {
        this.camperPhotoFile = null
        this.camperPhotoFileName = ''
        this.camperPhotoPreview = null
        return
      }

      if (!file.type?.startsWith('image/')) {
        this.camperPhotoError = 'Please choose a valid image file.'
        this.camperPhotoFile = null
        this.camperPhotoFileName = ''
        this.camperPhotoPreview = null
        event.target.value = ''
        return
      }

      const maxSize = 5 * 1024 * 1024
      if (file.size > maxSize) {
        this.camperPhotoError = 'Photo must be 5 MB or smaller.'
        this.camperPhotoFile = null
        this.camperPhotoFileName = ''
        this.camperPhotoPreview = null
        event.target.value = ''
        return
      }

      this.camperPhotoFile = file
      this.camperPhotoFileName = file.name
      this.camperPhotoPreview = URL.createObjectURL(file)
    },
    clearCamperPhoto() {
      this.camperPhotoFile = null
      this.camperPhotoFileName = ''
      this.camperPhotoError = ''
      if (this.camperPhotoPreview) {
        URL.revokeObjectURL(this.camperPhotoPreview)
      }
      this.camperPhotoPreview = null
    },
    deleteEditingCamper() {
      this.deletingCamper = true
      this.camperForm = {
        first_name: '',
        last_name: '',
        date_of_birth: '',
        gender: '',
      }
      this.showNewCamperModal = true
    }
  }
}
</script>

