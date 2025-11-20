<?php
require_once 'includes/helpers.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
require_once 'classes/Brand.php';
require_once 'classes/Testimonial.php';

$product = new Product();
$category = new Category();
$brand = new Brand();
$testimonial = new Testimonial();

// Get data for homepage
$categories = $category->getPopular(10);
$featuredProducts = $product->getFeatured(6);
$bestsellers = $product->getBestsellers(20);
$newArrivals = $product->getNewArrivals(20);
$brands = $brand->getPopular(6);
$testimonials = $testimonial->getActive();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoTrans | Leading Office Automation Supplier in Sri Lanka</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom {
            color: #8D4887;
        }

        .bg-purple-custom {
            background-color: #8D4887;
        }

        .hover\:bg-purple-custom:hover {
            background-color: #8D4887;
        }

        .hover\:text-purple-custom:hover {
            color: #8D4887;
        }

        .border-purple-custom {
            border-color: #8D4887;
        }

        .from-purple-custom {
            --tw-gradient-from: #8D4887;
        }

        .to-purple-custom {
            --tw-gradient-to: #8D4887;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            line-clamp: 2;
            -webkit-line-clamp: 2;
        }

        /* Banner Animation Styles */
        .banner-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out;
        }

        .banner-slide.active {
            opacity: 1;
            visibility: visible;
            z-index: 10;
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

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-delay-1 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .animate-delay-2 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        .animate-delay-3 {
            animation-delay: 0.6s;
            opacity: 0;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Rotating Banner Section -->
    <section class="w-full relative overflow-hidden" style="min-height: 600px;">
        <!-- Laptop Banner -->
        <div id="laptopBanner" class="banner-slide active absolute inset-0 w-full bg-purple-50 py-16 sm:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    
                    <!-- Left Side: Content -->
                    <div class="text-center lg:text-left">
                        <span class="inline-block bg-purple-100 text-purple-700 text-xs sm:text-sm px-4 py-2 rounded-full font-semibold mb-4 uppercase tracking-wide animate-fadeInUp" style="color: #8D4887;">
                            Premium Business Laptops
                        </span>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 leading-tight animate-fadeInUp animate-delay-1">
                            Power Your<br>Business <span class="text-purple-600" style="color: #8D4887;">Dreams</span>
                        </h1>
                        <p class="text-lg sm:text-xl text-gray-600 mb-6 max-w-lg mx-auto lg:mx-0 animate-fadeInUp animate-delay-2">
                            Experience unmatched performance with enterprise-grade laptops
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-6 animate-fadeInUp animate-delay-3">
                            <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
                                <div class="bg-purple-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs text-gray-500">Up to</p>
                                    <p class="text-lg font-bold text-gray-900">i7 Processor</p>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
                                <div class="bg-purple-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs text-gray-500">Up to</p>
                                    <p class="text-lg font-bold text-gray-900">32GB RAM</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-8 animate-fadeInUp animate-delay-3">
                            <p class="text-sm text-gray-500 uppercase mb-2">Starting From</p>
                            <p class="text-4xl sm:text-5xl font-bold text-purple-600 mb-2" style="color: #8D4887;">Rs.345,000</p>
                            <p class="text-sm text-gray-600">✓ 0% Interest Financing Available</p>
                        </div>
                    </div>
                    
                    <!-- Right Side: Image -->
                    <div class="relative">
                        <img src="assets/images/products/design.jpeg"
                             alt="Premium Business Laptops"
                             class="w-full max-w-lg mx-auto object-contain">
                        
                        <!-- Floating Badge 1 -->
                        <div class="absolute top-4 right-4 bg-white rounded-2xl p-4 shadow-2xl">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-purple-600" style="color: #8D4887;">512GB</p>
                                <p class="text-xs text-gray-600 font-semibold">SSD Storage</p>
                            </div>
                        </div>
                        
                        <!-- Floating Badge 2 -->
                        <div class="absolute bottom-8 left-4 bg-white rounded-2xl p-4 shadow-2xl" style="animation-delay: 0.5s;">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-purple-600" style="color: #8D4887;">15.6"</p>
                                <p class="text-xs text-gray-600 font-semibold">FHD Display</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Headphone Banner -->
        <div id="headphoneBanner" class="banner-slide absolute inset-0 w-full bg-blue-50 py-6 sm:py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    
                    <!-- Left Side: Content -->
                    <div class="text-center lg:text-left">
                        <span class="inline-block bg-blue-100 text-blue-700 text-xs sm:text-sm px-4 py-2 rounded-full font-semibold mb-4 uppercase tracking-wide animate-fadeInUp">
                            Premium Audio Experience
                        </span>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 leading-tight animate-fadeInUp animate-delay-1">
                            Immerse in<br>Pure <span class="text-blue-600">Sound</span>
                        </h1>
                        <p class="text-lg sm:text-xl text-gray-600 mb-6 max-w-lg mx-auto lg:mx-0 animate-fadeInUp animate-delay-2">
                            Crystal-clear audio with noise cancellation technology
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-6 animate-fadeInUp animate-delay-3">
                            <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
                                <div class="bg-blue-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs text-gray-500">Up to</p>
                                    <p class="text-lg font-bold text-gray-900">40H Battery</p>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
                                <div class="bg-blue-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs text-gray-500">Active</p>
                                    <p class="text-lg font-bold text-gray-900">Noise Cancel</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-8 animate-fadeInUp animate-delay-3">
                            <p class="text-sm text-gray-500 uppercase mb-2">Starting From</p>
                            <p class="text-4xl sm:text-5xl font-bold text-blue-600 mb-2">Rs.14,000</p>
                            <p class="text-sm text-gray-600">✓ Free Shipping Available</p>
                        </div>
                    </div>
                    
                    <!-- Right Side: Image -->
                    <div class="relative">
                        <img src="assets/images/home/black-wireless-headphones-black-surface 1.png"
                             alt="Premium Headphones"
                             class="w-full max-w-sm mx-auto object-contain">
                        
                        <!-- Floating Badge 1 -->
                        <!-- <div class="absolute top-0 right-0 bg-white rounded-xl p-3 shadow-2xl">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">40H</p>
                                <p class="text-xs text-gray-600 font-semibold">Battery</p>
                            </div>
                        </div> -->
                        
                        <!-- Floating Badge 2 -->
                        <!-- <div class="absolute bottom-2 left-0 bg-white rounded-xl p-3 shadow-2xl" style="animation-delay: 0.5s;">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">ANC</p>
                                <p class="text-xs text-gray-600 font-semibold">Noise Cancel</p>
                            </div>
                        </div> -->
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Banner Navigation Dots -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-20">
            <button onclick="switchBanner(0)" id="bannerDot0" class="w-3 h-3 rounded-full bg-purple-600 transition-all"></button>
            <button onclick="switchBanner(1)" id="bannerDot1" class="w-3 h-3 rounded-full bg-gray-300 transition-all"></button>
        </div>
    </section>

    <!-- Popular Categories Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Popular Categories</h2>
            <a href="category.php" class="text-gray-600 hover:text-purple-custom font-medium">View All</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-10 gap-4 sm:gap-6 mb-8 sm:mb-12">
            <?php foreach ($categories as $cat): ?>
                <a href="category.php?id=<?= $cat['category_id'] ?>" class="flex flex-col items-center group cursor-pointer">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-gray-200 rounded-full flex items-center justify-center mb-2 sm:mb-3 group-hover:bg-purple-100 transition-colors">
                        <img src="<?= !empty($cat['category_image']) ? 'assets/images/categories/' . htmlspecialchars($cat['category_image']) : 'assets/images/categories/default.png' ?>"
                            alt="<?= htmlspecialchars($cat['category_name']) ?>"
                            class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 object-contain">
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-purple-custom text-center"><?= htmlspecialchars($cat['category_name']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Featured Product Banners (static promotional banners) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
            <div class="bg-gradient-to-br from-gray-600 to-gray-800 rounded-2xl sm:rounded-3xl overflow-hidden relative h-64 sm:h-80">
                <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-center">
                    <h3 class="text-white text-2xl sm:text-4xl font-bold mb-1 sm:mb-2">Epson</h3>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-1">Work Force</h4>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-4 sm:mb-6">AL-M310DN</h4>
                    <p class="text-gray-300 text-xs sm:text-sm mb-1">EPSON WORK FORCE AL-M310DN</p>
                    <p class="text-gray-300 text-xs sm:text-sm mb-4 sm:mb-6">IN STOCK</p>
                    <a href="products.php?category=printers" class="bg-gradient-to-r from-purple-custom to-purple-700 hover:from-purple-700 hover:to-purple-600 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-full font-semibold w-fit text-sm sm:text-base">Shop Now</a>
                </div>
                <div class="absolute right-0 top-0 h-full w-1/2">
                    <img src="assets/images/home/Epson-WorkForce-AL-M310dn-Printer-2-1-removebg-preview 1.png" alt="Epson Printer" class="h-full w-full object-contain">
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-300 via-orange-200 to-orange-100 rounded-2xl sm:rounded-3xl overflow-hidden relative h-64 sm:h-80 bg-cover bg-center" style="background-image:url('assets/images/home/freepik__sleek-laptop-on-tidy-modern-desk-tiny-potted-succu__24669.png')">
                <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-center z-10">
                    <h3 class="text-white text-2xl sm:text-4xl font-bold mb-1 sm:mb-2">Laptop</h3>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-1">Workspace</h4>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-4 sm:mb-6">Setup</h4>
                    <p class="text-white text-xs sm:text-sm mb-1">MODERN WORKSPACE</p>
                    <p class="text-white text-xs sm:text-sm mb-4 sm:mb-6">HIGH PERFORMANCE</p>
                    <a href="products.php?category=laptops" class="bg-gradient-to-r from-purple-custom to-purple-700 hover:from-purple-700 hover:to-purple-600 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-full font-semibold w-fit text-sm sm:text-base">Discover Now</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-gradient-to-br from-yellow-100 via-orange-100 to-orange-200 rounded-3xl overflow-hidden relative h-64" style="background-image: url('assets/images/home/head_3.png.png'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 p-8">
                    <h3 class="text-gray-900 text-3xl font-bold mb-1">Keyboard</h3>
                    <h4 class="text-gray-900 text-3xl font-bold mb-2">2025</h4>
                    <p class="text-gray-700 text-sm mb-6">Mega Power in mini size</p>
                    <a href="products.php?category=keyboards" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-full font-semibold text-sm inline-block">Shop Now</a>
                </div>
            </div>

            <div class="bg-gradient-to-br rounded-3xl overflow-hidden relative h-64" style="background-image: url('assets/images/home/black-wireless-headphones-black-surface 1.png'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 p-8 flex flex-col">
                    <h3 class="text-white text-2xl font-bold mb-2">Headset</h3>
                    <div class="mt-auto">
                        <p class="text-gray-400 text-xs mb-1">FROM</p>
                        <p class="text-yellow-400 text-2xl font-bold">Rs14,000.00</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-300 to-gray-400 rounded-3xl overflow-hidden relative h-64">
                <div class="absolute inset-0 p-8">
                    <p class="text-gray-600 text-xs uppercase mb-1">MONITORS</p>
                    <h3 class="text-white text-xl font-bold mb-1">VS197DE</h3>
                    <h4 class="text-white text-xl font-bold mb-1">LED</h4>
                    <h5 class="text-white text-xl font-bold mb-6">Monitor</h5>
                    <a href="products.php?category=monitors" class="bg-white hover:bg-gray-100 text-gray-900 px-6 py-2.5 rounded-full font-semibold text-sm inline-block">Shop Now</a>
                </div>
                <div class="absolute right-0 bottom-0 w-1/2 h-3/4">
                    <img src="assets/images/home/modern-tv-screen-isolated 1.png" alt="Monitor" class="h-full w-full object-contain">
                </div>
            </div>
        </div>
    </section>

    <!-- Top Laptop Brands Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6 sm:mb-8">Top Brands</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 sm:gap-8 lg:gap-12 mb-8 sm:mb-12 justify-items-center">
            <?php if (!empty($brands)): ?>
                <?php foreach ($brands as $b): ?>
                    <a href="products.php?brand=<?= $b['brand_id'] ?>" class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                        <img src="<?= !empty($b['brand_logo']) ? 'assets/images/brands/' . htmlspecialchars($b['brand_logo']) : 'assets/images/home/hp-300x300-1 1.png' ?>"
                            alt="<?= htmlspecialchars($b['brand_name']) ?>"
                            class="h-full object-contain">
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <img src="assets/images/home/hp-300x300-1 1.png" alt="HP" class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                <img src="assets/images/home/hp-300x300-1 2.png" alt="ASUS" class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                <img src="assets/images/home/hp-300x300-1 3.png" alt="Lenovo" class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                <img src="assets/images/home/hp-300x300-1 4.png" alt="MSI" class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                <img src="assets/images/home/hp-300x300-1 5.png" alt="Dell" class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                <img src="assets/images/home/hp-300x300-1 6.png" alt="Acer" class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
            <?php endif; ?>
        </div>
    </section>

    <!-- Premium Laptop Promotion Banner -->
    <section class="w-full py-8 sm:py-12 bg-gradient-to-br from-purple-200 to-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">

                <!-- Left Side: Promotional Content -->
                <div class="text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-purple-600 uppercase font-semibold mb-2 tracking-wide">PREMIUM BUSINESS LAPTOPS</p>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-2">Power Your</h2>
                    <h3 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Business <span class="text-purple-custom">Dreams</span></h3>
                    
                    <p class="text-sm sm:text-base text-gray-600 mb-6 max-w-md mx-auto lg:mx-0">
                        Experience unmatched performance with the latest ThinkPad series. Built for professionals who demand the best.
                    </p>

                    <div class="mb-6">
                        <p class="text-xs text-gray-500 uppercase mb-1">STARTING FROM</p>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-green-600">Rs.345,000</p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">0% Interest Financing Available</p>
                    </div>

                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                        <a href="products.php?category=business-laptops" 
                           class="bg-purple-custom hover:bg-purple-700 text-white px-6 sm:px-8 py-3 rounded-full font-semibold transition-colors shadow-lg hover:shadow-xl">
                            Shop Now
                        </a>
                        <a href="products.php" 
                           class="bg-white hover:bg-gray-50 text-gray-900 px-6 sm:px-8 py-3 rounded-full font-semibold transition-colors border-2 border-gray-200">
                            View All Laptops
                        </a>
                    </div>
                </div>

                <!-- Right Side: Product Image -->
                <div class="relative">
                    <div class="relative z-10">
                        <img src="assets/images/products/design.jpeg"
                            alt="Premium Laptops"
                            class="w-full max-w-md mx-auto object-contain drop-shadow-2xl">
                    </div>
                    
                    <!-- Feature Badges -->
                    <div class="absolute top-0 right-0 bg-white rounded-full p-3 sm:p-4 shadow-lg">
                        <div class="text-center">
                            <p class="text-xl sm:text-2xl font-bold text-purple-custom">16GB</p>
                            <p class="text-xs text-gray-600">RAM</p>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-4 left-4 bg-white rounded-full p-3 sm:p-4 shadow-lg">
                        <div class="text-center">
                            <p class="text-xl sm:text-2xl font-bold text-purple-custom">i7</p>
                            <p class="text-xs text-gray-600">Processor</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Best Sellers Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 bg-gray-50">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Best Sellers</h2>
            <a href="products.php?sort=bestsellers" class="text-gray-600 hover:text-purple-custom font-medium">View All</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            <?php foreach ($bestsellers as $p): ?>
                <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group flex flex-col">
                    <button onclick="addToWishlist(<?= $p['product_id'] ?>)"
                        class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white hover:border-red-500 transition-colors z-10">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>

                    <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="block flex flex-col h-full">
                        <!-- Badge Section - Fixed Height -->
                        <div class="h-6 mb-2">
                            <?php if ($p['discount_percentage'] > 0): ?>
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block">
                                    -<?= $p['discount_percentage'] ?>% OFF
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Product Image -->
                        <div class="flex items-center justify-center h-48 mb-4">
                            <?php
                            $mainImage = !empty($p['main_image']) ? 'assets/images/products/' . $p['main_image'] : 'assets/images/categories/default.png';
                            ?>
                            <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="max-h-full object-contain">
                        </div>

                        <!-- Product Title - Fixed Height -->
                        <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">
                            <?= htmlspecialchars($p['product_name']) ?>
                        </h3>

                        <!-- Rating Section -->
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400 text-xs">
                                <?php
                                $rating = $p['avg_rating'] ?? 0;
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $rating ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <span class="text-gray-500 text-xs ml-1">(<?= $p['review_count'] ?? 0 ?>)</span>
                        </div>

                        <!-- Price Section -->
                        <div class="flex items-baseline flex-wrap gap-2 mb-3">
                            <?php if (!empty($p['sale_price']) && $p['sale_price'] < $p['price']): ?>
                                <span class="text-purple-custom font-bold text-lg">
                                    Rs<?= number_format($p['sale_price'], 2) ?>
                                </span>
                                <span class="text-gray-400 text-sm line-through">
                                    Rs<?= number_format($p['price'], 2) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-purple-custom font-bold text-lg">
                                    Rs<?= number_format($p['price'], 2) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Stock Section - Fixed Height -->
                        <div class="mt-auto">
                            <?php if (isset($p['stock_quantity']) && $p['stock_quantity'] > 0): ?>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                    <div class="bg-purple-custom h-1.5 rounded-full" style="width: <?= min(100, ($p['stock_quantity'] / 100) * 100) ?>%"></div>
                                </div>
                                <p class="text-xs text-gray-500"><?= $p['stock_quantity'] ?> in stock</p>
                            <?php else: ?>
                                <div class="h-6"></div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Pre Order Banner -->
    <section class="w-full py-8 sm:py-12 bg-gradient-to-r from-gray-800 via-gray-700 to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-center">

                <!-- Column 1: Left Text -->
                <div class="flex flex-col justify-center">
                    <span class="text-purple-400 font-bold text-base sm:text-lg uppercase tracking-wide mb-1">PRE ORDER</span>
                    <p class="text-gray-400 text-xs mb-1">BE THE FIRST TO OWN</p>
                    <p class="text-white font-bold text-lg sm:text-xl">From Rs.100,000.00</p>
                </div>

                <!-- Column 2: Image -->
                <div class="flex justify-center items-center">
                    <img src="assets/images/home/3d-illustration-2-laptops-with-open-browser-tab-screen-search-internet-template-search-user-interface 2.png"
                        alt="ASUS Laptops"
                        class="h-32 sm:h-40 lg:h-48 object-contain">
                </div>

                <!-- Column 3: Specs -->
                <div class="flex flex-col justify-center">
                    <h3 class="text-white text-xl sm:text-2xl lg:text-3xl font-bold mb-2">ASUS EXPERTBOOK</h3>
                    <p class="text-purple-400 text-xs sm:text-sm">ASUS EXPERTBOOK B1502CVA-i7/16512G5D | i7-1355U | 16GB DDR5 |</p>
                    <p class="text-purple-400 text-xs sm:text-sm">512GB NVME | 15.6 FHD | DOS</p>
                </div>

                <!-- Column 4: Button -->
                <div class="flex flex-col justify-center">
                    <a href="products.php?featured=preorder" class="bg-white hover:bg-gray-100 text-gray-900 px-6 sm:px-8 py-2 sm:py-2.5 rounded-full font-semibold transition-colors text-center">
                        Discover Now
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">New Arrivals</h2>
            <a href="products.php?sort=newest" class="text-gray-600 hover:text-purple-custom font-medium">View All</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            <?php foreach ($newArrivals as $p): ?>
                <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group flex flex-col">
                    <button onclick="addToWishlist(<?= $p['product_id'] ?>)"
                        class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white hover:border-red-500 transition-colors z-10">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>

                    <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="block flex flex-col h-full">
                        <!-- Badge Section - Fixed Height -->
                        <div class="h-6 mb-2">
                            <span class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold inline-block">NEW</span>
                        </div>

                        <!-- Product Image -->
                        <div class="flex items-center justify-center h-48 mb-4">
                            <?php
                            $mainImage = !empty($p['main_image']) ? 'assets/images/products/' . $p['main_image'] : 'assets/images/categories/default.png';
                            ?>
                            <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="max-h-full object-contain">
                        </div>

                        <!-- Product Title - Fixed Height -->
                        <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">
                            <?= htmlspecialchars($p['product_name']) ?>
                        </h3>

                        <!-- Rating Section -->
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400 text-xs">
                                <?php
                                $rating = $p['avg_rating'] ?? 0;
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $rating ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <span class="text-gray-500 text-xs ml-1">(<?= $p['review_count'] ?? 0 ?>)</span>
                        </div>

                        <!-- Price Section -->
                        <div class="flex items-baseline flex-wrap gap-2 mb-3">
                            <?php if (!empty($p['sale_price']) && $p['sale_price'] < $p['price']): ?>
                                <span class="text-purple-custom font-bold text-lg">
                                    Rs<?= number_format($p['sale_price'], 2) ?>
                                </span>
                                <span class="text-gray-400 text-sm line-through">
                                    Rs<?= number_format($p['price'], 2) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-purple-custom font-bold text-lg">
                                    Rs<?= number_format($p['price'], 2) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Stock Section - Fixed Height -->
                        <div class="mt-auto">
                            <?php if (isset($p['stock_quantity']) && $p['stock_quantity'] > 0): ?>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                    <div class="bg-purple-custom h-1.5 rounded-full" style="width: <?= min(100, ($p['stock_quantity'] / 100) * 100) ?>%"></div>
                                </div>
                                <p class="text-xs text-gray-500"><?= $p['stock_quantity'] ?> in stock</p>
                            <?php else: ?>
                                <div class="h-6"></div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Customer Feedback Section -->
    <section class="w-full px-4 sm:px-6 lg:px-8 py-12 sm:py-16" style="background-color: #f3e8f2;">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">What Our Customers Say</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Trusted by thousands of satisfied customers across Sri Lanka</p>
        </div>

        <div class="relative max-w-7xl mx-auto">
            <!-- Navigation Buttons -->
            <button id="prevFeedback" 
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 sm:-translate-x-12 z-10 bg-white hover:bg-purple-custom text-gray-700 hover:text-white w-10 h-10 sm:w-12 sm:h-12 rounded-full shadow-lg flex items-center justify-center transition-colors duration-300">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button id="nextFeedback" 
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 sm:translate-x-12 z-10 bg-white hover:bg-purple-custom text-gray-700 hover:text-white w-10 h-10 sm:w-12 sm:h-12 rounded-full shadow-lg flex items-center justify-center transition-colors duration-300">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Feedback Cards Container -->
            <div class="overflow-hidden px-2">
                <div id="feedbackContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-500 ease-in-out">
                    <!-- Feedback cards will be dynamically inserted here -->
                </div>
            </div>

            <!-- Pagination Dots -->
            <div id="feedbackDots" class="flex justify-center gap-2 mt-8">
                <!-- Dots will be dynamically inserted here -->
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Feedback testimonials data from database
        const feedbacks = <?= json_encode(array_map(function($t) {
            // Determine image path
            $imagePath = 'https://ui-avatars.com/api/?name=' . urlencode($t['customer_name']) . '&background=8D4887&color=fff&size=128';
            
            if (!empty($t['customer_image'])) {
                // Check if it's from user profile or custom testimonial image
                if (file_exists('assets/images/profiles/' . $t['customer_image'])) {
                    $imagePath = 'assets/images/profiles/' . $t['customer_image'];
                } elseif (file_exists('assets/images/testimonials/' . $t['customer_image'])) {
                    $imagePath = 'assets/images/testimonials/' . $t['customer_image'];
                }
            }
            
            return [
                'name' => $t['customer_name'],
                'role' => $t['customer_role'],
                'image' => $imagePath,
                'rating' => (int)$t['rating'],
                'text' => $t['feedback_text'],
                'date' => $t['feedback_date'] ?? '',
                'verified' => (int)($t['is_verified'] ?? 0)
            ];
        }, $testimonials)) ?>;

        let currentFeedbackIndex = 0;

        // Generate star rating HTML
        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += `<svg class="w-5 h-5 ${i <= rating ? 'text-yellow-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.602-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>`;
            }
            return stars;
        }

        // Create feedback card HTML
        function createFeedbackCard(feedback) {
            return `
                <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-purple-100">
                    <!-- Quote Icon -->
                    <div class="text-purple-custom mb-4">
                        <svg class="w-10 h-10 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                    </div>

                    <!-- Feedback Text -->
                    <p class="text-gray-700 text-sm sm:text-base mb-6 leading-relaxed">
                        "${feedback.text}"
                    </p>

                    <!-- Rating -->
                    <div class="flex items-center mb-4">
                        ${generateStars(feedback.rating)}
                    </div>

                    <!-- Customer Info -->
                    <div class="flex items-center justify-between pt-4 border-t border-purple-100">
                        <div class="flex items-center gap-3">
                            <img src="${feedback.image}" 
                                 alt="${feedback.name}" 
                                 class="w-12 h-12 rounded-full object-cover border-2 border-purple-200">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base">${feedback.name}</h4>
                                    ${feedback.verified ? '<i class="fas fa-check-circle text-blue-500 text-sm" title="Verified Customer"></i>' : ''}
                                </div>
                                <p class="text-gray-500 text-xs sm:text-sm">${feedback.role}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-xs">${feedback.date}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        // Render feedback cards
        function renderFeedbacks() {
            const container = document.getElementById('feedbackContainer');
            const feedback1 = feedbacks[currentFeedbackIndex];
            const feedback2 = feedbacks[(currentFeedbackIndex + 1) % feedbacks.length];
            
            container.innerHTML = createFeedbackCard(feedback1) + createFeedbackCard(feedback2);
            updateDots();
        }

        // Update pagination dots
        function updateDots() {
            const dotsContainer = document.getElementById('feedbackDots');
            const totalPages = Math.ceil(feedbacks.length / 2);
            const currentPage = Math.floor(currentFeedbackIndex / 2);
            
            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('button');
                dot.className = `w-2.5 h-2.5 rounded-full transition-all duration-300 ${i === currentPage ? 'bg-purple-custom w-8' : 'bg-gray-300 hover:bg-gray-400'}`;
                dot.onclick = () => goToPage(i);
                dotsContainer.appendChild(dot);
            }
        }

        // Navigate to specific page
        function goToPage(pageIndex) {
            currentFeedbackIndex = pageIndex * 2;
            if (currentFeedbackIndex >= feedbacks.length) {
                currentFeedbackIndex = 0;
            }
            renderFeedbacks();
        }

        // Navigate to next feedbacks
        function nextFeedbacks() {
            currentFeedbackIndex = (currentFeedbackIndex + 2) % feedbacks.length;
            renderFeedbacks();
        }

        // Navigate to previous feedbacks
        function prevFeedbacks() {
            currentFeedbackIndex = (currentFeedbackIndex - 2 + feedbacks.length) % feedbacks.length;
            renderFeedbacks();
        }

        // Initialize feedback section
        document.addEventListener('DOMContentLoaded', function() {
            renderFeedbacks();
            
            // Add event listeners to navigation buttons
            document.getElementById('nextFeedback').addEventListener('click', nextFeedbacks);
            document.getElementById('prevFeedback').addEventListener('click', prevFeedbacks);

            // Auto-rotate feedbacks every 5 seconds
            setInterval(nextFeedbacks, 5000);

            // Initialize banner rotation
            startBannerRotation();
        });

        // Banner Rotation Logic
        let currentBannerIndex = 0;
        let bannerInterval;
        const banners = [
            document.getElementById('laptopBanner'),
            document.getElementById('headphoneBanner')
        ];
        const dots = [
            document.getElementById('bannerDot0'),
            document.getElementById('bannerDot1')
        ];

        function switchBanner(index) {
            if (index === currentBannerIndex) return;

            const newBanner = banners[index];
            const oldBanner = banners[currentBannerIndex];
            
            // Hide old banner
            oldBanner.classList.remove('active');
            
            // Show new banner
            newBanner.classList.add('active');
            
            // Re-trigger animations for text in new banner
            setTimeout(() => {
                const animatedElements = newBanner.querySelectorAll('.animate-fadeInUp');
                animatedElements.forEach(el => {
                    el.style.animation = 'none';
                    el.offsetHeight; // Trigger reflow
                    el.style.animation = '';
                });
            }, 50);

            // Update dots
            dots[currentBannerIndex].classList.remove('bg-purple-600', 'bg-blue-600');
            dots[currentBannerIndex].classList.add('bg-gray-300');
            
            if (index === 0) {
                dots[index].classList.remove('bg-gray-300');
                dots[index].classList.add('bg-purple-600');
            } else {
                dots[index].classList.remove('bg-gray-300');
                dots[index].classList.add('bg-blue-600');
            }

            currentBannerIndex = index;

            // Reset interval
            clearInterval(bannerInterval);
            bannerInterval = setInterval(() => {
                switchBanner((currentBannerIndex + 1) % banners.length);
            }, 5000);
        }

        function startBannerRotation() {
            bannerInterval = setInterval(() => {
                switchBanner((currentBannerIndex + 1) % banners.length);
            }, 5000);
        }

        // Add to cart function for quick add buttons (using existing CartManager)
        function addToCart(productId, quantity = 1) {
            // Wait for CartManager to be initialized
            if (window.cartManager) {
                const formData = new FormData();
                formData.append('action', 'add');
                formData.append('product_id', productId);
                formData.append('quantity', quantity);

                fetch('api/cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.cartManager.showNotification('Product added to cart!', 'success');
                            window.cartManager.updateCartCount();
                        } else {
                            window.cartManager.showNotification('Failed to add product', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.cartManager.showNotification('An error occurred', 'error');
                    });
            }
        }

        // Add to wishlist function
        function addToWishlist(productId) {
            fetch('api/wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'add',
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (window.cartManager) {
                        window.cartManager.showNotification('Added to wishlist!', 'success');
                    } else {
                        alert('Added to wishlist!');
                    }
                    
                    // Update wishlist count in header
                    if (data.wishlist_count !== undefined) {
                        const wishlistCounts = document.querySelectorAll('.wishlist-count');
                        wishlistCounts.forEach(element => {
                            element.textContent = data.wishlist_count;
                            if (data.wishlist_count > 0) {
                                element.classList.remove('hidden');
                            }
                        });
                    }
                } else {
                    if (data.message === 'Please login first') {
                        if (window.cartManager) {
                            window.cartManager.showNotification('Please login to add to wishlist', 'warning');
                        }
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 1500);
                    } else {
                        if (window.cartManager) {
                            window.cartManager.showNotification(data.message || 'Failed to add to wishlist', 'error');
                        } else {
                            alert(data.message || 'Failed to add to wishlist');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.cartManager) {
                    window.cartManager.showNotification('An error occurred', 'error');
                } else {
                    alert('An error occurred');
                }
            });
        }
    </script>

</body>

</html>