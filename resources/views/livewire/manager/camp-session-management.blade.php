<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Camp Session Management</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage sessions for your assigned camps</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Session
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                        <input type="text" wire:model.live="searchTerm" id="search" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="Search sessions...">
                    </div>

                    <!-- Camp Filter -->
                    <div>
                        <label for="camp-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Camp</label>
                        <select wire:model.live="campFilter" id="camp-filter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                            <option value="">All Camps</option>
                            @foreach($camps as $camp)
                                <option value="{{ $camp->id }}">{{ $camp->display_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model.live="statusFilter" id="status-filter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions List -->
        <div class="space-y-6">
            @forelse($sessions as $session)
                <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $session->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $session->camp->display_name }}</p>
                                @if($session->description)
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $session->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if($session->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                        Inactive
                                    </span>
                                @endif
                                @if($session->price)
                                    <span class="text-sm text-gray-600 dark:text-gray-400">${{ number_format($session->price, 2) }}</span>
                                @endif
                                <div class="flex gap-2 flex-wrap">
                                    <button wire:click="toggleSessionStatus({{ $session->id }})" class="text-xs px-2 py-1 rounded {{ $session->is_active ? 'text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300' : 'text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300' }}">
                                        {{ $session->is_active ? 'Deactivate' : 'Make Active' }}
                                    </button>
                                    <a href="{{ route('camp-sessions.show', $session->id) }}" target="_blank" class="text-sm px-2 py-1 rounded text-purple-600 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300 border border-purple-600 dark:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20">
                                        Preview
                                    </a>
                                    <button wire:click="openDetailsModal({{ $session->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm">Edit</button>
                                    <button wire:click="openDeleteModal({{ $session->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm">Delete</button>
                                </div>
                            </div>
                        </div>

                        <!-- Session Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Start Date:</span>
                                <p class="text-gray-900 dark:text-white">{{ $session->start_date->format('M j, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">End Date:</span>
                                <p class="text-gray-900 dark:text-white">{{ $session->end_date->format('M j, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Capacity:</span>
                                <p class="text-gray-900 dark:text-white">{{ $session->max_capacity ?? 'Not set' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No sessions found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new session.</p>
                        <div class="mt-6">
                            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Session
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $sessions->links() }}
        </div>
    </div>

    <!-- Create Session Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Create New Session</h3>
                    
                    <form wire:submit.prevent="createSession">
                        <div class="space-y-4">
                            <div>
                                <label for="sessionName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Session Name</label>
                                <input type="text" wire:model="sessionName" id="sessionName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                @error('sessionName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="sessionStartDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                                    <input type="date" wire:model="sessionStartDate" id="sessionStartDate" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                    @error('sessionStartDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="sessionEndDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                                    <input type="date" wire:model="sessionEndDate" id="sessionEndDate" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                    @error('sessionEndDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="sessionCapacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capacity</label>
                                    <input type="number" wire:model="sessionCapacity" id="sessionCapacity" min="1" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                    @error('sessionCapacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="sessionPrice" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                                    <input type="number" wire:model="sessionPrice" id="sessionPrice" step="0.01" min="0" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                    @error('sessionPrice') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="sessionDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea wire:model="sessionDescription" id="sessionDescription" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm"></textarea>
                                @error('sessionDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="sessionIsActive" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-white">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
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

    <!-- Delete Session Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Delete Session</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Are you sure you want to delete <strong>{{ $selectedSession->name ?? '' }}</strong>? This action cannot be undone.
                    </p>
                    
                    <div class="flex justify-center gap-3">
                        <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                            Cancel
                        </button>
                        <button wire:click="deleteSession" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            Delete Session
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Details Modal -->
    @if($showDetailsModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white dark:bg-zinc-900 max-h-[90vh] overflow-y-auto">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit Session: {{ $selectedSession->name ?? '' }}</h3>
                    
                    <form 
                        wire:submit.prevent="updateSessionDetails"
                        x-data="{
                            syncQuillToLivewire() {
                                // Sync theme description
                                const themeInput = document.getElementById('manager_theme_description_input');
                                if (themeInput) {
                                    $wire.set('themeDescription', themeInput.value);
                                }
                            }
                        }"
                        @submit="syncQuillToLivewire()"
                    >
                        <div class="space-y-6">
                            <!-- Session Basic Information -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Session Information</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label for="edit-sessionName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Session Name</label>
                                        <input type="text" wire:model="sessionName" id="edit-sessionName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                        @error('sessionName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="edit-sessionStartDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                                            <input type="date" wire:model="sessionStartDate" id="edit-sessionStartDate" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                            @error('sessionStartDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label for="edit-sessionEndDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                                            <input type="date" wire:model="sessionEndDate" id="edit-sessionEndDate" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                            @error('sessionEndDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="edit-sessionCapacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capacity</label>
                                            <input type="number" wire:model="sessionCapacity" id="edit-sessionCapacity" min="1" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                            @error('sessionCapacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label for="edit-sessionPrice" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                                            <input type="number" wire:model="sessionPrice" id="edit-sessionPrice" step="0.01" min="0" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                            @error('sessionPrice') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="edit-sessionDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                        <textarea wire:model="sessionDescription" id="edit-sessionDescription" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm"></textarea>
                                        @error('sessionDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="sessionIsActive" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-white">Active</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                                                        <!-- Theme Photo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Theme Photo
                                </label>
                                
                                <!-- Drag and Drop Upload Zone -->
                                <div 
                                    x-data="{
                                        isDragging: false,
                                        uploading: false,
                                        uploadFile(file) {
                                            this.uploading = true;
                                            const formData = new FormData();
                                            formData.append('image', file);
                                            
                                            const xhr = new XMLHttpRequest();
                                            const csrfToken = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');
                                            
                                            xhr.open('POST', '/admin/session-templates/upload-image');
                                            if (csrfToken) {
                                                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                                            }
                                            
                                            const self = this;
                                            xhr.onload = () => {
                                                self.uploading = false;
                                                if (xhr.status === 200) {
                                                    try {
                                                        const response = JSON.parse(xhr.responseText);
                                                        if (response.success && response.url) {
                                                            $wire.addPhotoUrl(response.url);
                                                        } else {
                                                            alert('Upload failed: ' + (response.message || 'Unknown error'));
                                                        }
                                                    } catch (e) {
                                                        alert('Upload failed: Invalid response');
                                                    }
                                                } else {
                                                    alert('Upload failed: HTTP ' + xhr.status);
                                                }
                                            };
                                            
                                            xhr.onerror = () => {
                                                self.uploading = false;
                                                alert('Upload failed: Network error');
                                            };
                                            
                                            xhr.send(formData);
                                        },
                                        handleDrop(e) {
                                            this.isDragging = false;
                                            e.preventDefault();
                                            const files = Array.from(e.dataTransfer.files);
                                            if (files.length > 0 && files[0].type.startsWith('image/')) {
                                                this.uploadFile(files[0]);
                                            }
                                        },
                                        handleDragOver(e) {
                                            e.preventDefault();
                                            this.isDragging = true;
                                        },
                                        handleDragLeave() {
                                            this.isDragging = false;
                                        },
                                        handleFileSelect(e) {
                                            const files = Array.from(e.target.files);
                                            if (files.length > 0 && files[0].type.startsWith('image/')) {
                                                this.uploadFile(files[0]);
                                            }
                                            e.target.value = '';
                                        }
                                    }"
                                    @drop.prevent="handleDrop($event)"
                                    @dragover.prevent="handleDragOver($event)"
                                    @dragleave.prevent="handleDragLeave()"
                                    :class="isDragging ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-600'"
                                    class="border-2 border-dashed rounded-lg p-8 text-center transition-colors bg-gray-50 dark:bg-zinc-800/50 cursor-pointer hover:border-blue-400 dark:hover:border-blue-600 relative"
                                >
                                    <div x-show="uploading" x-cloak class="absolute inset-0 bg-white dark:bg-zinc-900 bg-opacity-75 dark:bg-opacity-75 flex items-center justify-center rounded-lg z-10">
                                        <div class="text-center">
                                            <svg class="animate-spin h-8 w-8 text-blue-600 dark:text-blue-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Uploading...</p>
                                        </div>
                                    </div>
                                    <input 
                                        type="file" 
                                        accept="image/*" 
                                        @change="handleFileSelect($event)"
                                        class="hidden"
                                        id="photo-upload-input"
                                        wire:ignore
                                    >
                                    <label for="photo-upload-input" class="cursor-pointer">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-semibold text-blue-600 dark:text-blue-400">Click to upload</span>
                                                or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                PNG, JPG, GIF, WEBP up to 5MB
                                            </p>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Uploaded Photo Preview -->
                                @if(!empty($themePhoto))
                                    <div class="mt-4">
                                        <div class="photo-item flex gap-3 items-start p-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-zinc-800">
                                            <div class="flex-shrink-0">
                                                <img src="{{ $themePhoto }}" alt="Theme photo" class="h-20 w-20 object-cover rounded-md">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $themePhoto }}</p>
                                            </div>
                                            <button 
                                                type="button" 
                                                wire:click="removePhotoUrl()" 
                                                class="flex-shrink-0 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 px-3 py-2"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Upload a photo that will appear on the public session detail page.</p>
                                @error('themePhoto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Theme Description -->
                            <div>
                                <label for="themeDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Theme Description
                                </label>
                                <!-- Vue component for HTML editing -->
                                <div 
                                    wire:ignore
                                    wire:key="theme-description-editor-{{ $selectedSession->id ?? 'new' }}"
                                    data-vue-component="EditHTMLPage"
                                    data-props="{{ json_encode([
                                        'modelValue' => $themeDescription ?? '',
                                        'editorId' => 'manager_theme_description_editor',
                                        'inputName' => 'themeDescription',
                                        'inputId' => 'manager_theme_description_input',
                                        'placeholder' => 'Enter the theme description for this camp session...'
                                    ]) }}"
                                ></div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">This appears in the Theme section of the public session detail page. Use the editor to format your content with HTML.</p>
                                @error('themeDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="$set('showDetailsModal', false)" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Update Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
