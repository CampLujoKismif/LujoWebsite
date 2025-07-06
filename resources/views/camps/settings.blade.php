<x-layouts.app :title="__($camp->display_name . ' Settings')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">{{ $camp->display_name }} Settings</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Manage camp configuration and preferences</p>
            </div>
            <a href="{{ route('camps.dashboard', $camp) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Settings Navigation -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="flex space-x-8 border-b border-neutral-200 dark:border-neutral-700">
                    <button class="px-4 py-2 text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 font-medium">
                        General
                    </button>
                    <button class="px-4 py-2 text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white">
                        Permissions
                    </button>
                    <button class="px-4 py-2 text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white">
                        Notifications
                    </button>
                    <button class="px-4 py-2 text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white">
                        Advanced
                    </button>
                </div>
            </div>
        </div>

        <!-- General Settings -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Camp Information -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Camp Information</h2>
                    
                    <form action="{{ route('admin.camps.update', $camp) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div>
                                <label for="display_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Display Name
                                </label>
                                <input type="text" id="display_name" name="display_name" value="{{ $camp->display_name }}" required
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="3"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $camp->description }}</textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Start Date
                                    </label>
                                    <input type="date" id="start_date" name="start_date" value="{{ $camp->start_date ? $camp->start_date->format('Y-m-d') : '' }}"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        End Date
                                    </label>
                                    <input type="date" id="end_date" name="end_date" value="{{ $camp->end_date ? $camp->end_date->format('Y-m-d') : '' }}"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label for="location" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Location
                                </label>
                                <input type="text" id="location" name="location" value="{{ $camp->location }}"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Capacity
                                </label>
                                <input type="number" id="capacity" name="capacity" value="{{ $camp->capacity }}" min="1"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Age/Grade Settings -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Age & Grade Settings</h2>
                    
                    <form action="{{ route('admin.camps.update', $camp) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="age_from" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Age From
                                    </label>
                                    <input type="number" id="age_from" name="age_from" value="{{ $camp->age_from }}" min="0" max="25"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="age_to" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Age To
                                    </label>
                                    <input type="number" id="age_to" name="age_to" value="{{ $camp->age_to }}" min="0" max="25"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="grade_from" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Grade From
                                    </label>
                                    <select id="grade_from" name="grade_from"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Grade</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $camp->grade_from == $i ? 'selected' : '' }}>
                                                {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Grade
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="grade_to" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Grade To
                                    </label>
                                    <select id="grade_to" name="grade_to"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Grade</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $camp->grade_to == $i ? 'selected' : '' }}>
                                                {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Grade
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Camp Status -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Camp Status</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg">
                        <div>
                            <span class="font-medium text-neutral-900 dark:text-white">Camp Status</span>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Active or Inactive</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-neutral-200 dark:bg-neutral-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg">
                        <div>
                            <span class="font-medium text-neutral-900 dark:text-white">Registration Open</span>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Allow new registrations</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-neutral-200 dark:bg-neutral-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg">
                        <div>
                            <span class="font-medium text-neutral-900 dark:text-white">Public Visibility</span>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Show on public website</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-neutral-200 dark:bg-neutral-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-red-200 dark:border-red-800">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-red-900 dark:text-red-100 mb-4">Danger Zone</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-red-200 dark:border-red-800 rounded-lg">
                        <div>
                            <span class="font-medium text-red-900 dark:text-red-100">Delete Camp</span>
                            <p class="text-sm text-red-700 dark:text-red-300">Permanently delete this camp and all associated data</p>
                        </div>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            Delete Camp
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-red-200 dark:border-red-800 rounded-lg">
                        <div>
                            <span class="font-medium text-red-900 dark:text-red-100">Archive Camp</span>
                            <p class="text-sm text-red-700 dark:text-red-300">Archive this camp (can be restored later)</p>
                        </div>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            Archive Camp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 