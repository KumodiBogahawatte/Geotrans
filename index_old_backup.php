<?php
require_once 'includes/helpers.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
require_once 'classes/Brand.php';

$product = new Product();
$category = new Category();
$brand = new Brand();

// Get data for homepage
$categories = $category->getPopular(10);
$featuredProducts = $product->getFeatured(10);
$bestsellers = $product->getBestsellers(20);
$newArrivals = $product->getNewArrivals(20);
$brands = $brand->getPopular(6);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoTrans | Leading Office Automation Supplier in Sri Lanka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom purple color definition */
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
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(141, 72, 135, 0));
        }

        .to-purple-custom {
            --tw-gradient-to: #8D4887;
        }

        /* Custom scrollbar */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Line clamp utility */
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            line-clamp: 2;
            -webkit-line-clamp: 2;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Popular Categories Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Section Header -->
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Popular Categories</h2>
            <a href="category.php" class="text-gray-600 hover:text-purple-custom font-medium">View All</a>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-10 gap-4 sm:gap-6 mb-8 sm:mb-12">

            <?php foreach ($categories as $cat): ?>
            <!-- <?= htmlspecialchars($cat['name']) ?> -->
            <a href="category.php?id=<?= $cat['category_id'] ?>" class="flex flex-col items-center group cursor-pointer">
                <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-gray-200 rounded-full flex items-center justify-center mb-2 sm:mb-3 group-hover:bg-purple-100 transition-colors">
                    <img src="<?= !empty($cat['image']) ? 'assets/images/categories/' . htmlspecialchars($cat['image']) : 'assets/images/categories/default.png' ?>" 
                         alt="<?= htmlspecialchars($cat['name']) ?>"
                         class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 object-contain">
                </div>
                <span class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-purple-custom text-center"><?= htmlspecialchars($cat['name']) ?></span>
            </a>
            <?php endforeach; ?>
                        class="w-16 h-16 object-contain">
                </div>
                <span class="text-sm font-medium text-gray-700 group-hover:text-purple-custom">Printers</span>
            </div>

            <!-- Projectors -->
            <div class="flex flex-col items-center group cursor-pointer">
                <div
                    class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-100 transition-colors">
                    <img src="assets/images/categories/black-multimedia-projector-isolated-white-bac 1.png" alt="Projectors"
                        class="w-16 h-16 object-contain">
                </div>
                <span class="text-sm font-medium text-gray-700 group-hover:text-purple-custom">Projectors</span>

        </div>

        <!-- Featured Products Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">

            <!-- Large Banner: Epson Printer -->
            <div class="bg-gradient-to-br from-gray-600 to-gray-800 rounded-2xl sm:rounded-3xl overflow-hidden relative h-64 sm:h-80">
                <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-center">
                    <h3 class="text-white text-2xl sm:text-4xl font-bold mb-1 sm:mb-2">Epson</h3>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-1">Work Force</h4>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-4 sm:mb-6">AL-M310DN</h4>
                    <p class="text-gray-300 text-xs sm:text-sm mb-1">EPSON WORK</p>
                    <p class="text-gray-300 text-xs sm:text-sm mb-1">FORCE AL-M310DN</p>
                    <p class="text-gray-300 text-xs sm:text-sm mb-4 sm:mb-6">2000 IN STOCK</p>
                    <button
                        class="bg-gradient-to-r from-purple-custom to-purple-700 hover:from-purple-700 hover:to-purple-600 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-full font-semibold w-fit text-sm sm:text-base">
                        Shop Now
                    </button>
                </div>
                <div class="absolute right-0 top-0 h-full w-1/2">
                    <img src="assets/images/home/Epson-WorkForce-AL-M310dn-Printer-2-1-removebg-preview 1.png" alt="Epson Printer"
                        class="h-full w-full object-contain">
                </div>
            </div>

            <!-- Laptop Banner -->
            <div class="bg-gradient-to-br from-red-300 via-orange-200 to-orange-100 rounded-2xl sm:rounded-3xl overflow-hidden relative h-64 sm:h-80 bg-cover bg-center"
                style="background-image:url('assets/images/home/freepik__sleek-laptop-on-tidy-modern-desk-tiny-potted-succu__24669.png')">

                <!-- Left Content -->
                <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-center z-10">
                    <h3 class="text-white text-2xl sm:text-4xl font-bold mb-1 sm:mb-2">Laptop</h3>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-1">Workspace</h4>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-4 sm:mb-6">Setup</h4>

                    <p class="text-white text-xs sm:text-sm mb-1">MODERN WORKSPACE</p>
                    <p class="text-white text-xs sm:text-sm mb-4 sm:mb-6">HIGH PERFORMANCE</p>

                    <button
                        class="bg-gradient-to-r from-purple-custom to-purple-700 hover:from-purple-700 hover:to-purple-600 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-full font-semibold w-fit text-sm sm:text-base">
                        Discover Now
                    </button>
                </div>
            </div>


        </div>

        <!-- Bottom Grid: 3 Products -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

            <!-- Keyboard 2025 -->
            <div
                class="bg-gradient-to-br from-yellow-100 via-orange-100 to-orange-200 rounded-3xl overflow-hidden relative h-64" style="background-image: url('assets/images/home/head_3.png.png'); background-size: cover; background-position: center; width: 100%;">
                <div class="absolute inset-0 p-8">
                    <h3 class="text-gray-900 text-3xl font-bold mb-1">Keyboard</h3>
                    <h4 class="text-gray-900 text-3xl font-bold mb-2">2025</h4>
                    <p class="text-gray-700 text-sm mb-6">Mega Power in mini size</p>
                    <button
                        class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-full font-semibold text-sm">
                        Shop Now
                    </button>
                </div>
                <!-- <div class="absolute right-0 bottom-0 w-1/2 h-3/4">
                    <img src="assets/images/home/head_3.png.png" alt="Keyboard"
                        class="h-full w-full object-contain">
                </div> -->
            </div>

            <!-- Headset -->
            <div class="bg-gradient-to-br rounded-3xl overflow-hidden relative h-64" style="background-image: url('assets/images/home/black-wireless-headphones-black-surface 1.png'); background-size: cover; background-position: center; width: 100%;">
                <div class="absolute inset-0 p-8 flex flex-col">
                    <h3 class="text-white text-2xl font-bold mb-2">Headset</h3>
                    <!-- <div class="flex-1 flex items-center justify-center">
                        <img src="assets/images/home/black-wireless-headphones-black-surface 1.png" alt="Headset"
                            class="w-20 h-20 object-contain">
                    </div> -->
                    <div class="mt-auto">
                        <p class="text-gray-400 text-xs mb-1">FROM</p>
                        <p class="text-yellow-400 text-2xl font-bold">14000.00</p>
                    </div>
                </div>
            </div>

            <!-- Monitor -->
            <div class="bg-gradient-to-br from-gray-300 to-gray-400 rounded-3xl overflow-hidden relative h-64">
                <div class="absolute inset-0 p-8">
                    <p class="text-gray-600 text-xs uppercase mb-1">MONITORS</p>
                    <h3 class="text-white text-xl font-bold mb-1">VS197DE</h3>
                    <h4 class="text-white text-xl font-bold mb-1">LED</h4>
                    <h5 class="text-white text-xl font-bold mb-6">Monitor</h5>
                    <button
                        class="bg-white hover:bg-gray-100 text-gray-900 px-6 py-2.5 rounded-full font-semibold text-sm">
                        Shop Now
                    </button>
                </div>
                <div class="absolute right-0 bottom-0 w-1/2 h-3/4">
                    <img src="assets/images/home/modern-tv-screen-isolated 1.png" alt="Monitor"
                        class="h-full w-full object-contain">
                </div>
            </div>

        </div>

    </section>

    <!-- Top Laptop Brands Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6 sm:mb-8">Top Laptop Brands</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 sm:gap-8 lg:gap-12 mb-8 sm:mb-12 justify-items-center">
            <img src="assets/images/home/hp-300x300-1 1.png" alt="HP"
                class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 hover:grayscale-0 cursor-pointer">
            <img src="assets/images/home/hp-300x300-1 2.png" alt="ASUS"
                class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 hover:grayscale-0 cursor-pointer">
            <img src="assets/images/home/hp-300x300-1 3.png" alt="Lenovo"
                class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 hover:grayscale-0 cursor-pointer">
            <img src="assets/images/home/hp-300x300-1 4.png" alt="MSI"
                class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 hover:grayscale-0 cursor-pointer">
            <img src="assets/images/home/hp-300x300-1 5.png" alt="Dell"
                class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 hover:grayscale-0 cursor-pointer">
            <img src="assets/images/home/hp-300x300-1 6.png" alt="Acer"
                class="h-20 sm:h-24 lg:h-32 transform transition duration-300 ease-out hover:scale-110 hover:grayscale-0 cursor-pointer">
        </div>

        <!-- Best Weekly Deals Section -->
        <div class="bg-gradient-to-br from-purple-100 to-blue-50 rounded-2xl sm:rounded-3xl p-6 sm:p-8">

            <!-- Header with Countdown -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 sm:mb-8 gap-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Best Weekly Deals</h2>
                <div
                    class="bg-red-500 text-white px-3 sm:px-4 lg:px-6 py-2 rounded-full flex flex-wrap items-center justify-center gap-1 sm:gap-2 text-xs sm:text-sm font-semibold">
                    <span class="hidden sm:inline">Expires in:</span>
                    <span class="sm:hidden">Expires:</span>
                    <span class="font-bold">132d</span>
                    <span class="hidden sm:inline">:</span>
                    <span class="font-bold">9h</span>
                    <span class="hidden sm:inline">:</span>
                    <span class="font-bold">35m</span>
                    <span class="hidden sm:inline">:</span>
                    <span class="font-bold">45s</span>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-4 sm:gap-6 auto-rows-fr">

                <!-- Product 1: Headset -->
                <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 relative shadow-sm hover:shadow-md transition-shadow">
                    <span
                        class="absolute top-2 sm:top-3 left-2 sm:left-3 bg-pink-100 text-pink-600 text-xs px-2 sm:px-3 py-1 rounded-full font-medium">
                        5% Installment
                    </span>
                    <button
                        class="absolute top-2 sm:top-3 right-2 sm:right-3 w-7 h-7 sm:w-8 sm:h-8 bg-purple-custom text-white rounded-full flex items-center justify-center hover:bg-purple-700">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>

                    <div class="flex items-center justify-center h-32 sm:h-40 lg:h-48 mb-3 sm:mb-4 mt-6 sm:mt-8">
                        <img src="assets/images/categories/gaming-headphones-black-red-with-wire 1.png" alt="Headset"
                            class="max-h-full w-full object-contain">
                    </div>

                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-2">-32%
                        OFF</span>

                    <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 min-h-[2.5rem]">Logitech H110 Stereo Analog
                        Headset</h3>

                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-xs">
                            ★★★★★
                        </div>
                        <span class="text-gray-500 text-xs ml-1">(5)</span>
                    </div>

                    <div class="flex items-baseline space-x-2 mb-3">
                        <span class="text-red-500 font-bold text-base sm:text-lg">Rs5000.00</span>
                        <span class="text-gray-400 text-sm line-through">Rs6500.00</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                        <div class="bg-purple-custom h-1.5 rounded-full" style="width: 40%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sold: 24 / 60</p>
                </div>

                <!-- Product 2: Monitor (Featured Large) -->
                <div
                    class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 relative shadow-sm hover:shadow-md transition-shadow sm:col-span-2 lg:col-span-2 xl:col-span-2">
                    <span
                        class="absolute top-2 sm:top-3 left-2 sm:left-3 bg-pink-100 text-pink-600 text-xs px-2 sm:px-3 py-1 rounded-full font-medium">
                        0% Installment
                    </span>
                    <button
                        class="absolute top-2 sm:top-3 right-2 sm:right-3 w-7 h-7 sm:w-8 sm:h-8 bg-purple-custom text-white rounded-full flex items-center justify-center hover:bg-purple-700">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>

                    <div class="flex items-center justify-center h-32 sm:h-40 lg:h-48 mb-3 sm:mb-4 mt-6 sm:mt-8">
                        <img src="assets/images/categories/view-computer-video-display-monitor 1.png" alt="Monitor"
                            class="max-h-full object-contain">
                    </div>

                    <div class="flex items-center space-x-2 mb-2">
                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold">-32% OFF</span>
                        <span class="bg-purple-custom text-white text-xs px-2 py-1 rounded font-semibold">BEST
                            SELLER</span>
                    </div>

                    <h3 class="text-base font-semibold text-gray-900 mb-2">Asus ProArt 27" PA279CRV 4K UHD 60 Hz USB-C
                        with PD 96W Monitor</h3>

                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-sm">
                            ★★★★★
                        </div>
                        <span class="text-gray-500 text-sm ml-1">(14)</span>
                    </div>

                    <div class="flex items-baseline space-x-2 mb-3">
                        <span class="text-red-500 font-bold text-xl">Rs204 000.00</span>
                        <span class="text-gray-400 text-base line-through">Rs165000.00</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                        <div class="bg-purple-custom h-1.5 rounded-full" style="width: 82%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sold: 82 / 100</p>
                </div>

                <!-- Product 3: Mouse -->
                <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-5 relative shadow-sm hover:shadow-md transition-shadow">
                    <span
                        class="absolute top-2 sm:top-3 left-2 sm:left-3 bg-pink-100 text-pink-600 text-xs px-2 sm:px-3 py-1 rounded-full font-medium">
                        0% Installment
                    </span>
                    <button
                        class="absolute top-2 sm:top-3 right-2 sm:right-3 w-7 h-7 sm:w-8 sm:h-8 bg-purple-custom text-white rounded-full flex items-center justify-center hover:bg-purple-700">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>

                    <div class="flex items-center justify-center h-32 sm:h-40 lg:h-48 mb-3 sm:mb-4 mt-6 sm:mt-8">
                        <img src="assets/images/categories/computer-mouse 1.png" alt="Mouse"
                            class="max-h-full w-full object-contain">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 mb-2">
                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold">-32% OFF</span>
                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded font-semibold">TOP RATED</span>
                    </div>

                    <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 min-h-[2.5rem]">LOGITECH G102 8000DPI GAMING MOUSE
                        BLACK</h3>

                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-xs">
                            ★★★★★
                        </div>
                        <span class="text-gray-500 text-xs ml-1">(2)</span>
                    </div>

                    <div class="flex items-baseline space-x-2 mb-3">
                        <span class="text-red-500 font-bold text-lg">Rs5000.00</span>
                        <span class="text-gray-400 text-sm line-through">Rs6500.00</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                        <div class="bg-purple-custom h-1.5 rounded-full" style="width: 11%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sold: 7 / 65</p>
                </div>

                <!-- Product 4: USB Hub -->
                <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-md transition-shadow">
                    <span
                        class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium">
                        0% Installment
                    </span>
                    <button
                        class="absolute top-3 right-3 w-8 h-8 bg-purple-custom text-white rounded-full flex items-center justify-center hover:bg-purple-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>

                    <div class="flex items-center justify-center h-48 mb-4">
                        <img src="assets/images/categories/USB-hub.png" alt="USB Hub"
                            class="max-h-full object-contain">
                    </div>

                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-2">-32%
                        OFF</span>

                    <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">Air Purifier with True HEPA H14
                        Filter</h3>

                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-xs">
                            ★★★★☆
                        </div>
                        <span class="text-gray-500 text-xs ml-1">(5)</span>
                    </div>

                    <div class="flex items-baseline space-x-2 mb-3">
                        <span class="text-red-500 font-bold text-lg">Rs5000.00</span>
                        <span class="text-gray-400 text-sm line-through">Rs6500.00</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                        <div class="bg-purple-custom h-1.5 rounded-full" style="width: 40%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sold: 24 / 60</p>
                </div>

                <!-- Product 5: Printer -->
                <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-md transition-shadow">
                    <span
                        class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium">
                        0% Installment
                    </span>
                    <button
                        class="absolute top-3 right-3 w-8 h-8 bg-purple-custom text-white rounded-full flex items-center justify-center hover:bg-purple-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>

                    <div class="flex items-center justify-center h-48 mb-4">
                        <img src="assets/images/categories/printer.png" alt="Printer"
                            class="max-h-full object-contain">
                    </div>

                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-2">-32%
                        OFF</span>

                    <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">Logitech H110 Stereo Analog
                        Headset</h3>

                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-xs">
                            ★★★★★
                        </div>
                        <span class="text-gray-500 text-xs ml-1">(5)</span>
                    </div>

                    <div class="flex items-baseline space-x-2 mb-3">
                        <span class="text-red-500 font-bold text-lg">Rs5000.00</span>
                        <span class="text-gray-400 text-sm line-through">Rs6500.00</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                        <div class="bg-purple-custom h-1.5 rounded-full" style="width: 7%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sold: 7 / 99</p>
                </div>

                <!-- Product 6: Monitor -->
                <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-md transition-shadow">
                    <span
                        class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium">
                        0% Installment
                    </span>
                    <button
                        class="absolute top-3 right-3 w-8 h-8 bg-purple-custom text-white rounded-full flex items-center justify-center hover:bg-purple-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>

                    <div class="flex items-center justify-center h-48 mb-4">
                        <img src="assets/images/categories/moder-screen.png" alt="Monitor"
                            class="max-h-full object-contain">
                    </div>

                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-2">HOT
                        OFFER</span>

                    <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">LENOVO L22i-40 21.5" IPS 75HZ
                        Frameless Monitor</h3>

                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-xs">
                            ★★★★★
                        </div>
                        <span class="text-gray-500 text-xs ml-1">(5)</span>
                    </div>

                    <div class="flex items-baseline space-x-2 mb-3">
                        <span class="text-red-500 font-bold text-lg">Rs5000.00</span>
                        <span class="text-gray-400 text-sm line-through">Rs6500.00</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                        <div class="bg-purple-custom h-1.5 rounded-full" style="width: 5%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sold: 1 / 19</p>
                </div>

                <!-- Product 7: PC Case -->
                <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-md transition-shadow">
                    <button
                        class="absolute top-3 right-3 w-8 h-8 bg-purple-custom text-white rounded-full flex items-center justify-center hover:bg-purple-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>

                    <div class="flex items-center justify-center h-48 mb-4">
                        <img src="assets/images/categories/UPS.png" alt="PC Case"
                            class="max-h-full object-contain">
                    </div>

                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-2">-5%
                        OFF</span>

                    <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">Sharkn Robot Vacuum with
                        Self-Empty Base</h3>

                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-xs">
                            ★★★★★
                        </div>
                        <span class="text-gray-500 text-xs ml-1">(5)</span>
                    </div>

                    <div class="flex items-baseline space-x-2 mb-3">
                        <span class="text-red-500 font-bold text-lg">Rs5000.00</span>
                        <span class="text-gray-400 text-sm line-through">Rs6500.00</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                        <div class="bg-purple-custom h-1.5 rounded-full" style="width: 5%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sold: 1 / 19</p>
                </div>

            </div>

            <!-- See All Products Button -->
            <div class="text-center mt-6 sm:mt-8">
                <a href="#" class="inline-block bg-gray-100 hover:bg-purple-100 text-gray-700 hover:text-purple-custom font-medium px-6 sm:px-8 py-3 rounded-full transition-colors">
                    See All Products (63)
                </a>
            </div>

        </div>

    </section>

    <!-- Trending Search Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Trending Search</h2>

        <div class="flex flex-wrap gap-2 sm:gap-3 mb-6 sm:mb-8">
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Processors</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Mother
                Boards</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Coolers</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Rams</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Macbook
                M1</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Storage</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Graphic
                Card</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">SSD</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Earbuds</a>
        </div>

        <div class="flex flex-wrap gap-2 sm:gap-3 mb-8 sm:mb-12">
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-5 py-2 rounded-full text-sm font-medium transition-colors">Monitors</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-5 py-2 rounded-full text-sm font-medium transition-colors">Printers</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-5 py-2 rounded-full text-sm font-medium transition-colors">Combo
                Pack</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-5 py-2 rounded-full text-sm font-medium transition-colors">Gaming
                Computer</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-5 py-2 rounded-full text-sm font-medium transition-colors">Pen
                Drives</a>
            <a href="#"
                class="bg-gray-200 hover:bg-purple-100 hover:text-purple-custom text-gray-700 px-5 py-2 rounded-full text-sm font-medium transition-colors">Web
                Cam</a>
        </div>

        <!-- Pre Order Banner -->
        <div class="bg-gradient-to-r from-gray-800 via-gray-700 to-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-5 mb-8 sm:mb-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 items-center">

            <!-- Column 1: Left Text -->
            <div class="flex flex-col justify-center order-1 text-center md:text-left">
                <span class="text-purple-400 font-bold text-base sm:text-lg uppercase tracking-wide mb-1">PRE ORDER</span>
                <p class="text-gray-400 text-xs mb-1">BE THE FIRST TO OWN</p>
                <p class="text-white font-bold text-lg sm:text-xl">From 100,000.00</p>
            </div>

            <!-- Column 2: Image -->
            <div class="flex justify-center items-center order-3 md:order-2">
                <img src="assets/images/home/3d-illustration-2-laptops-with-open-browser-tab-screen-search-internet-template-search-user-interface 2.png"
                    alt="ASUS Laptops"
                    class="h-32 sm:h-40 lg:h-50 object-contain">
            </div>

            <!-- Column 3: Specs -->
            <div class="flex flex-col justify-center text-blue-400 text-xs order-2 md:order-3 text-center lg:text-left">
                <h3 class="text-white text-xl sm:text-2xl lg:text-3xl font-bold">ASUS EXPERTBOOK</h3><br>
                <p class="text-purple-400 text-xs sm:text-sm">ASUS EXPERTBOOK B1502CVA-i7/16512G5D | i7-1355U | 16GB DDR5 |</p>
                <p class="text-purple-400 text-xs sm:text-sm">512GB NVME | 15.6 FHD | DOS</p>
            </div>

            <!-- Column 4: Product Name + Button -->
            <div class="flex flex-col justify-center items-center lg:items-start gap-2 order-4">
                <button class="bg-white hover:bg-gray-100 text-gray-900 px-6 sm:px-8 py-2 sm:py-2.5 rounded-full font-semibold transition-colors">
                    Discover Now
                </button>
            </div>

        </div>


        <!-- Best Seller Section -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Best Seller</h2>
            <a href="#" class="text-gray-700 hover:text-purple-custom font-medium flex items-center">
                VIEW ALL
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <!-- Category Tabs -->
        <div class="flex flex-wrap gap-2 sm:gap-3 mb-6 sm:mb-8">
            <button class="bg-purple-custom text-white px-4 sm:px-5 py-2 rounded-full text-sm font-medium">Top 30</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Televisions</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">PC
                Gaming</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Computers</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Cameras</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Gadgets</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Smart
                Home</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Sport
                Equipments</button>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6 mb-6 sm:mb-8">

            <!-- Product 1: Monitor -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="assets/images/categories/moder-screen.png" alt="Monitor"
                        class="max-h-full object-contain">
                </div>

                <div class="flex items-center space-x-2 mb-3">
                    <span class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold">NEW</span>
                    <span class="bg-purple-custom text-white text-xs px-2 py-1 rounded font-semibold">BEST SELLER</span>
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Asus VZ22EHE 22" IPS 75HZ Eye
                    Care Monitor</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">
                        ★★★★★
                    </div>
                    <span class="text-gray-500 text-xs ml-1">(2)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 2: PC Case -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group">
                <span
                    class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                    0% Installment
                </span>
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="assets/images/categories/UPS.png" alt="PC Case"
                        class="max-h-full object-contain">
                </div>

                <span class="bg-purple-custom text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-3">BEST
                    SELLER</span>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Gigabyte PC Gaming Case, Core i7,
                    32GB Ram</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">
                        ★★★★★
                    </div>
                    <span class="text-gray-500 text-xs ml-1">(2)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 3: Keyboard -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group">
                <span
                    class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                    0% Installment
                </span>
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="assets/images/categories/gaming-keyboard-with-cacklight 2.png" alt="Mouse"
                        class="max-h-full object-contain">
                </div>

                <div class="flex items-center space-x-2 mb-3">
                    <span class="bg-purple-custom text-white text-xs px-2 py-1 rounded font-semibold">BEST SELLER</span>
                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded font-semibold">TOP RATED</span>
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Logitech K120 Wired Keyboard</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">
                        ★★★★★
                    </div>
                    <span class="text-gray-500 text-xs ml-1">(12)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 4: Processor -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="assets/images/categories/processor.png" alt="Processor"
                        class="max-h-full object-contain">
                </div>

                <div class="flex items-center space-x-2 mb-3">
                    <span class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold">NEW</span>
                    <span class="bg-purple-custom text-white text-xs px-2 py-1 rounded font-semibold">BEST SELLER</span>
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">AMD Ryzen™ 5 5600GT Processor
                    (Core 6/Threads 12)</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">
                        ★★★★★
                    </div>
                    <span class="text-gray-500 text-xs ml-1">(5)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 5: Mouse 2 -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group">
                <span
                    class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                    0% Installment
                </span>
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="assets/images/categories/computer-mouse 1.png" alt="Mouse"
                        class="max-h-full object-contain">
                </div>

                <div class="flex items-center space-x-2 mb-3">
                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold">-15% OFF</span>
                    <span class="bg-purple-custom text-white text-xs px-2 py-1 rounded font-semibold">BEST SELLER</span>
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">LOGITECH G102 8000DPI GAMING
                    MOUSE BLACK</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">
                        ★★★★★
                    </div>
                    <span class="text-gray-500 text-xs ml-1">(12)</span>
                </div>

                <div class="flex items-baseline space-x-2">
                    <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
                    <p class="text-gray-400 text-sm line-through">5519.00</p>
                </div>
            </div>

        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-center space-x-2">
            <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button class="w-2 h-2 rounded-full bg-purple-custom"></button>
            <button class="w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400"></button>
            <button class="w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400"></button>
            <button class="w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400"></button>
            <button class="w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400"></button>
            <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

    </section>

    <!-- Popular Brands Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="bg-gradient-to-br from-purple-100 to-blue-50 rounded-2xl sm:rounded-3xl p-6 sm:p-8">

            <!-- Section Header -->
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Popular Brands</h2>
                <a href="#" class="text-gray-700 hover:text-purple-custom font-medium flex items-center">
                    VIEW ALL
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Brand Cards Carousel -->
            <div class="relative">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 overflow-x-auto scrollbar-hide">

                    <!-- Card 1: VR Headset -->
                    <div class="flex-shrink-0 w-full bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 relative overflow-hidden min-h-[200px] sm:min-h-[240px]">
                        <p class="text-xs sm:text-sm text-gray-500 uppercase mb-1 sm:mb-2">ARTICLES 365</p>
                        <h3 class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 mb-3 sm:mb-4 leading-tight">VR Headset and<br class="hidden sm:block">Controllers</h3>
                        <button class="bg-gray-900 hover:bg-gray-800 text-white px-3 sm:px-4 lg:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors">
                            Shop Now
                        </button>
                        <div class="absolute right-0 top-0 h-full w-2/3 sm:w-3/5">
                            <img src="assets/images/home/pup_2.png-removebg-preview.png" alt="VR Headset" class="h-full w-full object-contain object-right">
                        </div>
                    </div>

                    <!-- Card 2: Gaming PC -->
                    <div class="flex-shrink-0 w-full bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 relative overflow-hidden min-h-[200px] sm:min-h-[240px]">
                        <p class="text-xs sm:text-sm text-white font-semibold mb-1 sm:mb-2">GAMING PC</p>
                        <p class="text-xs sm:text-sm text-white mb-3 sm:mb-4">Build Your Own</p>
                        <button class="bg-white hover:bg-gray-100 text-gray-900 px-3 sm:px-4 lg:px-6 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold transition-colors">
                            Shop Now
                        </button>
                        <div class="absolute right-0 top-0 h-full w-2/3 sm:w-3/5">
                            <img src="assets/images/categories/UPS.png" alt="Gaming PC" class="h-full w-full object-contain object-right">
                        </div>
                    </div>

                    <!-- Card 3: Laptop -->
                    <div class="flex-shrink-0 w-full bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 relative overflow-hidden min-h-[200px] sm:min-h-[240px]">
                        <p class="text-xs sm:text-sm text-white font-semibold mb-1 sm:mb-2">Laptop</p>
                        <p class="text-xs sm:text-sm text-gray-300 mb-3 sm:mb-4">Asus<br class="hidden sm:block">black</p>
                        <p class="text-white font-bold text-sm sm:text-base lg:text-lg">Rs12000.00</p>
                        <div class="absolute right-0 top-0 h-full w-2/3 sm:w-3/5">
                            <img src="assets/images/home/3d-illustration-2-laptops-with-open-browser-tab-screen-search-internet-template-search-user-interface 2.png" alt="Laptop"
                                class="h-full w-full object-contain object-right">
                        </div>
                    </div>

                </div>


                <!-- Navigation Arrows -->
                <button
                    class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-8 h-8 lg:w-10 lg:h-10 bg-white rounded-full shadow-lg items-center justify-center hover:bg-gray-100 transition-colors z-10">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>
                <button
                    class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-8 h-8 lg:w-10 lg:h-10 bg-white rounded-full shadow-lg items-center justify-center hover:bg-gray-100 transition-colors z-10">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

        </div>
    </section>

    <!-- Suggest Today Section -->
    <section class="max-w-7xl mx-auto px-4 py-12">

        <!-- Section Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Suggest Today</h2>
            <a href="#" class="text-gray-700 hover:text-purple-custom font-medium">VIEW ALL ›</a>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-3 mb-8">
            <button class="bg-purple-custom text-white px-5 py-2 rounded-full text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z">
                    </path>
                </svg>
                Recommend For You
            </button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-full text-sm font-medium flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Top Best Seller
            </button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-full text-sm font-medium flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z">
                    </path>
                </svg>
                Top Rated
            </button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-full text-sm font-medium transition-colors">
                70% OFF
            </button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-full text-sm font-medium transition-colors">
                50% OFF
            </button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-full text-sm font-medium transition-colors">
                30% OFF
            </button>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

            <!-- Product 1: Monitor -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/200x150/4a90e2/ffffff?text=Monitor" alt="Monitor"
                        class="max-h-full object-contain">
                </div>

                <div class="flex items-center space-x-2 mb-3">
                    <span class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold">NEW</span>
                    <span class="bg-purple-custom text-white text-xs px-2 py-1 rounded font-semibold">BEST SELLER</span>
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Asus VZ22EHE 22" IPS 75HZ Eye Care Monitor</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(2)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 2: Refrigerator -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <span
                    class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                    0% Installment
                </span>
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/200x200/f0f0f0/666666?text=Refrigerator" alt="Refrigerator"
                        class="max-h-full object-contain">
                </div>

                <div class="flex items-center space-x-2 mb-3">
                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold">BEST OFFER</span>
                    <span class="bg-purple-custom text-white text-xs px-2 py-1 rounded font-semibold">TOP RATED</span>
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Teshub 2-Door Inverter 1200L Refrigerator</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★☆☆</div>
                    <span class="text-gray-500 text-xs ml-1">(2)</span>
                </div>

                <div class="flex items-baseline space-x-2">
                    <p class="text-red-500 font-bold text-lg">Rs5000.00</p>
                    <p class="text-gray-400 text-sm line-through">Rs469.00</p>
                </div>
            </div>

            <!-- Product 3: Projector -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <span
                    class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                    0% Installment
                </span>
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/200x150/5555ff/ffffff?text=Projector" alt="Projector"
                        class="max-h-full object-contain">
                </div>

                <span class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-3">NEW</span>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Epson Mini Portable Projector Wireless</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(5)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 4: Monitor -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/200x150/4a90e2/ffffff?text=Monitor" alt="Monitor"
                        class="max-h-full object-contain">
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Away VZ22HE 22" PS 79MZ Eye Care Monitor</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(2)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 5: Headset -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/150x150/ff1744/ffffff?text=🎧" alt="Headset"
                        class="max-h-full object-contain">
                </div>

                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-3">TOP RATED</span>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Lightech HITIO Stereo Analog Headset</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★☆☆</div>
                    <span class="text-gray-500 text-xs ml-1">(12)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 6: Monitor -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/200x150/4a90e2/ffffff?text=Monitor" alt="Monitor"
                        class="max-h-full object-contain">
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Away VZ22HE 27" PS 78MZ Eye Care Monitor</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(2)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 7: aPod -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/150x150/f5f5f5/666666?text=aPod" alt="aPod"
                        class="max-h-full object-contain">
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">aPod LTEF-09B Silver Grey</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★☆☆</div>
                    <span class="text-gray-500 text-xs ml-1">(1)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 8: Gaming PC -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <span
                    class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                    0% Installment
                </span>
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/180x180/1e293b/60a5fa?text=PC+Case" alt="Gaming PC"
                        class="max-h-full object-contain">
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Gigabyte PC Gaming Case, Core i7, 32GB Ram</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(2)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 9: Monitor -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/200x150/4a90e2/ffffff?text=Monitor" alt="Monitor"
                        class="max-h-full object-contain">
                </div>

                <span class="bg-gray-900 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-3">OUT OF STOCK</span>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Asus VZ22EHE 22" IPS 75HZ Eye Care Monitor</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(5)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 10: Monitor -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <span
                    class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                    0% Installment
                </span>
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/200x150/4a90e2/ffffff?text=Monitor" alt="Monitor"
                        class="max-h-full object-contain">
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Asus VZ22EHE 22" IPS 75HZ Eye Care Monitor</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(2)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

        </div>

    </section>

    <!-- Best Selling Speakers Section -->
    <section class="bg-gradient-to-br from-purple-100 to-blue-50 py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">

                <!-- Left Side: Featured Speaker -->
                <div class="text-center lg:text-left">
                    <p class="text-xs text-gray-500 uppercase mb-2">AMAZON AWARD-WINNING SPEAKER</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">Devialet Phantom</h2>
                    <h3 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">II <span class="font-normal">Speaker</span></h3>

                    <div class="mb-6">
                        <p class="text-xs text-gray-500 uppercase mb-1">STARTING AT PRICE</p>
                        <p class="text-2xl sm:text-3xl font-bold text-green-600">Rs1,590</p>
                    </div>

                    <div class="relative">
                        <img src="assets/images/categories/best_selling_speaker.png"
                            alt="Devialet Phantom II Speakers"
                            class="w-3/4 sm:w-2/3 md:w-1/2 mx-auto lg:mx-0 object-contain">
                    </div>

                </div>

                <!-- Right Side: Best Selling Speakers -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Best Selling Speakers</h3>
                        <div class="flex space-x-2">
                            <button
                                class="w-8 h-8 bg-white rounded-full shadow flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button
                                class="w-8 h-8 bg-white rounded-full shadow flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

                        <!-- Speaker 1: Monitor/Display -->
                        <div class="bg-white rounded-2xl p-4 relative shadow-sm hover:shadow-md transition-shadow">
                            <span
                                class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                                0% Installment
                            </span>
                            <button
                                class="absolute top-3 right-3 w-7 h-7 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-600 hover:text-white transition-colors z-10">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </button>

                            <div class="flex items-center justify-center h-32 mb-3">
                                <img src="https://via.placeholder.com/150x100/1a1a2e/ff0080?text=Monitor" alt="Monitor"
                                    class="max-h-full object-contain">
                            </div>

                            <h4 class="text-xs font-semibold text-gray-900 mb-2 line-clamp-2">Marshall Stanmore II
                                Wireless Bluetooth Speaker</h4>

                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-xs">★★★★★</div>
                                <span class="text-gray-500 text-xs ml-1">(2)</span>
                            </div>

                            <p class="text-purple-600 font-bold text-sm">Rs35000.00</p>
                        </div>

                        <!-- Speaker 2: Bose SoundLink -->
                        <div class="bg-white rounded-2xl p-4 relative shadow-sm hover:shadow-md transition-shadow">
                            <button
                                class="absolute top-3 right-3 w-7 h-7 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-600 hover:text-white transition-colors z-10">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </button>

                            <div class="flex items-center justify-center h-32 mb-3">
                                <img src="https://via.placeholder.com/120x120/e5e7eb/666666?text=Speaker" alt="Speaker"
                                    class="max-h-full object-contain">
                            </div>

                            <span
                                class="bg-green-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-2">TOP
                                RATED</span>

                            <h4 class="text-xs font-semibold text-gray-900 mb-2 line-clamp-2">Bose SoundLink III
                                Speaker</h4>

                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-xs">★★★★★</div>
                                <span class="text-gray-500 text-xs ml-1">(12)</span>
                            </div>

                            <p class="text-purple-600 font-bold text-sm">Rs35000.00</p>
                        </div>

                        <!-- Speaker 3: B&O Beolit -->
                        <div class="bg-white rounded-2xl p-4 relative shadow-sm hover:shadow-md transition-shadow">
                            <button
                                class="absolute top-3 right-3 w-7 h-7 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-600 hover:text-white transition-colors z-10">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </button>

                            <div class="flex items-center justify-center h-32 mb-3">
                                <img src="https://via.placeholder.com/120x120/d4a574/ffffff?text=Speaker" alt="Speaker"
                                    class="max-h-full object-contain">
                            </div>

                            <span
                                class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-2">NEW</span>

                            <h4 class="text-xs font-semibold text-gray-900 mb-2 line-clamp-2">B&O Beolit 20 Powerful
                                Portable Wireless Bluetooth</h4>

                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-xs">★★★★★</div>
                                <span class="text-gray-500 text-xs ml-1">(5)</span>
                            </div>

                            <p class="text-purple-600 font-bold text-sm">Rs35000.00</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Just Landing Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Section Header -->
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Just Landing</h2>
            <a href="#" class="text-gray-700 hover:text-purple-custom font-medium">VIEW ALL ›</a>
        </div>

        <!-- Category Tabs -->
        <div class="flex flex-wrap gap-2 sm:gap-3 mb-6 sm:mb-8">
            <button class="bg-purple-custom text-white px-4 sm:px-5 py-2 rounded-full text-sm font-medium">Processors</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Graphic
                Card</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Pen
                Drives</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Combo
                Pack</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Gadgets</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Monitors</button>
            <button
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-5 py-2 rounded-full text-sm font-medium transition-colors">Printers</button>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6 mb-6 sm:mb-8">

            <!-- Product 1: Monitor -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/200x150/4a90e2/ffffff?text=Monitor" alt="Monitor"
                        class="max-h-full object-contain">
                </div>

                <span
                    class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-3">NEW</span>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Name of Product with Lore</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(5)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 2: Headset -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/150x150/ff1744/ffffff?text=🎧" alt="Headset"
                        class="max-h-full object-contain">
                </div>

                <span
                    class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-3">NEW</span>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Name of Product with Lore</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(3)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 3: Curved Monitor -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <span
                    class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                    0% Installment
                </span>
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/200x120/1a1a2e/ff0080?text=Curved+Monitor"
                        alt="Curved Monitor" class="max-h-full object-contain">
                </div>

                <div class="flex items-center space-x-2 mb-3">
                    <span class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold">NEW</span>
                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded font-semibold">TOP RATED</span>
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Name of Product with Lore</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(12)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 4: USB Cables -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/180x100/f5f5f5/666666?text=USB+Cables" alt="USB Cables"
                        class="max-h-full object-contain">
                </div>

                <div class="flex items-center space-x-2 mb-3">
                    <span class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold">NEW</span>
                    <span class="bg-purple-custom text-white text-xs px-2 py-1 rounded font-semibold">BEST SELLER</span>
                </div>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Name of Product with Lore</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                    <span class="text-gray-500 text-xs ml-1">(8)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs35000.00</p>
            </div>

            <!-- Product 5: Gaming PC -->
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow">
                <span
                    class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full font-medium z-10">
                    0% Installment
                </span>
                <button
                    class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center justify-center h-48 mb-4">
                    <img src="https://via.placeholder.com/180x180/1e293b/ff00ff?text=Gaming+PC" alt="Gaming PC"
                        class="max-h-full object-contain">
                </div>

                <span
                    class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-3">NEW</span>

                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">Name of Product with Lore</h3>

                <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400 text-xs">★★☆☆☆</div>
                    <span class="text-gray-500 text-xs ml-1">(8)</span>
                </div>

                <p class="text-purple-custom font-bold text-lg">Rs209.00</p>
            </div>

        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-center space-x-2">
            <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button class="w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400"></button>
            <button class="w-2 h-2 rounded-full bg-purple-custom"></button>
            <button class="w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400"></button>
            <button class="w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400"></button>
            <button class="w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400"></button>
            <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">

            <!-- Just Landing Section -->
            <div class="bg-gradient-to-br from-purple-100 to-blue-50 rounded-2xl sm:rounded-3xl p-6 sm:p-8">

                <!-- Header -->
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Just Landing</h2>
                    <a href="#" class="text-gray-700 hover:text-purple-custom font-medium flex items-center">
                        VIEW ALL
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                <!-- Blog Posts -->
                <div class="space-y-4">

                    <!-- Blog Post 1 -->
                    <div
                        class="bg-white rounded-2xl p-5 flex items-start space-x-4 hover:shadow-md transition-shadow cursor-pointer">
                        <div class="flex-shrink-0">
                            <img src="assets/images/home/professional-gaming-empty-room-studio-with-neon-lights.jpg"
                                alt="Gaming Room" class="w-44 h-28 object-cover rounded-xl">
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-3 leading-tight">How to choose size of Monitor
                                fit to your Gaming room</h3>
                            <p class="text-sm text-gray-500">45 Minutes ago in <span
                                    class="font-semibold text-gray-700">EXPERIENCE</span></p>
                        </div>
                    </div>

                    <!-- Blog Post 2 -->
                    <div
                        class="bg-white rounded-2xl p-5 flex items-start space-x-4 hover:shadow-md transition-shadow cursor-pointer">
                        <div class="flex-shrink-0">
                            <img src="assets/images/home/top-view-ssd-laptop-desk.jpg"
                                alt="Samsung Hard Drive" class="w-44 h-28 object-cover rounded-xl">
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-3 leading-tight">Introduce New Generation of
                                Samsung Hard Drive 2025</h3>
                            <p class="text-sm text-gray-500">2 Days ago in <span
                                    class="font-semibold text-gray-700">TECHNOLOGY</span></p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Best Selling Speakers Testimonial Section -->
            <div class="bg-gradient-to-br from-purple-100 to-blue-50 rounded-2xl sm:rounded-3xl p-6 sm:p-8">

                <!-- Header -->
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Best Selling Speakers</h2>
                    <div class="flex space-x-2">
                        <button
                            class="w-10 h-10 bg-white rounded-full shadow flex items-center justify-center hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button
                            class="w-10 h-10 bg-white rounded-full shadow flex items-center justify-center hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Testimonial Card -->
                <div class="bg-white rounded-2xl p-8">

                    <!-- Rating and Title -->
                    <div class="flex items-center mb-4">
                        <div class="flex text-green-500 text-xl mr-3">
                            ★★★★★
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Fast shipping and flexiable price!</h3>
                    </div>

                    <!-- Testimonial Text -->
                    <p class="text-gray-700 leading-relaxed mb-8">
                        I used to have experience shopping on much platform as Amazon, Eboy, Esto, etc. And see that
                        Swoo Market really great. It'll be my 1st choice for any shopping experience. Competitive price,
                        fast shipping and support 24/7. Extremely recommended!
                    </p>

                    <!-- User Info -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <img src="assets/images/about/L1.png" alt="Drake N."
                                    class="w-12 h-12 rounded-full">
                                <span
                                    class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-gray-900">Drake N.</span>
                                    <span
                                        class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">VERIFIED
                                        BUYER</span>
                                </div>
                                <p class="text-sm text-gray-500">Brooklyn, Los Angeles</p>
                            </div>
                        </div>

                        <!-- Product Link -->
                        <a href="#" class="text-purple-custom hover:text-purple-700 font-medium text-sm underline">
                            Marshall Standmore Speaker / Black
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </section>


    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

</body>

</html>