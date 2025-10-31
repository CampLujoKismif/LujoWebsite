<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Email Template Management</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Customize email templates sent to admins and customers
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <div wire:ignore.self>
        @if ($message)
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-lg">
                {{ $message }}
            </div>
        @endif

        @if ($error)
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-lg">
                {{ $error }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Template List -->
        <div class="lg:col-span-1">
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white">Email Templates</h3>
                </div>
                <div class="divide-y divide-zinc-200 dark:divide-zinc-700 max-h-[600px] overflow-y-auto">
                    @foreach($templates as $template)
                        <button
                            wire:click="selectTemplate('{{ $template['path'] }}')"
                            class="w-full px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors
                                {{ $selectedTemplate === $template['path'] ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-600' : '' }}"
                        >
                            <div class="font-medium text-zinc-900 dark:text-white">{{ $template['name'] }}</div>
                            <div class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $template['description'] }}</div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Editor -->
        <div class="lg:col-span-2">
            @if($selectedTemplate)
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-zinc-900 dark:text-white">{{ $this->getTemplateName() }}</h3>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $this->getTemplateDescription() }}</p>
                        </div>
                        <button 
                            wire:click="resetEditor"
                            class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg"
                        >
                            Close
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">
                                Template Content (Blade + HTML)
                            </label>
                            <textarea
                                wire:model="templateContent"
                                rows="20"
                                class="block w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-4 py-3 text-sm text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:border-blue-600 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-400 font-mono"
                                placeholder="Email template content..."
                            ></textarea>
                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                This is a Blade template with HTML and CSS styling. Use {{ '$variable' }} syntax for dynamic content.
                            </p>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button 
                                wire:click="previewTemplate"
                                class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg"
                            >
                                Preview
                            </button>
                            <button 
                                wire:click="resetEditor"
                                class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg"
                            >
                                Cancel
                            </button>
                            <button 
                                wire:click="saveTemplate"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg disabled:opacity-50"
                            >
                                <span wire:loading.remove>Save Template</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-white">No template selected</h3>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Select a template from the list to begin editing</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Template Variables Help -->
    @if($selectedTemplate)
        <div class="mt-8 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <h4 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Available Variables</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h5 class="font-medium text-zinc-900 dark:text-white mb-2">Rental Variables</h5>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1 rounded">{{ '$reservation' }}</code> - Reservation object</li>
                        <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1 rounded">{{ '$contact_name' }}</code> - Customer name</li>
                        <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1 rounded">{{ '$contact_email' }}</code> - Customer email</li>
                        <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1 rounded">{{ '$total_amount' }}</code> - Total amount</li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-medium text-zinc-900 dark:text-white mb-2">General Variables</h5>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1 rounded">config('app.name')</code> - App name</li>
                        <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1 rounded">config('app.url')</code> - App URL</li>
                        <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1 rounded">now()</code> - Current datetime</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Preview Modal -->
    @if($showPreview)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data="{ htmlContent: @entangle('previewHtml') }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-zinc-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closePreview"></div>

                <!-- Center the modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-zinc-900 px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-zinc-900 dark:text-white">Email Preview</h3>
                            <button 
                                wire:click="closePreview"
                                class="text-zinc-400 hover:text-zinc-500"
                            >
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-auto" style="max-height: 70vh;">
                            <iframe 
                                x-bind:srcdoc="htmlContent"
                                style="width: 100%; min-height: 400px; border: none;"
                            ></iframe>
                        </div>
                    </div>
                    <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            wire:click="closePreview"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
