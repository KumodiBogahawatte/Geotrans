<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geotrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

    <!-- Top Banner -->
    <div class="text-white py-2 px-6 sm:px-8 lg:px-20 text-center text-sm" style="background-color: #8D4887;">
        <span class="bg-white px-3 py-1 rounded-full font-semibold text-xs mr-2" style="color: #8D4887;">Special</span>
        Get 10% <span class="font-bold">DISCOUNT</span> for first order
        <a href="#" class="underline ml-2" style="color: #FFFFFF;" onmouseover="this.style.color='#C4A3D1'" onmouseout="this.style.color='#FFFFFF'">Register Now</a>
    </div>

    <!-- Main Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <!-- Mobile Header -->
            <div class="lg:hidden flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="p-2" style="color: #8D4887;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Logo -->
                <a href="index.php" class="flex items-center">
                    <img src="assets/images/logo.png" alt="GeoTrans Pvt Ltd Logo" class="h-12 object-contain">
                </a>

                <!-- Mobile Icons -->
                <div class="flex items-center space-x-3">
                    <!-- Search Icon -->
                    <button id="mobile-search-button" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    
                    <!-- Wishlist -->
                    <a href="#" class="relative" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="absolute -top-2 -right-2 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #8D4887;">2</span>
                    </a>

                    <!-- Cart -->
                    <a href="#" class="relative" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="absolute -top-2 -right-2 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #8D4887;">4</span>
                    </a>
                </div>
            </div>

            <!-- Mobile Search Bar (Hidden by default) -->
            <div id="mobile-search" class="lg:hidden hidden mt-4">
                <div class="flex items-center">
                    <select class="bg-gray-100 border-0 rounded-l-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2" style="--tw-ring-color: #8D4887;">
                        <option>All</option>
                        <option>Electronics</option>
                        <option>Clothing</option>
                        <option>Home & Garden</option>
                    </select>
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search anything..."
                            class="w-full px-3 py-2 border-0 bg-gray-100 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color: #8D4887;" />
                    </div>
                    <button class="text-white px-4 py-2 rounded-r-lg transition-colors" style="background-color: #8D4887;" onmouseover="this.style.backgroundColor='#9C5AA2'" onmouseout="this.style.backgroundColor='#8D4887'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden lg:flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center">
                        <img src="assets/images/logo.png" alt="GeoTrans Pvt Ltd Logo" class="h-16 object-contain">
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex items-center flex-1 max-w-2xl mx-8">
                    <select class="bg-gray-100 border-0 rounded-l-lg px-4 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2" style="--tw-ring-color: #8D4887;">
                        <option>All Categories</option>
                        <option>Electronics</option>
                        <option>Clothing</option>
                        <option>Home & Garden</option>
                    </select>
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search anything..."
                            class="w-full px-4 py-2 border-0 bg-gray-100 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color: #8D4887;" />
                    </div>
                    <button class="text-white px-6 py-2 rounded-r-lg transition-colors" style="background-color: #8D4887;" onmouseover="this.style.backgroundColor='#9C5AA2'" onmouseout="this.style.backgroundColor='#8D4887'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Right Side Icons & Contact -->
                <div class="flex items-center space-x-6">
                    <!-- Hotline -->
                    <div class="text-right hidden xl:block">
                        <div class="text-xs text-gray-500">Hotline 24/7</div>
                        <div class="text-sm font-semibold" style="color: #8D4887;">+94 71 3757555</div>
                    </div>

                    <!-- Wishlist -->
                    <a href="#" class="relative" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="absolute -top-2 -right-2 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #8D4887;">2</span>
                    </a>

                    <!-- Cart -->
                    <a href="#" class="relative" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="absolute -top-2 -right-2 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #8D4887;">4</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="border-t border-gray-200">
            <!-- Mobile Menu (Hidden by default) -->
            <div id="mobile-menu" class="lg:hidden hidden border-b border-gray-200">
                <div class="px-4 py-3 space-y-3">
                    <!-- Main Navigation -->
                    <div class="space-y-2">
                        <a href="index" class="block py-2 text-sm" style="color: #8D4887;">Home</a>
                        <a href="about" class="block py-2 text-sm" style="color: #8D4887;">About</a>
                        <a href="products" class="block py-2 text-sm" style="color: #8D4887;">Products</a>
                        <a href="contact" class="block py-2 text-sm" style="color: #8D4887;">Contact</a>
                    </div>
                    
                    <!-- Additional Links -->
                    <div class="border-t border-gray-200 pt-3 space-y-2">
                        <a href="#" class="flex items-center py-2 text-sm" style="color: #8D4887;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Sell on Geotrans
                        </a>
                        <a href="#" class="flex items-center py-2 text-sm" style="color: #8D4887;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Order Tracking
                        </a>
                        <a href="#" class="block py-2 text-sm" style="color: #8D4887;">Recently Viewed</a>
                    </div>
                    
                    <!-- Language/Currency -->
                    <div class="border-t border-gray-200 pt-3">
                        <div class="text-xs text-gray-500 mb-2">Hotline: +94 71 3757555</div>
                        <div class="flex items-center space-x-4">
                            <select class="bg-gray-100 border rounded px-2 py-1 text-sm focus:outline-none" style="color: #8D4887;">
                                <option>USD</option>
                                <option>EUR</option>
                                <option>GBP</option>
                            </select>
                            <select class="bg-gray-100 border rounded px-2 py-1 text-sm focus:outline-none" style="color: #8D4887;">
                                <option>🇺🇸 Eng</option>
                                <option>🇪🇸 Esp</option>
                                <option>🇫🇷 Fra</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:block max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-3">
                    <ul class="flex items-center space-x-8 text-sm">
                        <li>
                            <a href="index" class="flex items-center" style="color: #8D4887;">
                                Home
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="about" class="flex items-center" style="color: #8D4887;">
                                About
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="products" class="flex items-center" style="color: #8D4887;">
                                Products
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="contact" style="color: #8D4887;">Contact</a>
                        </li>
                    </ul>

                    <ul class="flex items-center space-x-6 text-sm">
                        <li class="hidden xl:block">
                            <a href="#" class="flex items-center" style="color: #8D4887;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Sell on Geotrans
                            </a>
                        </li>
                        <li class="hidden xl:block">
                            <a href="#" class="flex items-center" style="color: #8D4887;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Order Tracking
                            </a>
                        </li>
                        <li class="hidden lg:block">
                            <a href="#" class="flex items-center" style="color: #8D4887;">
                                Recently Viewed
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <!-- Language/Currency -->
                            <div class="flex items-center space-x-2 lg:space-x-4">
                                <select class="bg-transparent border-0 text-xs lg:text-sm text-gray-600 focus:outline-none cursor-pointer" style="color: #8D4887;">
                                    <option>USD</option>
                                    <option>EUR</option>
                                    <option>GBP</option>
                                </select>
                                <select class="bg-transparent border-0 text-xs lg:text-sm text-gray-600 focus:outline-none cursor-pointer" style="color: #8D4887;">
                                    <option>🇺🇸 Eng</option>
                                    <option>🇪🇸 Esp</option>
                                    <option>🇫🇷 Fra</option>
                                </select>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Mobile search toggle
        document.getElementById('mobile-search-button').addEventListener('click', function() {
            const mobileSearch = document.getElementById('mobile-search');
            mobileSearch.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSearch = document.getElementById('mobile-search');
            const mobileSearchButton = document.getElementById('mobile-search-button');
            
            if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
            
            if (!mobileSearch.contains(event.target) && !mobileSearchButton.contains(event.target)) {
                mobileSearch.classList.add('hidden');
            }
        });
    </script>

</body>

</html>
