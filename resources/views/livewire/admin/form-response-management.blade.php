<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Form Response Management</h1>
            <p class="mt-2 text-sm text-gray-600">View and manage form submissions from campers and families</p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Filters</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="templateFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Form Template</label>
                        <select wire:model.live="templateFilter" id="templateFilter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                            <option value="">All Templates</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model.live="statusFilter" id="statusFilter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                            <option value="">All Statuses</option>
                            <option value="complete">Complete</option>
                            <option value="incomplete">Incomplete</option>
                        </select>
                    </div>

                    <div>
                        <label for="campInstanceFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Camp Session</label>
                        <select wire:model.live="campInstanceFilter" id="campInstanceFilter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                            <option value="">All Sessions</option>
                            @foreach($campInstances as $instance)
                                <option value="{{ $instance->id }}">{{ $instance->camp->display_name }} {{ $instance->year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="searchTerm" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="searchTerm" id="searchTerm" placeholder="Search by camper name or form..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- Responses List -->
        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Form Responses</h3>
                    <div class="text-sm text-gray-500">
                        {{ $this->responses->total() }} total responses
                    </div>
                </div>

                @if($this->responses->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No form responses found</h3>
                        <p class="mt-1 text-sm text-gray-500">No responses match your current filters.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Camper</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Form Template</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Camp Session</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($this->responses as $response)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $response->camper->full_name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $response->camper->family->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $response->formTemplate->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($response->formTemplate->scope) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($response->enrollment && $response->enrollment->campInstance)
                                                <div class="text-sm text-gray-900 dark:text-white">{{ $response->enrollment->campInstance->camp->display_name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $response->enrollment->campInstance->year }}</div>
                                            @else
                                                <span class="text-sm text-gray-500 dark:text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($response->is_complete) bg-green-100 text-green-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ $response->is_complete ? 'Complete' : 'Incomplete' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            @if($response->submitted_at)
                                                {{ $response->submitted_at->format('M j, Y g:i A') }}
                                            @else
                                                Not submitted
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button wire:click="openResponseModal({{ $response->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    View
                                                </button>
                                                @if($response->is_complete)
                                                    <button wire:click="markAsIncomplete({{ $response->id }})" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                        Mark Incomplete
                                                    </button>
                                                @else
                                                    <button wire:click="markAsComplete({{ $response->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                        Mark Complete
                                                    </button>
                                                @endif
                                                <button wire:click="deleteResponse({{ $response->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure you want to delete this response?')">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $this->responses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Response Detail Modal -->
    @if($showResponseModal && $selectedResponse)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Form Response Details</h3>
                        <button wire:click="$set('showResponseModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Response Header -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Camper Information</h4>
                                <p class="text-sm text-gray-600">{{ $selectedResponse->camper->full_name }}</p>
                                <p class="text-sm text-gray-600">{{ $selectedResponse->camper->family->name }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Form Information</h4>
                                <p class="text-sm text-gray-600">{{ $selectedResponse->formTemplate->name }}</p>
                                <p class="text-sm text-gray-600">{{ ucfirst($selectedResponse->formTemplate->scope) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Submission Details</h4>
                                <p class="text-sm text-gray-600">
                                    @if($selectedResponse->submitted_at)
                                        {{ $selectedResponse->submitted_at->format('M j, Y g:i A') }}
                                    @else
                                        Not submitted
                                    @endif
                                </p>
                                @if($selectedResponse->submittedBy)
                                    <p class="text-sm text-gray-600">By: {{ $selectedResponse->submittedBy->name }}</p>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Status</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedResponse->is_complete ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $selectedResponse->is_complete ? 'Complete' : 'Incomplete' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Answers -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Form Answers</h4>
                        @if($selectedResponse->answers->isEmpty())
                            <div class="text-center py-6">
                                <p class="text-sm text-gray-500">No answers submitted yet.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($selectedResponse->answers as $answer)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h5 class="text-sm font-medium text-gray-900">{{ $answer->formField->label }}</h5>
                                                @if($answer->formField->help_text)
                                                    <p class="text-xs text-gray-500 mt-1">{{ $answer->formField->help_text }}</p>
                                                @endif
                                                <div class="mt-2">
                                                    @if($answer->formField->type === 'file' && $answer->file_path)
                                                        <a href="{{ Storage::url($answer->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                            View File
                                                        </a>
                                                    @elseif($answer->formField->type === 'checkbox' && $answer->value_json)
                                                        <div class="space-y-1">
                                                            @foreach(json_decode($answer->value_json, true) ?: [] as $option)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                                                    {{ $option }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-sm text-gray-700">{{ $answer->value_text ?: 'No answer provided' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($answer->formField->type) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Response Actions -->
                    <div class="mt-6 flex justify-end space-x-3">
                        @if($selectedResponse->is_complete)
                            <button wire:click="markAsIncomplete({{ $selectedResponse->id }})" class="px-4 py-2 border border-yellow-300 rounded-md shadow-sm text-sm font-medium text-yellow-700 bg-white hover:bg-yellow-50">
                                Mark Incomplete
                            </button>
                        @else
                            <button wire:click="markAsComplete({{ $selectedResponse->id }})" class="px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-white hover:bg-green-50">
                                Mark Complete
                            </button>
                        @endif
                        <button wire:click="deleteResponse({{ $selectedResponse->id }})" class="px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50" onclick="return confirm('Are you sure you want to delete this response?')">
                            Delete Response
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
