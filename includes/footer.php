<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Footer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

    <!-- Newsletter Section -->
    <section class="relative py-8 md:py-16" style="background-color: #8D4887;">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <!-- Text and Offer -->
                <div class="flex flex-col sm:flex-row lg:flex-row items-center text-center lg:text-left gap-2 lg:gap-2">
                    <h2 class="text-white text-xl sm:text-2xl font-bold">Subscribe</h2>
                    <span class="text-white text-xl sm:text-2xl">& Get</span>
                    <span class="text-cyan-300 text-xl sm:text-2xl font-bold">10% OFF</span>
                    <span class="text-white text-xl sm:text-2xl">for first order</span>
                </div>

                <!-- Email Input -->
                <div class="flex flex-col sm:flex-row items-center bg-white px-2 sm:px-4 py-2 w-full lg:w-96 gap-2 sm:gap-0" style="border-radius: 9px;">
                    <div class="flex items-center w-full sm:flex-1">
                        <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <input type="email" placeholder="Enter your email address" class="flex-1 px-4 py-2 focus:outline-none text-sm" style="border-radius: 9px;" />
                    </div>
                    <button class="bg-black text-white px-4 sm:px-6 py-2 font-semibold hover:bg-gray-800 transition-colors text-sm w-full sm:w-auto" style="border-radius: 9px;">
                        SUBSCRIBE
                    </button>
                </div>
            </div>
        </div>

        <!-- Centered Decorative Plane -->
        <img src="assets/images/home/plane.png" alt="Decorative paper plane" class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 md:w-96 h-auto opacity-80 pointer-events-none" />
    </section>

    <!-- Features Section -->
    <section class="bg-white border-b py-4 md:py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
                <!-- Free Shipping -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: #8D4887;">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-semibold text-gray-800">FREE SHIPPING OVER $99</p>
                    </div>
                </div>

                <!-- Money Back -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: #8D4887;">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-semibold text-gray-800">30 DAYS MONEY BACK</p>
                    </div>
                </div>

                <!-- Secure Payment -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: #8D4887;">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-semibold text-gray-800">100% SECURE PAYMENT</p>
                    </div>
                </div>

                <!-- Support -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: #8D4887;">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-semibold text-gray-800">24/7 DEDICATED SUPPORT</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Footer -->
    <footer class="bg-white py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 lg:gap-8">

                <!-- Company Info -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <!-- Logo -->
                    <div class="flex items-center justify-center sm:justify-start">
                        <a href="index.php" class="flex items-center">
                            <img src="assets/images/logo.png" alt="GeoTrans Pvt Ltd Logo" class="h-12 md:h-16 object-contain">
                        </a>
                    </div>
                    <br>
                    <!-- <h3 class="font-bold mb-4" style="color: #8D4887;">Geotrans (PVT) LTD</h3> -->
                    <div class="space-y-2 text-sm text-gray-600 text-center sm:text-left">
                        <p class="text-xs text-gray-500 uppercase">Hotline 24/7</p>
                        <p class="font-bold text-lg" style="color: #8D4887;">071 3757555</p>
                        <p class="mt-4">No 60 Dhamma Road<br>NY 10092</p>
                        <p>infogeotrans1@gmail.com</p>
                    </div>

                    <!-- Social Icons -->
                    <div class="flex space-x-3 mt-6 justify-center sm:justify-start">
                        <a href="#" class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center hover:bg-[#8D4887] hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center hover:bg-[#8D4887] hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                            </svg>
                        </a>
                        <!-- <a href="#" class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center hover:bg-[#8D4887] hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                                <path d="M7.5 2h9A5.5 5.5 0 0122 7.5v9a5.5 5.5 0 01-5.5 5.5h-9A5.5 5.5 0 012 16.5v-9A5.5 5.5 0 017.5 2z"></path>
                            </svg>
                        </a> -->
                        <a href="#" class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center hover:bg-[#8D4887] hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23 9.71a8.5 8.5 0 00-.91-4.13 2.92 2.92 0 00-1.72-1A78.36 78.36 0 0012 4.27a78.45 78.45 0 00-8.34.3 2.87 2.87 0 00-1.46.74c-.9.83-1 2.25-1.1 3.45a48.29 48.29 0 000 6.48 9.55 9.55 0 00.3 2.12 2.93 2.93 0 001.71 1.54A78.36 78.36 0 0012 19.73a78.45 78.45 0 008.34-.3 2.87 2.87 0 001.46-.74c.9-.83 1-2.25 1.1-3.45a48.29 48.29 0 000-6.48zM9.74 14.85V8.66l5.92 3.11z"></path>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center hover:bg-[#8D4887] hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.5 1.15a26 26 0 00-3.36-.15C11.7 1 10 2.47 10 5.3v2.6H6.5v4.2H10V23h4.5V12.1h3.5l.5-4.2h-4V6c0-1.03.28-1.72 1.75-1.72h2.25V.17z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Top Categories -->
                <div class="text-center sm:text-left">
                    <h4 class="font-bold mb-4" style="color: #8D4887;">Top Categories</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-[#8D4887]">TV/Televisions</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Computers</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Laptops</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Mobiles & Tablets</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Audios</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Cameras</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Gadget</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Sport Equipments</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Office</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Smart Home</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div class="text-center sm:text-left">
                    <h4 class="font-bold mb-4" style="color: #8D4887;">Company</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-[#8D4887]">About GeoTrans</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Contact</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Career</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Blog</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Sitemap</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Store Locations</a></li>
                    </ul>
                </div>

                <!-- Help Center -->
                <div class="text-center sm:text-left">
                    <h4 class="font-bold mb-4" style="color: #8D4887;">Help Center</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-[#8D4887]">Customer Service</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Policy</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Terms & Conditions</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Track Order</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">FAQs</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">My Account</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Product Support</a></li>
                    </ul>
                </div>

                <!-- Partner -->
                <div class="text-center sm:text-left">
                    <h4 class="font-bold mb-4" style="color: #8D4887;">Partner</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-[#8D4887]">Become Seller</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Affiliate</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Advertise</a></li>
                        <li><a href="#" class="hover:text-[#8D4887]">Partnership</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>


    <!-- Bottom Footer -->
    <div class="bg-gray-100 py-4 border-t">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4 lg:gap-0">
                <div class="text-xs md:text-sm text-gray-600 text-center lg:text-left">
                    © 2025 SLTDS. All Rights Reserved.
                </div>

                <!-- Payment Methods -->
                <div class="flex items-center space-x-2 md:space-x-4 order-first lg:order-none">
                    <img src="assets/images/footer/paypal.png" alt="PayPal" class="h-3 md:h-4">
                    <img src="assets/images/footer/stripe.png" alt="Stripe" class="h-3 md:h-4">
                    <img src="assets/images/footer/master.png" alt="Mastercard" class="h-3 md:h-4">
                    <img src="assets/images/footer/klarna.png" alt="Klarna" class="h-3 md:h-4">
                    <img src="assets/images/footer/visa.png" alt="Visa" class="h-3 md:h-4">
                </div>

                <!-- Language/Currency -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    <select class="bg-transparent border-0 text-xs md:text-sm text-gray-600 focus:outline-none cursor-pointer">
                        <option>USD</option>
                        <option>EUR</option>
                        <option>GBP</option>
                    </select>
                    <select class="bg-transparent border-0 text-xs md:text-sm text-gray-600 focus:outline-none cursor-pointer">
                        <option>🇺🇸 Eng</option>
                        <option>🇪🇸 Esp</option>
                        <option>🇫🇷 Fra</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

</body>

</html>