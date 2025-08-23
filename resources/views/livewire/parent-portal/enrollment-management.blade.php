<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Enrollment Management</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage your family's camp enrollments</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Current Enrollments -->
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Current Enrollments</h3>
                    @if($enrollments->isEmpty())
                        <div class="mt-6 text-center">
                            <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No enrollments found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your family doesn't have any camp enrollments yet.</p>
                        </div>
                    @else
                        <div class="mt-6 space-y-6">
                            @foreach($enrollments as $enrollment)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $enrollment->campInstance->camp->display_name }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $enrollment->campInstance->name }} - {{ $enrollment->campInstance->start_date->format('M j, Y') }} to {{ $enrollment->campInstance->end_date->format('M j, Y') }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Camper: {{ $enrollment->camper->full_name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($enrollment->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($enrollment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($enrollment->status === 'waitlisted') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @endif">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">Balance: ${{ number_format($enrollment->balance_cents / 100, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Active Sessions -->
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Active Sessions Open for Registration</h3>
                    @if($upcomingSessions->isEmpty())
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No active sessions</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">There are currently no active camp sessions open for registration.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($upcomingSessions as $session)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $session->camp->display_name }} {{ $session->year }}</h4>
                                            @if($session->start_date && $session->end_date)
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $session->start_date->format('M j') }} - {{ $session->end_date->format('M j, Y') }}</p>
                                            @endif
                                            @if($session->price)
                                                <p class="text-sm text-gray-600 dark:text-gray-300">${{ number_format($session->price, 2) }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                Registration Open
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="#" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">Register Now</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Enrollment Statistics -->
        <div class="mt-6 bg-white dark:bg-zinc-900 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Enrollment Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $enrollments->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Enrollments</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $enrollments->where('status', 'confirmed')->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Confirmed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $enrollments->where('status', 'pending')->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $enrollments->where('status', 'waitlisted')->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Waitlisted</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
