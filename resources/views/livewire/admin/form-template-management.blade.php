<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Form Template Management</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Create and manage form templates for camp registration and information collection</p>
        </div>

        <!-- Create Template Button -->
        <div class="mb-6">
            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Template
            </button>
        </div>

        <!-- Templates List -->
        @if($templates->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No form templates found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first form template.</p>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Form Templates</h3>
                        <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Template
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Template</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Scope</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fields</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($templates as $template)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $template->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $template->description }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($template->scope === 'global') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                                @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 @endif">
                                                {{ ucfirst($template->scope) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $template->fields->count() }} fields
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($template->is_active) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                                @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
                                                {{ $template->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button wire:click="openFieldsModal({{ $template->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Fields
                                                </button>
                                                <button wire:click="openEditModal({{ $template->id }})" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                    Edit
                                                </button>
                                                <button wire:click="toggleTemplateStatus({{ $template->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    {{ $template->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                                <button wire:click="deleteTemplate({{ $template->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Create Template Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Create New Form Template</h3>
                    <form wire:submit.prevent="createTemplate">
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Template Name</label>
                                <input type="text" wire:model="name" id="name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="scope" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Scope</label>
                                <select wire:model="scope" id="scope" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    <option value="global">Global (All Sessions)</option>
                                    <option value="camp_session">Session Specific</option>
                                </select>
                                @error('scope') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            @if($scope === 'camp_session')
                                <div>
                                    <label for="campInstanceId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Camp Session</label>
                                    <select wire:model="campInstanceId" id="campInstanceId" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                        <option value="">Select Camp Session</option>
                                        @foreach($campInstances as $instance)
                                            <option value="{{ $instance->id }}">{{ $instance->camp->display_name }} {{ $instance->year }}</option>
                                        @endforeach
                                    </select>
                                    @error('campInstanceId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="sortOrder" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                                    <input type="number" wire:model="sortOrder" id="sortOrder" min="0" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    @error('sortOrder') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex items-center">
                                    <input wire:model="isActive" type="checkbox" id="isActive" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="isActive" class="ml-2 block text-sm text-gray-900 dark:text-white">Active</label>
                                </div>
                                <div class="flex items-center">
                                    <input wire:model="requiresAnnualCompletion" type="checkbox" id="requiresAnnualCompletion" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="requiresAnnualCompletion" class="ml-2 block text-sm text-gray-900 dark:text-white">Annual Completion Required</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Create Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Template Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit Form Template</h3>
                    <form wire:submit.prevent="updateTemplate">
                        <div class="space-y-4">
                            <div>
                                <label for="editName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Template Name</label>
                                <input type="text" wire:model="name" id="editName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea wire:model="description" id="editDescription" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editScope" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Scope</label>
                                <select wire:model="scope" id="editScope" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    <option value="global">Global (All Sessions)</option>
                                    <option value="camp_session">Session Specific</option>
                                </select>
                                @error('scope') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            @if($scope === 'camp_session')
                                <div>
                                    <label for="editCampInstanceId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Camp Session</label>
                                    <select wire:model="campInstanceId" id="editCampInstanceId" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                        <option value="">Select Camp Session</option>
                                        @foreach($campInstances as $instance)
                                            <option value="{{ $instance->id }}">{{ $instance->camp->display_name }} {{ $instance->year }}</option>
                                        @endforeach
                                    </select>
                                    @error('campInstanceId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="editSortOrder" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                                    <input type="number" wire:model="sortOrder" id="editSortOrder" min="0" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    @error('sortOrder') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex items-center">
                                    <input wire:model="isActive" type="checkbox" id="editIsActive" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="editIsActive" class="ml-2 block text-sm text-gray-900 dark:text-white">Active</label>
                                </div>
                                <div class="flex items-center">
                                    <input wire:model="requiresAnnualCompletion" type="checkbox" id="editRequiresAnnualCompletion" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="editRequiresAnnualCompletion" class="ml-2 block text-sm text-gray-900 dark:text-white">Annual Completion Required</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Update Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Manage Fields Modal -->
    @if($showFieldsModal && $selectedTemplate)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                            <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Manage Fields - {{ $selectedTemplate->name }}</h3>
                        <button wire:click="$set('showFieldsModal', false)" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Add/Edit Field Form -->
                        <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">{{ $editingField ? 'Edit Field' : 'Add New Field' }}</h4>
                            <form wire:submit.prevent="{{ $editingField ? 'updateField' : 'addField' }}">
                                <div class="space-y-4">
                                    <div>
                                        <label for="fieldType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Field Type</label>
                                        <select wire:model="fieldType" id="fieldType" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                            <option value="text">Text Input</option>
                                            <option value="textarea">Text Area</option>
                                            <option value="email">Email</option>
                                            <option value="number">Number</option>
                                            <option value="select">Dropdown</option>
                                            <option value="checkbox">Checkbox</option>
                                            <option value="radio">Radio Buttons</option>
                                            <option value="date">Date</option>
                                            <option value="file">File Upload</option>
                                        </select>
                                        @error('fieldType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="fieldLabel" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Field Label</label>
                                        <input type="text" wire:model="fieldLabel" id="fieldLabel" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                        @error('fieldLabel') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="flex items-center">
                                            <input wire:model="fieldRequired" type="checkbox" id="fieldRequired" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="fieldRequired" class="ml-2 block text-sm text-gray-900 dark:text-white">Required</label>
                                        </div>
                                        <div>
                                            <label for="fieldSort" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                                            <input type="number" wire:model="fieldSort" id="fieldSort" min="0" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="fieldHelpText" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Help Text</label>
                                        <input type="text" wire:model="fieldHelpText" id="fieldHelpText" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                        @error('fieldHelpText') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="fieldValidationRules" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Validation Rules</label>
                                        <input type="text" wire:model="fieldValidationRules" id="fieldValidationRules" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white" placeholder="e.g., min:3|max:255">
                                        @error('fieldValidationRules') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    @if(in_array($fieldType, ['select', 'checkbox', 'radio']))
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options</label>
                                            @foreach($fieldOptions as $index => $option)
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <input type="text" wire:model="fieldOptions.{{ $index }}" class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white" placeholder="Option {{ $index + 1 }}">
                                                    <button type="button" wire:click="removeFieldOption({{ $index }})" class="text-red-600 hover:text-red-900">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                            <button type="button" wire:click="addFieldOption" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">+ Add Option</button>
                                        </div>
                                    @endif

                                    <div class="flex space-x-3">
                                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                            {{ $editingField ? 'Update Field' : 'Add Field' }}
                                        </button>
                                        @if($editingField)
                                            <button type="button" wire:click="resetFieldForm" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                                Cancel Edit
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Fields List -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Current Fields</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Drag the handle to reorder fields</p>
                            @if($selectedTemplate->fields->isEmpty())
                                <div class="text-center py-6">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No fields added yet.</p>
                                </div>
                            @else
                                <div id="fields-container" class="space-y-2" wire:ignore>
                                    @foreach($selectedTemplate->fields->sortBy('sort') as $field)
                                        <div class="field-item flex items-center justify-between p-3 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-gray-700 rounded-lg cursor-move hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors" data-field-id="{{ $field->id }}">
                                            <div class="flex items-center space-x-3">
                                                <div class="drag-handle text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h8M8 15h8M8 12h8"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $field->label }}</h5>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($field->type) }} @if($field->required)(Required)@endif</p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button wire:click="editField({{ $field->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button wire:click="deleteField({{ $field->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" onclick="return confirm('Are you sure you want to delete this field?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Drag and Drop JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let dragSrcEl = null;
            let dragSrcIndex = null;

            function handleDragStart(e) {
                // Only allow dragging if clicking on the drag handle
                if (!e.target.closest('.drag-handle') && !e.target.classList.contains('drag-handle')) {
                    e.preventDefault();
                    return false;
                }
                
                // Prevent dragging if clicking on buttons
                if (e.target.closest('button')) {
                    e.preventDefault();
                    return false;
                }
                
                dragSrcEl = this;
                dragSrcIndex = Array.from(this.parentNode.children).indexOf(this);
                this.style.opacity = '0.4';
                this.classList.add('dragging');
            }

            function handleDragEnd(e) {
                this.style.opacity = '1';
                this.classList.remove('dragging');
            }

            function handleDragOver(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
                return false;
            }

            function handleDragEnter(e) {
                this.classList.add('over');
            }

            function handleDragLeave(e) {
                this.classList.remove('over');
            }

            function handleDrop(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }

                if (dragSrcEl !== this) {
                    const container = document.getElementById('fields-container');
                    const items = Array.from(container.children);
                    const dragSrcIndex = items.indexOf(dragSrcEl);
                    const dropIndex = items.indexOf(this);

                    // Reorder the DOM
                    if (dragSrcIndex < dropIndex) {
                        this.parentNode.insertBefore(dragSrcEl, this.nextSibling);
                    } else {
                        this.parentNode.insertBefore(dragSrcEl, this);
                    }

                    // Get the new order of field IDs
                    const newOrder = Array.from(container.children).map(item => 
                        parseInt(item.getAttribute('data-field-id'))
                    );

                    // Call Livewire method to update the order
                    @this.reorderFields(newOrder);
                }

                return false;
            }

            function handleDragDrop(e) {
                this.classList.remove('over');
            }

            function initializeDragAndDrop() {
                const container = document.getElementById('fields-container');
                if (!container) return;

                const items = container.querySelectorAll('.field-item');
                
                items.forEach(function(item) {
                    item.setAttribute('draggable', 'true');
                    item.addEventListener('dragstart', handleDragStart, false);
                    item.addEventListener('dragend', handleDragEnd, false);
                    item.addEventListener('dragover', handleDragOver, false);
                    item.addEventListener('dragenter', handleDragEnter, false);
                    item.addEventListener('dragleave', handleDragLeave, false);
                    item.addEventListener('drop', handleDrop, false);
                    item.addEventListener('dragdrop', handleDragDrop, false);
                });
            }

            // Initialize on page load
            initializeDragAndDrop();

            // Re-initialize when Livewire updates the DOM
            document.addEventListener('livewire:load', function() {
                Livewire.hook('message.processed', (message, component) => {
                    if (component.fingerprint.name === 'admin.form-template-management') {
                        setTimeout(initializeDragAndDrop, 100);
                    }
                });
            });
        });
    </script>

    <style>
        .field-item.dragging {
            transform: rotate(5deg);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .field-item.over {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .dark .field-item.over {
            background-color: #1e3a8a;
        }

        .drag-handle {
            cursor: grab;
            transition: all 0.2s ease;
        }

        .drag-handle:hover {
            background-color: #f3f4f6;
            transform: scale(1.1);
        }

        .dark .drag-handle:hover {
            background-color: #374151;
        }

        .drag-handle:active {
            cursor: grabbing;
            transform: scale(0.95);
        }
    </style>
</div>
