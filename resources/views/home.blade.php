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

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .hero-gradient {
                background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            }
            .camp-green {
                background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
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
        </style>
    </head>
    <body class="bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-2xl font-bold text-blue-600">Camp LUJO-KISMIF</h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="#about" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
                        <a href="#sessions" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Sessions</a>
                        <a href="{{ route('strive-week') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Strive Week</a>
                        <a href="#contact" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
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
        <section class="hero-gradient min-h-screen flex items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative z-10 text-center text-white px-4">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 text-shadow">
                    KEEP IT SPIRITUAL!
                </h1>
                <h2 class="text-4xl md:text-6xl font-bold mb-8 text-shadow">
                    MAKE IT FUN!
                </h2>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto text-shadow">
                    Equipping the next generation for Christian service by enriching their lives through Christ's teachings, 
                    a Christian environment, and lifelong friendships.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#sessions" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        View Camp Sessions
                    </a>
                    <a href="#about" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg transition duration-300">
                        Learn More
                    </a>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="card-hover bg-blue-50 p-8 rounded-lg">
                        <div class="text-4xl font-bold text-blue-600 mb-2">500+</div>
                        <div class="text-xl text-gray-700">Campers</div>
                    </div>
                    <div class="card-hover bg-green-50 p-8 rounded-lg">
                        <div class="text-4xl font-bold text-green-600 mb-2">50+</div>
                        <div class="text-xl text-gray-700">Staff Members</div>
                    </div>
                    <div class="card-hover bg-purple-50 p-8 rounded-lg">
                        <div class="text-4xl font-bold text-purple-600 mb-2">100+</div>
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
                    <div class="card-hover bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Spark Week</h3>
                        <p class="mb-4">1st-4th Grade</p>
                        <p class="text-blue-100">May 28-30</p>
                    </div>
                    
                    <div class="card-hover bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Jump Week</h3>
                        <p class="mb-4">9th Grade & Up</p>
                        <p class="text-green-100">June 1-7</p>
                    </div>
                    
                    <div class="card-hover bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Reunion Week</h3>
                        <p class="mb-4">4th-12th Grade</p>
                        <p class="text-purple-100">June 8-14</p>
                    </div>
                    
                    <div class="card-hover bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Day Camp</h3>
                        <p class="mb-4">1st-4th Grade</p>
                        <p class="text-yellow-100">June 9-11</p>
                    </div>
                    
                    <div class="card-hover bg-gradient-to-br from-red-500 to-red-600 text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Super Week</h3>
                        <p class="mb-4">4th-6th Grade</p>
                        <p class="text-red-100">June 15-21</p>
                    </div>
                    
                    <div class="card-hover bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Strive Week</h3>
                        <p class="mb-4">5th Grade & Up</p>
                        <p class="text-indigo-100">June 22-28</p>
                        <a href="{{ route('strive-week') }}" class="inline-block mt-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded transition duration-300">
                            Learn More
                        </a>
                    </div>
                    
                    <div class="card-hover bg-gradient-to-br from-pink-500 to-pink-600 text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Connect Week</h3>
                        <p class="mb-4">6th Grade & Up</p>
                        <p class="text-pink-100">June 29-July 5</p>
                    </div>
                    
                    <div class="card-hover bg-gradient-to-br from-teal-500 to-teal-600 text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Elevate Week</h3>
                        <p class="mb-4">7th-10th Girls</p>
                        <p class="text-teal-100">July 6-12</p>
                    </div>
                    
                    <div class="card-hover bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Fall Focus</h3>
                        <p class="mb-4">5th-12th Grade</p>
                        <p class="text-orange-100">Nov 1-3</p>
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
                            <li><a href="#contact" class="text-gray-300 hover:text-white">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Connect</h4>
                        <p class="text-gray-300">
                            Follow us on social media for updates and camp highlights.
                        </p>
                        <div class="flex space-x-4 mt-4">
                            <a href="#" class="text-gray-300 hover:text-white">
                                <span class="sr-only">Facebook</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white">
                                <span class="sr-only">Instagram</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                </svg>
                            </a>
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