<x-layouts.app :title="__('Manage Camps')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Manage Camps</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Create and manage camp sessions</p>
            </div>
            <a href="{{ route('admin.camps.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                Add New Camp
            </a>
        </div>

        <!-- Camps List -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Camp Name</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Dates</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Status</th>
                                <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($camps as $camp)
                            <tr class="border-b border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                <td class="py-4 px-4">
                                    <div>
                                        <h3 class="font-medium text-neutral-900 dark:text-white">{{ $camp->display_name }}</h3>
                                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $camp->name }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm">
                                        <span class="text-neutral-500 dark:text-neutral-400">N/A</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm">
                                        <span class="text-neutral-500 dark:text-neutral-400">N/A</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($camp->is_active)
                                        <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-full">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.camps.show', $camp) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">View</a>
                                        <a href="{{ route('admin.camps.edit', $camp) }}" class="text-green-600 dark:text-green-400 hover:underline text-sm">Edit</a>
                                        <a href="{{ route('admin.camps.session-template', $camp) }}" class="text-purple-600 dark:text-purple-400 hover:underline text-sm" title="Manage Session Detail Template">
                                            Template
                                        </a>
                                        <form action="{{ route('admin.camps.destroy', $camp) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this camp?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center text-neutral-500 dark:text-neutral-400">
                                    No camps found. <a href="{{ route('admin.camps.create') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Create your first camp</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($camps->hasPages())
                <div class="mt-6">
                    {{ $camps->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app> 