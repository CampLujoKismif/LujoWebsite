<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Strive Week - Camp LUJO-KISMIF</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .strive-gradient {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
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
        <section class="strive-gradient min-h-screen flex items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative z-10 text-center text-white px-4">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 text-shadow">
                    STRIVE WEEK
                </h1>
                <h2 class="text-3xl md:text-5xl font-bold mb-8 text-shadow">
                    June 22-28, 2024
                </h2>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto text-shadow">
                    A week of spiritual growth, adventure, and unforgettable memories for 5th grade and up!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#about" class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        Learn More
                    </a>
                    <a href="#register" class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        Register Now
                    </a>
                </div>
            </div>
        </section>

        <!-- About Strive Week Section -->
        <section id="about" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">About Strive Week</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Strive Week is one of our most popular camp sessions, designed specifically for campers in 5th grade and up. 
                        This week-long adventure combines spiritual growth with exciting activities and lasting friendships.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">What Makes Strive Week Special?</h3>
                        <ul class="space-y-4 text-gray-600">
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-indigo-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Age-appropriate Bible studies and devotionals</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-indigo-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Exciting outdoor activities and team challenges</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-indigo-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Creative arts and crafts sessions</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-indigo-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Campfire sing-alongs and skits</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-indigo-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Swimming, hiking, and nature exploration</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-indigo-50 p-8 rounded-lg">
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
                                <span>Bible Study</span>
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
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Strive Week Gallery</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Take a look at some of the amazing moments from previous Strive Week sessions!
                    </p>
                </div>
                
                <div class="carousel-container relative">
                    <div class="flex carousel-slide" id="carousel">
                        <!-- Placeholder images - replace with actual camp photos -->
                        <div class="w-full md:w-1/3 flex-shrink-0 px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div class="h-64 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">Camp Activities</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">Outdoor Adventures</h3>
                                    <p class="text-gray-600 text-sm">Campers enjoying nature hikes and outdoor games</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex-shrink-0 px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div class="h-64 bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">Bible Study</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">Spiritual Growth</h3>
                                    <p class="text-gray-600 text-sm">Engaging Bible studies and devotionals</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex-shrink-0 px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div class="h-64 bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">Campfire</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">Evening Campfire</h3>
                                    <p class="text-gray-600 text-sm">Singing, skits, and fellowship around the fire</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex-shrink-0 px-2">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div class="h-64 bg-gradient-to-br from-pink-400 to-red-500 flex items-center justify-center">
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
                                <div class="h-64 bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">Swimming</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900">Water Fun</h3>
                                    <p class="text-gray-600 text-sm">Cooling off in the pool</p>
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
                        Everything you need to know about Strive Week
                    </p>
                </div>
                
                <div class="space-y-4">
                    <div class="faq-item">
                        <button class="w-full text-left py-4 px-6 bg-gray-50 hover:bg-gray-100 rounded-lg flex justify-between items-center" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-900">What should my child pack for Strive Week?</span>
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
                                    <li>Swimsuit and towel</li>
                                    <li>Sleeping bag and pillow</li>
                                    <li>Toiletries and personal items</li>
                                    <li>Closed-toe shoes for hiking</li>
                                    <li>Flashlight</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="w-full text-left py-4 px-6 bg-gray-50 hover:bg-gray-100 rounded-lg flex justify-between items-center" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-900">What is the cost of Strive Week?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content px-6">
                            <div class="py-4 text-gray-600">
                                <p>The cost for Strive Week is $250 per camper. This includes all meals, activities, and materials. 
                                Scholarships are available for families in need. Please contact us for more information about financial assistance.</p>
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
                                <p>We maintain a staff-to-camper ratio of approximately 1:8, ensuring that each camper receives 
                                individual attention and supervision. All staff members are carefully selected and trained.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="w-full text-left py-4 px-6 bg-gray-50 hover:bg-gray-100 rounded-lg flex justify-between items-center" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-900">Can my child bring electronics or phones?</span>
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
                            <span class="font-semibold text-gray-900">What if my child has dietary restrictions?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content px-6">
                            <div class="py-4 text-gray-600">
                                <p>We can accommodate most dietary restrictions and allergies. Please indicate any special 
                                dietary needs on the registration form, and our kitchen staff will work to provide appropriate 
                                meal options for your child.</p>
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
                        Our experienced and dedicated directors are committed to making Strive Week an unforgettable experience for every camper.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">SM</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Sarah Mitchell</h3>
                        <p class="text-indigo-600 font-semibold mb-3">Camp Director</p>
                        <p class="text-gray-600 text-sm">
                            Sarah has been involved with Camp LUJO-KISMIF for over 15 years and has a passion for 
                            creating meaningful experiences for young people. She holds a degree in Youth Ministry 
                            and has extensive experience in Christian camping.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">MJ</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Michael Johnson</h3>
                        <p class="text-green-600 font-semibold mb-3">Program Director</p>
                        <p class="text-gray-600 text-sm">
                            Michael oversees all program activities and ensures that each day is filled with 
                            engaging, age-appropriate activities that promote spiritual growth and fun. 
                            He has a background in education and outdoor recreation.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">LW</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Lisa Williams</h3>
                        <p class="text-yellow-600 font-semibold mb-3">Spiritual Life Director</p>
                        <p class="text-gray-600 text-sm">
                            Lisa leads our Bible studies and devotionals, helping campers grow in their faith 
                            through interactive lessons and meaningful discussions. She has a Master's degree 
                            in Christian Education and loves working with young people.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-pink-400 to-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">DC</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">David Chen</h3>
                        <p class="text-pink-600 font-semibold mb-3">Activities Director</p>
                        <p class="text-gray-600 text-sm">
                            David coordinates all outdoor activities, games, and recreational programs. 
                            He is certified in wilderness first aid and has a passion for outdoor education 
                            and team building activities.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">RG</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Rachel Garcia</h3>
                        <p class="text-teal-600 font-semibold mb-3">Health & Safety Director</p>
                        <p class="text-gray-600 text-sm">
                            Rachel ensures the health and safety of all campers and staff. She is a registered 
                            nurse and oversees our medical protocols, emergency procedures, and overall camp safety.
                        </p>
                    </div>
                    
                    <div class="card-hover bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-32 h-32 mx-auto mb-4 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">KT</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Kevin Thompson</h3>
                        <p class="text-purple-600 font-semibold mb-3">Facilities Director</p>
                        <p class="text-gray-600 text-sm">
                            Kevin manages our camp facilities and ensures everything runs smoothly behind the scenes. 
                            He oversees maintenance, food service, and all logistical aspects of camp operations.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Registration CTA Section -->
        <section id="register" class="py-16 bg-indigo-600">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold text-white mb-4">Ready to Join Strive Week?</h2>
                <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                    Don't miss out on this amazing opportunity for spiritual growth, adventure, and friendship!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        Register Now
                    </a>
                    <a href="{{ route('home') }}#contact" class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
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
                            <li><a href="#about" class="text-gray-300 hover:text-white">About Strive Week</a></li>
                            <li><a href="#gallery" class="text-gray-300 hover:text-white">Gallery</a></li>
                            <li><a href="#faq" class="text-gray-300 hover:text-white">FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact</h4>
                        <p class="text-gray-300">
                            Questions about Strive Week?<br>
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