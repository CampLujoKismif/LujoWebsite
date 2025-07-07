<x-layouts.app :title="__('Create Camp Instance - ' . $camp->display_name)">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Create Camp Instance</h1>
                <p class="text-neutral-600 dark:text-neutral-400">{{ $camp->display_name }} - {{ now()->year }}</p>
            </div>
            <a href="{{ route('camps.settings', $camp) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                ← Back to Settings
            </a>
        </div>

        <!-- Create Form -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <form action="{{ route('camps.instances.store', $camp) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Basic Information</h2>
                            
                            <div>
                                <label for="year" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Year <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="year" name="year" value="{{ old('year', now()->year) }}" required min="2020" max="2030"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('year') border-red-500 @enderror">
                                @error('year')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Instance Name
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="{{ $camp->display_name }} {{ now()->year }}"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Leave blank to use default name</p>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="3" placeholder="Description for this year's camp session"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="theme_description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Theme Description
                                </label>
                                <textarea id="theme_description" name="theme_description" rows="3" placeholder="Theme and special focus for this year"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('theme_description') border-red-500 @enderror">{{ old('theme_description') }}</textarea>
                                @error('theme_description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dates and Capacity -->
                        <div class="space-y-6">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Dates & Capacity</h2>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Start Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        End Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="max_capacity" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Maximum Capacity
                                    </label>
                                    <input type="number" id="max_capacity" name="max_capacity" value="{{ old('max_capacity') }}" min="1"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_capacity') border-red-500 @enderror">
                                    @error('max_capacity')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="price" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Price ($)
                                    </label>
                                    <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="registration_open_date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Registration Opens
                                    </label>
                                    <input type="date" id="registration_open_date" name="registration_open_date" value="{{ old('registration_open_date') }}"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_open_date') border-red-500 @enderror">
                                    @error('registration_open_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="registration_close_date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Registration Closes
                                    </label>
                                    <input type="date" id="registration_close_date" name="registration_close_date" value="{{ old('registration_close_date') }}"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_close_date') border-red-500 @enderror">
                                    @error('registration_close_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Age & Grade Ranges -->
                    <div class="mt-8 pt-8 border-t border-neutral-200 dark:border-neutral-700">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-6">Age & Grade Ranges</h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Age Range -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Age Range</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="age_from" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                            Age From
                                        </label>
                                        <input type="number" id="age_from" name="age_from" value="{{ old('age_from') }}" min="0" max="21"
                                            class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('age_from') border-red-500 @enderror">
                                        @error('age_from')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="age_to" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                            Age To
                                        </label>
                                        <input type="number" id="age_to" name="age_to" value="{{ old('age_to') }}" min="0" max="21"
                                            class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('age_to') border-red-500 @enderror">
                                        @error('age_to')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Grade Range -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Grade Range</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="grade_from" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                            Grade From
                                        </label>
                                        <select id="grade_from" name="grade_from"
                                            class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('grade_from') border-red-500 @enderror">
                                            <option value="">Select Grade</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ old('grade_from') == $i ? 'selected' : '' }}>
                                                    {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Grade
                                                </option>
                                            @endfor
                                            <option value="13" {{ old('grade_from') == 13 ? 'selected' : '' }}>Graduated Senior</option>
                                        </select>
                                        @error('grade_from')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="grade_to" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                            Grade To
                                        </label>
                                        <select id="grade_to" name="grade_to"
                                            class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('grade_to') border-red-500 @enderror">
                                            <option value="">Select Grade</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ old('grade_to') == $i ? 'selected' : '' }}>
                                                    {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Grade
                                                </option>
                                            @endfor
                                            <option value="13" {{ old('grade_to') == 13 ? 'selected' : '' }}>Graduated Senior</option>
                                        </select>
                                        @error('grade_to')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mt-8 pt-8 border-t border-neutral-200 dark:border-neutral-700">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-neutral-100 border-neutral-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-neutral-800 focus:ring-2 dark:bg-neutral-700 dark:border-neutral-600">
                            <label for="is_active" class="ml-2 text-sm font-medium text-neutral-900 dark:text-white">
                                Active Instance
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Active instances are available for registration and staff assignment</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 pt-8 border-t border-neutral-200 dark:border-neutral-700 flex justify-end space-x-4">
                        <a href="{{ route('camps.settings', $camp) }}" 
                            class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                            Create Instance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app> 