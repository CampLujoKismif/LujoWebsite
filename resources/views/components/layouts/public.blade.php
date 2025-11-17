@props(['title' => 'Camp Sessions'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title }} - Camp LUJO-KISMIF</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Force light mode on public pages -->
        <script>
            // Public pages always use light mode
            localStorage.setItem('flux.appearance', 'light');
            document.documentElement.classList.remove('dark');
            if (document.body) {
                document.body.classList.remove('dark');
            }
        </script>
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Stripe.js -->
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            @if(config('services.stripe.key'))
                window.stripePublishableKey = '{{ config('services.stripe.key') }}';
            @else
                console.error('Stripe publishable key is not configured. Please set STRIPE_KEY in your .env file.');
            @endif
        </script>
        
        @stack('head')
    </head>
    <body class="bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg fixed top-0 w-full z-50" x-data="{ mobileMenuOpen: false, sessionsDropdownOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex-shrink-0">
                            <h1 class="text-xl md:text-2xl font-bold text-blue-600">Camp LUJO-KISMIF</h1>
                        </a>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    @php
                        $activeCampSessions = $activeCampSessions ?? collect();
                    @endphp
                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="{{ route('home') }}#about" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
                        
                        <!-- Sessions Dropdown -->
                        <div class="relative" @mouseenter="sessionsDropdownOpen = true" @mouseleave="sessionsDropdownOpen = false">
                            <button class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                                Sessions
                                <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': sessionsDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="sessionsDropdownOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute left-0 mt-2 min-w-max bg-white rounded-md shadow-lg z-50">
                                <div class="py-2">
                                    <a href="{{ route('camp-sessions.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">All Sessions</a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    @forelse($activeCampSessions as $session)
                                        @php
                                            $subtitle = $session->grade_range ?: $session->age_range;
                                        @endphp
                                        <a href="{{ route('camp-sessions.show', ['instance' => $session]) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">
                                            <span class="block">{{ $session->camp->display_name }}</span>
                                            <span class="block text-xs text-gray-500">
                                                {{ $session->year }}
                                                @if($subtitle)
                                                    · {{ $subtitle }}
                                                @endif
                                            </span>
                                        </a>
                                    @empty
                                        <span class="block px-4 py-2 text-sm text-gray-500 whitespace-nowrap">No active sessions available yet.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('rentals') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Rentals</a>
                        <a href="{{ route('home') }}#faq" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">FAQ</a>
                        <a href="{{ route('home') }}#contact" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                        
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Login</a>
                        @endauth
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="lg:hidden flex items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!mobileMenuOpen">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="mobileMenuOpen">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Navigation Menu -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="lg:hidden bg-white border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}#about" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">About</a>
                    
                    <!-- Mobile Sessions Dropdown -->
                    <div x-data="{ mobileSessionsOpen: false }">
                        <button @click="mobileSessionsOpen = !mobileSessionsOpen" class="w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md flex justify-between items-center">
                            <span>Sessions</span>
                            <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': mobileSessionsOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="mobileSessionsOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="pl-4 space-y-1">
                            <a href="{{ route('camp-sessions.index') }}" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">All Sessions</a>
                            @forelse($activeCampSessions as $session)
                                @php
                                    $subtitle = $session->grade_range ?: $session->age_range;
                                @endphp
                                <a href="{{ route('camp-sessions.show', ['instance' => $session]) }}"
                                   @click="mobileMenuOpen = false; mobileSessionsOpen = false"
                                   class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">
                                    <span class="block">{{ $session->camp->display_name }}</span>
                                    <span class="block text-xs text-gray-500">
                                        {{ $session->year }}
                                        @if($subtitle)
                                            · {{ $subtitle }}
                                        @endif
                                    </span>
                                </a>
                            @empty
                                <span class="block px-3 py-2 text-sm text-gray-500 rounded-md">No active sessions available yet.</span>
                            @endforelse
                        </div>
                    </div>
                    
                    <a href="{{ route('rentals') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">Rentals</a>
                    <a href="{{ route('home') }}#faq" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">FAQ</a>
                    <a href="{{ route('home') }}#contact" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">Contact</a>
                    
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        @auth
                            <a href="{{ url('/dashboard') }}" @click="mobileMenuOpen = false" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-base font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-base font-medium">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="pt-16">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">Camp LUJO-KISMIF</h3>
                        <p class="text-gray-300">
                            Keep It Spiritual, Make It Fun!<br>
                            Equipping the next generation for Christian service.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}#about" class="text-gray-300 hover:text-white">About</a></li>
                            <li><a href="{{ route('camp-sessions.index') }}" class="text-gray-300 hover:text-white">Camp Sessions</a></li>
                            <li><a href="{{ route('home') }}#faq" class="text-gray-300 hover:text-white">FAQ</a></li>
                            <li><a href="{{ route('home') }}#contact" class="text-gray-300 hover:text-white">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Connect</h4>
                        <p class="text-gray-300">
                            Follow us on social media for updates and camp highlights.
                        </p>
                        <div class="flex space-x-4 mt-4">
                            <a href="https://www.facebook.com/CAMPLUJOKISMIF" target="_blank" class="text-gray-300 hover:text-white">
                                <span class="sr-only">Facebook</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                    <p class="text-gray-300">
                        © {{ date('Y') }} Camp LUJO-KISMIF. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
        
        @stack('scripts')
    </body>
</html> 