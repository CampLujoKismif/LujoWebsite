<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Error Log Viewer</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View and manage application error logs
                </p>
            </div>
            <div class="flex space-x-3">
                <flux:button variant="outline" wire:click="refreshLogs" icon="arrow-path">
                    Refresh
                </flux:button>
                <flux:button variant="danger" wire:click="clearLogs" wire:confirm="Are you sure you want to clear all logs? This action cannot be undone." icon="trash">
                    Clear Logs
                </flux:button>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:callout variant="success" class="mb-6">
            {{ session('message') }}
        </flux:callout>
    @endif

    @if (session()->has('error'))
        <flux:callout variant="danger" class="mb-6">
            {{ session('error') }}
        </flux:callout>
    @endif

    <!-- Filters -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <flux:field>
                    <flux:label>Log Level</flux:label>
                    <flux:select wire:model.live="logLevel">
                        <flux:select.option value="all">All Levels</flux:select.option>
                        <flux:select.option value="emergency">Emergency</flux:select.option>
                        <flux:select.option value="alert">Alert</flux:select.option>
                        <flux:select.option value="critical">Critical</flux:select.option>
                        <flux:select.option value="error">Error</flux:select.option>
                        <flux:select.option value="warning">Warning</flux:select.option>
                        <flux:select.option value="notice">Notice</flux:select.option>
                        <flux:select.option value="info">Info</flux:select.option>
                        <flux:select.option value="debug">Debug</flux:select.option>
                    </flux:select>
                </flux:field>
            </div>
            <div>
                <flux:field>
                    <flux:label>Search</flux:label>
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search log entries..." />
                </flux:field>
            </div>
            <div>
                <flux:field>
                    <flux:label>Lines Per Page</flux:label>
                    <flux:select wire:model.live="linesPerPage">
                        <flux:select.option value="25">25</flux:select.option>
                        <flux:select.option value="50">50</flux:select.option>
                        <flux:select.option value="100">100</flux:select.option>
                        <flux:select.option value="200">200</flux:select.option>
                    </flux:select>
                </flux:field>
            </div>
        </div>
    </div>

    <!-- Log Statistics -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <flux:icon.exclamation-triangle class="h-8 w-8 text-red-500" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Entries</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalLines) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <flux:icon.document-text class="h-8 w-8 text-blue-500" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">File Size</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $logFileSize > 0 ? number_format($logFileSize / 1024, 2) . ' KB' : '0 KB' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <flux:icon.clock class="h-8 w-8 text-green-500" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Modified</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $lastModified ? date('M j, Y H:i:s', $lastModified) : 'Never' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <flux:icon.cog-6-tooth class="h-8 w-8 text-purple-500" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lines Per Page</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $linesPerPage }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Entries -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Log Entries</h3>
        </div>
        
        @if (empty($logEntries))
            <div class="p-6 text-center">
                <flux:icon.document-text class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No log entries found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if (!empty($search))
                        No entries match your search criteria.
                    @else
                        No log entries available for the selected level.
                    @endif
                </p>
            </div>
        @else
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($logEntries as $index => $entry)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                @php
                                    $logLevel = 'info';
                                    if (strpos($entry, '] EMERGENCY:') !== false) $logLevel = 'emergency';
                                    elseif (strpos($entry, '] ALERT:') !== false) $logLevel = 'alert';
                                    elseif (strpos($entry, '] CRITICAL:') !== false) $logLevel = 'critical';
                                    elseif (strpos($entry, '] ERROR:') !== false) $logLevel = 'error';
                                    elseif (strpos($entry, '] WARNING:') !== false) $logLevel = 'warning';
                                    elseif (strpos($entry, '] NOTICE:') !== false) $logLevel = 'notice';
                                    elseif (strpos($entry, '] INFO:') !== false) $logLevel = 'info';
                                    elseif (strpos($entry, '] DEBUG:') !== false) $logLevel = 'debug';
                                @endphp
                                
                                @switch($logLevel)
                                    @case('emergency')
                                        <flux:icon.exclamation-triangle class="h-5 w-5 text-red-600" />
                                        @break
                                    @case('alert')
                                        <flux:icon.exclamation-triangle class="h-5 w-5 text-red-500" />
                                        @break
                                    @case('critical')
                                        <flux:icon.exclamation-triangle class="h-5 w-5 text-red-500" />
                                        @break
                                    @case('error')
                                        <flux:icon.x-circle class="h-5 w-5 text-red-500" />
                                        @break
                                    @case('warning')
                                        <flux:icon.exclamation-triangle class="h-5 w-5 text-yellow-500" />
                                        @break
                                    @case('notice')
                                        <flux:icon.information-circle class="h-5 w-5 text-blue-500" />
                                        @break
                                    @case('info')
                                        <flux:icon.information-circle class="h-5 w-5 text-blue-500" />
                                        @break
                                    @case('debug')
                                        <flux:icon.bug-ant class="h-5 w-5 text-gray-500" />
                                        @break
                                    @default
                                        <flux:icon.information-circle class="h-5 w-5 text-gray-500" />
                                @endswitch
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-mono text-gray-900 dark:text-white break-all">
                                    {{ $entry }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if ($totalLines > $linesPerPage)
        <div class="mt-6">
            {{-- Custom pagination since we're not using Eloquent models --}}
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Showing {{ (($currentPage - 1) * $linesPerPage) + 1 }} to {{ min($currentPage * $linesPerPage, $totalLines) }} of {{ $totalLines }} entries
                </div>
                <div class="flex space-x-2">
                    @if ($currentPage > 1)
                        <flux:button variant="outline" wire:click="previousPage" icon="chevron-left">
                            Previous
                        </flux:button>
                    @endif
                    
                    @if ($currentPage * $linesPerPage < $totalLines)
                        <flux:button variant="outline" wire:click="nextPage" icon="chevron-right">
                            Next
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
