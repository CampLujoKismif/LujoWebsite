<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $family->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Family Management Dashboard</p>
            </div>
            <a href="{{ route('dashboard.parent.families') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Families</span>
            </a>
        </div>

        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="setTab('overview')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    Overview
                </button>
                <button wire:click="setTab('insurance')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'insurance' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    Insurance
                </button>
                <button wire:click="setTab('congregation')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'congregation' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    Home Congregation
                </button>
                <button wire:click="setTab('attachments')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'attachments' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    Attachments
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-6">
            @if($activeTab === 'overview')
                <div class="space-y-6">
                    <!-- Family Information -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Family Information</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Family Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $family->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        @if($family->phone)
                                            <a href="tel:{{ $family->phone }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ $family->phone }}</a>
                                        @else
                                            Not provided
                                        @endif
                                    </dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        @if($family->address)
                                            {{ $family->address }}<br>
                                            {{ $family->city }}, {{ $family->state }} {{ $family->zip_code }}
                                        @else
                                            Not provided
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Emergency Contact</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $family->emergency_contact_name ?: 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        @if($family->emergency_contact_phone)
                                            <a href="tel:{{ $family->emergency_contact_phone }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ $family->emergency_contact_phone }}</a>
                                        @else
                                            Not provided
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Relationship</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $family->emergency_contact_relationship ?: 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Family Members -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Family Members</h3>
                        </div>
                        <div class="px-6 py-4">
                            @if($family->users->count() > 0)
                                <div class="space-y-3">
                                    @foreach($family->users as $user)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                        <span class="text-white text-sm font-medium">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                {{ ucfirst($user->pivot->role) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No family members found.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Campers -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Campers</h3>
                        </div>
                        <div class="px-6 py-4">
                            @if($family->campers->count() > 0)
                                <div class="space-y-3">
                                    @foreach($family->campers as $camper)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                        <span class="text-white text-sm font-medium">{{ substr($camper->first_name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $camper->first_name }} {{ $camper->last_name }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Age: {{ $camper->age }} | Grade: {{ $camper->grade }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('dashboard.parent.campers') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                                View Details
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No campers found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($activeTab === 'insurance')
                @livewire('parent-portal.family-insurance-info', ['family' => $family])
            @elseif($activeTab === 'congregation')
                @livewire('parent-portal.family-congregation-info', ['family' => $family])
            @elseif($activeTab === 'attachments')
                @livewire('parent-portal.family-attachments', ['family' => $family])
            @endif
        </div>
    </div>
</div>
