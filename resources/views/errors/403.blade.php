<x-layouts.public title="Access Forbidden">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-red-900 to-orange-700 py-20 min-h-[60vh] flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-8">
                <h1 class="text-9xl md:text-[12rem] font-bold text-white mb-4 leading-none">403</h1>
                <div class="text-6xl md:text-8xl font-bold text-white/90 mb-6">Access Denied</div>
            </div>
            <p class="text-xl md:text-2xl text-red-100 max-w-3xl mx-auto">
                Sorry, you don't have permission to access this resource. Please contact an administrator if you believe this is an error.
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-8 md:p-12 text-center">
                <div class="mb-8">
                    <svg class="w-24 h-24 mx-auto text-red-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Permission Denied</h2>
                    <p class="text-lg text-gray-600 mb-8">
                        This area requires special permissions. If you need access, please contact your administrator or return to our public pages.
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
                    
                    @guest
                        <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-4 rounded-lg font-semibold text-lg transition duration-300 transform hover:scale-105 shadow-md">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span>Login</span>
                            </div>
                        </a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-4 rounded-lg font-semibold text-lg transition duration-300 transform hover:scale-105 shadow-md">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    @endguest
                </div>

                <!-- Helpful Message -->
                <div class="bg-red-50 border-l-4 border-red-600 p-6 rounded-md text-left">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Need Access?</h3>
                            <p class="text-red-800">
                                If you believe you should have access to this resource, please 
                                <a href="{{ route('home') }}#contact" class="font-semibold underline hover:text-red-900">contact us</a> 
                                or speak with your administrator. They can help you get the appropriate permissions.
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

