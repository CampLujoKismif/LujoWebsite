<x-layouts.public>
    <x-slot name="title">
        {{ $instance->camp->display_name }} - Camp LUJO-KISMIF
    </x-slot>

    <div class="min-h-screen mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-20" style="padding-top: 164px;">
        <!-- Hero Card -->
        <div class="relative bg-white dark:bg-zinc-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 p-8 sm:p-12 mb-12">
            <div class="absolute top-0 left-1/2 -translate-x-1/2">
                <span class="inline-block rounded-full bg-blue-600 px-4 py-1 text-xs font-semibold text-white shadow-lg tracking-wide uppercase">
                    @if($instance->isRegistrationOpen()) Registration Open @else Registration Closed @endif
                </span>
            </div>
            
            <!-- Theme Photo(s) -->
            @if($instance->theme_photos && is_array($instance->theme_photos) && count($instance->theme_photos) > 0)
                <div class="mb-6">
                    @if(count($instance->theme_photos) === 1)
                        <!-- Single photo -->
                        <img src="{{ $instance->theme_photos[0] }}" alt="{{ $instance->camp->display_name }}" class="w-full h-64 sm:h-80 object-cover rounded-xl shadow-lg">
                    @else
                        <!-- Multiple photos - show first one, could add gallery later -->
                        <div class="relative">
                            <img src="{{ $instance->theme_photos[0] }}" alt="{{ $instance->camp->display_name }}" class="w-full h-64 sm:h-80 object-cover rounded-xl shadow-lg">
                            <div class="absolute bottom-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
                                {{ count($instance->theme_photos) }} photos
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white text-center mb-2">{{ $instance->camp->display_name }}</h1>
            <div class="flex flex-col sm:flex-row justify-center gap-6 mt-6 mb-2">
                <div class="flex-1 text-center">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Age/Grade</div>
                    <div class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                        @if($instance->grade_from && $instance->grade_to)
                            Grade {{ $instance->grade_from }}@if($instance->grade_from != $instance->grade_to)–{{ $instance->grade_to }}@endif
                        @elseif($instance->age_from && $instance->age_to)
                            Ages {{ $instance->age_from }}–{{ $instance->age_to }}
                        @endif
                    </div>
                </div>
                <div class="hidden sm:block w-px bg-gray-200 dark:bg-gray-700"></div>
                <div class="flex-1 text-center">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dates</div>
                    <div class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                        {{ $instance->start_date ? $instance->start_date->format('M j') : '' }}@if($instance->end_date) – {{ $instance->end_date->format('M j, Y') }}@endif
                    </div>
                </div>
                <div class="hidden sm:block w-px bg-gray-200 dark:bg-gray-700"></div>
                <div class="flex-1 text-center">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fee</div>
                    <div class="text-lg font-semibold text-gray-800 dark:text-white mt-1">
                        @if($instance->price)
                            ${{ number_format($instance->price, 2) }}
                        @else
                            Free
                        @endif
                    </div>
                    @if($instance->max_capacity)
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $instance->available_spots }} spots left</div>
                    @endif
                </div>
            </div>
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                @if($instance->isRegistrationOpen())
                    <a href="#" class="inline-block rounded-full bg-blue-600 px-8 py-3 text-base font-semibold text-white shadow hover:bg-blue-700 transition">Register Now</a>
                @endif
                <a href="{{ route('home') }}#faq" class="inline-block rounded-full border border-blue-600 px-8 py-3 text-base font-semibold text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">Learn More</a>
            </div>
        </div>

        <!-- Theme Description Content -->
        @if($instance->theme_description)
            <div class="mb-12 text-gray-700 dark:text-gray-300 leading-relaxed editor-content">
                {!! $instance->theme_description !!}
            </div>
        @endif

        <!-- Schedule Section -->
        @if($instance->schedule_data && is_array($instance->schedule_data))
            <div class="mb-12">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span> Daily Schedule
                </h2>
                <ol class="relative border-l-2 border-blue-100 ml-3">
                    @foreach($instance->schedule_data as $time => $activity)
                        <li class="mb-8 ml-6">
                            <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-blue-50 rounded-full ring-2 ring-blue-200">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            </span>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <time class="text-sm font-semibold text-blue-700 min-w-[70px]">{{ $time }}</time>
                                <span class="text-gray-700 dark:text-gray-300 text-base">{{ $activity }}</span>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif

        <!-- Additional Info Content -->
        @if($instance->additional_info_content)
            <div class="mb-12 text-gray-700 dark:text-gray-300 leading-relaxed editor-content">
                {!! $instance->additional_info_content !!}
            </div>
        @endif

        <!-- CTA Section -->
        @if($instance->isRegistrationOpen())
            <div class="mt-16 bg-gradient-to-r from-blue-600 to-blue-400 rounded-2xl shadow-lg p-10 text-center">
                <h2 class="text-2xl font-extrabold text-white mb-2">Ready to Join?</h2>
                <p class="text-lg text-blue-100 mb-6">Don't miss out on this amazing opportunity for spiritual growth, fun activities, and lifelong friendships.</p>
                <a href="#" class="inline-block rounded-full bg-white px-8 py-3 text-base font-semibold text-blue-700 shadow hover:bg-blue-50 transition">Register Now</a>
            </div>
        @endif
    </div>

    @push('head')
        <style>
            /* Editor content styling for HTML rendering (CKEditor/Quill) */
            .editor-content {
                line-height: 1.75;
            }
            .editor-content p {
                margin-bottom: 1rem;
            }
            .editor-content h1, .editor-content h2, .editor-content h3 {
                font-weight: bold;
                margin-top: 1.5rem;
                margin-bottom: 0.75rem;
            }
            .editor-content h1 {
                font-size: 2rem;
            }
            .editor-content h2 {
                font-size: 1.5rem;
            }
            .editor-content h3 {
                font-size: 1.25rem;
            }
            .editor-content ul, .editor-content ol {
                margin-left: 1.5rem;
                margin-bottom: 1rem;
            }
            .editor-content li {
                margin-bottom: 0.5rem;
            }
            .editor-content a {
                color: #2563eb;
                text-decoration: underline;
            }
            .editor-content a:hover {
                color: #1d4ed8;
            }
            .editor-content img {
                /* Base styles - max-width constrains but doesn't override inline width */
                max-width: 100%;
                margin: 1rem 0;
                /* CRITICAL: Override Tailwind's global height: auto for editor images */
                /* Tailwind base has img{height:auto} which overrides inline styles */
            }
            /* Images with inline styles MUST preserve those dimensions */
            /* This overrides Tailwind's global img{height:auto} rule */
            .editor-content img[style] {
                /* When ANY inline style is present, respect it */
                /* Force inline styles to take precedence over global CSS */
            }
            /* Images with BOTH width AND height inline styles - preserve both dimensions */
            /* Override Tailwind's global height:auto when both dimensions are set inline */
            .editor-content img[style*="width"][style*="height"] {
                /* When both dimensions are set inline, don't override with CSS */
                /* Inline styles have highest specificity, but we need to unset Tailwind's height:auto */
                height: unset !important;
                width: unset !important;
            }
            /* Images with only width (not height) - maintain aspect ratio */
            .editor-content img[style*="width"]:not([style*="height"]) {
                height: auto;
            }
            /* Images with only height (not width) - maintain aspect ratio */
            .editor-content img[style*="height"]:not([style*="width"]) {
                width: auto;
            }
            .editor-content blockquote {
                border-left: 4px solid #3b82f6;
                padding-left: 1rem;
                margin: 1rem 0;
                font-style: italic;
            }
            
            /* Text alignment support (CKEditor uses style attributes, Quill uses classes) */
            .editor-content .ql-align-center,
            .editor-content p.ql-align-center,
            .editor-content h1.ql-align-center,
            .editor-content h2.ql-align-center,
            .editor-content h3.ql-align-center,
            .editor-content div.ql-align-center,
            .editor-content li.ql-align-center,
            .editor-content [style*="text-align: center"],
            .editor-content [style*="text-align:center"] {
                text-align: center !important;
            }
            
            .editor-content .ql-align-right,
            .editor-content p.ql-align-right,
            .editor-content h1.ql-align-right,
            .editor-content h2.ql-align-right,
            .editor-content h3.ql-align-right,
            .editor-content div.ql-align-right,
            .editor-content li.ql-align-right,
            .editor-content [style*="text-align: right"],
            .editor-content [style*="text-align:right"] {
                text-align: right !important;
            }
            
            .editor-content .ql-align-justify,
            .editor-content p.ql-align-justify,
            .editor-content h1.ql-align-justify,
            .editor-content h2.ql-align-justify,
            .editor-content h3.ql-align-justify,
            .editor-content div.ql-align-justify,
            .editor-content li.ql-align-justify,
            .editor-content [style*="text-align: justify"],
            .editor-content [style*="text-align:justify"] {
                text-align: justify !important;
            }
            
            /* Image alignment support - BlotFormatter uses ql-image-align-* classes */
            .editor-content .ql-image-align-left {
                display: block;
                margin-left: 0;
                margin-right: auto;
            }
            
            .editor-content .ql-image-align-center {
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
            
            .editor-content .ql-image-align-right {
                display: block;
                margin-left: auto;
                margin-right: 0;
            }
            
            /* Table styling */
            .editor-content table {
                border-collapse: collapse;
                width: 100%;
                margin: 1rem 0;
                border: 1px solid #e5e7eb;
            }
            
            .dark .editor-content table {
                border-color: #3f3f46;
            }
            
            .editor-content table td,
            .editor-content table th {
                border: 1px solid #e5e7eb;
                padding: 0.5rem 1rem;
                text-align: left;
            }
            
            .dark .editor-content table td,
            .dark .editor-content table th {
                border-color: #3f3f46;
            }
            
            .editor-content table th {
                background-color: #f3f4f6;
                font-weight: 600;
            }
            
            .dark .editor-content table th {
                background-color: #3f3f46;
            }
            
            .editor-content table tr:nth-child(even) {
                background-color: #f9fafb;
            }
            
            .dark .editor-content table tr:nth-child(even) {
                background-color: #27272a;
            }
        </style>
    @endpush
</x-layouts.public> 