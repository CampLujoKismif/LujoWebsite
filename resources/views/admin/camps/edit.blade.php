<x-layouts.app :title="__('Edit Camp')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Edit Camp</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Update camp information</p>
            </div>
            <a href="{{ route('admin.camps.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                ← Back to Camps
            </a>
        </div>

        <!-- Edit Form -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <form action="{{ route('admin.camps.update', $camp) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Basic Information</h3>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Camp Identifier *
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $camp->name) }}" required
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., super_week_2024">
                                @error('name')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="display_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Display Name *
                                </label>
                                <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $camp->display_name) }}" required
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., Super Week 2024">
                                @error('display_name')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Camp description...">{{ old('description', $camp->description) }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dates and Settings -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-neutral-900 dark:text-white">Dates & Settings</h3>
                            <div>
                                <label for="price" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Price ($)
                                </label>
                                <input type="number" name="price" id="price" value="{{ old('price', $camp->price) }}" min="0" step="0.01"
                                    class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 350.00">
                                @error('price')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $camp->is_active) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-neutral-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-neutral-700 dark:text-neutral-300">
                                    Active Camp
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-between items-center">
                        <a href="{{ route('admin.camps.session-template', $camp) }}" class="text-purple-600 dark:text-purple-400 hover:underline text-sm font-medium">
                            Manage Session Detail Template →
                        </a>
                        <div class="flex space-x-4">
                            <a href="{{ route('admin.camps.index') }}" class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                                Update Camp
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app> 