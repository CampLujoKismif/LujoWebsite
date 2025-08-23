<x-layouts.public>
    <x-slot name="title">
        {{ $instance->camp->display_name }} - Camp LUJO-KISMIF
    </x-slot>

    <div class="min-h-screen mx-auto max-w-2xl px-4 sm:px-6 lg:px-0 py-20" style="padding-top: 164px;">
        <!-- Hero Card -->
        <div class="relative bg-white dark:bg-zinc-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 p-8 sm:p-12 mb-12">
            <div class="absolute top-0 left-1/2 -translate-x-1/2">
                <span class="inline-block rounded-full bg-blue-600 px-4 py-1 text-xs font-semibold text-white shadow-lg tracking-wide uppercase">
                    @if($instance->isRegistrationOpen()) Registration Open @else Registration Closed @endif
                </span>
            </div>
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

        <!-- Theme Section -->
        @if($instance->theme_description)
            <div class="mb-12">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span> Theme
                </h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $instance->theme_description }}</p>
            </div>
        @endif

        <!-- Schedule Section -->
        @if($instance->schedule_data)
            <div class="mb-12">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span> Daily Schedule
                </h2>
                <ol class="relative border-l-2 border-blue-100 ml-3">
                    @foreach(json_decode($instance->schedule_data) as $time => $activity)
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

        <!-- Additional Info Section -->
        @if($instance->additional_info)
            <div class="mb-12">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span> Additional Information
                </h2>
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed">{!! nl2br(e($instance->additional_info)) !!}</div>
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
</x-layouts.public> 