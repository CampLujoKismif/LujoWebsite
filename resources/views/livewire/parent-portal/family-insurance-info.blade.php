<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Insurance Information</h2>
                <p class="text-gray-600">Manage your family's insurance details</p>
            </div>
            <button wire:click="edit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit Insurance</span>
            </button>
        </div>

        <!-- Insurance Information Display -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Current Insurance Details</h3>
            </div>
            
            <div class="px-6 py-4">
                @if($family->insurance_provider || $family->insurance_policy_number || $family->insurance_group_number || $family->insurance_phone)
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Insurance Provider</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $family->insurance_provider ?: 'Not provided' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Policy Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $family->insurance_policy_number ?: 'Not provided' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Group Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $family->insurance_group_number ?: 'Not provided' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Insurance Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($family->insurance_phone)
                                    <a href="tel:{{ $family->insurance_phone }}" class="text-blue-600 hover:text-blue-800">{{ $family->insurance_phone }}</a>
                                @else
                                    Not provided
                                @endif
                            </dd>
                        </div>
                    </dl>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No insurance information</h3>
                        <p class="mt-1 text-sm text-gray-500">Add your insurance details to get started.</p>
                        <div class="mt-6">
                            <button wire:click="edit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Insurance Information
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
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Insurance Information</h3>
                    <form wire:submit.prevent="update">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="insurance_provider" class="block text-sm font-medium text-gray-700">Insurance Provider</label>
                                <input wire:model="insurance_provider" type="text" id="insurance_provider" placeholder="e.g., Blue Cross Blue Shield" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('insurance_provider') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700">Policy Number</label>
                                <input wire:model="insurance_policy_number" type="text" id="insurance_policy_number" placeholder="e.g., 123456789" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('insurance_policy_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="insurance_group_number" class="block text-sm font-medium text-gray-700">Group Number</label>
                                <input wire:model="insurance_group_number" type="text" id="insurance_group_number" placeholder="e.g., GRP123456" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('insurance_group_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="insurance_phone" class="block text-sm font-medium text-gray-700">Insurance Phone</label>
                                <input wire:model="insurance_phone" type="tel" id="insurance_phone" placeholder="e.g., (555) 123-4567" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('insurance_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                Update Insurance
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
            Livewire.on('insurance-updated', () => {
                // Show success message
                console.log('Insurance information updated successfully');
            });
        });
    </script>
    @endscript
</div>
