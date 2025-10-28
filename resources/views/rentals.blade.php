<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Rent Our Facilities - Camp LUJO-KISMIF</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex-shrink-0">
                            <img class="h-8 w-auto" src="/LujoWideLogoBlack.svg" alt="Camp LUJO-KISMIF">
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="{{ route('camp-sessions.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Camp Sessions</a>
                        <a href="{{ route('rentals') }}" class="text-blue-600 px-3 py-2 rounded-md text-sm font-medium font-semibold">Rentals</a>
                        <a href="{{ route('strive-week') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Strive Week</a>
                        <a href="{{ route('elevate-week') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Elevate Week</a>
                    </div>
                </div>
            </div>
        </nav>

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

        <!-- Features Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">What We Offer</h2>
                    <p class="text-lg text-gray-600">Perfect facilities for your next event</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
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
        </section>

        <!-- Pricing Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Simple Pricing</h2>
                    <p class="text-lg text-gray-600">Transparent pricing with no hidden fees</p>
                </div>
                
                <div class="max-w-md mx-auto bg-blue-50 p-8 rounded-lg">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2">$20</div>
                        <div class="text-xl text-gray-700 mb-4">per person per day</div>
                        <p class="text-gray-600">Includes access to all facilities</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Rental Calendar Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Check Availability</h2>
                    <p class="text-lg text-gray-600">Select your dates to see availability and pricing</p>
                </div>
                
        <!-- Vue Rental Calendar Component -->
        <div class="max-w-4xl mx-auto">
            <x-vue-component component="RentalCalendar" />
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

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <img class="h-8 w-auto mx-auto mb-4" src="/LujoSignWhite.png" alt="Camp LUJO-KISMIF">
                    <p class="text-gray-400">&copy; {{ date('Y') }} Camp LUJO-KISMIF. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
