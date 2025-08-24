<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Home Congregation</h2>
                <p class="text-gray-600 dark:text-gray-400">Manage your family's home congregation</p>
            </div>
            <button wire:click="edit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit Congregation</span>
            </button>
        </div>

        <!-- Current Congregation Display -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Current Home Congregation</h3>
            </div>
            
            <div class="px-6 py-4">
                @if($family->homeCongregation)
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $family->homeCongregation->name }}</h4>
                        </div>
                        
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            @if($family->homeCongregation->address)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $family->homeCongregation->address }}</dd>
                                </div>
                            @endif
                            
                            @if($family->homeCongregation->city || $family->homeCongregation->state || $family->homeCongregation->zip_code)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $family->homeCongregation->city }}{{ $family->homeCongregation->city && $family->homeCongregation->state ? ', ' : '' }}{{ $family->homeCongregation->state }} {{ $family->homeCongregation->zip_code }}
                                    </dd>
                                </div>
                            @endif
                            
                            @if($family->homeCongregation->phone)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <a href="tel:{{ $family->homeCongregation->phone }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ $family->homeCongregation->phone }}</a>
                                    </dd>
                                </div>
                            @endif
                            
                            @if($family->homeCongregation->website)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Website</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <a href="{{ $family->homeCongregation->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ $family->homeCongregation->website }}</a>
                                    </dd>
                                </div>
                            @endif
                            
                            @if($family->homeCongregation->contact_person)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Person</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $family->homeCongregation->contact_person }}</dd>
                                </div>
                            @endif
                            
                            @if($family->homeCongregation->contact_email)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <a href="mailto:{{ $family->homeCongregation->contact_email }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ $family->homeCongregation->contact_email }}</a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No home congregation set</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add your home congregation to get started.</p>
                        <div class="mt-6">
                            <button wire:click="edit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Home Congregation
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-zinc-900 border-gray-300 dark:border-gray-600">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Select Home Congregation</h3>
                    
                    <!-- Search Existing Congregations -->
                    <div class="mb-6">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Existing Congregations</label>
                        <input wire:model.live="search" type="text" id="search" placeholder="Search congregations..." class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                        
                        @if($search && $congregations->count() > 0)
                            <div class="mt-3 max-h-48 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-md">
                                @foreach($congregations as $congregation)
                                    <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0" wire:click="selectCongregation({{ $congregation->id }})">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $congregation->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            @if($congregation->city && $congregation->state)
                                                {{ $congregation->city }}, {{ $congregation->state }}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($search && $congregations->count() === 0)
                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-md">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No congregations found. You can add a new one below.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Selected Congregation -->
                    @if($home_congregation_id)
                        @php $selectedCongregation = $congregations->firstWhere('id', $home_congregation_id) @endphp
                        @if($selectedCongregation)
                            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                                <h4 class="font-medium text-blue-900 dark:text-blue-100">Selected Congregation:</h4>
                                <p class="text-blue-800 dark:text-blue-200">{{ $selectedCongregation->name }}</p>
                                @if($selectedCongregation->city && $selectedCongregation->state)
                                    <p class="text-blue-700 dark:text-blue-300 text-sm">{{ $selectedCongregation->city }}, {{ $selectedCongregation->state }}</p>
                                @endif
                            </div>
                        @endif
                    @endif

                    <!-- Or Add New Congregation -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Or Add New Congregation</h4>
                        <button wire:click="showAddCongregation" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            Add New Congregation
                        </button>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md">
                            Cancel
                        </button>
                        <button wire:click="update" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Update Congregation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Add New Congregation Modal -->
    @if($showAddCongregationModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-zinc-900 border-gray-300 dark:border-gray-600">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add New Congregation</h3>
                    <form wire:submit.prevent="addCongregation">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="new_congregation_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Congregation Name *</label>
                                <input wire:model="new_congregation_name" type="text" id="new_congregation_name" placeholder="e.g., First Baptist Church" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('new_congregation_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="new_congregation_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                                <input wire:model="new_congregation_address" type="text" id="new_congregation_address" placeholder="e.g., 123 Main Street" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('new_congregation_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="new_congregation_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                <input wire:model="new_congregation_city" type="text" id="new_congregation_city" placeholder="e.g., Springfield" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('new_congregation_city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="new_congregation_state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                                <input wire:model="new_congregation_state" type="text" id="new_congregation_state" placeholder="e.g., IL" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('new_congregation_state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="new_congregation_zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ZIP Code</label>
                                <input wire:model="new_congregation_zip_code" type="text" id="new_congregation_zip_code" placeholder="e.g., 62701" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('new_congregation_zip_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="new_congregation_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                <input wire:model="new_congregation_phone" type="tel" id="new_congregation_phone" placeholder="e.g., (555) 123-4567" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('new_congregation_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="new_congregation_website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                                <input wire:model="new_congregation_website" type="url" id="new_congregation_website" placeholder="e.g., https://example.org" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('new_congregation_website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="new_congregation_contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Person</label>
                                <input wire:model="new_congregation_contact_person" type="text" id="new_congregation_contact_person" placeholder="e.g., Pastor John Smith" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('new_congregation_contact_person') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="new_congregation_contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Email</label>
                                <input wire:model="new_congregation_contact_email" type="email" id="new_congregation_contact_email" placeholder="e.g., pastor@example.org" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('new_congregation_contact_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md">
                                Cancel
                            </button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Add Congregation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @script
    <script>
        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            Livewire.on('congregation-updated', () => {
                console.log('Congregation information updated successfully');
            });
            
            Livewire.on('congregation-added', () => {
                console.log('New congregation added successfully');
            });
        });
    </script>
    @endscript
</div>
