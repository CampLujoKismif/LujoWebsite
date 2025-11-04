<x-layouts.app :title="__('Session Templates')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Session Detail Templates</h1>
                <p class="text-neutral-600 dark:text-neutral-400">Manage master templates for public-facing session detail pages</p>
            </div>
            <a href="{{ route('dashboard.admin.camps') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                ← Back to Camps
            </a>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>How it works:</strong> These templates are automatically used when creating new camp sessions. 
                    New sessions will copy their detail page content from the last active session, or from these templates if no active session exists.
                </div>
            </div>
        </div>

        <!-- Camps List -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                @if($camps->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-white">No camps found</h3>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Create camps first before managing templates.</p>
                        <div class="mt-6">
                            <a href="{{ route('dashboard.admin.camps') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Go to Camp Management
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Camp Name</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Template Status</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Last Updated</th>
                                    <th class="text-left py-3 px-4 font-medium text-neutral-900 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($camps as $camp)
                                    <tr class="border-b border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                        <td class="py-4 px-4">
                                            <div>
                                                <h3 class="font-medium text-neutral-900 dark:text-white">{{ $camp->display_name }}</h3>
                                                <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $camp->name }}</p>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($camp->sessionDetailTemplate)
                                                @php
                                                    $hasContent = !empty($camp->sessionDetailTemplate->theme_description) || 
                                                                  !empty($camp->sessionDetailTemplate->schedule_data) ||
                                                                  !empty($camp->sessionDetailTemplate->additional_info);
                                                @endphp
                                                @if($hasContent)
                                                    <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                                                        Configured
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full">
                                                        Empty
                                                    </span>
                                                @endif
                                            @else
                                                <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 rounded-full">
                                                    Not Created
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                                @if($camp->sessionDetailTemplate && $camp->sessionDetailTemplate->updated_at)
                                                    {{ $camp->sessionDetailTemplate->updated_at->diffForHumans() }}
                                                @else
                                                    Never
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <a href="{{ route('admin.camps.session-template', $camp) }}" 
                                               class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                                                Manage Template
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>

