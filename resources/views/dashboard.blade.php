<x-layouts.app :title="__('Camp Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-6 text-white">
            <h1 class="text-3xl font-bold mb-2">Welcome to Camp LUJO-KISMIF</h1>
            <p class="text-blue-100">Keep It Spiritual, Make It Fun!</p>
            
            @if(auth()->user()->primaryCamp())
                <div class="mt-4 p-4 bg-blue-700/50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-1">Your Primary Camp Assignment</h3>
                    <p class="text-blue-100">{{ auth()->user()->primaryCamp()->display_name }}</p>
                    @if(auth()->user()->primaryCampAssignment())
                        <p class="text-sm text-blue-200">{{ auth()->user()->primaryCampAssignment()->position }}</p>
                    @endif
                </div>
            @endif

            @role('super_admin')
                <div class="mt-4 p-4 bg-purple-700/50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-1">Admin Access</h3>
                    <p class="text-blue-100 mb-2">You have super administrator privileges</p>
                    <a href="{{ route('admin.dashboard') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Go to Admin Dashboard
                    </a>
                </div>
            @endrole
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Campers</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white">247</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Active Sessions</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white">3</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Staff Members</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white">42</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Upcoming Events</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white">8</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Sessions and Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Current Sessions -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Current Sessions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div>
                                <h3 class="font-semibold text-neutral-900 dark:text-white">Jump Week</h3>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">9th Grade & Up • June 1-7</p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm rounded-full">Active</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div>
                                <h3 class="font-semibold text-neutral-900 dark:text-white">Reunion Week</h3>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">4th-12th Grade • June 8-14</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full">Starting Soon</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <div>
                                <h3 class="font-semibold text-neutral-900 dark:text-white">Day Camp</h3>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">1st-4th Grade • June 9-11</p>
                            </div>
                            <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-sm rounded-full">Registration Open</span>
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Quick Actions -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Quick Actions</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    @permission('create_campers')
                    <a href="#" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm font-medium text-neutral-900 dark:text-white">Add Camper</span>
                    </a>
                    @endpermission
                    
                    @permission('view_reports')
                    <a href="#" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="text-sm font-medium text-neutral-900 dark:text-white">View Reports</span>
                    </a>
                    @endpermission
                    
                    @permission('view_sessions')
                    <a href="#" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-neutral-900 dark:text-white">Schedule</span>
                    </a>
                    @endpermission
                    
                    @anyrole(['super_admin', 'camp_director', 'camp_staff'])
                    <a href="#" class="flex flex-col items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/40 transition-colors">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-neutral-900 dark:text-white">Messages</span>
                    </a>
                    @endanyrole
                </div>
            </div>
        </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Recent Activity</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-900 dark:text-white">New camper registration: <span class="font-medium">Sarah Johnson</span> for Jump Week</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">2 hours ago</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-900 dark:text-white">Staff member <span class="font-medium">Mike Wilson</span> checked in for duty</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">4 hours ago</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-900 dark:text-white">New message from <span class="font-medium">Parent of Emma Davis</span></p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">6 hours ago</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-900 dark:text-white">Daily report generated for <span class="font-medium">Jump Week</span></p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
