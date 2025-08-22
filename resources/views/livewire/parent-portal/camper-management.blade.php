<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Camper Management</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your campers' information and medical records</p>
        </div>

        <!-- Add Camper Button -->
        <div class="mb-6">
            <button wire:click="openAddCamperModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Camper
            </button>
        </div>

        <!-- Campers List -->
        @if($campers->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No campers found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding your first camper.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($campers as $camper)
                    <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <!-- Camper Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $camper->full_name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Grade {{ $camper->grade }} • Age {{ $camper->age }}</p>
                                    @if($camper->hasMedicalAlerts())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Medical Alerts
                                        </span>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click="openEditCamperModal({{ $camper->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="openMedicalModal({{ $camper->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Camper Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Birth Date:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $camper->birth_date->format('M j, Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ ucfirst($camper->gender) }}</p>
                                </div>
                                @if($camper->allergies)
                                    <div class="md:col-span-2">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Allergies:</span>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $camper->allergies }}</p>
                                    </div>
                                @endif
                                @if($camper->medical_conditions)
                                    <div class="md:col-span-2">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Medical Conditions:</span>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $camper->medical_conditions }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Active Enrollments -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Active Enrollments ({{ $camper->enrollments->where('status', 'confirmed')->count() }})</h4>
                                </div>
                                @if($camper->enrollments->where('status', 'confirmed')->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($camper->enrollments->where('status', 'confirmed')->take(3) as $enrollment)
                                            <div class="flex items-center p-2 bg-gray-50 dark:bg-gray-800 rounded">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $enrollment->campInstance->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $enrollment->campInstance->start_date->format('M j') }} - {{ $enrollment->campInstance->end_date->format('M j, Y') }}</p>
                                                </div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Confirmed
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No active enrollments</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Add Camper Modal -->
    @if($showAddCamperModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Camper</h3>
                    <form wire:submit.prevent="createCamper">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="familyId" class="block text-sm font-medium text-gray-700">Family</label>
                                <select wire:model="familyId" id="familyId" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Family</option>
                                    @foreach($families as $family)
                                        <option value="{{ $family->id }}">{{ $family->name }}</option>
                                    @endforeach
                                </select>
                                @error('familyId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" wire:model="firstName" id="firstName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('firstName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" wire:model="lastName" id="lastName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('lastName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="dateOfBirth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" wire:model="dateOfBirth" id="dateOfBirth" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('dateOfBirth') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select wire:model="gender" id="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="grade" class="block text-sm font-medium text-gray-700">Grade</label>
                                <select wire:model="grade" id="grade" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Grade</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('grade') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="school" class="block text-sm font-medium text-gray-700">School</label>
                                <input type="text" wire:model="school" id="school" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('school') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Allergies -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Allergies</label>
                            @foreach($allergies as $index => $allergy)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="allergies.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter allergy">
                                    <button type="button" wire:click="removeAllergy({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addAllergy" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Allergy</button>
                        </div>

                        <!-- Medical Conditions -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions</label>
                            @foreach($medicalConditions as $index => $condition)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="medicalConditions.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter medical condition">
                                    <button type="button" wire:click="removeMedicalCondition({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addMedicalCondition" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Medical Condition</button>
                        </div>

                        <!-- Medications -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medications</label>
                            @foreach($medications as $index => $medication)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="medications.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter medication">
                                    <button type="button" wire:click="removeMedication({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addMedication" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Medication</button>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showAddCamperModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Create Camper
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Camper Modal -->
    @if($showEditCamperModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Camper</h3>
                    <form wire:submit.prevent="updateCamper">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editFamilyId" class="block text-sm font-medium text-gray-700">Family</label>
                                <select wire:model="familyId" id="editFamilyId" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Family</option>
                                    @foreach($families as $family)
                                        <option value="{{ $family->id }}">{{ $family->name }}</option>
                                    @endforeach
                                </select>
                                @error('familyId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editFirstName" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" wire:model="firstName" id="editFirstName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('firstName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editLastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" wire:model="lastName" id="editLastName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('lastName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editDateOfBirth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" wire:model="dateOfBirth" id="editDateOfBirth" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('dateOfBirth') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editGender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select wire:model="gender" id="editGender" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editGrade" class="block text-sm font-medium text-gray-700">Grade</label>
                                <select wire:model="grade" id="editGrade" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Grade</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('grade') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editSchool" class="block text-sm font-medium text-gray-700">School</label>
                                <input type="text" wire:model="school" id="editSchool" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('school') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Allergies -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Allergies</label>
                            @foreach($allergies as $index => $allergy)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="allergies.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter allergy">
                                    <button type="button" wire:click="removeAllergy({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addAllergy" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Allergy</button>
                        </div>

                        <!-- Medical Conditions -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions</label>
                            @foreach($medicalConditions as $index => $condition)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="medicalConditions.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter medical condition">
                                    <button type="button" wire:click="removeMedicalCondition({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addMedicalCondition" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Medical Condition</button>
                        </div>

                        <!-- Medications -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medications</label>
                            @foreach($medications as $index => $medication)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="medications.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter medication">
                                    <button type="button" wire:click="removeMedication({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addMedication" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Medication</button>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showEditCamperModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Update Camper
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Medical Records Modal -->
    @if($showMedicalModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Medical Records - {{ $selectedCamper->full_name ?? '' }}</h3>
                    <form wire:submit.prevent="saveMedicalRecord">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="physicianName" class="block text-sm font-medium text-gray-700">Physician Name</label>
                                <input type="text" wire:model="physicianName" id="physicianName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('physicianName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="physicianPhone" class="block text-sm font-medium text-gray-700">Physician Phone</label>
                                <input type="tel" wire:model="physicianPhone" id="physicianPhone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('physicianPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="policyNumber" class="block text-sm font-medium text-gray-700">Insurance Policy Number</label>
                                <input type="text" wire:model="policyNumber" id="policyNumber" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('policyNumber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="insuranceProvider" class="block text-sm font-medium text-gray-700">Insurance Provider</label>
                                <input type="text" wire:model="insuranceProvider" id="insuranceProvider" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('insuranceProvider') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="medicalEmergencyContactName" class="block text-sm font-medium text-gray-700">Emergency Contact Name</label>
                                <input type="text" wire:model="medicalEmergencyContactName" id="medicalEmergencyContactName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('medicalEmergencyContactName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="medicalEmergencyContactPhone" class="block text-sm font-medium text-gray-700">Emergency Contact Phone</label>
                                <input type="tel" wire:model="medicalEmergencyContactPhone" id="medicalEmergencyContactPhone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('medicalEmergencyContactPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="medicalEmergencyContactRelationship" class="block text-sm font-medium text-gray-700">Relationship</label>
                                <input type="text" wire:model="medicalEmergencyContactRelationship" id="medicalEmergencyContactRelationship" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('medicalEmergencyContactRelationship') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Medical Allergies -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Allergies</label>
                            @foreach($medicalAllergies as $index => $allergy)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="medicalAllergies.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter allergy">
                                    <button type="button" wire:click="removeMedicalAllergy({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addMedicalAllergy" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Allergy</button>
                        </div>

                        <!-- Medical Medications -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Medications</label>
                            @foreach($medicalMedications as $index => $medication)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="medicalMedications.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter medication">
                                    <button type="button" wire:click="removeMedicalMedication({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addMedicalMedication" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Medication</button>
                        </div>

                        <!-- Medical Conditions -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions</label>
                            @foreach($medicalConditions as $index => $condition)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="medicalConditions.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter medical condition">
                                    <button type="button" wire:click="removeMedicalCondition({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addMedicalCondition" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Medical Condition</button>
                        </div>

                        <!-- Dietary Restrictions -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dietary Restrictions</label>
                            @foreach($dietaryRestrictions as $index => $restriction)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" wire:model="dietaryRestrictions.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter dietary restriction">
                                    <button type="button" wire:click="removeDietaryRestriction({{ $index }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addDietaryRestriction" class="text-sm text-indigo-600 hover:text-indigo-900">+ Add Dietary Restriction</button>
                        </div>

                        <div class="mt-4">
                            <label for="medicalNotes" class="block text-sm font-medium text-gray-700">Medical Notes</label>
                            <textarea wire:model="medicalNotes" id="medicalNotes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Additional medical notes..."></textarea>
                            @error('medicalNotes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showMedicalModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Save Medical Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
