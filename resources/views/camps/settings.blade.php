<x-layouts.app :title="__($camp->display_name . ' Settings')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">{{ $camp->display_name }} Settings</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Manage camp configuration and preferences</p>
            </div>
            <a href="{{ route('camps.dashboard', $camp) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Settings Navigation -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="flex space-x-8 border-b border-neutral-200 dark:border-neutral-700">
                    <button class="px-4 py-2 text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 font-medium" onclick="showTab('general')">
                        General
                    </button>
                    <button class="px-4 py-2 text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white" onclick="showTab('instances')">
                        Instances
                    </button>
                    <button class="px-4 py-2 text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white" onclick="showTab('permissions')">
                        Permissions
                    </button>
                    <button class="px-4 py-2 text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white" onclick="showTab('notifications')">
                        Notifications
                    </button>
                    <button class="px-4 py-2 text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white" onclick="showTab('advanced')">
                        Advanced
                    </button>
                </div>
            </div>
        </div>

        <!-- General Settings Tab -->
        <div id="general-tab" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Camp Information -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Camp Information</h2>
                        
                        <form action="{{ route('admin.camps.update', $camp) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="display_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Display Name
                                    </label>
                                    <input type="text" id="display_name" name="display_name" value="{{ $camp->display_name }}" required
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Description
                                    </label>
                                    <textarea id="description" name="description" rows="3"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $camp->description }}</textarea>
                                </div>
                            
                                
                                <div>
                                    <label for="location" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Location
                                    </label>
                                    <input type="text" id="location" name="location" value="{{ $camp->location }}"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="capacity" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Capacity
                                    </label>
                                    <input type="number" id="capacity" name="capacity" 
                                        value="{{ $currentInstance ? $currentInstance->max_capacity : '' }}" min="1"
                                        class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Current Instance Settings -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Current Instance Settings</h2>
                        
                        @if($currentInstance)
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                            Year
                                        </label>
                                        <p class="text-neutral-900 dark:text-white">{{ $currentInstance->year }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                            Status
                                        </label>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $currentInstance->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $currentInstance->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                            Start Date
                                        </label>
                                        <p class="text-neutral-900 dark:text-white">{{ $currentInstance->start_date ? $currentInstance->start_date->format('M j, Y') : 'Not set' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                            End Date
                                        </label>
                                        <p class="text-neutral-900 dark:text-white">{{ $currentInstance->end_date ? $currentInstance->end_date->format('M j, Y') : 'Not set' }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                            Capacity
                                        </label>
                                        <p class="text-neutral-900 dark:text-white">{{ $currentInstance->max_capacity ?? 'Not set' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                            Price
                                        </label>
                                        <p class="text-neutral-900 dark:text-white">{{ $currentInstance->price ? '$' . number_format($currentInstance->price, 2) : 'Not set' }}</p>
                                    </div>
                                </div>
                                
                                <div class="pt-4 border-t border-neutral-200 dark:border-neutral-700">
                                    <a href="{{ route('camps.instances.edit', [$camp, $currentInstance]) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                        Edit Current Instance →
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-neutral-500 dark:text-neutral-400 mb-4">No active instance found</p>
                                <a href="{{ route('camps.instances.create', $camp) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                                    Create New Instance
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Instances Tab -->
        <div id="instances-tab" class="tab-content hidden">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Camp Instances</h2>
                        <a href="{{ route('camps.instances.create', $camp) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                            Create New Instance
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Year</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Name</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Dates</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Capacity</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Price</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Status</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($camp->instances()->orderByDesc('year')->get() as $instance)
                                    <tr class="border-b border-neutral-200 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                        <td class="py-3 px-4 text-neutral-900 dark:text-white">{{ $instance->year }}</td>
                                        <td class="py-3 px-4 text-neutral-900 dark:text-white">{{ $instance->name }}</td>
                                        <td class="py-3 px-4 text-neutral-900 dark:text-white">
                                            @if($instance->start_date && $instance->end_date)
                                                {{ $instance->start_date->format('M j') }} - {{ $instance->end_date->format('M j, Y') }}
                                            @else
                                                Not set
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-neutral-900 dark:text-white">{{ $instance->max_capacity ?? 'Not set' }}</td>
                                        <td class="py-3 px-4 text-neutral-900 dark:text-white">
                                            {{ $instance->price ? '$' . number_format($instance->price, 2) : 'Not set' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $instance->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ $instance->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('camps.instances.edit', [$camp, $instance]) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">Edit</a>
                                                <a href="{{ route('camps.instances.show', [$camp, $instance]) }}" class="text-green-600 dark:text-green-400 hover:underline text-sm">View</a>
                                                <form action="{{ route('camps.instances.destroy', [$camp, $instance]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this camp instance?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm bg-transparent border-none p-0">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-neutral-500 dark:text-neutral-400">
                                            No camp instances found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Tab -->
        <div id="permissions-tab" class="tab-content hidden">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Permissions</h2>
                    <p class="text-neutral-500 dark:text-neutral-400">Permission settings coming soon...</p>
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications-tab" class="tab-content hidden">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Notifications</h2>
                    <p class="text-neutral-500 dark:text-neutral-400">Notification settings coming soon...</p>
                </div>
            </div>
        </div>

        <!-- Advanced Tab -->
        <div id="advanced-tab" class="tab-content hidden">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Advanced Settings</h2>
                    <p class="text-neutral-500 dark:text-neutral-400">Advanced settings coming soon...</p>
                </div>
            </div>
        </div>

        <!-- Camp Status -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Camp Status</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg">
                        <div>
                            <span class="font-medium text-neutral-900 dark:text-white">Camp Status</span>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Active or Inactive</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-neutral-200 dark:bg-neutral-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg">
                        <div>
                            <span class="font-medium text-neutral-900 dark:text-white">Registration Open</span>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Allow new registrations</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-neutral-200 dark:bg-neutral-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg">
                        <div>
                            <span class="font-medium text-neutral-900 dark:text-white">Public Visibility</span>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Show on public website</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-neutral-200 dark:bg-neutral-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-red-200 dark:border-red-800">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-red-900 dark:text-red-100 mb-4">Danger Zone</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-red-200 dark:border-red-800 rounded-lg">
                        <div>
                            <span class="font-medium text-red-900 dark:text-red-100">Delete Camp</span>
                            <p class="text-sm text-red-700 dark:text-red-300">Permanently delete this camp and all associated data</p>
                        </div>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            Delete Camp
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-red-200 dark:border-red-800 rounded-lg">
                        <div>
                            <span class="font-medium text-red-900 dark:text-red-100">Archive Camp</span>
                            <p class="text-sm text-red-700 dark:text-red-300">Archive this camp (can be restored later)</p>
                        </div>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            Archive Camp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active state from all tab buttons
            const tabButtons = document.querySelectorAll('[onclick^="showTab"]');
            tabButtons.forEach(button => {
                button.classList.remove('text-blue-600', 'dark:text-blue-400', 'border-b-2', 'border-blue-600', 'dark:border-blue-400', 'font-medium');
                button.classList.add('text-neutral-600', 'dark:text-neutral-400', 'hover:text-neutral-900', 'dark:hover:text-white');
            });
            
            // Show selected tab content
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
            }
            
            // Add active state to selected tab button
            const selectedButton = document.querySelector(`[onclick="showTab('${tabName}')"]`);
            if (selectedButton) {
                selectedButton.classList.remove('text-neutral-600', 'dark:text-neutral-400', 'hover:text-neutral-900', 'dark:hover:text-white');
                selectedButton.classList.add('text-blue-600', 'dark:text-blue-400', 'border-b-2', 'border-blue-600', 'dark:border-blue-400', 'font-medium');
            }
        }
        
        // Initialize with general tab active
        document.addEventListener('DOMContentLoaded', function() {
            showTab('general');
        });
    </script>
</x-layouts.app> 