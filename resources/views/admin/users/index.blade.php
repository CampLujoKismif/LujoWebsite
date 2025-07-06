<x-layouts.app :title="__('Manage Users')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Manage Users</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Create and manage user accounts</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                Add New User
            </a>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by name or email..."
                            class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="md:w-48">
                        <select name="role" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-zinc-800 text-neutral-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            Search
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users List -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">User</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Roles</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Camp Assignments</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Status</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="border-b border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                <td class="py-4 px-4">
                                    <div>
                                        <h3 class="font-medium text-neutral-900 dark:text-white">{{ $user->name }}</h3>
                                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $user->email }}</p>
                                        <p class="text-xs text-neutral-400 dark:text-neutral-500">Joined {{ $user->created_at->format('M j, Y') }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                                {{ $role->display_name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-neutral-500 dark:text-neutral-400">No roles</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm">
                                        @forelse($user->assignedCamps as $camp)
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-neutral-900 dark:text-white">{{ $camp->display_name }}</span>
                                                @if($camp->pivot->role_id)
                                                    @php
                                                        $role = \App\Models\Role::find($camp->pivot->role_id);
                                                    @endphp
                                                    @if($role)
                                                        <span class="px-1 py-0.5 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">{{ $role->display_name }}</span>
                                                    @endif
                                                @endif
                                                @if($camp->pivot->is_primary)
                                                    <span class="px-1 py-0.5 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">Primary</span>
                                                @endif
                                            </div>
                                        @empty
                                            <span class="text-neutral-500 dark:text-neutral-400">No assignments</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($user->email_verified_at)
                                        <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                                            Verified
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">View</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-green-600 dark:text-green-400 hover:underline text-sm">Edit</a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-neutral-400 text-sm">Current User</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 px-4 text-center text-neutral-500 dark:text-neutral-400">
                                    No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Create your first user</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app> 