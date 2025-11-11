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
                    
                    @php
                        $sessionData = [
                            'id' => $instance->id,
                            'campName' => $instance->camp->display_name,
                            'gradeFrom' => $instance->grade_from,
                            'gradeTo' => $instance->grade_to,
                            'ageFrom' => $instance->age_from,
                            'ageTo' => $instance->age_to,
                            'startDate' => optional($instance->start_date)->toIso8601String(),
                            'endDate' => optional($instance->end_date)->toIso8601String(),
                            'price' => $instance->price,
                            'description' => $instance->description,
                            'isRegistrationOpen' => $instance->isRegistrationOpen(),
                            'maxCapacity' => $instance->max_capacity,
                            'availableSpots' => $instance->available_spots,
                            'themeDescription' => $instance->theme_description,
                            'detailsUrl' => $instance->theme_description ? route('camp-sessions.show', $instance) : null,
                        ];
                    @endphp

                    <div
                        data-vue-component="CampSessionCard"
                        data-props='@json([
                            "session" => $sessionData,
                            "gradientClass" => $gradientClass,
                        ])'
                    ></div>
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

    <!-- Registration Modal -->
    <div x-data="{ showModal: false, campInstanceId: null }"
         x-init="
            showModal = false;
            window.openCampRegistrationModal = function(instanceId) {
                campInstanceId = instanceId;
                showModal = true;
                $nextTick(() => {
                    setTimeout(() => {
                        if (typeof mountVueComponents === 'function') {
                            mountVueComponents();
                        }
                        window.dispatchEvent(new Event('modal-rendered'));
                    }, 100);
                });
            };
            window.closeCampRegistrationModal = function() {
                showModal = false;
                campInstanceId = null;
            };
         ">
        <template x-if="showModal && campInstanceId">
            <div x-show="showModal" 
                 x-cloak
                 data-vue-component="CampRegistrationModal"
                 :data-props="JSON.stringify({ campInstanceId: campInstanceId, show: showModal })">
            </div>
        </template>
    </div>
</div>

<script>
function openRegistrationModal(instanceId) {
    if (window.openCampRegistrationModal) {
        window.openCampRegistrationModal(instanceId);
    }
}
</script>
