<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Camp Sessions</h1>
                <p class="text-xl md:text-2xl max-w-3xl mx-auto">
                    Discover all our active camp sessions and find the perfect one for your child
                </p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="searchTerm"
                        placeholder="Search camps..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                <!-- Grade Filter -->
                <div>
                    <label for="gradeFilter" class="block text-sm font-medium text-gray-700 mb-1">Grade Level</label>
                    <select 
                        wire:model.live="gradeFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">All Grades</option>
                        <option value="elementary">Elementary (1st-5th)</option>
                        <option value="middle">Middle School (6th-8th)</option>
                        <option value="high">High School (9th-12th)</option>
                    </select>
                </div>

                <!-- Price Filter -->
                <div>
                    <label for="priceFilter" class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                    <select 
                        wire:model.live="priceFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">All Prices</option>
                        <option value="free">Free</option>
                        <option value="low">$1 - $100</option>
                        <option value="medium">$101 - $200</option>
                        <option value="high">$201+</option>
                    </select>
                </div>

                <!-- Results Count -->
                <div class="flex items-end">
                    <div class="text-sm text-gray-600">
                        {{ $this->campInstances->count() }} session{{ $this->campInstances->count() !== 1 ? 's' : '' }} found
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($this->campInstances->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($this->campInstances as $instance)
                    @php
                        // Generate a random gradient class
                        $gradientClasses = [
                            'from-blue-500 to-blue-600',
                            'from-green-500 to-green-600',
                            'from-purple-500 to-purple-600',
                            'from-yellow-500 to-yellow-600',
                            'from-red-500 to-red-600',
                            'from-indigo-500 to-indigo-600',
                            'from-pink-500 to-pink-600',
                            'from-teal-500 to-teal-600',
                            'from-orange-500 to-orange-600'
                        ];
                        $gradientClass = $gradientClasses[$loop->index % count($gradientClasses)];
                    @endphp
                    
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Header with gradient -->
                        <div class="bg-gradient-to-r {{ $gradientClass }} text-white p-6">
                            <h3 class="text-2xl font-bold mb-2">{{ $instance->camp->display_name }}</h3>
                            
                            <!-- Grade/Age Range -->
                            @if($instance->grade_from && $instance->grade_to)
                                <span class="inline-block bg-white px-3 py-1 rounded-full text-sm font-medium text-gray-900">
                                    {{ $instance->grade_from }}{{ $this->getGradeSuffix($instance->grade_from) }} Grade
                                    @if($instance->grade_from != $instance->grade_to)
                                        - {{ $instance->grade_to }}{{ $this->getGradeSuffix($instance->grade_to) }} Grade
                                    @endif
                                </span>
                            @elseif($instance->age_from && $instance->age_to)
                                <span class="inline-block bg-white px-3 py-1 rounded-full text-sm font-medium text-gray-900">
                                    Ages {{ $instance->age_from }} - {{ $instance->age_to }}
                                </span>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Dates -->
                            <div class="mb-4">
                                <div class="flex items-center text-gray-600 mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium">Dates</span>
                                </div>
                                <div class="text-lg font-semibold text-gray-900">
                                    @if($instance->start_date && $instance->end_date)
                                        {{ $instance->start_date->format('M j') }} - {{ $instance->end_date->format('M j, Y') }}
                                    @elseif($instance->start_date)
                                        {{ $instance->start_date->format('M j, Y') }}
                                    @else
                                        TBD
                                    @endif
                                </div>
                            </div>

                            <!-- Price -->
                            @if($instance->price)
                                <div class="mb-4">
                                    <div class="flex items-center text-gray-600 mb-2">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span class="font-medium">Price</span>
                                    </div>
                                    <div class="text-3xl font-bold text-gray-900">
                                        ${{ number_format($instance->price, 0) }}
                                    </div>
                                    <div class="text-sm text-gray-600">per camper</div>
                                </div>
                            @endif

                            <!-- Description -->
                            @if($instance->description)
                                <div class="mb-4">
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ Str::limit($instance->description, 150) }}
                                    </p>
                                </div>
                            @endif

                            <!-- Registration Status -->
                            <div class="mb-4">
                                @if($instance->isRegistrationOpen())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Registration Open
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        Registration Closed
                                    </span>
                                @endif
                            </div>

                            <!-- Capacity Info -->
                            @if($instance->max_capacity)
                                <div class="mb-4 text-sm text-gray-600">
                                    <span class="font-medium">{{ $instance->available_spots }}</span> spots available
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                @if($instance->theme_description)
                                    <a href="{{ route('camp-sessions.show', $instance) }}" 
                                       class="flex-1 bg-blue-100 hover:bg-blue-200 text-gray-900 text-center py-2 px-4 rounded-md font-medium transition duration-300">
                                        Learn More
                                    </a>
                                @endif
                                
                                @if($instance->isRegistrationOpen())
                                    <a href="{{ route('login') }}" 
                                       class="flex-1 bg-green-100 hover:bg-green-200 text-gray-900 text-center py-2 px-4 rounded-md font-medium transition duration-300">
                                        Register Now
                                    </a>
                                @else
                                    <button disabled 
                                            class="flex-1 bg-gray-200 text-gray-600 text-center py-2 px-4 rounded-md font-medium cursor-not-allowed">
                                        Registration Closed
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No camp sessions found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Try adjusting your search criteria or check back later for new sessions.
                </p>
                <div class="mt-6">
                    <button wire:click="$set('searchTerm', '')" 
                            wire:click="$set('gradeFilter', '')" 
                            wire:click="$set('priceFilter', '')"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Clear Filters
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
