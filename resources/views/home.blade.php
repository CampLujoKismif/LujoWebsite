<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Camp LUJO-KISMIF - Keep It Spiritual, Make It Fun!</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Force light mode on public pages -->
        <script>
            // Public pages always use light mode
            localStorage.setItem('flux.appearance', 'light');
            document.documentElement.classList.remove('dark');
            if (document.body) {
                document.body.classList.remove('dark');
            }
        </script>

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Smooth Scrolling Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const scrollProgress = document.getElementById('scrollProgress');
                const navLinks = document.querySelectorAll('nav a[href^="#"]');
                
                // Smooth scrolling for all anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        
                        const targetId = this.getAttribute('href');
                        const targetElement = document.querySelector(targetId);
                        
                        if (targetElement) {
                            // Get the offset position of the target element
                            const targetPosition = targetElement.offsetTop - 80; // Account for fixed navbar
                            
                            // Smooth scroll to the target
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                            
                            // Update URL without jumping
                            history.pushState(null, null, targetId);
                            
                            // Update active navigation link
                            updateActiveNavLink(targetId);
                        }
                    });
                });
                
                // Scroll progress tracking
                window.addEventListener('scroll', function() {
                    const scrollTop = window.pageYOffset;
                    const docHeight = document.body.offsetHeight - window.innerHeight;
                    const scrollPercent = (scrollTop / docHeight) * 100;
                    
                    if (scrollProgress) {
                        scrollProgress.style.width = scrollPercent + '%';
                    }
                    
                    // Update active navigation link based on scroll position
                    updateActiveNavLinkOnScroll();
                });
                
                // Update active navigation link
                function updateActiveNavLink(targetId) {
                    navLinks.forEach(link => {
                        link.classList.remove('nav-link-active');
                        if (link.getAttribute('href') === targetId) {
                            link.classList.add('nav-link-active');
                        }
                    });
                }
                
                // Update active navigation link based on scroll position
                function updateActiveNavLinkOnScroll() {
                    const sections = document.querySelectorAll('section[id]');
                    const scrollPosition = window.pageYOffset + 100;
                    
                    sections.forEach(section => {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.offsetHeight;
                        const sectionId = section.getAttribute('id');
                        
                        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                            updateActiveNavLink('#' + sectionId);
                        }
                    });
                }
                
                // Intersection Observer for section animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };
                
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                        }
                    });
                }, observerOptions);
                
                // Observe sections for animation
                document.querySelectorAll('section').forEach(section => {
                    section.classList.add('section-fade-in');
                    observer.observe(section);
                });
                
                // Handle browser back/forward buttons
                window.addEventListener('popstate', function() {
                    const hash = window.location.hash;
                    if (hash) {
                        const targetElement = document.querySelector(hash);
                        if (targetElement) {
                            const targetPosition = targetElement.offsetTop - 80;
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
                
                // Handle direct links with hash on page load
                if (window.location.hash) {
                    setTimeout(() => {
                        const targetElement = document.querySelector(window.location.hash);
                        if (targetElement) {
                            const targetPosition = targetElement.offsetTop - 80;
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }, 100);
                }
                
                // Initialize active nav link on page load
                updateActiveNavLinkOnScroll();
            });
        </script>
        
        <style>
            .hero-gradient {
                background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            }
            .camp-green {
                background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
            }
            .text-shadow {
                text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.7), 1px 1px 2px rgba(0, 0, 0, 0.5);
            }
            .card-hover {
                transition: all 0.3s ease;
            }
            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }
            
            /* Hero Section Enhancements */
            .hero-image-transition {
                transition: opacity 1s ease-in-out;
            }
            
            .hero-text-animation {
                animation: fadeInUp 1s ease-out forwards;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .hero-button-hover {
                transition: all 0.3s ease;
            }
            
            .hero-button-hover:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            }
            
            /* Image carousel dots */
            .carousel-dot {
                transition: all 0.3s ease;
            }
            
            .carousel-dot:hover {
                transform: scale(1.2);
            }
            
            /* Hero image styling to prevent stretching */
            .hero-background-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }
            
            /* Smooth scrolling enhancements */
            html {
                scroll-behavior: smooth;
            }
            
            /* Active navigation link styling */
            .nav-link-active {
                color: #2563eb !important;
                font-weight: 600;
            }
            
            /* Scroll progress indicator */
            .scroll-progress {
                position: fixed;
                top: 0;
                left: 0;
                width: 0%;
                height: 3px;
                background: linear-gradient(90deg, #3b82f6, #1d4ed8);
                z-index: 9999;
                transition: width 0.1s ease;
            }
            
            /* Section transition effects */
            .section-fade-in {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease;
            }
            
            .section-fade-in.visible {
                opacity: 1;
                transform: translateY(0);
            }
        </style>
    </head>
    <body class="bg-gray-50">
        <!-- Scroll Progress Indicator -->
        <div class="scroll-progress" id="scrollProgress"></div>
        
        <!-- Navigation -->
        <nav class="bg-white shadow-lg fixed top-0 w-full z-50" x-data="{ mobileMenuOpen: false, sessionsDropdownOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl md:text-2xl font-bold text-blue-600">Camp LUJO-KISMIF</h1>
                        </div>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="#about" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
                        
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
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">Spark Week (1st-4th Grade)</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">Jump Week (9th Grade & Up)</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">Reunion Week (4th-12th Grade)</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">Day Camp (1st-4th Grade)</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">Super Week (4th-6th Grade)</a>
                                    <a href="{{ route('strive-week') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">Strive Week (5th Grade & Up)</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">Connect Week (6th Grade & Up)</a>
                                    <a href="{{ route('elevate-week') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">Elevate Week (7th-10th Girls)</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 whitespace-nowrap">Fall Focus (5th-12th Grade)</a>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('rentals') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Rentals</a>
                        
                        <a href="#faq" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">FAQ</a>
                        <a href="#contact" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
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
                    <a href="#about" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">About</a>
                    
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
                            <a href="#" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Spark Week (1st-4th Grade)</a>
                            <a href="#" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Jump Week (9th Grade & Up)</a>
                            <a href="#" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Reunion Week (4th-12th Grade)</a>
                            <a href="#" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Day Camp (1st-4th Grade)</a>
                            <a href="#" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Super Week (4th-6th Grade)</a>
                            <a href="{{ route('strive-week') }}" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Strive Week (5th Grade & Up)</a>
                            <a href="#" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Connect Week (6th Grade & Up)</a>
                            <a href="{{ route('elevate-week') }}" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Elevate Week (7th-10th Girls)</a>
                            <a href="#" @click="mobileMenuOpen = false; mobileSessionsOpen = false" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Fall Focus (5th-12th Grade)</a>
                        </div>
                    </div>
                    
                    <a href="{{ route('rentals') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">Rentals</a>
                    
                    <a href="#faq" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">FAQ</a>
                    <a href="#contact" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">Contact</a>
                    
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

        <!-- Hero Section -->
        <section class="min-h-screen flex items-center justify-center relative overflow-hidden" 
                 x-data="{ 
                     currentImage: 0,
                     images: [],
                     loading: true,
                     async init() {
                         await this.loadImages();
                         this.rotateImages();
                     },
                     async loadImages() {
                         try {
                             const response = await fetch('/api/frontpage-images');
                             if (!response.ok) {
                                 throw new Error('Failed to fetch images');
                             }
                             const data = await response.json();
                             this.images = data;
                             
                             // Preload images for smooth transitions
                             if (this.images.length > 0) {
                                 this.images.forEach(src => {
                                     const img = new Image();
                                     img.src = src;
                                 });
                             }
                             
                             this.loading = false;
                         } catch (error) {
                             console.error('Error loading images:', error);
                             // Fallback to default images if API fails
                             this.images = [
                                 '/FrontPage/bridge.jpg',
                                 '/FrontPage/groupprayer.jpg', 
                                 '/FrontPage/flagpolestretches.jpg',
                                 '/FrontPage/sunsetSign.jpg',
                                 '/FrontPage/gagaball.jpg',
                                 '/FrontPage/worship.jpg',
                             ];
                             this.loading = false;
                         }
                     },
                     rotateImages() {
                         if (this.images.length > 0) {
                             setInterval(() => {
                                 this.currentImage = (this.currentImage + 1) % this.images.length;
                             }, 5000);
                         }
                     }
                 }">
            
            <!-- Loading State -->
            <div x-show="loading" class="absolute inset-0 bg-gradient-to-r from-blue-900 to-blue-700 flex items-center justify-center">
                <div class="text-white text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4"></div>
                    <p class="text-lg">Loading images...</p>
                </div>
            </div>
            
            <!-- No Images State -->
            <div x-show="!loading && images.length === 0" class="absolute inset-0 bg-gradient-to-r from-blue-900 to-blue-700 flex items-center justify-center">
                <div class="text-white text-center">
                    <p class="text-lg">No images found in FrontPage folder</p>
                </div>
            </div>
            
            <!-- Background Images with Fade Transitions -->
            <template x-for="(image, index) in images" :key="index">
                <div class="absolute inset-0 hero-image-transition"
                     :class="{ 'opacity-100': currentImage === index, 'opacity-0': currentImage !== index }"
                     x-show="!loading">
                    <img :src="image" 
                         :alt="'Camp LUJO-KISMIF - Image ' + (index + 1)"
                         class="hero-background-image">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 via-blue-800/70 to-blue-900/80"></div>
                </div>
            </template>
            
            <!-- Enhanced overlay for better text readability -->
            <div class="absolute inset-0 bg-black/50"></div>
            
            <!-- Content -->
            <div class="relative z-10 text-center text-white px-4 max-w-6xl mx-auto">
                <!-- Animated Title -->
                <div class="mb-6" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
                    <h1 class="text-5xl md:text-7xl font-bold mb-4 text-shadow transition-all duration-1000 bg-black/20 px-6 py-3 rounded-lg backdrop-blur-sm"
                        :class="{ 'opacity-100 transform translate-y-0': show, 'opacity-0 transform translate-y-8': !show }">
                        KEEP IT SPIRITUAL!
                    </h1>
                    <h2 class="text-4xl md:text-6xl font-bold text-shadow transition-all duration-1000 delay-300 bg-black/20 px-6 py-3 rounded-lg backdrop-blur-sm"
                        :class="{ 'opacity-100 transform translate-y-0': show, 'opacity-0 transform translate-y-8': !show }">
                        MAKE IT FUN!
                    </h2>
                </div>
                
                <!-- Animated Description -->
                <div class="mb-8" x-data="{ show: false }" x-init="setTimeout(() => show = true, 600)">
                    <p class="text-xl md:text-2xl max-w-4xl mx-auto text-shadow leading-relaxed transition-all duration-1000 bg-black/30 px-8 py-6 rounded-lg backdrop-blur-sm"
                       :class="{ 'opacity-100 transform translate-y-0': show, 'opacity-0 transform translate-y-8': !show }">
                        Equipping the next generation for Christian service by enriching their lives through Christ's teachings, 
                        a Christian environment, and lifelong friendships.
                    </p>
                </div>
                
                <!-- Animated Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center" 
                     x-data="{ show: false }" x-init="setTimeout(() => show = true, 900)">
                    <a href="{{ route('camp-sessions.index') }}" 
                       class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg hero-button-hover shadow-lg backdrop-blur-sm"
                       :class="{ 'opacity-100 transform translate-y-0': show, 'opacity-0 transform translate-y-8': !show }">
                        View Camp Sessions
                    </a>
                    <a href="#about" 
                       class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg hero-button-hover bg-black/20 backdrop-blur-sm"
                       :class="{ 'opacity-100 transform translate-y-0': show, 'opacity-0 transform translate-y-8': !show }">
                        Learn More
                    </a>
                </div>
            </div>
            
            <!-- Image Navigation Dots -->
            <div class="absolute bottom-16 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20" x-show="!loading && images.length > 0">
                <template x-for="(image, index) in images" :key="index">
                    <button @click="currentImage = index" 
                            class="w-3 h-3 rounded-full carousel-dot"
                            :class="{ 'bg-white': currentImage === index, 'bg-white/50 hover:bg-white/75': currentImage !== index }">
                    </button>
                </template>
            </div>
            
            <!-- Scroll Indicator -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 animate-bounce z-20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="card-hover bg-blue-50 p-8 rounded-lg">
                        <div class="text-4xl font-bold text-blue-600 mb-2">21k</div>
                        <div class="text-xl text-gray-700">Campers</div>
                    </div>
                    <div class="card-hover bg-green-50 p-8 rounded-lg">
                        <div class="text-4xl font-bold text-green-600 mb-2">11k</div>
                        <div class="text-xl text-gray-700">Staff Members</div>
                    </div>
                    <div class="card-hover bg-purple-50 p-8 rounded-lg">
                        <div class="text-4xl font-bold text-purple-600 mb-2">550+</div>
                        <div class="text-xl text-gray-700">Baptisms</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">About Camp LUJO-KISMIF</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Nestled in the great plains of southwest Oklahoma between the Wichita Mountains and the Red River, 
                        there is a large youth camp that was founded for the purpose of cultivating character in a manner that appeals to young people.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                        <p class="text-gray-600 mb-6">
                            The mission of Camp Lu-Jo KISMIF is to provide wholesome activity for young people in a Christian environment 
                            by enriching their lives and cultivating spirituality, decency, and a good moral and Christian character.
                        </p>
                        <p class="text-gray-600 mb-6">
                            The Board of Directors dedicates Camp Lu-Jo KISMIF to the training of young people spiritually, mentally, 
                            socially, and physically in the example of Luke 2:52: "And Jesus kept increasing in wisdom and stature 
                            and in favor with God and men."
                        </p>
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h4 class="font-bold text-blue-900 mb-2">What does KISMIF mean?</h4>
                            <p class="text-blue-800">
                                <strong>"Lu-Jo"</strong> comes from the name Lucille Jones.<br>
                                <strong>"KISMIF"</strong> stands for: Keep It Spiritual, Make It Fun!
                            </p>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Our History</h3>
                        <p class="text-gray-600 mb-4">
                            In 1960, Mrs. Lucille (Jones) Aubrey, a member at the church of Christ in Walters, OK, 
                            made the campgrounds available to the members of the original board of directors.
                        </p>
                        <p class="text-gray-600 mb-4">
                            The first session of Camp Lu-Jo was held June 23-30, 1962, with Harold McRay and Clyde Sloan co-directing the session. 
                            Camp Lu-Jo currently offers eight sessions and a fall retreat geared toward various age groups.
                        </p>
                        <p class="text-gray-600">
                            Camp Lu-Jo KISMIF has been celebrating Christian camping for over 60 summers!
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Camp Sessions Section -->
        <section id="sessions" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Camp Sessions</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        We offer a variety of sessions throughout the summer for different age groups, 
                        each designed to provide spiritual growth and fun activities.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($campInstances as $instance)
                        @php
                            $gradeSuffix = function($num) {
                                if($num == 1) return 'st';
                                if($num == 2) return 'nd';
                                if($num == 3) return 'rd';
                                return 'th';
                            };
                            
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
                        
                        <div class="card-hover bg-gradient-to-br {{ $gradientClass }} text-white p-6 rounded-lg relative overflow-hidden">
                            <!-- Camp Name - Large and Prominent -->
                            <h3 class="text-2xl font-bold mb-3">{{ $instance->camp->display_name }}</h3>
                            
                            <!-- Grade/Age Range -->
                            <div class="mb-4">
                                @if($instance->grade_from && $instance->grade_to)
                                    <span class="inline-block bg-white px-3 py-1 rounded-full text-sm font-medium text-gray-900">
                                        {{ $instance->grade_from }}{{ $gradeSuffix($instance->grade_from) }} Grade
                                        @if($instance->grade_from != $instance->grade_to)
                                            - {{ $instance->grade_to }}{{ $gradeSuffix($instance->grade_to) }} Grade
                                        @endif
                                    </span>
                                @elseif($instance->age_from && $instance->age_to)
                                    <span class="inline-block bg-white px-3 py-1 rounded-full text-sm font-medium text-gray-900">
                                        Ages {{ $instance->age_from }} - {{ $instance->age_to }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Dates - More Prominent -->
                            <div class="mb-4">
                                <div class="text-lg font-semibold">
                                    @if($instance->start_date && $instance->end_date)
                                        {{ $instance->start_date->format('M j') }} - {{ $instance->end_date->format('M j, Y') }}
                                    @elseif($instance->start_date)
                                        {{ $instance->start_date->format('M j, Y') }}
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Price - Large and Prominent -->
                            @if($instance->price)
                                <div class="mb-4">
                                    <div class="text-3xl font-bold text-white">
                                        ${{ number_format($instance->price, 0) }}
                                    </div>
                                    <div class="text-sm opacity-90">per camper</div>
                                </div>
                            @endif
                            
                            <!-- Description -->
                            @if($instance->description)
                                <p class="mb-4 text-sm opacity-90">{{ Str::limit($instance->description, 120) }}</p>
                            @endif
                            
                            <!-- Registration Status -->
                            @if($instance->isRegistrationOpen())
                                <div class="mb-4">
                                    <span class="inline-block bg-green-500 bg-opacity-90 px-4 py-2 rounded-full text-sm font-bold text-white">
                                        ✓ Registration Open
                                    </span>
                                </div>
                            @else
                                <div class="mb-4">
                                    <span class="inline-block bg-red-500 bg-opacity-90 px-4 py-2 rounded-full text-sm font-bold text-white">
                                        Registration Closed
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Capacity Info -->
                            @if($instance->max_capacity)
                                <div class="mb-4 text-sm opacity-90">
                                    <span class="font-medium">{{ $instance->available_spots }}</span> spots available
                                </div>
                            @endif
                            
                            <!-- Action Button -->
                            <div class="mt-4">
                                @if($instance->theme_description)
                                    <a href="{{ route('camp-sessions.show', $instance) }}" class="inline-block bg-white hover:bg-gray-100 text-gray-900 px-6 py-3 rounded-lg font-semibold transition duration-300 transform hover:scale-105">
                                        Learn More & Register
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-block bg-white hover:bg-gray-100 text-gray-900 px-6 py-3 rounded-lg font-semibold transition duration-300 transform hover:scale-105">
                                        Register Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-600">No camp sessions are currently scheduled. Please check back later!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Find answers to common questions about Camp LUJO-KISMIF and what to expect during your stay.
                    </p>
                </div>
                
                <div class="max-w-4xl mx-auto space-y-6">
                    <!-- FAQ Item 1 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">How old does my child have to be to attend camp?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">Each session has its own age requirements, so please check out our Sessions page to discover specifics. Generally speaking, Camp Lu-Jo has week-long sessions for those entering 4th grade through graduated seniors and a special day camp for children entering 1st grade through 4th grade.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">When can campers show up for their camp session?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">Camper registration does not begin UNTIL 2:30 P.M. Sunday and ends about 5:00 P.M. Please remember that campers must not enter the dorms until they have completed registration! If you are running late, please don't hesitate to contact the staff for that session!</p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">What if I didn't have time to send in a registration form by mail? Can we just show up and register on Sunday?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">Absolutely! Please note however that each camp week has a MAXIMUM number of beds available, so it is possible that the session's registration will close even before camp begins. You can also now register online on each session's individual page! Contact that session's director for more information.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">When should campers be picked up at the end of the camp week?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">Please pick up your camper AFTER 9:00 A.M. and BEFORE 10:00 A.M. on Saturday morning of each camp session. We appreciate everyone staying until at least that time to help clean so camp can be ready for the next session! The staff are dismissed at 10:00 am on Saturday so campers must be picked up prior to that time.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">When can parents, family, and friends visit during the week?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">Guests are encouraged to join their camper for evening worship services, which occur daily Sunday through Friday. Please check your session's schedule for their worship times.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">What should a camper bring to camp?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">Here is our most basic list:</p>
                            <ul class="text-gray-600 mt-2 space-y-1">
                                <li>• Bible</li>
                                <li>• Pencils and paper</li>
                                <li>• Clothes for a week</li>
                                <li>• Closed-toe shoes (something suitable for sports)</li>
                                <li>• Sweater or jacket (for cool nights)</li>
                                <li>• Cap or hat (for sun)</li>
                                <li>• Personal articles: toothpaste, toothbrush, soap, comb, towels, etc.</li>
                                <li>• Sheets, blankets, pillow, laundry bag, etc.</li>
                                <li>• Flashlight and batteries</li>
                                <li>• Sunscreen and Insect Repellant</li>
                                <li>• Items for Kismif Kapers, if desired</li>
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Item 7 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">Can I bring my stereo, mp3, iPad, Headphones, Gameboys and electronics…stuff like that?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">Camp Lu-Jo is an oasis away from all the regular, routine things of life where you will have a full schedule of spending time with new friends and outdoors in God's creation. You will experience first-hand how you can have a closer relationship with God. So leave the electronics at home – trust us, you won't miss them!</p>
                        </div>
                    </div>

                    <!-- FAQ Item 8 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">Is there anything else that should be left at home when coming to camp?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">We've already mentioned the electronics, but also on the DO NOT BRING list are lighters, matches, tobacco products, fireworks, hunting knives, firearms, laser pointers, playing cards, and/or collectors cards.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 9 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">What is the dress code for Camp Lu-Jo?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">Here is a list of clothing items that are not allowed at camp:</p>
                            <ul class="text-gray-600 mt-2 space-y-1">
                                <li>• Tank tops, low cut blouses/t-shirts, short shorts. MINIMAL length allowed on shorts, skirts, or dresses is the top of the knee when standing.</li>
                                <li>• Tight-fitting clothing</li>
                                <li>• Clothing with inappropriate printing or messages (as determined by the Camp Director or staff) are also not allowed.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Item 10 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">What if my child has prescriptions and medications?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">The assigned medical staff must be notified of ALL medicines and prescriptions a camper brings to camp. Please note that all prescriptions must be in ORIGINAL PRESCRIPTION CONTAINERS before they can be distributed to campers.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 11 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">Where do we send mail to campers during a camp session?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">During camp sessions, send all mail for campers to:</p>
                            <p class="text-gray-600 mt-2 font-medium">(Camper's Name)<br>c/o CAMP LU-JO KISMIF<br>178498 N 2520 Rd<br>FAXON, OK 73540</p>
                        </div>
                    </div>

                    <!-- FAQ Item 12 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h3 class="text-lg font-semibold text-gray-900">What about use of Camp Lu-Jo's grounds and facilities?</h3>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <p class="text-gray-600">Camp Lu-Jo specializes in hosting families, churches, and other groups for events like retreats, reunions, seminars, and fellowships.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Get in touch with us for more information about camp sessions, registration, or facility rentals.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Phone</p>
                                    <p class="text-sm text-gray-600">(580) 353-8370 (During Camp Sessions Only)</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Email</p>
                                    <p class="text-sm text-gray-600">contact@lujo.camp</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Location</p>
                                    <p class="text-sm text-gray-600">178498 N 2520 Rd<br>Faxon, OK 73540</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>
                        <form class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="name" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea id="message" name="message" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

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
                            <li><a href="#about" class="text-gray-300 hover:text-white">About</a></li>
                            <li><a href="#sessions" class="text-gray-300 hover:text-white">Camp Sessions</a></li>
                            <li><a href="#faq" class="text-gray-300 hover:text-white">FAQ</a></li>
                            <li><a href="#contact" class="text-gray-300 hover:text-white">Contact</a></li>
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
<!--                             <a href="#" class="text-gray-300 hover:text-white">
                                <span class="sr-only">Instagram</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                </svg>
                            </a> -->
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                    <p class="text-gray-300">
                        © 2024 Camp LUJO-KISMIF. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html> 