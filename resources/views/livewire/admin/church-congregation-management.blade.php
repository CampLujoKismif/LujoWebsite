<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Church Congregations</h2>
                <p class="text-gray-600 dark:text-gray-400">Manage church congregations and their information</p>
            </div>
            <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add Congregation</span>
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search" placeholder="Search congregations..." class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select wire:model.live="status" id="status" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Congregations List -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Families</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($congregations as $congregation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $congregation->name }}</div>
                                    @if($congregation->website)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <a href="{{ $congregation->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ $congregation->website }}</a>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $congregation->full_address }}</div>
                                    @if($congregation->phone)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $congregation->phone }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($congregation->contact_person)
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $congregation->contact_person }}</div>
                                    @endif
                                    @if($congregation->contact_email)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $congregation->contact_email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $congregation->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                        {{ $congregation->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $congregation->families_count ?? 0 }} families
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="toggleStatus({{ $congregation->id }})" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="edit({{ $congregation->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $congregation->id }})" onclick="return confirm('Are you sure you want to delete this congregation?')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                                                 @empty
                             <tr>
                                 <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                     No congregations found.
                                 </td>
                             </tr>
                         @endforelse
                    </tbody>
                </table>
            </div>
            
                         <!-- Pagination -->
             <div class="bg-white dark:bg-zinc-900 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                 {{ $congregations->links() }}
             </div>
        </div>
    </div>

         <!-- Create Modal -->
     @if($showCreateModal)
         <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
             <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-zinc-900 border-gray-300 dark:border-gray-600">
                 <div class="mt-3">
                     <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add New Congregation</h3>
                     <form wire:submit.prevent="store">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                             <div class="md:col-span-2">
                                 <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name *</label>
                                 <input wire:model="name" type="text" id="name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div class="md:col-span-2">
                                 <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                                 <textarea wire:model="address" id="address" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"></textarea>
                                 @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                 <input wire:model="city" type="text" id="city" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                                 <input wire:model="state" type="text" id="state" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ZIP Code</label>
                                 <input wire:model="zip_code" type="text" id="zip_code" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('zip_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                 <input wire:model="phone" type="text" id="phone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div class="md:col-span-2">
                                 <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                                 <input wire:model="website" type="url" id="website" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Person</label>
                                 <input wire:model="contact_person" type="text" id="contact_person" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('contact_person') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Email</label>
                                 <input wire:model="contact_email" type="email" id="contact_email" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('contact_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div class="md:col-span-2">
                                 <label class="flex items-center">
                                     <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                     <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                 </label>
                             </div>
                         </div>
                         
                         <div class="mt-6 flex justify-end space-x-3">
                             <button type="button" wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md">
                                 Cancel
                             </button>
                             <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                 Create Congregation
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     @endif

         <!-- Edit Modal -->
     @if($showEditModal)
         <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
             <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-zinc-900 border-gray-300 dark:border-gray-600">
                 <div class="mt-3">
                     <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit Congregation</h3>
                     <form wire:submit.prevent="update">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                             <div class="md:col-span-2">
                                 <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name *</label>
                                 <input wire:model="name" type="text" id="edit_name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div class="md:col-span-2">
                                 <label for="edit_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                                 <textarea wire:model="address" id="edit_address" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"></textarea>
                                 @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="edit_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                 <input wire:model="city" type="text" id="edit_city" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="edit_state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                                 <input wire:model="state" type="text" id="edit_state" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="edit_zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ZIP Code</label>
                                 <input wire:model="zip_code" type="text" id="edit_zip_code" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('zip_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="edit_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                 <input wire:model="phone" type="text" id="edit_phone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div class="md:col-span-2">
                                 <label for="edit_website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                                 <input wire:model="website" type="url" id="edit_website" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="edit_contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Person</label>
                                 <input wire:model="contact_person" type="text" id="edit_contact_person" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('contact_person') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div>
                                 <label for="edit_contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Email</label>
                                 <input wire:model="contact_email" type="email" id="edit_contact_email" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                 @error('contact_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                             </div>
                             
                             <div class="md:col-span-2">
                                 <label class="flex items-center">
                                     <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                     <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                 </label>
                             </div>
                         </div>
                         
                         <div class="mt-6 flex justify-end space-x-3">
                             <button type="button" wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md">
                                 Cancel
                             </button>
                             <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                 Update Congregation
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
            Livewire.on('congregation-created', () => {
                // Show success message
                console.log('Congregation created successfully');
            });
            
            Livewire.on('congregation-updated', () => {
                // Show success message
                console.log('Congregation updated successfully');
            });
            
            Livewire.on('congregation-deleted', () => {
                // Show success message
                console.log('Congregation deleted successfully');
            });
            
            Livewire.on('error', (message) => {
                // Show error message
                console.error('Error:', message);
            });
        });
    </script>
    @endscript
</div>
