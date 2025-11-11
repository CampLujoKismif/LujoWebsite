<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manager Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage your assigned camp sessions</p>
        </div>

        @if($assignedCamps->isEmpty())
            <div class="mt-6 text-center">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No camps assigned</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You don't have any camps assigned to manage.</p>
            </div>
        @else
            <!-- Camp Selector Banner -->
            <div class="mb-8">
                @if($assignedCamps->count() > 1)
                    <!-- Multiple Camps - Large Switcher -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-white mb-1">Switch Camp</h2>
                                <p class="text-blue-100 text-sm">Select which camp you'd like to manage</p>
                            </div>
                            <div class="flex-1 max-w-md ml-6">
                                <select wire:model.live="selectedCampId" class="block w-full px-4 py-3 text-base font-semibold border-0 rounded-lg shadow-sm bg-white text-gray-900 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                                    @foreach($assignedCamps as $camp)
                                        <option value="{{ $camp->id }}">{{ $camp->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Current Camp Display -->
                @if($selectedCamp)
                    <div class="bg-white dark:bg-zinc-900 border-2 border-blue-500 rounded-lg shadow-lg p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Currently Managing</span>
                                        @if($assignedCamps->count() > 1)
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">{{ $assignedCamps->count() }} Camps</span>
                                        @endif
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $selectedCamp->display_name }}</h2>
                                    @if($selectedCamp->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $selectedCamp->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($assignedCamps->count() > 1)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $assignedCamps->count() }} camps assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if($campInstances->isEmpty())
                <div class="mt-6 text-center bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                    <div class="mx-auto h-12 w-12 text-yellow-400">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-yellow-800 dark:text-yellow-200">No camp sessions found</h3>
                    <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">There are no sessions for <strong>{{ $selectedCamp->display_name ?? 'this camp' }}</strong> yet.</p>
                </div>
            @else
                <!-- Session Selector -->
                <div class="mb-6">
                    <label for="sessionSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Camp Session</label>
                    <select wire:model.live="selectedSessionId" id="sessionSelect" class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white sm:text-sm rounded-lg shadow-sm">
                        <option value="">Choose a session...</option>
                        @foreach($campInstances as $instance)
                            <option value="{{ $instance->id }}">
                                {{ $instance->name }} 
                                @if($instance->start_date)
                                    ({{ $instance->start_date->format('M j, Y') }})
                                @endif
                                @if($instance->is_active)
                                    <span class="text-green-600">● Active</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($selectedSession)
                <!-- Session Statistics -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Enrollments</dt>
                                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['total'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Confirmed</dt>
                                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['confirmed'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending</dt>
                                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['pending'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Waitlisted</dt>
                                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['waitlisted'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('dashboard.manager.enrollments') }}" class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">View Enrollments</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage session enrollments</p>
                                </div>
                            </div>
                        </a>

                        <a href="#" class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Session Details</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Edit session information</p>
                                </div>
                            </div>
                        </a>

                        <div @class([
                                'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4 transition-colors',
                                'hover:bg-gray-50 dark:hover:bg-gray-800' => $selectedSession,
                                'opacity-60' => !$selectedSession,
                            ])>
                            <div class="flex items-start justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Generate Reports</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $selectedSession ? 'Download camper summary' : 'Select a session to enable' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="ml-3 text-xs font-medium text-purple-600 dark:text-purple-300"
                                    wire:loading
                                    wire:target="downloadSessionReport">
                                    Preparing…
                                </span>
                            </div>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <button type="button"
                                    wire:click="downloadSessionReport"
                                    wire:target="downloadSessionReport"
                                    wire:loading.attr="disabled"
                                    @disabled(!$selectedSession)
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-purple-600 text-white hover:bg-purple-700 disabled:bg-purple-400 disabled:cursor-not-allowed">
                                    Download PDF
                                </button>
                                @if($selectedSession)
                                    <a href="{{ route('dashboard.manager.reports.sessions.preview', ['campInstance' => $selectedSession->id]) }}"
                                        target="_blank"
                                        class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium border border-purple-200 text-purple-600 hover:border-purple-400 hover:text-purple-700 dark:border-purple-400/40 dark:text-purple-200 dark:hover:border-purple-300 dark:hover:text-purple-100">
                                        Open HTML Preview
                                    </a>
                                @else
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Select a session to preview</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Enrollments -->
                <div class="mt-8">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Recent Enrollments</h3>
                    <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            @if($this->recentEnrollments->isEmpty())
                                <div class="text-center py-6">
                                    <div class="mx-auto h-12 w-12 text-gray-400">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No recent enrollments</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No new enrollments for this session.</p>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($this->recentEnrollments as $enrollment)
                                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $enrollment->camper->full_name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $enrollment->camper->family->name }}</p>
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
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $enrollment->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            @endif
        @endif
    </div>
</div>