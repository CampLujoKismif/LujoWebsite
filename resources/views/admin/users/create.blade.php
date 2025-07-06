<x-layouts.app :title="__('Create User')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Create New User</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Add a new user to the system</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                ← Back to Users
            </a>
        </div>

        <!-- Create User Form -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Basic Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Full Name *
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Email Address *
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Password *
                                </label>
                                <input type="password" id="password" name="password" required
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Confirm Password *
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
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
                                        {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
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
                                <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="camp_assignments[]" value="{{ $camp->id }}" 
                                            {{ in_array($camp->id, old('camp_assignments', [])) ? 'checked' : '' }}
                                            class="rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <span class="font-medium text-neutral-900 dark:text-white">{{ $camp->display_name }}</span>
                                            <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $camp->description }}</p>
                                        </div>
                                    </label>
                                    
                                    <div class="mt-3 ml-6 space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                                Role for this camp
                                            </label>
                                            <select name="camp_role_{{ $camp->id }}" 
                                                class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select a role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" {{ old('camp_role_' . $camp->id) == $role->id ? 'selected' : '' }}>
                                                        {{ $role->display_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <label class="flex items-center">
                                            <input type="radio" name="primary_camp_{{ $camp->id }}" value="1" 
                                                {{ old('primary_camp_' . $camp->id) == '1' ? 'checked' : '' }}
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
                                {{ old('email_verified') ? 'checked' : '' }}
                                class="rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-neutral-700 dark:text-neutral-300">
                                Mark email as verified (user won't need to verify their email)
                            </span>
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                        <a href="{{ route('admin.users.index') }}" 
                            class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app> 