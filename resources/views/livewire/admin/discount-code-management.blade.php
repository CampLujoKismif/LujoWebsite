<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Discount Code Management</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage discount codes for rentals and campers</p>
            </div>
            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Discount Code
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                        <input type="text" wire:model.live="searchTerm" id="search" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="Search by code...">
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label for="type-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                        <select wire:model.live="typeFilter" id="type-filter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                            <option value="">All Types</option>
                            <option value="rental">Rental</option>
                            <option value="camper">Camper</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model.live="statusFilter" id="status-filter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Discount Type Filter -->
                    <div>
                        <label for="discount-type-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Type</label>
                        <select wire:model.live="discountTypeFilter" id="discount-type-filter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                            <option value="">All Types</option>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount Codes Table -->
        <div class="bg-white dark:bg-zinc-900 shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Discount Codes ({{ $discountCodes->total() }})</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Usage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valid Until</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($discountCodes as $discount)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $discount->code }}</div>
                                    @if($discount->description)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $discount->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        {{ ucfirst($discount->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($discount->discount_type === 'percentage')
                                        {{ $discount->discount_value }}%
                                    @else
                                        ${{ number_format($discount->discount_value, 2) }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $discount->used_count }}{{ $discount->max_uses ? '/' . $discount->max_uses : '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $discount->valid_until ? $discount->valid_until->format('M j, Y') : 'No expiry' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($discount->is_active && $discount->isValid())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="openEditModal({{ $discount->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                            Edit
                                        </button>
                                        <button wire:click="toggleStatus({{ $discount->id }})" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300">
                                            {{ $discount->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                        <button wire:click="openDeleteModal({{ $discount->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No discount codes found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 sm:px-6 border-t border-gray-200 dark:border-gray-700">
                {{ $discountCodes->links() }}
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="createDiscountCode">
                        <div class="bg-white dark:bg-zinc-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">
                                        Create Discount Code
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="create_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Code</label>
                                            <input type="text" wire:model="code" id="create_code" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="e.g., SUMMER2024">
                                            @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="create_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                                                <select wire:model="type" id="create_type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                    <option value="rental">Rental</option>
                                                    <option value="camper">Camper</option>
                                                </select>
                                                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="create_discount_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Type</label>
                                                <select wire:model="discountType" id="create_discount_type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                    <option value="percentage">Percentage</option>
                                                    <option value="fixed">Fixed Amount</option>
                                                </select>
                                                @error('discountType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div>
                                            <label for="create_discount_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Value</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                @if($discountType === 'percentage')
                                                    <input type="number" wire:model="discountValue" id="create_discount_value" min="0" max="100" class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">%</span>
                                                    </div>
                                                @else
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                                    </div>
                                                    <input type="number" wire:model="discountValue" step="0.01" min="0" id="create_discount_value" class="pl-7 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                @endif
                                            </div>
                                            @error('discountValue') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="create_max_uses" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum Uses (Optional)</label>
                                            <input type="number" wire:model="maxUses" id="create_max_uses" min="1" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="Leave empty for unlimited">
                                            @error('maxUses') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="create_valid_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valid From (Optional)</label>
                                                <input type="date" wire:model="validFrom" id="create_valid_from" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                @error('validFrom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="create_valid_until" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valid Until (Optional)</label>
                                                <input type="date" wire:model="validUntil" id="create_valid_until" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                @error('validUntil') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div>
                                            <label for="create_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optional)</label>
                                            <input type="text" wire:model="description" id="create_description" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="e.g., Summer 2024 Special">
                                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" wire:model="isActive" id="create_is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="create_is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Create Discount Code
                            </button>
                            <button type="button" wire:click="$set('showCreateModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="updateDiscountCode">
                        <div class="bg-white dark:bg-zinc-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">
                                        Edit Discount Code
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="edit_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Code</label>
                                            <input type="text" wire:model="code" id="edit_code" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="e.g., SUMMER2024">
                                            @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="edit_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                                                <select wire:model="type" id="edit_type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                    <option value="rental">Rental</option>
                                                    <option value="camper">Camper</option>
                                                </select>
                                                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="edit_discount_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Type</label>
                                                <select wire:model="discountType" id="edit_discount_type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                    <option value="percentage">Percentage</option>
                                                    <option value="fixed">Fixed Amount</option>
                                                </select>
                                                @error('discountType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div>
                                            <label for="edit_discount_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Value</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                @if($discountType === 'percentage')
                                                    <input type="number" wire:model="discountValue" id="edit_discount_value" min="0" max="100" class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">%</span>
                                                    </div>
                                                @else
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                                    </div>
                                                    <input type="number" wire:model="discountValue" step="0.01" min="0" id="edit_discount_value" class="pl-7 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                @endif
                                            </div>
                                            @error('discountValue') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="edit_max_uses" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum Uses (Optional)</label>
                                            <input type="number" wire:model="maxUses" id="edit_max_uses" min="1" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="Leave empty for unlimited">
                                            @error('maxUses') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="edit_valid_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valid From (Optional)</label>
                                                <input type="date" wire:model="validFrom" id="edit_valid_from" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                @error('validFrom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="edit_valid_until" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valid Until (Optional)</label>
                                                <input type="date" wire:model="validUntil" id="edit_valid_until" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                                @error('validUntil') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div>
                                            <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optional)</label>
                                            <input type="text" wire:model="description" id="edit_description" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="e.g., Summer 2024 Special">
                                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" wire:model="isActive" id="edit_is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="edit_is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Update Discount Code
                            </button>
                            <button type="button" wire:click="$set('showEditModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-zinc-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Delete Discount Code
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete this discount code? This action cannot be undone.
                                    </p>
                                    @if($selectedDiscount)
                                        <div class="mt-4 p-4 bg-gray-50 dark:bg-zinc-800 rounded-md">
                                            <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $selectedDiscount->code }}</p>
                                            @if($selectedDiscount->description)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $selectedDiscount->description }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteDiscountCode" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete Discount Code
                        </button>
                        <button wire:click="$set('showDeleteModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

