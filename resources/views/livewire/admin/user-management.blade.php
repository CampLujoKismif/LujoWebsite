<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">User Management</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage system users, roles, and camp assignments</p>
            </div>
            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add User
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                        <input type="text" wire:model.live="searchTerm" id="search" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" placeholder="Search by name or email...">
                    </div>

                    <!-- Role Filter -->
                    <div>
                        <label for="role-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                        <select wire:model.live="roleFilter" id="role-filter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User Status</label>
                        <select wire:model.live="statusFilter" id="status-filter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                            <option value="all">All Users</option>
                            <option value="active">Active Users</option>
                            <option value="deleted">Deleted Users</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-zinc-900 shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Users ({{ $users->total() }})</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Camp Assignments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr($user->name, 0, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if($user->campAssignments->count() > 0)
                                            @foreach($user->campAssignments as $assignment)
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-xs font-medium">{{ $assignment->camp->display_name }}</span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                                        {{ $assignment->role->display_name ?? 'No Role' }}
                                                    </span>
                                                    @if($assignment->is_primary)
                                                        <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Primary</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-xs">No assignments</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->trashed())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                            Deleted
                                        </span>
                                    @elseif($user->email_verified_at)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                            Unverified
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if(!$user->trashed())
                                        <button wire:click="openEditModal({{ $user->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">Edit</button>
                                        @if($user->id !== auth()->id())
                                            <button wire:click="openDeleteModal({{ $user->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 mr-3">Delete</button>
                                        @endif
                                    @else
                                        @if($user->id !== auth()->id())
                                            <button wire:click="restoreUser({{ $user->id }})" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 mr-3">Restore</button>
                                            <button wire:click="openHardDeleteModal({{ $user->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Permanently Delete</button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-zinc-800">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Create New User</h3>
                    
                    <form wire:submit.prevent="createUser">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" wire:model="name" id="name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" wire:model="email" id="email" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                <input type="password" wire:model="password" id="password" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                                <input type="password" wire:model="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Roles</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($roles as $role)
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:bg-zinc-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($role->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('selectedRoles') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="emailVerified" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:bg-zinc-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Email Verified</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Camp Assignments</label>
                            <div class="max-h-48 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-md p-2">
                                @foreach($camps as $camp)
                                    <div class="flex items-center gap-2 mb-2">
                                        <input type="checkbox" wire:model.live="selectedCamps" value="{{ $camp->id }}" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:bg-zinc-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="text-sm text-gray-700 dark:text-gray-300 w-32">{{ $camp->display_name }}</span>
                                        
                                        @if(in_array($camp->id, $selectedCamps))
                                            <select wire:model="campRoles.{{ $camp->id }}" class="ml-2 text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white flex-1">
                                                <option value="">Select Role</option>
                                                @foreach($availableCampRoles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                                @endforeach
                                            </select>
                                            
                                            <label class="flex items-center ml-2">
                                                <input type="checkbox" wire:model="primaryCamps" value="{{ $camp->id }}" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:bg-zinc-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-1 text-xs text-gray-600 dark:text-gray-400">Primary</span>
                                            </label>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @error('campRoles') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit User Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-zinc-800">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit User: {{ $selectedUser->name ?? '' }}</h3>
                    
                    <form wire:submit.prevent="updateUser">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="edit-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" wire:model="name" id="edit-name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="edit-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" wire:model="email" id="edit-email" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm" required>
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="edit-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password (leave blank to keep current)</label>
                                <input type="password" wire:model="password" id="edit-password" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="edit-password-confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                                <input type="password" wire:model="password_confirmation" id="edit-password-confirmation" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Roles</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($roles as $role)
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:bg-zinc-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($role->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('selectedRoles') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="emailVerified" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:bg-zinc-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Email Verified</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Camp Assignments</label>
                            <div class="max-h-48 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-md p-2">
                                @foreach($camps as $camp)
                                    <div class="flex items-center gap-2 mb-2">
                                        <input type="checkbox" wire:model.live="selectedCamps" value="{{ $camp->id }}" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:bg-zinc-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="text-sm text-gray-700 dark:text-gray-300 w-32">{{ $camp->display_name }}</span>
                                        
                                        @if(in_array($camp->id, $selectedCamps))
                                            <select wire:model="campRoles.{{ $camp->id }}" class="ml-2 text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white flex-1">
                                                <option value="">Select Role</option>
                                                @foreach($availableCampRoles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                                @endforeach
                                            </select>
                                            
                                            <label class="flex items-center ml-2">
                                                <input type="checkbox" wire:model="primaryCamps" value="{{ $camp->id }}" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:bg-zinc-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-1 text-xs text-gray-600 dark:text-gray-400">Primary</span>
                                            </label>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @error('campRoles') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete User Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-800">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Delete User</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Are you sure you want to soft delete <strong>{{ $selectedUser->name ?? '' }}</strong>? This user will be moved to the deleted users list and can be restored later.
                    </p>
                    
                    <div class="flex justify-center gap-3">
                        <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                            Cancel
                        </button>
                        <button wire:click="deleteUser" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Hard Delete User Modal -->
    @if($showHardDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-800">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Permanently Delete User</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Are you sure you want to <strong class="text-red-600 dark:text-red-400">permanently delete</strong> <strong>{{ $selectedUser->name ?? '' }}</strong>? This action <strong class="text-red-600 dark:text-red-400">cannot be undone</strong> and all user data will be permanently removed from the system.
                    </p>
                    
                    <div class="flex justify-center gap-3">
                        <button wire:click="$set('showHardDeleteModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                            Cancel
                        </button>
                        <button wire:click="hardDeleteUser" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            Permanently Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
