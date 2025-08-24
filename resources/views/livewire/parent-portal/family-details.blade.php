<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $family->name }}</h1>
                <p class="text-gray-600">Family Management Dashboard</p>
            </div>
            <a href="{{ route('dashboard.parent.families') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Families</span>
            </a>
        </div>

        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="setTab('overview')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Overview
                </button>
                <button wire:click="setTab('insurance')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'insurance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Insurance
                </button>
                <button wire:click="setTab('congregation')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'congregation' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Home Congregation
                </button>
                <button wire:click="setTab('attachments')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'attachments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Attachments
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-6">
            @if($activeTab === 'overview')
                <div class="space-y-6">
                    <!-- Family Information -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Family Information</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Family Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $family->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($family->phone)
                                            <a href="tel:{{ $family->phone }}" class="text-blue-600 hover:text-blue-800">{{ $family->phone }}</a>
                                        @else
                                            Not provided
                                        @endif
                                    </dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
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
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Emergency Contact</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $family->emergency_contact_name ?: 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($family->emergency_contact_phone)
                                            <a href="tel:{{ $family->emergency_contact_phone }}" class="text-blue-600 hover:text-blue-800">{{ $family->emergency_contact_phone }}</a>
                                        @else
                                            Not provided
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Relationship</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $family->emergency_contact_relationship ?: 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Family Members -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Family Members</h3>
                        </div>
                        <div class="px-6 py-4">
                            @if($family->users->count() > 0)
                                <div class="space-y-3">
                                    @foreach($family->users as $user)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                        <span class="text-white text-sm font-medium">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($user->pivot->role) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">No family members found.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Campers -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Campers</h3>
                        </div>
                        <div class="px-6 py-4">
                            @if($family->campers->count() > 0)
                                <div class="space-y-3">
                                    @foreach($family->campers as $camper)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                        <span class="text-white text-sm font-medium">{{ substr($camper->first_name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $camper->first_name }} {{ $camper->last_name }}</p>
                                                    <p class="text-sm text-gray-500">Age: {{ $camper->age }} | Grade: {{ $camper->grade }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('dashboard.parent.campers') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                View Details
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">No campers found.</p>
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
