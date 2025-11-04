<x-layouts.app :title="__('Session Detail Template: ' . $camp->display_name)">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Session Detail Template</h1>
                <p class="text-neutral-600 dark:text-neutral-400">{{ $camp->display_name }} - Master template for public-facing session detail pages</p>
            </div>
            <a href="{{ route('admin.camps.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                ← Back to Camps
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>How it works:</strong> This template is automatically used when creating new camp sessions. 
                    New sessions will copy their detail page content from the last active session, or from this template if no active session exists.
                </div>
            </div>
        </div>

        <!-- Template Form -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <form action="{{ route('admin.camps.session-template.update', $camp) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Theme Description -->
                        <div>
                            <label for="theme_description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Theme Description
                            </label>
                            <!-- Vue component for HTML editing -->
                            <div 
                                data-vue-component="EditHTMLPage"
                                data-props="{{ json_encode([
                                    'modelValue' => old('theme_description', $template->theme_description ?? ''),
                                    'editorId' => 'theme_description_editor',
                                    'inputName' => 'theme_description',
                                    'inputId' => 'theme_description_input',
                                    'placeholder' => 'Enter the theme description for this camp session...'
                                ]) }}"
                            ></div>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">This appears in the Theme section of the public session detail page. Use the editor to format your content with HTML.</p>
                        </div>

                        <!-- Schedule Data -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Daily Schedule
                            </label>
                            <div id="schedule-items" class="space-y-3">
                                @php
                                    $scheduleItems = [];
                                    if (old('schedule_data')) {
                                        $scheduleData = old('schedule_data');
                                        if (is_array($scheduleData)) {
                                            foreach ($scheduleData as $index => $item) {
                                                if (isset($item['time']) && isset($item['activity'])) {
                                                    $scheduleItems[] = ['time' => $item['time'], 'activity' => $item['activity']];
                                                }
                                            }
                                        }
                                    } elseif ($template->schedule_data && is_array($template->schedule_data)) {
                                        foreach ($template->schedule_data as $time => $activity) {
                                            $scheduleItems[] = ['time' => $time, 'activity' => $activity];
                                        }
                                    }
                                @endphp
                                
                                @if(count($scheduleItems) > 0)
                                    @foreach($scheduleItems as $index => $item)
                                        <div class="schedule-item flex gap-3 items-start">
                                            <input type="time" name="schedule_data[{{ $index }}][time]" value="{{ $item['time'] }}" required
                                                class="flex-1 px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white">
                                            <input type="text" name="schedule_data[{{ $index }}][activity]" value="{{ $item['activity'] }}" placeholder="Activity description" required
                                                class="flex-1 px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white">
                                            <button type="button" onclick="this.closest('.schedule-item').remove()" class="text-red-600 dark:text-red-400 hover:text-red-800 px-3 py-2">
                                                Remove
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" onclick="addScheduleItem()" class="mt-3 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                + Add Schedule Item
                            </button>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Add daily schedule times and activities. This appears in the Daily Schedule section.</p>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <label for="additional_info" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Additional Information
                            </label>
                            <!-- Vue component for HTML editing -->
                            <div 
                                data-vue-component="EditHTMLPage"
                                data-props="{{ json_encode([
                                    'modelValue' => old('additional_info', $template->additional_info ?? ''),
                                    'editorId' => 'additional_info_editor',
                                    'inputName' => 'additional_info',
                                    'inputId' => 'additional_info_input',
                                    'placeholder' => 'Enter any additional information...'
                                ]) }}"
                            ></div>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">This appears in the Additional Information section of the public session detail page. Use the editor to format your content with HTML.</p>
                        </div>

                        <!-- Theme Photos -->
                        <div>
                            <label for="theme_photos" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Theme Photos (URLs)
                            </label>
                            <div id="photo-urls" class="space-y-2">
                                @if(old('theme_photos'))
                                    @foreach(old('theme_photos') as $index => $photo)
                                        <div class="photo-item flex gap-3 items-start">
                                            <input type="url" name="theme_photos[]" value="{{ $photo }}" placeholder="https://example.com/photo.jpg"
                                                class="flex-1 px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white">
                                            <button type="button" onclick="this.closest('.photo-item').remove()" class="text-red-600 dark:text-red-400 hover:text-red-800 px-3 py-2">
                                                Remove
                                            </button>
                                        </div>
                                    @endforeach
                                @elseif($template->theme_photos && is_array($template->theme_photos))
                                    @foreach($template->theme_photos as $photo)
                                        <div class="photo-item flex gap-3 items-start">
                                            <input type="url" name="theme_photos[]" value="{{ $photo }}" placeholder="https://example.com/photo.jpg"
                                                class="flex-1 px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white">
                                            <button type="button" onclick="this.closest('.photo-item').remove()" class="text-red-600 dark:text-red-400 hover:text-red-800 px-3 py-2">
                                                Remove
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" onclick="addPhotoUrl()" class="mt-3 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                + Add Photo URL
                            </button>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Add URLs to theme-related photos. One URL per line.</p>
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Meta Description (SEO)
                            </label>
                            <input type="text" name="meta_description" id="meta_description" value="{{ old('meta_description', $template->meta_description) }}" maxlength="255"
                                class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Brief description for search engines...">
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">SEO meta description for the session detail pages (max 255 characters).</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.camps.index') }}" class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            Save Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            @php
                $scheduleCount = 0;
                if (old('schedule_data') && is_array(old('schedule_data'))) {
                    $scheduleCount = count(array_filter(old('schedule_data'), function($item) {
                        return isset($item['time']) && isset($item['activity']);
                    }));
                } elseif ($template->schedule_data && is_array($template->schedule_data)) {
                    $scheduleCount = count($template->schedule_data);
                }
                
                $photoCount = 0;
                if (old('theme_photos') && is_array(old('theme_photos'))) {
                    $photoCount = count(array_filter(old('theme_photos')));
                } elseif ($template->theme_photos && is_array($template->theme_photos)) {
                    $photoCount = count($template->theme_photos);
                }
            @endphp

            let scheduleItemIndex = {{ $scheduleCount }};
            let photoItemIndex = {{ $photoCount }};

            function addScheduleItem() {
                const container = document.getElementById('schedule-items');
                const item = document.createElement('div');
                item.className = 'schedule-item flex gap-3 items-start';
                item.innerHTML = `
                    <input type="time" name="schedule_data[${scheduleItemIndex}][time]" required
                        class="flex-1 px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white">
                    <input type="text" name="schedule_data[${scheduleItemIndex}][activity]" placeholder="Activity description" required
                        class="flex-1 px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white">
                    <button type="button" onclick="this.closest('.schedule-item').remove()" class="text-red-600 dark:text-red-400 hover:text-red-800 px-3 py-2">
                        Remove
                    </button>
                `;
                container.appendChild(item);
                scheduleItemIndex++;
            }

            function addPhotoUrl() {
                const container = document.getElementById('photo-urls');
                const item = document.createElement('div');
                item.className = 'photo-item flex gap-3 items-start';
                item.innerHTML = `
                    <input type="url" name="theme_photos[]" placeholder="https://example.com/photo.jpg"
                        class="flex-1 px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white">
                    <button type="button" onclick="this.closest('.photo-item').remove()" class="text-red-600 dark:text-red-400 hover:text-red-800 px-3 py-2">
                        Remove
                    </button>
                `;
                container.appendChild(item);
            }
        </script>
    @endpush
</x-layouts.app>

