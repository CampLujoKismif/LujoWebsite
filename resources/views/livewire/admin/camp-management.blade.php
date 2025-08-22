<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Camp Management</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage camps and their sessions</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="openSessionModal" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Add Session
                </button>
                <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Camp
                </button>
            </div>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                        <input type="text" wire:model.live="searchTerm" id="search" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="Search camps...">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model.live="statusFilter" id="status-filter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                            <option value="">All Camps</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Camps List -->
        <div class="space-y-6">
            @forelse($camps as $camp)
                <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $camp->display_name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $camp->name }}</p>
                                @if($camp->description)
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $camp->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if($camp->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                        Inactive
                                    </span>
                                @endif
                                @if($camp->price)
                                    <span class="text-sm text-gray-600 dark:text-gray-400">${{ number_format($camp->price, 2) }}</span>
                                @endif
                                <div class="flex gap-2">
                                    <button wire:click="openEditModal({{ $camp->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm">Edit</button>
                                    <button wire:click="openSessionModal({{ $camp->id }})" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 text-sm">Add Session</button>
                                    @if($camp->instances->where('is_active', true)->count() === 0)
                                        <button wire:click="openDeleteModal({{ $camp->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm">Delete</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Sessions -->
                        @if($camp->instances->count() > 0)
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Sessions</h4>
                                <div class="space-y-2">
                                    @foreach($camp->instances as $instance)
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $instance->name }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $instance->start_date->format('M j, Y') }} - {{ $instance->end_date->format('M j, Y') }}
                                                    @if($instance->capacity)
                                                        • Capacity: {{ $instance->capacity }}
                                                    @endif
                                                    @if($instance->price)
                                                        • ${{ number_format($instance->price, 2) }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if($instance->is_active)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mt-4 text-center py-4 text-gray-500">
                                <p class="text-sm">No sessions created yet.</p>
                                <button wire:click="openSessionModal({{ $camp->id }})" class="mt-2 text-indigo-600 hover:text-indigo-900 text-sm">
                                    Add first session
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No camps found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new camp.</p>
                        <div class="mt-6">
                            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Camp
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $camps->links() }}
        </div>
    </div>

    <!-- Create Camp Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Camp</h3>
                    
                    <form wire:submit.prevent="createCamp">
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Camp Name (Internal)</label>
                                <input type="text" wire:model="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="displayName" class="block text-sm font-medium text-gray-700">Display Name</label>
                                <input type="text" wire:model="displayName" id="displayName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('displayName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Default Price</label>
                                <input type="number" wire:model="price" id="price" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Create Camp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Camp Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Camp: {{ $selectedCamp->display_name ?? '' }}</h3>
                    
                    <form wire:submit.prevent="updateCamp">
                        <div class="space-y-4">
                            <div>
                                <label for="edit-name" class="block text-sm font-medium text-gray-700">Camp Name (Internal)</label>
                                <input type="text" wire:model="name" id="edit-name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="edit-displayName" class="block text-sm font-medium text-gray-700">Display Name</label>
                                <input type="text" wire:model="displayName" id="edit-displayName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('displayName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="edit-description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="description" id="edit-description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="edit-price" class="block text-sm font-medium text-gray-700">Default Price</label>
                                <input type="number" wire:model="price" id="edit-price" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Update Camp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Create Session Modal -->
    @if($showSessionModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Create New Session
                        @if($selectedCamp)
                            for {{ $selectedCamp->display_name }}
                        @endif
                    </h3>
                    
                    <form wire:submit.prevent="createSession">
                        <div class="space-y-4">
                            <div>
                                <label for="sessionName" class="block text-sm font-medium text-gray-700">Session Name</label>
                                <input type="text" wire:model="sessionName" id="sessionName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('sessionName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="sessionStartDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" wire:model="sessionStartDate" id="sessionStartDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    @error('sessionStartDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="sessionEndDate" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" wire:model="sessionEndDate" id="sessionEndDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    @error('sessionEndDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="sessionCapacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                                    <input type="number" wire:model="sessionCapacity" id="sessionCapacity" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    @error('sessionCapacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="sessionPrice" class="block text-sm font-medium text-gray-700">Price</label>
                                    <input type="number" wire:model="sessionPrice" id="sessionPrice" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('sessionPrice') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="sessionDescription" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="sessionDescription" id="sessionDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                @error('sessionDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="sessionIsActive" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="$set('showSessionModal', false)" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Create Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Camp Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Camp</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Are you sure you want to delete <strong>{{ $selectedCamp->display_name ?? '' }}</strong>? This action cannot be undone.
                    </p>
                    
                    <div class="flex justify-center gap-3">
                        <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </button>
                        <button wire:click="deleteCamp" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            Delete Camp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
