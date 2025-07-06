<x-layouts.app :title="__($camp->display_name . ' Activities')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">{{ $camp->display_name }} Activities</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Manage camp schedule and activities</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('camps.dashboard', $camp) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                    ← Back to Dashboard
                </a>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    Add Activity
                </button>
            </div>
        </div>

        <!-- Activity Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">12</div>
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Total Activities</div>
            </div>
            
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">8</div>
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Scheduled</div>
            </div>
            
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">3</div>
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Pending</div>
            </div>
            
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">5</div>
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Activity Types</div>
            </div>
        </div>

        <!-- Weekly Schedule -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Weekly Schedule</h2>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 text-sm border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                            Previous Week
                        </button>
                        <button class="px-3 py-1 text-sm border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                            Next Week
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white w-24">Time</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Monday</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Tuesday</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Wednesday</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Thursday</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Friday</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Saturday</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Sunday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-neutral-100 dark:border-neutral-800">
                                <td class="py-4 px-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">7:00 AM</td>
                                <td class="py-4 px-4">
                                    <div class="bg-blue-100 dark:bg-blue-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-blue-900 dark:text-blue-100">Morning Devotion</h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">Chapel</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-blue-100 dark:bg-blue-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-blue-900 dark:text-blue-100">Morning Devotion</h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">Chapel</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-blue-100 dark:bg-blue-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-blue-900 dark:text-blue-100">Morning Devotion</h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">Chapel</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-blue-100 dark:bg-blue-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-blue-900 dark:text-blue-100">Morning Devotion</h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">Chapel</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-blue-100 dark:bg-blue-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-blue-900 dark:text-blue-100">Morning Devotion</h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">Chapel</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-blue-100 dark:bg-blue-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-blue-900 dark:text-blue-100">Morning Devotion</h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">Chapel</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-blue-100 dark:bg-blue-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-blue-900 dark:text-blue-100">Morning Devotion</h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">Chapel</p>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-neutral-100 dark:border-neutral-800">
                                <td class="py-4 px-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">8:00 AM</td>
                                <td class="py-4 px-4">
                                    <div class="bg-green-100 dark:bg-green-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-green-900 dark:text-green-100">Breakfast</h4>
                                        <p class="text-xs text-green-700 dark:text-green-300">Dining Hall</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-green-100 dark:bg-green-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-green-900 dark:text-green-100">Breakfast</h4>
                                        <p class="text-xs text-green-700 dark:text-green-300">Dining Hall</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-green-100 dark:bg-green-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-green-900 dark:text-green-100">Breakfast</h4>
                                        <p class="text-xs text-green-700 dark:text-green-300">Dining Hall</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-green-100 dark:bg-green-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-green-900 dark:text-green-100">Breakfast</h4>
                                        <p class="text-xs text-green-700 dark:text-green-300">Dining Hall</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-green-100 dark:bg-green-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-green-900 dark:text-green-100">Breakfast</h4>
                                        <p class="text-xs text-green-700 dark:text-green-300">Dining Hall</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-green-100 dark:bg-green-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-green-900 dark:text-green-100">Breakfast</h4>
                                        <p class="text-xs text-green-700 dark:text-green-300">Dining Hall</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-green-100 dark:bg-green-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-green-900 dark:text-green-100">Breakfast</h4>
                                        <p class="text-xs text-green-700 dark:text-green-300">Dining Hall</p>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-neutral-100 dark:border-neutral-800">
                                <td class="py-4 px-4 text-sm font-medium text-neutral-700 dark:text-neutral-300">9:00 AM</td>
                                <td class="py-4 px-4">
                                    <div class="bg-purple-100 dark:bg-purple-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-purple-900 dark:text-purple-100">Archery</h4>
                                        <p class="text-xs text-purple-700 dark:text-purple-300">Range</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-orange-100 dark:bg-orange-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-orange-900 dark:text-orange-100">Swimming</h4>
                                        <p class="text-xs text-orange-700 dark:text-orange-300">Pool</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-red-100 dark:bg-red-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-red-900 dark:text-red-100">Rock Climbing</h4>
                                        <p class="text-xs text-red-700 dark:text-red-300">Wall</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-indigo-100 dark:bg-indigo-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-indigo-900 dark:text-indigo-100">Crafts</h4>
                                        <p class="text-xs text-indigo-700 dark:text-indigo-300">Craft Hall</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-yellow-100 dark:bg-yellow-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-yellow-900 dark:text-yellow-100">Hiking</h4>
                                        <p class="text-xs text-yellow-700 dark:text-yellow-300">Trails</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-pink-100 dark:bg-pink-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-pink-900 dark:text-pink-100">Talent Show</h4>
                                        <p class="text-xs text-pink-700 dark:text-pink-300">Amphitheater</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="bg-gray-100 dark:bg-gray-900/20 rounded-lg p-3">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Free Time</h4>
                                        <p class="text-xs text-gray-700 dark:text-gray-300">Camp Grounds</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Activity List -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">All Activities</h2>
                    <div class="flex gap-2">
                        <select class="px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Types</option>
                            <option value="sports">Sports</option>
                            <option value="arts">Arts & Crafts</option>
                            <option value="outdoor">Outdoor</option>
                            <option value="spiritual">Spiritual</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Activity Cards -->
                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-medium text-neutral-900 dark:text-white">Archery</h3>
                            <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">Sports</span>
                        </div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">Learn archery skills with certified instructors</p>
                        <div class="flex justify-between items-center text-xs text-neutral-500 dark:text-neutral-400">
                            <span>Duration: 1 hour</span>
                            <span>Max: 12 campers</span>
                        </div>
                    </div>

                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-medium text-neutral-900 dark:text-white">Swimming</h3>
                            <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">Sports</span>
                        </div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">Swimming lessons and free swim time</p>
                        <div class="flex justify-between items-center text-xs text-neutral-500 dark:text-neutral-400">
                            <span>Duration: 1.5 hours</span>
                            <span>Max: 20 campers</span>
                        </div>
                    </div>

                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-medium text-neutral-900 dark:text-white">Rock Climbing</h3>
                            <span class="px-2 py-1 text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded">Outdoor</span>
                        </div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">Indoor rock climbing with safety equipment</p>
                        <div class="flex justify-between items-center text-xs text-neutral-500 dark:text-neutral-400">
                            <span>Duration: 1 hour</span>
                            <span>Max: 8 campers</span>
                        </div>
                    </div>

                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-medium text-neutral-900 dark:text-white">Crafts</h3>
                            <span class="px-2 py-1 text-xs bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded">Arts</span>
                        </div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">Creative arts and crafts projects</p>
                        <div class="flex justify-between items-center text-xs text-neutral-500 dark:text-neutral-400">
                            <span>Duration: 1 hour</span>
                            <span>Max: 15 campers</span>
                        </div>
                    </div>

                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-medium text-neutral-900 dark:text-white">Hiking</h3>
                            <span class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded">Outdoor</span>
                        </div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">Guided hiking on camp trails</p>
                        <div class="flex justify-between items-center text-xs text-neutral-500 dark:text-neutral-400">
                            <span>Duration: 2 hours</span>
                            <span>Max: 16 campers</span>
                        </div>
                    </div>

                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-medium text-neutral-900 dark:text-white">Morning Devotion</h3>
                            <span class="px-2 py-1 text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded">Spiritual</span>
                        </div>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">Daily morning worship and devotion</p>
                        <div class="flex justify-between items-center text-xs text-neutral-500 dark:text-neutral-400">
                            <span>Duration: 30 min</span>
                            <span>All campers</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 