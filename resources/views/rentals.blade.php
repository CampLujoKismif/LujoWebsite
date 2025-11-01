<x-layouts.public title="Rent Our Facilities">
    @push('head')
        <!-- Stripe.js -->
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            window.stripePublishableKey = '{{ config('services.stripe.key') }}';
        </script>
    @endpush

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-900 to-blue-700 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                Rent Our Facilities
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto">
                Host your next event at Camp LUJO-KISMIF. Perfect for retreats, conferences, family reunions, and more.
            </p>
        </div>
    </section>

    <!-- Main Content Section with Side-by-Side Layout -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Left Column: Features and Pricing -->
                <div class="space-y-12">
                    <!-- Features -->
                    <div>
                        <div class="mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">What We Offer</h2>
                            <p class="text-lg text-gray-600">Perfect facilities for your next event</p>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <div class="text-blue-600 text-4xl mb-4">🏕️</div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Accommodations</h3>
                                <p class="text-gray-600">Comfortable dormitory-style lodging for groups of all sizes.</p>
                            </div>
                            
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <div class="text-blue-600 text-4xl mb-4">🍽️</div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Dining Facilities</h3>
                                <p class="text-gray-600">Full kitchen and dining hall to accommodate your group's needs.</p>
                            </div>
                            
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <div class="text-blue-600 text-4xl mb-4">🎯</div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Recreation</h3>
                                <p class="text-gray-600">Outdoor activities, meeting spaces, and recreational facilities.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div>
                        <div class="mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">Simple Pricing</h2>
                            <p class="text-lg text-gray-600">Transparent pricing with no hidden fees</p>
                        </div>
                        
                        <div class="bg-blue-50 p-8 rounded-lg">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-blue-600 mb-2">$20</div>
                                <div class="text-xl text-gray-700 mb-4">per person per day</div>
                                <p class="text-gray-600">Includes access to all facilities</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Rental Calendar -->
                <div>
                    <div class="mb-6 sm:mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 sm:mb-4">Check Availability</h2>
                        <p class="text-base sm:text-lg text-gray-600">Select your dates to see availability and pricing</p>
                    </div>
                    
                    <div class="bg-white p-0 sm:p-6 rounded-lg shadow-md overflow-hidden">
                        <x-vue-component component="RentalCalendar" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Questions?</h2>
            <p class="text-lg text-gray-600 mb-8">Contact us for more information about our facilities and availability</p>
            <a href="mailto:contact@lujo.camp" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-semibold">
                Contact Us
            </a>
        </div>
    </section>
</x-layouts.public>
