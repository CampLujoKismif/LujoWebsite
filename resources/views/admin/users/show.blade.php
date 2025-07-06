<x-layouts.app :title="__('User Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">User Details</h1>
                <p class="text-neutral-600 dark:text-neutral-400">View user information and permissions</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                    ← Back to Users
                </a>
            </div>
        </div>

        <!-- User Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Basic Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Personal Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Full Name
                                </label>
                                <p class="text-neutral-900 dark:text-white">{{ $user->name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Email Address
                                </label>
                                <p class="text-neutral-900 dark:text-white">{{ $user->email }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Account Created
                                </label>
                                <p class="text-neutral-900 dark:text-white">{{ $user->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Last Updated
                                </label>
                                <p class="text-neutral-900 dark:text-white">{{ $user->updated_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Roles & Permissions</h2>
                        
                        @if($user->roles->count() > 0)
                            <div class="space-y-4">
                                @foreach($user->roles as $role)
                                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="font-medium text-neutral-900 dark:text-white">{{ $role->display_name }}</h3>
                                            <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                                {{ $role->name }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">{{ $role->description }}</p>
                                        
                                        @if($role->permissions->count() > 0)
                                            <div>
                                                <h4 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Permissions:</h4>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($role->permissions as $permission)
                                                        <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">
                                                            {{ $permission->display_name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-neutral-500 dark:text-neutral-400">No roles assigned</p>
                        @endif
                    </div>
                </div>

                <!-- Camp Assignments -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Camp Assignments</h2>
                        
                        @if($user->assignedCamps->count() > 0)
                            <div class="space-y-4">
                                @foreach($user->assignedCamps as $camp)
                                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="font-medium text-neutral-900 dark:text-white">{{ $camp->display_name }}</h3>
                                            <div class="flex items-center gap-2">
                                                @if($camp->pivot->role_id)
                                                    @php
                                                        $role = \App\Models\Role::find($camp->pivot->role_id);
                                                    @endphp
                                                    @if($role)
                                                        <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
                                                            {{ $role->display_name }}
                                                        </span>
                                                    @endif
                                                @endif
                                                @if($camp->pivot->is_primary)
                                                    <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">
                                                        Primary Camp
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">{{ $camp->description }}</p>
                                        <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                            Assigned: {{ $camp->pivot->created_at ? \Carbon\Carbon::parse($camp->pivot->created_at)->format('M j, Y') : 'Unknown' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-neutral-500 dark:text-neutral-400">No camp assignments</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Account Status -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Account Status</h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-700 dark:text-neutral-300">Email Verified</span>
                                @if($user->email_verified_at)
                                    <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                                        Verified
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full">
                                        Pending
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-700 dark:text-neutral-300">Account Status</span>
                                @if($user->trashed())
                                    <span class="px-2 py-1 text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-full">
                                        Deleted
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                                        Active
                                    </span>
                                @endif
                            </div>
                            
                            @if($user->email_verified_at)
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    Verified on {{ $user->email_verified_at->format('M j, Y \a\t g:i A') }}
                                </div>
                            @endif
                            
                            @if($user->trashed())
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    Deleted on {{ $user->deleted_at->format('M j, Y \a\t g:i A') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Quick Actions</h2>
                        
                        <div class="space-y-3">
                            <a href="{{ route('admin.users.edit', $user) }}" 
                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-center block">
                                Edit User
                            </a>
                            
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this user?')"
                                        class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                                        Delete User
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full px-4 py-2 bg-neutral-300 dark:bg-neutral-600 text-neutral-500 dark:text-neutral-400 rounded-lg font-medium cursor-not-allowed">
                                    Cannot Delete Self
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 