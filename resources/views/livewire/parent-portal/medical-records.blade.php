<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Medical Records</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage medical information for your campers</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Camper List -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Select Camper</h3>
                        @if($campers->isEmpty())
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No campers found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add campers to your family to manage their medical records.</p>
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach($campers as $camper)
                                    <button 
                                        wire:click="selectCamper({{ $camper->id }})"
                                        class="w-full text-left p-3 rounded-lg border {{ $selectedCamper && $selectedCamper->id === $camper->id ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-zinc-800' }}"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $camper->full_name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $camper->family->name }}</p>
                                            </div>
                                            @if($camper->hasMedicalAlerts())
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                                    Medical Alert
                                                </span>
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Medical Record Details -->
            <div class="lg:col-span-2">
                @if($selectedCamper)
                    <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ $selectedCamper->full_name }} - Medical Record</h3>
                                <a href="{{ route('dashboard.parent.campers') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">Edit Medical Info</a>
                            </div>

                            @if($medicalRecord)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Basic Medical Info -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Basic Information</h4>
                                        <div class="space-y-3">
                                            @if($medicalRecord->physician_name)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Physician:</span>
                                                    <p class="text-sm text-gray-900 dark:text-white">{{ $medicalRecord->physician_name }}</p>
                                                    @if($medicalRecord->physician_phone)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $medicalRecord->physician_phone }}</p>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($medicalRecord->insurance_provider)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Insurance:</span>
                                                    <p class="text-sm text-gray-900 dark:text-white">{{ $medicalRecord->insurance_provider }}</p>
                                                    @if($medicalRecord->policy_number)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">Policy: {{ $medicalRecord->policy_number }}</p>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($medicalRecord->emergency_contact_name)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Emergency Contact:</span>
                                                    <p class="text-sm text-gray-900 dark:text-white">{{ $medicalRecord->emergency_contact_name }}</p>
                                                    @if($medicalRecord->emergency_contact_phone)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $medicalRecord->emergency_contact_phone }}</p>
                                                    @endif
                                                    @if($medicalRecord->emergency_contact_relationship)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $medicalRecord->emergency_contact_relationship }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Medical Conditions -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Medical Information</h4>
                                        <div class="space-y-3">
                                            @if($medicalRecord->allergies)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Allergies:</span>
                                                    <div class="mt-1">
                                                        @foreach(json_decode($medicalRecord->allergies, true) ?: [] as $allergy)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 mr-1 mb-1">
                                                                {{ $allergy }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if($medicalRecord->medical_conditions)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Medical Conditions:</span>
                                                    <div class="mt-1">
                                                        @foreach(json_decode($medicalRecord->medical_conditions, true) ?: [] as $condition)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 mr-1 mb-1">
                                                                {{ $condition }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if($medicalRecord->medications)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Medications:</span>
                                                    <div class="mt-1">
                                                        @foreach(json_decode($medicalRecord->medications, true) ?: [] as $medication)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-1 mb-1">
                                                                {{ $medication }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if($medicalRecord->dietary_restrictions)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Dietary Restrictions:</span>
                                                    <div class="mt-1">
                                                        @foreach(json_decode($medicalRecord->dietary_restrictions, true) ?: [] as $restriction)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 mr-1 mb-1">
                                                                {{ $restriction }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($medicalRecord->notes)
                                    <div class="mt-6">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Additional Notes</h4>
                                        <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-3">
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $medicalRecord->notes }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                                    Last updated: {{ $medicalRecord->updated_at ? $medicalRecord->updated_at->format('M j, Y g:i A') : 'Never' }}
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No medical record found</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This camper doesn't have a medical record yet.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('dashboard.parent.campers') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">Add Medical Information</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Select a camper</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Choose a camper from the list to view their medical record.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
