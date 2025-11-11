<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Enrollment Management</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage camper enrollments and track payments</p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-800 shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Filters</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model.live="statusFilter" id="statusFilter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="waitlisted">Waitlisted</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <!-- Camp Instance Filter -->
                    <div>
                        <label for="campInstanceFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Camp Session</label>
                        <select wire:model.live="campInstanceFilter" id="campInstanceFilter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                            <option value="">All Sessions</option>
                            @foreach($this->campInstances as $instance)
                                <option value="{{ $instance->id }}">{{ $instance->camp->display_name }} {{ $instance->year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Balance Filter -->
                    <div>
                        <label for="balanceFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                        <select wire:model.live="balanceFilter" id="balanceFilter" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                            <option value="">All Payments</option>
                            <option value="has_balance">Has Balance</option>
                            <option value="paid_in_full">Paid in Full</option>
                            <option value="no_payment">No Payment</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label for="searchTerm" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="searchTerm" id="searchTerm" placeholder="Camper or family name..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollments Table -->
        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Camper</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Family</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Camp Session</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Balance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Enrolled</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @php $currentYear = config('annual_forms.default_year') ?? now()->year; @endphp
                            @forelse($this->enrollments as $enrollment)
                                @php $camperGrade = $enrollment->camper->gradeForYear($currentYear); @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $enrollment->camper->full_name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Grade {{ $camperGrade ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $enrollment->camper->family->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $enrollment->campInstance->camp->display_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $enrollment->campInstance->year }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusColor($enrollment->status) }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">${{ number_format($enrollment->balance_cents / 100, 2) }}</div>
                                        <div class="text-sm {{ $this->getBalanceStatusColor($enrollment) }}">
                                            {{ $this->getBalanceStatus($enrollment) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M j, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button wire:click="openDetailsModal({{ $enrollment->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Details</button>
                                            <button wire:click="openStatusModal({{ $enrollment->id }})" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300">Status</button>
                                            <button wire:click="openPaymentModal({{ $enrollment->id }})" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Payment</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No enrollments found matching your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $this->enrollments->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Details Modal -->
    @if($showDetailsModal && $selectedEnrollment)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border border-gray-300 dark:border-zinc-700 w-full max-w-4xl shadow-lg rounded-md bg-white dark:bg-zinc-800">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Enrollment Details</h3>
                        <button wire:click="$set('showDetailsModal', false)" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Camper Information -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Camper Information</h4>
                            <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-4">
                                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                                    <div><span class="font-medium">Name:</span> {{ $selectedEnrollment->camper->full_name }}</div>
                                    @php
                                        $detailsYear = $currentYear ?? (config('annual_forms.default_year') ?? now()->year);
                                        $selectedCamperGrade = $selectedEnrollment->camper->gradeForYear($detailsYear);
                                    @endphp
                                    <div><span class="font-medium">Grade:</span> {{ $selectedCamperGrade ?? '—' }}</div>
                                    <div><span class="font-medium">School:</span> {{ $selectedEnrollment->camper->school }}</div>
                                    <div><span class="font-medium">Family:</span> {{ $selectedEnrollment->camper->family->name }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Camp Information -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Camp Information</h4>
                            <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-4">
                                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                                    <div><span class="font-medium">Camp:</span> {{ $selectedEnrollment->campInstance->camp->display_name }}</div>
                                    <div><span class="font-medium">Year:</span> {{ $selectedEnrollment->campInstance->year }}</div>
                                    <div><span class="font-medium">Status:</span> 
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusColor($selectedEnrollment->status) }}">
                                            {{ ucfirst($selectedEnrollment->status) }}
                                        </span>
                                    </div>
                                    <div><span class="font-medium">Enrolled:</span> {{ $selectedEnrollment->enrolled_at ? $selectedEnrollment->enrolled_at->format('M j, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Payment Information</h4>
                            <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-4">
                                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                                    <div><span class="font-medium">Total Balance:</span> ${{ number_format($selectedEnrollment->balance_cents / 100, 2) }}</div>
                                    <div><span class="font-medium">Amount Paid:</span> ${{ number_format($selectedEnrollment->amount_paid_cents / 100, 2) }}</div>
                                    <div><span class="font-medium">Outstanding:</span> ${{ number_format($selectedEnrollment->outstanding_balance, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Completion -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Form Completion</h4>
                            <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-4">
                                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                                    <div><span class="font-medium">Forms Complete:</span> 
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedEnrollment->forms_complete ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                            {{ $selectedEnrollment->forms_complete ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                    <div><span class="font-medium">Forms Submitted:</span> {{ $selectedEnrollment->formResponses->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($selectedEnrollment->notes)
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Notes</h4>
                            <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-4">
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $selectedEnrollment->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end">
                        <button wire:click="$set('showDetailsModal', false)" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Update Modal -->
    @if($showStatusModal && $selectedEnrollment)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border border-gray-300 dark:border-zinc-700 w-full max-w-md shadow-lg rounded-md bg-white dark:bg-zinc-800">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Update Enrollment Status</h3>
                    <form wire:submit.prevent="updateStatus">
                        <div class="space-y-4">
                            <div>
                                <label for="newStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Status</label>
                                <select wire:model="newStatus" id="newStatus" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="waitlisted">Waitlisted</option>
                                    <option value="cancelled">Cancelled</option>

                                </select>
                                @error('newStatus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="statusNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                                <textarea wire:model="statusNotes" id="statusNotes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"></textarea>
                                @error('statusNotes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showStatusModal', false)" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Modal -->
    @if($showPaymentModal && $selectedEnrollment)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border border-gray-300 dark:border-zinc-700 w-full max-w-md shadow-lg rounded-md bg-white dark:bg-zinc-800">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Record Payment</h3>
                    <form wire:submit.prevent="recordPayment">
                        <div class="space-y-4">
                            <div>
                                <label for="paymentAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" wire:model="paymentAmount" id="paymentAmount" step="0.01" min="0.01" class="pl-7 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                </div>
                                @error('paymentAmount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="paymentMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                                <select wire:model="paymentMethod" id="paymentMethod" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="online">Online</option>
                                </select>
                                @error('paymentMethod') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="paymentReference" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference (Optional)</label>
                                <input type="text" wire:model="paymentReference" id="paymentReference" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('paymentReference') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="paymentNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                                <textarea wire:model="paymentNotes" id="paymentNotes" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"></textarea>
                                @error('paymentNotes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showPaymentModal', false)" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                                Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
