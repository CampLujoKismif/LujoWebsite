<x-layouts.public title="Page Not Found">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-900 to-blue-700 py-20 min-h-[60vh] flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-8">
                <h1 class="text-9xl md:text-[12rem] font-bold text-white mb-4 leading-none">404</h1>
                <div class="text-6xl md:text-8xl font-bold text-white/90 mb-6">Oops!</div>
            </div>
            <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto">
                We couldn't find the page you're looking for. It might have been moved or doesn't exist.
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-8 md:p-12 text-center">
                <div class="mb-8">
                    <svg class="w-24 h-24 mx-auto text-blue-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Page Not Found</h2>
                    <p class="text-lg text-gray-600 mb-8">
                        Don't worry though! You can navigate back to our main pages or explore what we have to offer.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-lg font-semibold text-lg transition duration-300 transform hover:scale-105 shadow-md">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Go Home</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('camp-sessions.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-lg font-semibold text-lg transition duration-300 transform hover:scale-105 shadow-md">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Camp Sessions</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('rentals') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-4 rounded-lg font-semibold text-lg transition duration-300 transform hover:scale-105 shadow-md">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Rentals</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('home') }}#contact" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-4 rounded-lg font-semibold text-lg transition duration-300 transform hover:scale-105 shadow-md">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Contact Us</span>
                        </div>
                    </a>
                </div>

                <!-- Helpful Message -->
                <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded-md text-left">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">Need Help?</h3>
                            <p class="text-blue-800">
                                If you believe this is an error or you're looking for something specific, 
                                please <a href="{{ route('home') }}#contact" class="font-semibold underline hover:text-blue-900">contact us</a> 
                                and we'll be happy to assist you!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fun Camp-Themed Section -->
    <section class="py-12 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-600 text-lg mb-4">
                While you're here, remember:
            </p>
            <div class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                Keep It Spiritual, Make It Fun!
            </div>
        </div>
    </section>
</x-layouts.public>

