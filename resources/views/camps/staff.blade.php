<x-layouts.app :title="__($camp->display_name . ' Staff')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">{{ $camp->display_name }} Staff</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Manage staff assignments and roles</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('camps.dashboard', $camp) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                    ← Back to Dashboard
                </a>
                <a href="{{ route('admin.users.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    Add Staff
                </a>
            </div>
        </div>

        <!-- Staff Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $camp->assignedUsers->count() }}</div>
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Total Staff</div>
            </div>
            
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $camp->assignedUsers->where('pivot.is_primary', true)->count() }}</div>
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Primary Staff</div>
            </div>
            
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $camp->assignedUsers->unique('pivot.role_id')->count() }}</div>
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Role Types</div>
            </div>
            
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $camp->assignedUsers->where('email_verified_at', '!=', null)->count() }}</div>
                <div class="text-sm text-neutral-600 dark:text-neutral-400">Verified Staff</div>
            </div>
        </div>

        <!-- Staff List -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Staff Roster</h2>
                    <div class="flex gap-2">
                        <select class="px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Roles</option>
                            @foreach($camp->assignedUsers->unique('pivot.role_id') as $user)
                                @php
                                    $role = \App\Models\Role::find($user->pivot->role_id);
                                @endphp
                                @if($role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($camp->assignedUsers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Staff Member</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Role</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Status</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Assignment</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($camp->assignedUsers as $user)
                                    @php
                                        $role = \App\Models\Role::find($user->pivot->role_id);
                                    @endphp
                                    <tr class="border-b border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                                    {{ $user->initials() }}
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-neutral-900 dark:text-white">{{ $user->name }}</h3>
                                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($role)
                                                <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                                    {{ $role->display_name }}
                                                </span>
                                            @else
                                                <span class="text-sm text-neutral-500 dark:text-neutral-400">No Role</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-2">
                                                @if($user->email_verified_at)
                                                    <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                                                        Verified
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full">
                                                        Pending
                                                    </span>
                                                @endif
                                                
                                                @if($user->pivot->is_primary)
                                                    <span class="px-2 py-1 text-xs bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full">
                                                        Primary
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm text-neutral-600 dark:text-neutral-400">
                                                Assigned: {{ $user->pivot->created_at ? \Carbon\Carbon::parse($user->pivot->created_at)->format('M j, Y') : 'Unknown' }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">View</a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-green-600 dark:text-green-400 hover:underline text-sm">Edit</a>
                                                <form action="{{ route('camps.remove-staff', ['camp' => $camp, 'user' => $user]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this staff member from the camp?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Remove</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-neutral-500 dark:text-neutral-400">No staff assigned to this camp yet.</p>
                        <a href="{{ route('admin.users.create') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Add your first staff member</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Role Distribution -->
        @if($camp->assignedUsers->count() > 0)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Role Distribution</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $roleDistribution = $camp->assignedUsers->groupBy('pivot.role_id')->map(function ($users, $roleId) {
                                $role = \App\Models\Role::find($roleId);
                                return [
                                    'name' => $role ? $role->display_name : 'No Role',
                                    'count' => $users->count(),
                                    'users' => $users
                                ];
                            });
                        @endphp
                        
                        @foreach($roleDistribution as $role)
                            <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-medium text-neutral-900 dark:text-white">{{ $role['name'] }}</h3>
                                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $role['count'] }}</span>
                                </div>
                                
                                <div class="space-y-2">
                                    @foreach($role['users'] as $user)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-medium">
                                                {{ $user->initials() }}
                                            </div>
                                            <span class="text-sm text-neutral-700 dark:text-neutral-300">{{ $user->name }}</span>
                                            @if($user->pivot->is_primary)
                                                <span class="px-1 py-0.5 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">Primary</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app> 