<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Elevate Week - Camp LUJO-KISMIF</title>

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
        
        <style>
            .elevate-gradient {
                background: linear-gradient(135deg, #ec4899 0%, #f472b6 50%, #f9a8d4 100%);
            }
            .text-shadow {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            }
            .card-hover {
                transition: all 0.3s ease;
            }
            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }
            .carousel-container {
                position: relative;
                overflow: hidden;
            }
            .carousel-slide {
                transition: transform 0.5s ease-in-out;
            }
            .faq-item {
                border-bottom: 1px solid #e5e7eb;
            }
            .faq-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease-out;
            }
            .faq-content.active {
                max-height: 500px;
                transition: max-height 0.3s ease-in;
            }
        </style>
    </head>
    
    <body class="bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">Camp LUJO-KISMIF</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="#about" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
                        <a href="#gallery" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Gallery</a>
                        <a href="#faq" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">FAQ</a>
                        <a href="#directors" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Directors</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="elevate-gradient min-h-screen flex items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative z-10 text-center text-white px-4">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 text-shadow">
                    ELEVATE WEEK
                </h1>
                <h2 class="text-3xl md:text-5xl font-bold mb-8 text-shadow">
                    July 6-12, 2024
                </h2>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto text-shadow">
                    A special week designed for 7th-10th grade girls to grow in faith, build confidence, and create lasting friendships!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#about" class="bg-white text-pink-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        Learn More
                    </a>
                    <a href="#register" class="border-2 border-white text-white hover:bg-white hover:text-pink-600 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        Register Now
                    </a>
                </div>
            </div>
        </section>

        <!-- About Elevate Week Section -->
        <section id="about" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">About Elevate Week</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Elevate Week is our special session designed exclusively for girls in 7th-10th grade. 
                        This empowering week focuses on spiritual growth, self-confidence, and building strong Christian friendships 
                        in a supportive, all-girls environment.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">What Makes Elevate Week Special?</h3>
                        <ul class="space-y-4 text-gray-600">
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-pink-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Girls-only environment for focused spiritual growth</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-pink-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Age-appropriate Bible studies and devotionals</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-pink-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Creative arts, crafts, and self-expression activities</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-pink-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Confidence-building workshops and team challenges</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-pink-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Swimming, hiking, and outdoor adventures</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-pink-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Evening worship and fellowship around the campfire</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-pink-50 p-8 rounded-lg">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Daily Schedule</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">7:00 AM</span>
                                <span>Wake up & Morning Devotional</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">8:00 AM</span>
                                <span>Breakfast</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">9:00 AM</span>
                                <span>Bible Study & Discussion</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">10:30 AM</span>
                                <span>Morning Activities</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">12:00 PM</span>
                                <span>Lunch</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">1:30 PM</span>
                                <span>Afternoon Activities</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">5:30 PM</span>
                                <span>Dinner</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">7:00 PM</span>
                                <span>Evening Program</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">9:30 PM</span>
                                <span>Lights Out</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Photo Carousel Section -->
        <section id="gallery" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Elevate Week Gallery</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Take a look at some of the amazing moments from previous Elevate Week sessions!
                    </p>
                </div>
                
                <div class="carousel-container relative">
                    <div class="flex carousel-slide" id="carousel">
                        <!-- Placeholder images - replace with actual camp photos -->
                        <div class="w-full md:w-1/3 flex-shrink-0 px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div class="h-64 bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">Girls Bible Study</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">Spiritual Growth</h3>
                                    <p class="text-gray-600 text-sm">Engaging Bible studies designed for teen girls</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex-shrink-0 px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div class="h-64 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">Arts & Crafts</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">Creative Expression</h3>
                                    <p class="text-gray-600 text-sm">Fun arts and crafts projects</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex-shrink-0 px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div class="h-64 bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">Campfire</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">Evening Fellowship</h3>
                                    <p class="text-gray-600 text-sm">Singing, sharing, and fellowship around the fire</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex-shrink-0 px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div class="h-64 bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">Swimming</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">Water Fun</h3>
                                    <p class="text-gray-600 text-sm">Cooling off and having fun in the pool</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex-shrink-0 px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div class="h-64 bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">Outdoor Activities</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">Nature Adventures</h3>
                                    <p class="text-gray-600 text-sm">Hiking and exploring God's creation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carousel Navigation -->
                    <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 shadow-lg" onclick="moveCarousel(-1)">
                        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 shadow-lg" onclick="moveCarousel(1)">
                        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                    <p class="text-xl text-gray-600">
                        Everything you need to know about Elevate Week
                    </p>
                </div>
                
                <div class="space-y-4">
                    <div class="faq-item">
                        <button class="w-full text-left py-4 px-6 bg-gray-50 hover:bg-gray-100 rounded-lg flex justify-between items-center" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-900">What should my daughter pack for Elevate Week?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content px-6">
                            <div class="py-4 text-gray-600">
                                <p class="mb-2">Essential items include:</p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Bible and notebook</li>
                                    <li>Comfortable clothing for outdoor activities</li>
                                    <li>Modest swimsuit and towel</li>
                                    <li>Sleeping bag and pillow</li>
                                    <li>Toiletries and personal items</li>
                                    <li>Closed-toe shoes for hiking</li>
                                    <li>Flashlight</li>
                                    <li>Arts and crafts supplies (optional)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="w-full text-left py-4 px-6 bg-gray-50 hover:bg-gray-100 rounded-lg flex justify-between items-center" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-900">What is the cost of Elevate Week?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content px-6">
                            <div class="py-4 text-gray-600">
                                <p>The cost for Elevate Week is $250 per camper. This includes all meals, activities, and materials. 
                                Scholarships are available for families in need. Please contact us for more information about financial assistance.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="w-full text-left py-4 px-6 bg-gray-50 hover:bg-gray-100 rounded-lg flex justify-between items-center" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-900">What makes Elevate Week different from other camp sessions?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content px-6">
                            <div class="py-4 text-gray-600">
                                <p>Elevate Week is specifically designed for girls in 7th-10th grade, providing a safe, 
                                supportive environment where they can grow spiritually and build confidence. The program includes 
                                age-appropriate Bible studies, confidence-building activities, and opportunities for creative expression 
                                that are tailored to the unique needs and interests of teen girls.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="w-full text-left py-4 px-6 bg-gray-50 hover:bg-gray-100 rounded-lg flex justify-between items-center" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-900">Can my daughter bring electronics or phones?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content px-6">
                            <div class="py-4 text-gray-600">
                                <p>We encourage campers to disconnect from technology during their time at camp to fully engage 
                                in activities and build relationships. Phones and electronics are collected upon arrival and 
                                returned at the end of the week. Emergency contact information is available to parents.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="w-full text-left py-4 px-6 bg-gray-50 hover:bg-gray-100 rounded-lg flex justify-between items-center" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-900">What if my daughter has never been to camp before?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content px-6">
                            <div class="py-4 text-gray-600">
                                <p>Elevate Week is perfect for first-time campers! Our experienced staff is trained to help 
                                new campers feel comfortable and included. The girls-only environment creates a supportive 
                                atmosphere where friendships form quickly. Many of our campers return year after year!</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="w-full text-left py-4 px-6 bg-gray-50 hover:bg-gray-100 rounded-lg flex justify-between items-center" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-900">What is the staff-to-camper ratio?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content px-6">
                            <div class="py-4 text-gray-600">
                                <p>We maintain a low staff-to-camper ratio to ensure individual attention and safety. 
                                Our staff includes experienced counselors, activity leaders, and medical personnel. 
                                All staff members are carefully screened and trained to work with teen girls.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Directors Section -->
        <section id="directors" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Meet Our Directors</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Our experienced and dedicated directors are committed to making Elevate Week an empowering and unforgettable experience for every young woman.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-pink-400 to-rose-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">SM</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Sarah Mitchell</h3>
                        <p class="text-pink-600 font-semibold mb-3">Camp Director</p>
                        <p class="text-gray-600 text-sm">
                            Sarah has been involved with Camp LUJO-KISMIF for over 15 years and has a passion for 
                            empowering young women. She holds a degree in Youth Ministry and has extensive experience 
                            in Christian camping and girls' ministry.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">LW</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Lisa Williams</h3>
                        <p class="text-purple-600 font-semibold mb-3">Spiritual Life Director</p>
                        <p class="text-gray-600 text-sm">
                            Lisa leads our Bible studies and devotionals, helping young women grow in their faith 
                            through interactive lessons and meaningful discussions. She has a Master's degree 
                            in Christian Education and specializes in teen girls' ministry.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-rose-400 to-pink-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">RG</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Rachel Garcia</h3>
                        <p class="text-rose-600 font-semibold mb-3">Health & Safety Director</p>
                        <p class="text-gray-600 text-sm">
                            Rachel ensures the health and safety of all campers and staff. She is a registered 
                            nurse and oversees our medical protocols, emergency procedures, and overall camp safety.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">MJ</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Maria Johnson</h3>
                        <p class="text-cyan-600 font-semibold mb-3">Activities Director</p>
                        <p class="text-gray-600 text-sm">
                            Maria coordinates all activities and ensures that each day is filled with 
                            engaging, age-appropriate activities that promote confidence and fun. 
                            She has a background in education and outdoor recreation.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-emerald-400 to-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">KT</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Katie Thompson</h3>
                        <p class="text-emerald-600 font-semibold mb-3">Creative Arts Director</p>
                        <p class="text-gray-600 text-sm">
                            Katie leads our creative arts and crafts sessions, helping campers express themselves 
                            through various artistic mediums. She has a degree in Art Education and loves 
                            encouraging creativity in young women.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-violet-400 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">DC</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Diana Chen</h3>
                        <p class="text-violet-600 font-semibold mb-3">Facilities Director</p>
                        <p class="text-gray-600 text-sm">
                            Diana manages our camp facilities and ensures everything runs smoothly behind the scenes. 
                            She oversees maintenance, food service, and all logistical aspects of camp operations.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Registration CTA Section -->
        <section id="register" class="py-16 bg-pink-600">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold text-white mb-4">Ready to Elevate Your Faith?</h2>
                <p class="text-xl text-pink-100 mb-8 max-w-2xl mx-auto">
                    Join us for an empowering week of spiritual growth, friendship, and fun designed specifically for teen girls!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="bg-white text-pink-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        Register Now
                    </a>
                    <a href="{{ route('home') }}#contact" class="border-2 border-white text-white hover:bg-white hover:text-pink-600 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        Contact Us
                    </a>
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
                            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a></li>
                            <li><a href="#about" class="text-gray-300 hover:text-white">About Elevate Week</a></li>
                            <li><a href="#gallery" class="text-gray-300 hover:text-white">Gallery</a></li>
                            <li><a href="#faq" class="text-gray-300 hover:text-white">FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact</h4>
                        <p class="text-gray-300">
                            Questions about Elevate Week?<br>
                            Get in touch with us!
                        </p>
                        <div class="mt-4">
                            <p class="text-gray-300 text-sm">Phone: (580) 353-8370</p>
                            <p class="text-gray-300 text-sm">Email: contact@lujo.camp</p>
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

        <script>
            // Carousel functionality
            let currentSlide = 0;
            const slides = document.querySelectorAll('#carousel > div');
            const totalSlides = slides.length;
            const slidesToShow = window.innerWidth >= 768 ? 3 : 1;

            function moveCarousel(direction) {
                currentSlide += direction;
                if (currentSlide < 0) currentSlide = totalSlides - slidesToShow;
                if (currentSlide > totalSlides - slidesToShow) currentSlide = 0;
                
                const offset = -(currentSlide * (100 / slidesToShow));
                document.getElementById('carousel').style.transform = `translateX(${offset}%)`;
            }

            // FAQ functionality
            function toggleFAQ(button) {
                const content = button.nextElementSibling;
                const icon = button.querySelector('svg');
                
                content.classList.toggle('active');
                icon.style.transform = content.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
            }

            // Auto-advance carousel
            setInterval(() => moveCarousel(1), 5000);
        </script>
    </body>
</html> 