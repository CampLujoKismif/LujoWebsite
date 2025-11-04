<x-layouts.app :title="__('Edit User')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Edit User</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Update user information and permissions</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                ← Back to Users
            </a>
        </div>

        <!-- Edit User Form -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Basic Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Full Name *
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Email Address *
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    New Password (leave blank to keep current)
                                </label>
                                <input type="password" id="password" name="password"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Confirm New Password
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Roles -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Roles</h2>
                        
                        <div class="space-y-3">
                            @foreach($roles as $role)
                                <label class="flex items-center">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                        {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-neutral-700 dark:text-neutral-300">
                                        <span class="font-medium">{{ $role->display_name }}</span>
                                        <span class="text-neutral-500 dark:text-neutral-400"> - {{ $role->description }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Camp Assignments -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Camp Assignments</h2>
                        
                        <div class="space-y-4">
                            @foreach($camps as $camp)
                                @php
                                    $isAssigned = $user->assignedCamps->contains($camp->id);
                                    $isPrimary = $isAssigned && $user->assignedCamps->find($camp->id)->pivot->is_primary;
                                @endphp
                                <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="camp_assignments[]" value="{{ $camp->id }}" 
                                            {{ in_array($camp->id, old('camp_assignments', $user->assignedCamps->pluck('id')->toArray())) ? 'checked' : '' }}
                                            class="rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <span class="font-medium text-neutral-900 dark:text-white">{{ $camp->display_name }}</span>
                                            <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $camp->description }}</p>
                                        </div>
                                    </label>
                                    
                                    <div class="mt-3 ml-6">
                                        <label class="flex items-center">
                                            <input type="radio" name="primary_camp_{{ $camp->id }}" value="1" 
                                                {{ old('primary_camp_' . $camp->id, $isPrimary ? '1' : '') == '1' ? 'checked' : '' }}
                                                class="rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">Set as primary camp</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('camp_assignments')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Verification -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Email Verification</h2>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="email_verified" value="1" 
                                {{ old('email_verified', $user->email_verified_at ? '1' : '') ? 'checked' : '' }}
                                class="rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-neutral-700 dark:text-neutral-300">
                                Mark email as verified
                            </span>
                        </label>
                        
                        @if($user->email_verified_at)
                            <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                Email was verified on {{ $user->email_verified_at->format('M j, Y \a\t g:i A') }}
                            </p>
                        @endif
                    </div>

                    <!-- User Status -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">User Status</h2>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg">
                                <div>
                                    <span class="font-medium text-neutral-900 dark:text-white">Account Status</span>
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                        @if($user->trashed())
                                            Deleted on {{ $user->deleted_at->format('M j, Y') }}
                                        @else
                                            Active
                                        @endif
                                    </p>
                                </div>
                                @if($user->trashed())
                                    <form action="{{ route('admin.users.restore', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded">
                                            Restore
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg">
                                <div>
                                    <span class="font-medium text-neutral-900 dark:text-white">Account Status</span>
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                        @if($user->trashed())
                                            Deleted on {{ $user->deleted_at->format('M j, Y') }}
                                        @else
                                            Active
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                        <a href="{{ route('admin.users.index') }}" 
                            class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app> 