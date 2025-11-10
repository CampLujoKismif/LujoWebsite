<template>
  <div class="parent-onboarding bg-white dark:bg-zinc-900 rounded-lg shadow-lg p-6">
    <!-- Step Indicator -->
    <div class="mb-8">
      <div class="flex items-center justify-center space-x-4 sm:space-x-8">
        <!-- Step 1: Family Information -->
        <div class="flex items-center space-x-2 sm:space-x-3">
          <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 transition-all duration-300"
               :class="currentStep >= 1 ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-gray-100 dark:bg-zinc-800 border-gray-300 dark:border-zinc-600 text-gray-500 dark:text-gray-400'">
            <span class="text-sm sm:text-base font-semibold">1</span>
          </div>
          <span class="text-sm sm:text-base font-medium transition-colors duration-300 hidden sm:inline"
                :class="currentStep >= 1 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'">
            Family Info
          </span>
        </div>
        
        <!-- Arrow -->
        <div class="w-6 sm:w-8 h-0.5 bg-gray-300 dark:bg-zinc-600"></div>
        
        <!-- Step 2: Campers -->
        <div class="flex items-center space-x-2 sm:space-x-3">
          <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 transition-all duration-300"
               :class="currentStep >= 2 ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-gray-100 dark:bg-zinc-800 border-gray-300 dark:border-zinc-600 text-gray-500 dark:text-gray-400'">
            <span class="text-sm sm:text-base font-semibold">2</span>
          </div>
          <span class="text-sm sm:text-base font-medium transition-colors duration-300 hidden sm:inline"
                :class="currentStep >= 2 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'">
            Add Campers
          </span>
        </div>
        
        <!-- Arrow -->
        <div class="w-6 sm:w-8 h-0.5 bg-gray-300 dark:bg-zinc-600"></div>
        
        <!-- Step 3: Registration -->
        <div class="flex items-center space-x-2 sm:space-x-3">
          <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 transition-all duration-300"
               :class="currentStep >= 3 ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-gray-100 dark:bg-zinc-800 border-gray-300 dark:border-zinc-600 text-gray-500 dark:text-gray-400'">
            <span class="text-sm sm:text-base font-semibold">3</span>
          </div>
          <span class="text-sm sm:text-base font-medium transition-colors duration-300 hidden sm:inline"
                :class="currentStep >= 3 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400'">
            Register & Pay
          </span>
        </div>
      </div>
    </div>

    <!-- Step Content Container -->
    <div class="relative overflow-hidden min-h-[400px]">
      <!-- Step 1: Family Information -->
      <div class="transition-all duration-500 ease-in-out"
           :class="currentStep === 1 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'">
        <div class="max-w-2xl mx-auto">
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Family Information</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">Please provide your family's contact and emergency information.</p>
          
          <form @submit.prevent="saveFamilyInfo" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Family Name *</label>
                <input v-model="familyForm.name" required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                <input v-model="familyForm.phone" type="tel"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
              </div>
              
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                <input v-model="familyForm.address"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">City</label>
                <input v-model="familyForm.city"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">State</label>
                <input v-model="familyForm.state"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ZIP Code</label>
                <input v-model="familyForm.zip_code"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
              </div>
            </div>
            
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
              <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Emergency Contact</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Emergency Contact Name</label>
                  <input v-model="familyForm.emergency_contact_name"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Emergency Contact Phone</label>
                  <input v-model="familyForm.emergency_contact_phone" type="tel"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relationship</label>
                  <input v-model="familyForm.emergency_contact_relationship"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                </div>
              </div>
            </div>
            
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
              <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Insurance Information</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Insurance Provider</label>
                  <input v-model="familyForm.insurance_provider"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Policy Number</label>
                  <input v-model="familyForm.insurance_policy_number"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                </div>
              </div>
            </div>
            
            <div class="flex justify-end pt-6">
              <button type="submit"
                      class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                Continue to Campers
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Step 2: Add Campers -->
      <div class="transition-all duration-500 ease-in-out"
           :class="currentStep === 2 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'">
        <div class="max-w-2xl mx-auto">
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Add Campers</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">Add your campers' information. You can skip this step and add campers later.</p>
          
          <!-- Existing Campers -->
          <div v-if="campers.length > 0" class="mb-6 space-y-3">
            <div v-for="camper in campers" :key="camper.id" class="p-4 bg-gray-50 dark:bg-zinc-800 rounded-lg flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <img v-if="camper.photo_url" :src="camper.photo_url" :alt="camper.first_name" class="w-12 h-12 rounded-full object-cover">
                <div v-else class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                  <span class="text-gray-500 dark:text-gray-400">{{ camper.first_name[0] }}{{ camper.last_name[0] }}</span>
                </div>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ camper.first_name }} {{ camper.last_name }}</p>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Grade {{ camper.grade }}</p>
                </div>
              </div>
              <button @click="deleteCamper(camper.id)" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </button>
            </div>
          </div>
          
          <!-- Add Camper Form -->
          <div v-if="showCamperForm" class="mb-6 p-6 bg-gray-50 dark:bg-zinc-800 rounded-lg">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ editingCamper ? 'Edit Camper' : 'Add New Camper' }}</h4>
            <form @submit.prevent="saveCamper" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">First Name *</label>
                  <input v-model="camperForm.first_name" required
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white">
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Last Name *</label>
                  <input v-model="camperForm.last_name" required
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white">
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth *</label>
                  <input v-model="camperForm.date_of_birth" type="date" required
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white">
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Grade *</label>
                  <select v-model.number="camperForm.grade" required
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white">
                    <option value="">Select Grade</option>
                    <option v-for="i in 12" :key="i" :value="i">{{ i }}</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">T-Shirt Size</label>
                  <select v-model="camperForm.t_shirt_size"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white">
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
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Photo</label>
                  <input type="file" @change="handlePhotoUpload" accept="image/*"
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white">
                </div>
              </div>
              
              <div class="flex space-x-3">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                  {{ editingCamper ? 'Update' : 'Add' }} Camper
                </button>
                <button type="button" @click="cancelCamperForm"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700">
                  Cancel
                </button>
              </div>
            </form>
          </div>
          
          <!-- Add Camper Button -->
          <div v-if="!showCamperForm" class="mb-6">
            <button @click="showCamperForm = true; editingCamper = null"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
              + Add Camper
            </button>
          </div>
          
          <div class="flex justify-between pt-6">
            <button @click="goToStep(1)"
                    class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700">
              ← Back
            </button>
            <div class="space-x-3">
              <button @click="skipToRegistration"
                      class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700">
                Skip for Now
              </button>
              <button @click="goToStep(3)"
                      :disabled="campers.length === 0"
                      class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                Continue to Registration
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 3: Registration & Payment -->
      <div class="transition-all duration-500 ease-in-out"
           :class="currentStep === 3 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0'">
        <div class="max-w-2xl mx-auto">
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Register for Camp Sessions</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">Select camp sessions for your campers and complete payment.</p>
          
          <!-- Camp Sessions -->
          <div v-if="campSessions.length === 0" class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400 mb-4">No camp sessions available at this time.</p>
            <button @click="completeOnboarding"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
              Complete Onboarding
            </button>
          </div>
          
          <div v-else class="space-y-4 mb-6">
            <div v-for="session in campSessions" :key="session.id" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <h4 class="font-semibold text-gray-900 dark:text-white">{{ session.name }}</h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ session.camp_name }}</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ formatDate(session.start_date) }} - {{ formatDate(session.end_date) }}
                  </p>
                  <p v-if="session.price" class="text-lg font-semibold text-indigo-600 dark:text-indigo-400 mt-2">
                    ${{ parseFloat(session.price).toFixed(2) }}
                  </p>
                </div>
                <div class="ml-4">
                  <label class="flex items-center space-x-2">
                    <input type="checkbox" :value="session.id" v-model="selectedSessions"
                           class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Select</span>
                  </label>
                </div>
              </div>
              
              <!-- Camper Selection for this Session -->
              <div v-if="selectedSessions.includes(session.id)" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select campers for this session:</p>
                <div class="space-y-2">
                  <label v-for="camper in campers" :key="camper.id" class="flex items-center space-x-2">
                    <input type="checkbox" :value="camper.id"
                           @change="toggleEnrollment(session.id, camper.id)"
                           :checked="isEnrolled(session.id, camper.id)"
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ camper.first_name }} {{ camper.last_name }}</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Summary -->
          <div v-if="enrollments.length > 0" class="mb-6 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Registration Summary</h4>
            <div class="space-y-2">
              <div v-for="enrollment in enrollments" :key="`${enrollment.camper_id}-${enrollment.camp_instance_id}`"
                   class="flex justify-between text-sm">
                <span class="text-gray-700 dark:text-gray-300">
                  {{ getCamperName(enrollment.camper_id) }} - {{ getSessionName(enrollment.camp_instance_id) }}
                </span>
                <span class="font-semibold text-gray-900 dark:text-white">
                  ${{ (getSessionPrice(enrollment.camp_instance_id) / 100).toFixed(2) }}
                </span>
              </div>
              <div class="border-t border-indigo-200 dark:border-indigo-800 pt-2 flex justify-between">
                <span class="font-semibold text-gray-900 dark:text-white">Total:</span>
                <span class="font-bold text-lg text-indigo-600 dark:text-indigo-400">
                  ${{ (totalAmount / 100).toFixed(2) }}
                </span>
              </div>
            </div>
          </div>
          
          <div class="flex justify-between pt-6">
            <button @click="goToStep(2)"
                    class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700">
              ← Back
            </button>
            <button @click="processRegistration"
                    :disabled="enrollments.length === 0 || processing"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
              {{ processing ? 'Processing...' : 'Complete Registration & Pay' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ParentOnboarding',
  data() {
    return {
      currentStep: 1,
      loading: false,
      processing: false,
      family: {},
      campers: [],
      campSessions: [],
      familyForm: {
        name: '',
        phone: '',
        address: '',
        city: '',
        state: '',
        zip_code: '',
        emergency_contact_name: '',
        emergency_contact_phone: '',
        emergency_contact_relationship: '',
        insurance_provider: '',
        insurance_policy_number: '',
      },
      showCamperForm: false,
      editingCamper: null,
      camperForm: {
        first_name: '',
        last_name: '',
        date_of_birth: '',
        grade: '',
        t_shirt_size: '',
        photo: null,
      },
      selectedSessions: [],
      enrollments: [],
    }
  },
  computed: {
    totalAmount() {
      return this.enrollments.reduce((sum, enrollment) => {
        return sum + this.getSessionPrice(enrollment.camp_instance_id);
      }, 0);
    }
  },
  mounted() {
    this.loadInitialData();
  },
  methods: {
    async loadInitialData() {
      this.loading = true;
      try {
        const response = await fetch('/api/parent-onboarding/initial-data', {
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
          },
          credentials: 'same-origin',
        });
        
        const data = await response.json();
        
        this.family = data.family;
        this.campers = data.campers;
        this.campSessions = data.campSessions;
        
        // Populate family form
        Object.keys(this.familyForm).forEach(key => {
          if (data.family[key]) {
            this.familyForm[key] = data.family[key];
          }
        });
      } catch (error) {
        console.error('Error loading initial data:', error);
        alert('Failed to load data. Please refresh the page.');
      } finally {
        this.loading = false;
      }
    },
    
    async saveFamilyInfo() {
      this.loading = true;
      try {
        const response = await fetch('/api/parent-onboarding/family', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
          },
          credentials: 'same-origin',
          body: JSON.stringify(this.familyForm),
        });
        
        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.message || 'Failed to save family information');
        }
        
        this.goToStep(2);
      } catch (error) {
        console.error('Error saving family info:', error);
        alert(error.message || 'Failed to save family information');
      } finally {
        this.loading = false;
      }
    },
    
    async saveCamper() {
      this.loading = true;
      try {
        const formData = new FormData();
        formData.append('first_name', this.camperForm.first_name);
        formData.append('last_name', this.camperForm.last_name);
        formData.append('date_of_birth', this.camperForm.date_of_birth);
        formData.append('grade', this.camperForm.grade);
        formData.append('t_shirt_size', this.camperForm.t_shirt_size || '');
        if (this.editingCamper) {
          formData.append('id', this.editingCamper);
        }
        if (this.camperForm.photo) {
          formData.append('photo', this.camperForm.photo);
        }
        
        const response = await fetch('/api/parent-onboarding/camper', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
          },
          credentials: 'same-origin',
          body: formData,
        });
        
        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.message || 'Failed to save camper');
        }
        
        const data = await response.json();
        
        if (this.editingCamper) {
          const index = this.campers.findIndex(c => c.id === this.editingCamper);
          if (index !== -1) {
            this.campers[index] = data.camper;
          }
        } else {
          this.campers.push(data.camper);
        }
        
        this.cancelCamperForm();
      } catch (error) {
        console.error('Error saving camper:', error);
        alert(error.message || 'Failed to save camper');
      } finally {
        this.loading = false;
      }
    },
    
    async deleteCamper(camperId) {
      if (!confirm('Are you sure you want to delete this camper?')) {
        return;
      }
      
      try {
        const response = await fetch(`/api/parent-onboarding/camper/${camperId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
          },
          credentials: 'same-origin',
        });
        
        if (!response.ok) {
          throw new Error('Failed to delete camper');
        }
        
        this.campers = this.campers.filter(c => c.id !== camperId);
      } catch (error) {
        console.error('Error deleting camper:', error);
        alert('Failed to delete camper');
      }
    },
    
    handlePhotoUpload(event) {
      this.camperForm.photo = event.target.files[0];
    },
    
    cancelCamperForm() {
      this.showCamperForm = false;
      this.editingCamper = null;
      this.camperForm = {
        first_name: '',
        last_name: '',
        date_of_birth: '',
        grade: '',
        t_shirt_size: '',
        photo: null,
      };
    },
    
    toggleEnrollment(sessionId, camperId) {
      const index = this.enrollments.findIndex(
        e => e.camp_instance_id === sessionId && e.camper_id === camperId
      );
      
      if (index === -1) {
        this.enrollments.push({
          camp_instance_id: sessionId,
          camper_id: camperId,
        });
      } else {
        this.enrollments.splice(index, 1);
      }
    },
    
    isEnrolled(sessionId, camperId) {
      return this.enrollments.some(
        e => e.camp_instance_id === sessionId && e.camper_id === camperId
      );
    },
    
    getCamperName(camperId) {
      const camper = this.campers.find(c => c.id === camperId);
      return camper ? `${camper.first_name} ${camper.last_name}` : '';
    },
    
    getSessionName(sessionId) {
      const session = this.campSessions.find(s => s.id === sessionId);
      return session ? session.name : '';
    },
    
    getSessionPrice(sessionId) {
      const session = this.campSessions.find(s => s.id === sessionId);
      return session && session.price ? Math.round(session.price * 100) : 0;
    },
    
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    },
    
    async processRegistration() {
      if (this.enrollments.length === 0) {
        alert('Please select at least one camper and session');
        return;
      }
      
      this.processing = true;
      try {
        const response = await fetch('/api/parent-onboarding/enrollments', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
          },
          credentials: 'same-origin',
          body: JSON.stringify({
            enrollments: this.enrollments,
          }),
        });
        
        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.message || 'Failed to create enrollments');
        }
        
        // Complete onboarding
        await this.completeOnboarding();
        
        // Redirect to dashboard
        window.location.href = '/dashboard';
      } catch (error) {
        console.error('Error processing registration:', error);
        alert(error.message || 'Failed to process registration');
      } finally {
        this.processing = false;
      }
    },
    
    async completeOnboarding() {
      try {
        const response = await fetch('/api/parent-onboarding/complete', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
          },
          credentials: 'same-origin',
        });
        
        if (!response.ok) {
          throw new Error('Failed to complete onboarding');
        }
      } catch (error) {
        console.error('Error completing onboarding:', error);
      }
    },
    
    goToStep(step) {
      this.currentStep = step;
    },
    
    skipToRegistration() {
      this.goToStep(3);
    },
  }
}
</script>

