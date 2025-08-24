<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Form Filling</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Complete required forms for your campers</p>
        </div>

        @if($campers->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No campers found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add campers to fill out forms for them.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Camper Selection -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Select Camper</h3>
                            <div class="space-y-2">
                                @foreach($campers as $camper)
                                    <button 
                                        wire:click="selectCamper({{ $camper->id }})"
                                        class="w-full text-left p-3 rounded-lg border {{ $selectedCamper && $selectedCamper->id === $camper->id ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-zinc-800' }}"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $camper->full_name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $camper->family->name }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Grade {{ $camper->grade }}</span>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if($selectedCamper)
                        <!-- Enrollment Selection -->
                        @if($selectedCamper->enrollments->isNotEmpty())
                            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg mt-6">
                                <div class="px-4 py-5 sm:p-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Select Camp Session (Optional)</h3>
                                    <div class="space-y-2">
                                        <button 
                                            wire:click="selectEnrollment(null)"
                                            class="w-full text-left p-3 rounded-lg border {{ !$selectedEnrollment ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-zinc-800' }}"
                                        >
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">General Forms</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Global forms not tied to specific sessions</p>
                                            </div>
                                        </button>
                                        @foreach($selectedCamper->enrollments as $enrollment)
                                            <button 
                                                wire:click="selectEnrollment({{ $enrollment->id }})"
                                                class="w-full text-left p-3 rounded-lg border {{ $selectedEnrollment && $selectedEnrollment->id === $enrollment->id ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-zinc-800' }}"
                                            >
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $enrollment->campInstance->camp->display_name }} {{ $enrollment->campInstance->year }}</h4>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($enrollment->status) }}</p>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Available Forms -->
                <div class="lg:col-span-2">
                    @if($selectedCamper)
                        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Available Forms for {{ $selectedCamper->full_name }}</h3>
                                
                                @php
                                    $availableTemplates = $this->getAvailableTemplatesForCamper($selectedCamper);
                                @endphp

                                @if($availableTemplates->isEmpty())
                                    <div class="text-center py-6">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No forms available</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No forms are currently available for this camper.</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach($availableTemplates as $template)
                                            @php
                                                $status = $this->getResponseStatus($template, $selectedCamper, $selectedEnrollment);
                                            @endphp
                                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $template->name }}</h4>
                                                        @if($template->description)
                                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $template->description }}</p>
                                                        @endif
                                                        <div class="flex items-center space-x-4 mt-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->scope === 'global' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200' }}">
                                                                {{ ucfirst($template->scope) }}
                                                            </span>
                                                            @if($template->requires_annual_completion)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                                                    Annual Required
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-3">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            @if($status === 'complete') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                                            @elseif($status === 'incomplete') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                                            @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 @endif">
                                                            @if($status === 'complete') Complete
                                                            @elseif($status === 'incomplete') Incomplete
                                                            @else Not Started @endif
                                                        </span>
                                                        <button 
                                                            wire:click="openForm({{ $template->id }}, {{ $selectedCamper->id }}, {{ $selectedEnrollment?->id }})"
                                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            {{ $status === 'not_started' ? 'Start Form' : 'Edit Form' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Select a camper</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Choose a camper from the list to view available forms.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Form Modal -->
    @if($showFormModal && $selectedTemplate && $selectedCamper)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $selectedTemplate->name }} - {{ $selectedCamper->full_name }}</h3>
                        <button wire:click="$set('showFormModal', false)" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    @if($selectedTemplate->description)
                        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-sm text-blue-700 dark:text-blue-300">{{ $selectedTemplate->description }}</p>
                        </div>
                    @endif

                    <form wire:submit.prevent="submitForm">
                        <div class="space-y-6">
                            @foreach($selectedTemplate->fields->sortBy('sort') as $field)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    
                                    @if($field->help_text)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $field->help_text }}</p>
                                    @endif

                                    @switch($field->type)
                                        @case('text')
                                            <input type="text" wire:model="formData.{{ $field->id }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                            @break

                                        @case('textarea')
                                            <textarea wire:model="formData.{{ $field->id }}" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"></textarea>
                                            @break

                                        @case('email')
                                            <input type="email" wire:model="formData.{{ $field->id }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                            @break

                                        @case('number')
                                            <input type="number" wire:model="formData.{{ $field->id }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                            @break

                                        @case('date')
                                            <input type="date" wire:model="formData.{{ $field->id }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white" placeholder="Select a date">
                                            @break

                                        @case('select')
                                            <select wire:model="formData.{{ $field->id }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                                <option value="">Select an option</option>
                                                @foreach(json_decode($field->options_json, true) ?: [] as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            @break

                                        @case('radio')
                                            <div class="space-y-2">
                                                @foreach(json_decode($field->options_json, true) ?: [] as $option)
                                                    <div class="flex items-center">
                                                        <input type="radio" wire:model="formData.{{ $field->id }}" value="{{ $option }}" id="radio_{{ $field->id }}_{{ $loop->index }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                                        <label for="radio_{{ $field->id }}_{{ $loop->index }}" class="ml-2 block text-sm text-gray-900 dark:text-white">{{ $option }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('checkbox')
                                            <div class="space-y-2">
                                                @foreach(json_decode($field->options_json, true) ?: [] as $option)
                                                    <div class="flex items-center">
                                                        <input type="checkbox" wire:model="formData.{{ $field->id }}" value="{{ $option }}" id="checkbox_{{ $field->id }}_{{ $loop->index }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                        <label for="checkbox_{{ $field->id }}_{{ $loop->index }}" class="ml-2 block text-sm text-gray-900 dark:text-white">{{ $option }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('file')
                                            <input type="file" wire:model="fileUploads.{{ $field->id }}" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                            @break

                                        @default
                                            <input type="text" wire:model="formData.{{ $field->id }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    @endswitch
                                    
                                    @error('formData.' . $field->id)
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    
                                    @if(config('app.debug'))
                                        <p class="mt-1 text-xs text-gray-500">
                                            Field ID: {{ $field->id }}, Type: {{ $field->type }}, 
                                            Value: {{ is_array($formData[$field->id] ?? null) ? json_encode($formData[$field->id]) : ($formData[$field->id] ?? 'null') }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showFormModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                <span wire:loading.remove>Submit Form</span>
                                <span wire:loading>Submitting...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
