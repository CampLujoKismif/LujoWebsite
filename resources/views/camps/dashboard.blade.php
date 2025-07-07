<x-layouts.app :title="__($camp->display_name . ' Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">{{ $camp->display_name }}</h1>
                <p class="text-neutral-600 dark:text-neutral-400">{{ $camp->description }}</p>
            </div>
            <div class="flex gap-2 items-center">
                <!-- Instance Selector -->
                <div class="relative">
                    <label for="instance-selector" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Camp Instance
                    </label>
                    <select id="instance-selector" onchange="changeInstance(this.value)" 
                        class="px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($instances as $instance)
                            <option value="{{ $instance->id }}" {{ $currentInstance && $currentInstance->id == $instance->id ? 'selected' : '' }}>
                                {{ $instance->year }} - {{ $instance->name }}
                                @if($instance->is_active)
                                    (Active)
                                @endif
                            </option>
                        @endforeach
                        @if($instances->isEmpty())
                            <option disabled>No instances available</option>
                        @endif
                    </select>
                </div>
                
                <a href="{{ route('camps.staff', $camp) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    View Staff
                </a>
                <a href="{{ route('camps.activities', $camp) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    Activities
                </a>
            </div>
        </div>

        <!-- Camp Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Instance Information Banner -->
                @if($currentInstance)
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">
                                    {{ $currentInstance->year }} - {{ $currentInstance->name }}
                                </h3>
                                <p class="text-blue-700 dark:text-blue-300 text-sm">
                                    @if($currentInstance->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Active Instance
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            Inactive Instance
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-blue-700 dark:text-blue-300 text-sm">
                                    @if($currentInstance->start_date && $currentInstance->end_date)
                                        {{ $currentInstance->start_date->format('M j') }} - {{ $currentInstance->end_date->format('M j, Y') }}
                                    @else
                                        Dates TBD
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Camp Details -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Camp Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Session Dates
                                </label>
                                <p class="text-neutral-900 dark:text-white">
                                    @if($currentInstance && $currentInstance->start_date && $currentInstance->end_date)
                                        {{ $currentInstance->start_date->format('M j, Y') }} - 
                                        {{ $currentInstance->end_date->format('M j, Y') }}
                                    @else
                                        TBD
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Age/Grade Range
                                </label>
                                <p class="text-neutral-900 dark:text-white">
                                    @if($currentInstance)
                                        @if($currentInstance->age_from && $currentInstance->age_to)
                                            Ages {{ $currentInstance->age_from }}-{{ $currentInstance->age_to }}
                                        @elseif($currentInstance->grade_from && $currentInstance->grade_to)
                                            {{ $currentInstance->grade_from }}{{ $currentInstance->grade_from == 1 ? 'st' : ($currentInstance->grade_from == 2 ? 'nd' : ($currentInstance->grade_from == 3 ? 'rd' : 'th')) }} - 
                                            {{ $currentInstance->grade_to }}{{ $currentInstance->grade_to == 1 ? 'st' : ($currentInstance->grade_to == 2 ? 'nd' : ($currentInstance->grade_to == 3 ? 'rd' : 'th')) }} Grade
                                        @else
                                            Not specified
                                        @endif
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Location
                                </label>
                                <p class="text-neutral-900 dark:text-white">{{ $camp->location ?? 'Not specified' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Capacity
                                </label>
                                <p class="text-neutral-900 dark:text-white">
                                    @if($currentInstance && $currentInstance->max_capacity)
                                        {{ $currentInstance->max_capacity }} campers
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Price
                                </label>
                                <p class="text-neutral-900 dark:text-white">
                                    @if($currentInstance && $currentInstance->price)
                                        ${{ number_format($currentInstance->price, 2) }}
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    Registration Status
                                </label>
                                <p class="text-neutral-900 dark:text-white">
                                    @if($currentInstance && $currentInstance->isRegistrationOpen())
                                        <span class="text-green-600 dark:text-green-400 font-medium">Open</span>
                                    @elseif($currentInstance)
                                        <span class="text-red-600 dark:text-red-400 font-medium">Closed</span>
                                    @else
                                        Not available
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Staff Overview -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Staff Overview</h2>
                            <a href="{{ route('camps.staff', $camp) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                View All Staff →
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $staffStats['total'] }}</div>
                                <div class="text-sm text-blue-600 dark:text-blue-400">Total Staff</div>
                            </div>
                            
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $primaryStaff->count() }}</div>
                                <div class="text-sm text-green-600 dark:text-green-400">Primary Staff</div>
                            </div>
                            
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $staffStats['by_role']->count() }}</div>
                                <div class="text-sm text-purple-600 dark:text-purple-400">Role Types</div>
                            </div>
                        </div>

                        @if($staffStats['by_role']->count() > 0)
                            <div>
                                <h3 class="font-medium text-neutral-900 dark:text-white mb-3">Staff by Role</h3>
                                <div class="space-y-2">
                                    @foreach($staffStats['by_role'] as $role => $count)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-neutral-700 dark:text-neutral-300">{{ $role }}</span>
                                            <span class="text-sm font-medium text-neutral-900 dark:text-white">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Recent Activities</h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-neutral-900 dark:text-white">Camp session planning completed</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">2 hours ago</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-neutral-900 dark:text-white">New staff member assigned</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">1 day ago</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-neutral-900 dark:text-white">Activity schedule updated</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">3 days ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Quick Actions</h2>
                        
                        <div class="space-y-3">
                            <a href="{{ route('camps.staff', $camp) }}" 
                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-center block">
                                Manage Staff
                            </a>
                            
                            <a href="{{ route('camps.activities', $camp) }}" 
                                class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-center block">
                                View Activities
                            </a>
                            
                            <a href="{{ route('camps.settings', $camp) }}" 
                                class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium text-center block">
                                Camp Settings
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Primary Staff -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Primary Staff</h2>
                        
                        @if($primaryStaff->count() > 0)
                            <div class="space-y-3">
                                @foreach($primaryStaff as $user)
                                    <div class="flex items-center gap-3 p-3 bg-neutral-50 dark:bg-neutral-800 rounded-lg">
                                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                            {{ $user->initials() }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ $user->roles->first() ? $user->roles->first()->display_name : 'No Role' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-neutral-500 dark:text-neutral-400 text-sm">No primary staff assigned</p>
                        @endif
                    </div>
                </div>

                <!-- Camp Status -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Camp Status</h2>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-700 dark:text-neutral-300">Status</span>
                                <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                                    Active
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-700 dark:text-neutral-300">Staff Ready</span>
                                <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                    {{ $staffStats['total'] > 0 ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-700 dark:text-neutral-300">Schedule Set</span>
                                <span class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full">
                                    Pending
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeInstance(instanceId) {
            if (instanceId) {
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('instance', instanceId);
                window.location.href = currentUrl.toString();
            }
        }
    </script>
</x-layouts.app> 