<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Parent Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Welcome back! Here's an overview of your family's camp activities.</p>
        </div>

        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Campers</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['active_campers'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Confirmed Enrollments</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['confirmed_enrollments'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mt-6 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        @endif

        <!-- Family Information -->
        <div class="mt-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Family Information</h2>
                <button wire:click="openFamilyEditModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Family
                </button>
            </div>
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        @foreach($this->families as $family)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Family Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $family->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $family->phone ?: 'Not provided' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Emergency Contact</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $family->emergency_contact_name ?: 'Not provided' }}
                                    @if($family->emergency_contact_phone)
                                        <br><span class="text-xs text-gray-500">{{ $family->emergency_contact_phone }}</span>
                                    @endif
                                </dd>
                            </div>
                            @if($family->address || $family->city || $family->state)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $family->address ?: '' }}
                                        @if($family->city || $family->state || $family->zip_code)
                                            <br>{{ trim(($family->city ?: '') . ', ' . ($family->state ?: '') . ' ' . ($family->zip_code ?: '')) }}
                                        @endif
                                    </dd>
                                </div>
                            @endif
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>

        <!-- Campers -->
        <div class="mt-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Campers</h2>
                <button wire:click="openCamperModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Camper
                </button>
            </div>
            
            @if($this->allCampers->isEmpty())
                <div class="bg-white dark:bg-zinc-900 shadow rounded-lg p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No campers</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding your first camper.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->allCampers as $camper)
                        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        @if($camper->photo_url)
                                            <img src="{{ $camper->photo_url }}" alt="{{ $camper->full_name }}" class="h-12 w-12 rounded-full object-cover">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-white font-medium">{{ substr($camper->first_name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $camper->full_name }}</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Age: {{ $camper->age }} | Grade: {{ $camper->grade }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    @if($camper->school)
                                        <p class="text-gray-600 dark:text-gray-400"><span class="font-medium">School:</span> {{ $camper->school }}</p>
                                    @endif
                                    @if($camper->t_shirt_size)
                                        <p class="text-gray-600 dark:text-gray-400"><span class="font-medium">T-Shirt Size:</span> {{ $camper->t_shirt_size }}</p>
                                    @endif
                                    @if($camper->allergies || $camper->medical_conditions || $camper->medications)
                                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                            @if($camper->allergies)
                                                <p class="text-xs text-red-600 dark:text-red-400"><span class="font-medium">Allergies:</span> {{ $camper->allergies }}</p>
                                            @endif
                                            @if($camper->medical_conditions)
                                                <p class="text-xs text-red-600 dark:text-red-400"><span class="font-medium">Medical:</span> {{ $camper->medical_conditions }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4 flex space-x-2">
                                    <button wire:click="openCamperModal({{ $camper->id }})" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                        Edit
                                    </button>
                                    <button wire:click="deleteCamper({{ $camper->id }})" wire:confirm="Are you sure you want to delete this camper?" class="inline-flex items-center px-3 py-2 border border-red-300 dark:border-red-600 text-sm font-medium rounded-md text-red-700 dark:text-red-400 bg-white dark:bg-zinc-800 hover:bg-red-50 dark:hover:bg-red-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('dashboard.parent.payments') }}" class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Process Payments</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Make payments for enrollments</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        @if($this->recentActivity->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Activity</h2>
                <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($this->recentActivity as $activity)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-zinc-900">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $activity->description }}</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                        <time datetime="{{ $activity->created_at->format('Y-m-d') }}">{{ $activity->created_at->diffForHumans() }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Upcoming Sessions -->
        @if($this->upcomingSessions->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Upcoming Sessions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->upcomingSessions as $session)
                        <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $session->camp->display_name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $session->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $session->start_date->format('M j, Y') }} - {{ $session->end_date->format('M j, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Confirmed
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Family Edit Modal -->
        @if($showFamilyEditModal)
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="family-modal">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-zinc-800">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Family Information</h3>
                        <button wire:click="closeFamilyEditModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="saveFamily">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Family Name *</label>
                                <input type="text" wire:model="familyName" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                @error('familyName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                <input type="text" wire:model="phone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                                <input type="text" wire:model="address" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                    <input type="text" wire:model="city" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                                    <input type="text" wire:model="state" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Zip Code</label>
                                    <input type="text" wire:model="zipCode" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Contact Name</label>
                                <input type="text" wire:model="emergencyContactName" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Contact Phone</label>
                                    <input type="text" wire:model="emergencyContactPhone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Relationship</label>
                                    <input type="text" wire:model="emergencyContactRelationship" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="closeFamilyEditModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-600">Cancel</button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Camper Add/Edit Modal -->
        @if($showCamperModal)
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="camper-modal">
                <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white dark:bg-zinc-800 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $editingCamperId ? 'Edit Camper' : 'Add New Camper' }}</h3>
                        <button wire:click="closeCamperModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="saveCamper">
                        <div class="space-y-4">
                            @if($camperPhotoPreview)
                                <div class="flex justify-center">
                                    <img src="{{ $camperPhotoPreview }}" alt="Photo preview" class="h-24 w-24 rounded-full object-cover">
                                </div>
                            @endif
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name *</label>
                                    <input type="text" wire:model="camperFirstName" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                    @error('camperFirstName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name *</label>
                                    <input type="text" wire:model="camperLastName" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                    @error('camperLastName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth *</label>
                                    <input type="date" wire:model="camperDateOfBirth" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                    @error('camperDateOfBirth') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biological Gender</label>
                                    <select wire:model="camperBiologicalGender" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                        <option value="">Select...</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grade *</label>
                                    <input type="number" wire:model="camperGrade" min="1" max="12" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                    @error('camperGrade') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">T-Shirt Size</label>
                                    <input type="text" wire:model="camperTShirtSize" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">School</label>
                                    <input type="text" wire:model="camperSchool" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                    <input type="text" wire:model="camperPhone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <input type="email" wire:model="camperEmail" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white">
                                    @error('camperEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                                <input type="file" wire:model="camperPhoto" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-zinc-700 dark:file:text-blue-300">
                                @error('camperPhoto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Allergies</label>
                                <textarea wire:model="camperAllergies" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medical Conditions</label>
                                <textarea wire:model="camperMedicalConditions" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medications</label>
                                <textarea wire:model="camperMedications" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:text-white"></textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="closeCamperModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-600">Cancel</button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>