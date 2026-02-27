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
            color: #B61AA8;
        }

        .bg-purple-custom {
            background-color: #B61AA8;
        }

        .hover\:bg-purple-custom:hover {
            background-color: #B61AA8;
        }

        .hover\:text-purple-custom:hover {
            color: #B61AA8;
        }

        .border-purple-custom {
            border-color: #B61AA8;
        }

        .from-purple-custom {
            --tw-gradient-from: #B61AA8;
        }

        .to-purple-custom {
            --tw-gradient-to: #B61AA8;
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

        :root {
            --purple: #B61AA8;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f8f5f8;
        }

        /* ── HERO BANNER ── */
        .hero-wrap {
            position: relative;
            overflow: hidden;
            background: linear-gradient(120deg, #12071A 0%, #1c0b28 60%, #0f0418 100%);
            min-height: 500px;
        }

        /* Subtle radial glow in background */
        .hero-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 80% at 80% 50%, rgba(182, 26, 168, 0.12) 0%, transparent 65%),
                radial-gradient(ellipse 40% 60% at 10% 50%, rgba(120, 0, 160, 0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .banner-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.7s ease, visibility 0.7s;
        }

        .banner-slide.active {
            opacity: 1;
            visibility: visible;
        }

        .banner-slide.active .anim-up {
            animation: up 0.65s cubic-bezier(.22, 1, .36, 1) both;
        }

        .banner-slide.active .delay-1 {
            animation-delay: 0.1s;
        }

        .banner-slide.active .delay-2 {
            animation-delay: 0.22s;
        }

        .banner-slide.active .delay-3 {
            animation-delay: 0.34s;
        }

        .banner-slide.active .delay-4 {
            animation-delay: 0.46s;
        }

        .banner-slide.active .anim-img {
            animation: imgIn 0.8s cubic-bezier(.22, 1, .36, 1) 0.15s both;
        }

        @keyframes up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes imgIn {
            from {
                opacity: 0;
                transform: translateX(30px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        .badge-a {
            animation: floatA 4s ease-in-out infinite alternate;
        }

        .badge-b {
            animation: floatA 4.5s ease-in-out 0.6s infinite alternate;
        }

        @keyframes floatA {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-8px);
            }
        }

        .stat-badge {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            padding: 12px 16px;
            text-align: center;
            min-width: 80px;
        }

        .stat-val {
            font-size: 1.3rem;
            font-weight: 800;
            color: #e870df;
            line-height: 1;
        }

        .stat-lbl {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-top: 3px;
        }

        .tag-pill {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #e870df;
            background: rgba(182, 26, 168, 0.15);
            border: 1px solid rgba(182, 26, 168, 0.25);
            border-radius: 999px;
            padding: 5px 14px;
            margin-bottom: 14px;
        }

        .hero-title {
            font-size: clamp(2rem, 4vw, 3.4rem);
            font-weight: 800;
            line-height: 1.1;
            color: #fff;
            letter-spacing: -0.02em;
        }

        .hero-title .hl {
            background: linear-gradient(135deg, #e870df, #B61AA8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.5);
            line-height: 1.65;
            max-width: 400px;
        }

        .spec-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .spec-chip {
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .spec-chip span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #e870df;
        }

        .price-tag {
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
            line-height: 1;
        }

        .price-from {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 4px;
        }

        .price-note {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.35);
            margin-top: 5px;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 26px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.04em;
            background: var(--purple);
            color: #fff;
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(182, 26, 168, 0.4);
        }

        .cta-btn:hover {
            background: #8a1280;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(182, 26, 168, 0.5);
        }

        .cta-btn svg {
            transition: transform 0.2s;
        }

        .cta-btn:hover svg {
            transform: translateX(3px);
        }

        /* Blue variant for headphone slide */
        .cta-btn.blue {
            background: #0284c7;
            box-shadow: 0 4px 20px rgba(2, 132, 199, 0.35);
        }

        .cta-btn.blue:hover {
            background: #0369a1;
            box-shadow: 0 8px 28px rgba(2, 132, 199, 0.45);
        }

        .tag-pill.blue {
            color: #7dd3fc;
            background: rgba(2, 132, 199, 0.12);
            border-color: rgba(2, 132, 199, 0.25);
        }

        .stat-val.blue {
            color: #7dd3fc;
        }

        .hero-title .hl.blue {
            background: linear-gradient(135deg, #7dd3fc, #0284c7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .spec-chip span.blue {
            background: #7dd3fc;
        }

        /* nav dots */
        .nav-dot {
            height: 3px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: background 0.3s, width 0.35s cubic-bezier(.4, 0, .2, 1);
        }

        .nav-dot.active {
            background: var(--purple);
            width: 32px !important;
        }

        /* progress line */
        .prog-line {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            background: var(--purple);
            transition: width 5s linear;
            opacity: 0.6;
        }


        /* ── PRE-ORDER BANNER ── */
        .preorder-wrap {
            background: linear-gradient(120deg, #12071A 0%, #1c0b28 60%, #0f0418 100%);
            border-top: 1px solid rgba(182, 26, 168, 0.15);
            position: relative;
            overflow: hidden;
        }

        .preorder-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 60% 80% at 70% 50%, rgba(182, 26, 168, 0.1) 0%, transparent 65%);
            pointer-events: none;
        }

        .po-item {
            display: none;
            animation: poFade 0.5s ease both;
        }

        .po-item.active {
            display: flex;
        }

        @keyframes poFade {
            from {
                opacity: 0;
                transform: translateX(16px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .po-eyebrow {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #e870df;
        }

        .po-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(182, 26, 168, 0.15);
            border: 1px solid rgba(182, 26, 168, 0.3);
            border-radius: 999px;
            padding: 4px 12px 4px 6px;
        }

        .po-badge-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--purple);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .po-title {
            font-size: clamp(1.2rem, 2vw, 1.7rem);
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.01em;
            line-height: 1.15;
        }

        .po-spec {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.4);
            line-height: 1.7;
        }

        .po-price-from {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.3);
        }

        .po-price {
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
        }

        .po-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
        }

        .po-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.05em;
            background: var(--purple);
            color: #fff;
            white-space: nowrap;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 4px 20px rgba(182, 26, 168, 0.4);
        }

        .po-cta:hover {
            background: #8a1280;
            transform: translateY(-1px);
        }

        /* thumbnail nav */
        .po-thumb {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.07);
            border: 2px solid rgba(255, 255, 255, 0.08);
            cursor: pointer;
            overflow: hidden;
            transition: border-color 0.2s, transform 0.2s;
            flex-shrink: 0;
        }

        .po-thumb:hover {
            transform: scale(1.05);
            border-color: rgba(182, 26, 168, 0.4);
        }

        .po-thumb.active {
            border-color: var(--purple);
            background: rgba(182, 26, 168, 0.1);
        }

        .po-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }

        .po-divider {
            width: 1px;
            align-self: stretch;
            background: linear-gradient(180deg, transparent, rgba(255, 255, 255, 0.08) 30%, rgba(255, 255, 255, 0.08) 70%, transparent);
        }

        .laptop-wrap {
            position: relative;
        }

        .laptop-wrap img {
            animation: laptopHover 5s ease-in-out infinite alternate;
        }

        @keyframes laptopHover {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-10px);
            }
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Swiper Banner Slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <div class="banner-swiper swiper" style="width:100vw; height:35vw; max-width:100%; margin:0 auto;">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="assets/images/home/banner1.jpeg" alt="Banner Image 1" style="width:100%; height:35vw; object-fit:cover;">
            </div>
            <div class="swiper-slide">
                <img src="assets/images/home/banner2.jpg" alt="Banner Image 2" style="width:100%; height:35vw; object-fit:cover;">
            </div>
            <div class="swiper-slide">
                <img src="assets/images/home/banner3.jpg" alt="Banner Image 3" style="width:100%; height:35vw; object-fit:cover;">
            </div>
        </div>
        <!-- <div class="swiper-pagination"></div> -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="assets/js/banner-slider.js"></script>

    <!-- Popular Categories Section -->
    <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
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
            <div class="bg-gradient-to-br from-red-300 via-orange-200 to-orange-100 rounded-2xl sm:rounded-3xl overflow-hidden relative h-64 sm:h-80 bg-cover bg-center" style="background-image:url('assets/images/home/home-photocopy-machine.jpg')">
                <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-center">
                    <h3 class="text-white text-2xl sm:text-4xl font-bold mb-1 sm:mb-2">Photocopy Machine</h3>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-1">Canon iR 2520</h4>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-4 sm:mb-6">Multi-Function</h4>
                    <p class="text-white text-xs sm:text-sm mb-1">Fast, Reliable, Efficient</p>
                    <p class="text-white text-xs sm:text-sm mb-4 sm:mb-6">IN STOCK</p>
                    <a href="products.php?category=photocopiers" class="bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-800 hover:to-blue-600 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-full font-semibold w-fit text-sm sm:text-base">Shop Now</a>
                </div>
                <!-- <div class="absolute right-0 top-0 h-full w-1/2">
                    <img src="assets/images/home/photocopier.png" alt="Photocopy Machine" class="h-full w-full object-contain">
                </div> -->
            </div>

            <div class="bg-gradient-to-br from-orange-400 via-orange-500 to-orange-200 rounded-2xl sm:rounded-3xl overflow-hidden relative h-64 sm:h-80 bg-cover bg-center" style="background-image:url('assets/images/home/home-printer-based-toner.jpg')">
                <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-center">
                    <h3 class="text-white text-2xl sm:text-4xl font-bold mb-1 sm:mb-2">Computer Printers</h3>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-1">Epson Work Force</h4>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-4 sm:mb-6">AL-M310DN</h4>
                    <p class="text-gray-300 text-xs sm:text-sm mb-1">EPSON WORK FORCE AL-M310DN</p>
                    <p class="text-gray-300 text-xs sm:text-sm mb-4 sm:mb-6">IN STOCK</p>
                    <a href="products.php?category=printers" class="bg-gradient-to-r from-gray-custom to-orange-700 hover:from-orange-700 hover:to-orange-600 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-full font-semibold w-fit text-sm sm:text-base">Shop Now</a>
                </div>
                <!-- <div class="absolute right-0 top-0 h-full w-1/2">
                    <img src="assets/images/home/Epson-WorkForce-AL-M310dn-Printer-2-1-removebg-preview 1.png" alt="Epson Printer" class="h-full w-full object-contain">
                </div> -->
            </div>

            <div class="bg-gradient-to-br from-red-300 via-orange-200 to-orange-100 rounded-2xl sm:rounded-3xl overflow-hidden relative h-64 sm:h-80 bg-cover bg-center" style="background-image:url('assets/images/home/freepik__sleek-laptop-on-tidy-modern-desk-tiny-potted-succu__24669.png')">
                <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-center z-10">
                    <h3 class="text-white text-2xl sm:text-4xl font-bold mb-1 sm:mb-2">Laptops</h3>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-1">Workspace</h4>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-4 sm:mb-6">Setup</h4>
                    <p class="text-white text-xs sm:text-sm mb-1">MODERN WORKSPACE</p>
                    <p class="text-white text-xs sm:text-sm mb-4 sm:mb-6">HIGH PERFORMANCE</p>
                    <a href="products.php?category=laptops" class="bg-gradient-to-r from-yellow-600 to-yellow-800 hover:from-yellow-800 hover:to-yellow-600 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-full font-semibold w-fit text-sm sm:text-base">Shop Now</a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-300 via-orange-200 to-orange-100 rounded-2xl sm:rounded-3xl overflow-hidden relative h-64 sm:h-80 bg-cover bg-center" style="background-image:url('assets/images/home/view-computer-video-display-monitor.jpg')">
                <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-center">
                    <h3 class="text-white text-2xl sm:text-4xl font-bold mb-1 sm:mb-2">Desktop</h3>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-1">HP EliteDesk</h4>
                    <h4 class="text-white text-xl sm:text-3xl font-bold mb-4 sm:mb-6">800 G5</h4>
                    <p class="text-white text-xs sm:text-sm mb-1">Powerful, Compact, Secure</p>
                    <p class="text-white text-xs sm:text-sm mb-4 sm:mb-6">IN STOCK</p>
                    <a href="products.php?category=desktop" class="bg-gradient-to-r from-green-600 to-green-800 hover:from-green-800 hover:to-green-600 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-full font-semibold w-fit text-sm sm:text-base">Shop Now</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-gradient-to-br from-yellow-100 via-orange-100 to-orange-200 rounded-3xl overflow-hidden relative h-64" style="background-image: url('assets/images/home/head_3.png.png'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 p-8">
                    <h3 class="text-gray-900 text-3xl font-bold mb-1">Keyboard</h3>
                    <h4 class="text-gray-900 text-3xl font-bold mb-2">2025</h4>
                    <p class="text-gray-700 text-sm mb-6">Mega Power in mini size</p>
                    <a href="products.php?category=keyboard" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-full font-semibold text-sm inline-block">Shop Now</a>
                </div>
            </div>

            <div class="bg-gradient-to-br rounded-3xl overflow-hidden relative h-64" style="background-image: url('assets/images/home/wireless-mouse-wheel-scrolling-data-input-tool-generated-by-ai.jpg'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 p-8 flex flex-col justify-center">
                    <h3 class="text-black text-2xl font-bold mb-2">Mouse</h3>
                    <h4 class="text-black text-lg font-semibold mb-2">Wireless & Wired</h4>
                    <p class="text-black-300 text-sm mb-4">Precision, Comfort, Performance</p>
                    <!-- <div class="mt-auto">
                        <p class="text-black-400 text-xs mb-1">FROM</p>
                        <p class="text-black-400 text-2xl font-bold">Rs14,000.00</p>
                    </div> -->
                    <a href="products.php?category=mouse" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-full font-semibold text-sm w-fit inline-block">Shop Now</a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-300 to-gray-400 rounded-3xl overflow-hidden relative h-64" style="background-image: url('assets/images/home/modern-security-camera-indoors.jpg'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 p-8 flex flex-col justify-center">
                    <p class="text-white text-xs uppercase mb-1">WEBCAMS</p>
                    <h3 class="text-white text-xl font-bold mb-1">HD 1080p</h3>
                    <h4 class="text-white text-lg font-semibold mb-2">USB & Wireless</h4>
                    <h5 class="text-white text-xl font-bold mb-6">Webcam</h5>
                    <a href="products.php?category=webcams" class="bg-white hover:bg-gray-100 text-gray-900 px-6 py-2.5 rounded-full font-semibold text-sm w-fit inline-block">Shop Now</a>
                </div>
                <!--<div class="absolute right-0 bottom-0 w-1/2 h-3/4">-->
                <!--    <img src="assets/images/pages/view-computer-monitor-display.jpg" alt="Webcam" class="h-full w-full object-contain">-->
                <!--</div>-->
            </div>
        </div>
    </section>

    <!-- Top Laptop Brands Section -->
    <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6 sm:mb-8">Top Brands</h2>
        <div class="relative overflow-hidden w-full">
            <div id="brands-marquee" class="flex items-center gap-12 animate-marquee-fast" style="will-change: transform; min-width: 1600px;">
                <?php if (!empty($brands)): ?>
                    <?php for ($loop = 0; $loop < 2; $loop++): // Duplicate for seamless loop 
                    ?>
                        <?php foreach ($brands as $b): ?>
                            <a href="products.php?brand=<?= $b['brand_id'] ?>" class="h-20 sm:h-24 lg:h-32 flex-shrink-0 w-40 sm:w-48 lg:w-56 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                                <img src="<?= !empty($b['brand_logo']) ? 'assets/images/brands/' . htmlspecialchars($b['brand_logo']) : 'assets/images/home/hp-300x300-1 1.png' ?>"
                                    alt="<?= htmlspecialchars($b['brand_name']) ?>"
                                    class="h-full object-contain mx-auto">
                            </a>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                <?php else: ?>
                    <?php for ($loop = 0; $loop < 2; $loop++): ?>
                        <img src="assets/images/home/hp-300x300-1 1.png" alt="HP" class="h-20 sm:h-24 lg:h-32 flex-shrink-0 w-40 sm:w-48 lg:w-56 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                        <img src="assets/images/home/hp-300x300-1 2.png" alt="ASUS" class="h-20 sm:h-24 lg:h-32 flex-shrink-0 w-40 sm:w-48 lg:w-56 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                        <img src="assets/images/home/hp-300x300-1 3.png" alt="Lenovo" class="h-20 sm:h-24 lg:h-32 flex-shrink-0 w-40 sm:w-48 lg:w-56 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                        <img src="assets/images/home/hp-300x300-1 4.png" alt="MSI" class="h-20 sm:h-24 lg:h-32 flex-shrink-0 w-40 sm:w-48 lg:w-56 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                        <img src="assets/images/home/hp-300x300-1 5.png" alt="Dell" class="h-20 sm:h-24 lg:h-32 flex-shrink-0 w-40 sm:w-48 lg:w-56 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                        <img src="assets/images/home/hp-300x300-1 6.png" alt="Acer" class="h-20 sm:h-24 lg:h-32 flex-shrink-0 w-40 sm:w-48 lg:w-56 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                        <img src="assets/images/home/hp-300x300-1 7.png" alt="Brother" class="h-20 sm:h-24 lg:h-32 flex-shrink-0 w-40 sm:w-48 lg:w-56 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                        <img src="assets/images/home/hp-300x300-1 8.png" alt="Samsung" class="h-20 sm:h-24 lg:h-32 flex-shrink-0 w-40 sm:w-48 lg:w-56 transform transition duration-300 ease-out hover:scale-110 cursor-pointer">
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>
        <style>
            @keyframes marquee-fast {
                0% {
                    transform: translateX(0);
                }

                100% {
                    transform: translateX(-50%);
                }
            }

            .animate-marquee-fast {
                animation: marquee-fast 10s linear infinite;
            }
        </style>
    </section>

    <section class="hero-wrap" style="min-height:600px;">

        <!-- SLIDE 1 — Laptops -->
        <div class="banner-slide active" id="slide0">
            <div class="max-w-full mx-auto px-6 lg:px-14 py-14 lg:py-20 h-full flex items-center relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center w-full">
                    <div>
                        <div class="tag-pill anim-up">Premium Business Laptops</div>
                        <h1 class="hero-title anim-up delay-1 mb-4">
                            Power Your<br><span class="hl">Business Dreams</span>
                        </h1>
                        <p class="hero-desc anim-up delay-2 mb-6">
                            Enterprise-grade performance for the modern professional. Built to keep you ahead.
                        </p>
                        <div class="spec-row anim-up delay-2 mb-7">
                            <div class="spec-chip"><span></span>Intel i7</div>
                            <div class="spec-chip"><span></span>Up to 32GB RAM</div>
                            <div class="spec-chip"><span></span>512GB SSD</div>
                            <div class="spec-chip"><span></span>15.6" FHD</div>
                        </div>
                        <div class="anim-up delay-3 mb-7">
                            <div class="price-from">Starting from</div>
                            <div class="price-tag">Rs.345,000</div>
                            <div class="price-note">✓ 0% Interest Financing &nbsp;·&nbsp; ✓ Free Delivery</div>
                        </div>
                        <div class="anim-up delay-4">
                            <a href="products.php?category=laptops" class="cta-btn">
                                Shop Laptops
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="relative flex justify-center items-center anim-img">
                        <img src="assets/images/products/design.jpeg" alt="Business Laptop" class="w-full max-w-lg object-contain" style="filter:drop-shadow(0 20px 60px rgba(182,26,168,0.3));">
                        <div class="stat-badge badge-a absolute top-4 right-4">
                            <div class="stat-val">512GB</div>
                            <div class="stat-lbl">SSD</div>
                        </div>
                        <div class="stat-badge badge-b absolute bottom-8 left-4">
                            <div class="stat-val">15.6"</div>
                            <div class="stat-lbl">FHD</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 2 — Headphones -->
        <div class="banner-slide" id="slide1">
            <div class="max-w-full mx-auto px-6 lg:px-14 py-14 lg:py-20 h-full flex items-center relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center w-full">
                    <div>
                        <div class="tag-pill blue anim-up">Premium Webcams</div>
                        <h1 class="hero-title anim-up delay-1 mb-4">
                            Connect & Secure<br><span class="hl blue">HD Webcams</span>
                        </h1>
                        <p class="hero-desc anim-up delay-2 mb-6">
                            High-definition video for meetings, streaming, and security. Plug & play, USB & wireless options.
                        </p>
                        <div class="spec-row anim-up delay-2 mb-7">
                            <div class="spec-chip"><span class="blue"></span>1080p HD</div>
                            <div class="spec-chip"><span class="blue"></span>USB & Wireless</div>
                            <div class="spec-chip"><span class="blue"></span>Built-in Mic</div>
                        </div>
                        <div class="anim-up delay-3 mb-7">
                            <div class="price-from">Starting from</div>
                            <div class="price-tag">Rs.14,000</div>
                            <div class="price-note">✓ Free Shipping &nbsp;·&nbsp; ✓ 1 Year Warranty</div>
                        </div>
                        <div class="anim-up delay-4">
                            <a href="products.php?category=webcams" class="cta-btn blue">
                                Shop Webcams
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-center items-center anim-img">
                        <div class="relative inline-flex">
                            <img src="assets/images/home/webcam.png" alt="Premium Webcams" class="w-full max-w-3xl object-contain" style="filter:drop-shadow(0 20px 60px rgba(2,132,199,0.3)); min-height: 440px; min-width: 540px;">
                            <div class="stat-badge badge-a absolute top-12 right-20">
                                <div class="stat-val blue">40H</div>
                                <div class="stat-lbl">Battery</div>
                            </div>
                            <div class="stat-badge badge-b absolute bottom-6 -left-10">
                                <div class="stat-val blue">autofocus</div>
                                <div class="stat-lbl">HD video</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nav + Progress -->
        <div class="absolute bottom-5 left-6 lg:left-14 flex items-center gap-3 z-10">
            <div class="nav-dot active" style="width:32px;" id="nd0" onclick="toSlide(0)"></div>
            <div class="nav-dot" style="width:12px;" id="nd1" onclick="toSlide(1)"></div>
        </div>
        <div class="prog-line" id="progressLine" style="width:0%;"></div>

    </section>

    <!-- Best Sellers Section -->
    <?php if (!empty($bestsellers)): ?>
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 bg-gray-50">
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
    <?php endif; ?>

    <!-- Pre Order Banner -->
    <section class="preorder-wrap py-10 lg:py-12">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-14">

            <!-- Header row -->
            <div class="flex items-center justify-between mb-7">
                <div class="flex items-center gap-3">
                    <div class="po-badge">
                        <div class="po-badge-dot">
                            <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24" class="text-white">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span style="font-family:'Outfit',sans-serif;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--purple);">Pre Order</span>
                    </div>
                    <span class="po-label">Be the first to own the latest arrivals</span>
                </div>

                <!-- Thumbnail nav -->
                <div class="flex gap-2" id="poThumbs"></div>
            </div>

            <!-- Product cards (one visible at a time) -->
            <div id="poCards"></div>

        </div>
    </section>

    <!-- Customer Feedback Section -->
    <section class="w-full px-4 sm:px-6 lg:px-8 py-12 sm:py-16" style="background-color: #f3e8f2;">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">What Our Customers Say</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Trusted by thousands of satisfied customers across Sri Lanka</p>
        </div>
        <div class="relative max-w-7xl mx-auto">
            <button id="prevFeedback" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 sm:-translate-x-12 z-10 bg-white hover:bg-purple-custom text-gray-700 hover:text-white w-10 h-10 sm:w-12 sm:h-12 rounded-full shadow-lg flex items-center justify-center transition-colors duration-300">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button id="nextFeedback" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 sm:translate-x-12 z-10 bg-white hover:bg-purple-custom text-gray-700 hover:text-white w-10 h-10 sm:w-12 sm:h-12 rounded-full shadow-lg flex items-center justify-center transition-colors duration-300">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div class="overflow-hidden px-2">
                <div id="feedbackContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
            </div>
            <div id="feedbackDots" class="flex justify-center gap-2 mt-8"></div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Feedback testimonials data from database
        const feedbacks = <?= json_encode(array_map(function ($t) {
                                // Determine image path
                                $imagePath = 'https://ui-avatars.com/api/?name=' . urlencode($t['customer_name']) . '&background=7D1074&color=fff&size=128';

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
        });

        // ── HERO BANNER ──
        let cur = 0;
        const DURATION = 5000;
        let timer;
        const slides = [document.getElementById('slide0'), document.getElementById('slide1')];
        const dots = [document.getElementById('nd0'), document.getElementById('nd1')];
        const prog = document.getElementById('progressLine');

        function startProgress() {
            prog.style.transition = 'none';
            prog.style.width = '0%';
            requestAnimationFrame(() => requestAnimationFrame(() => {
                prog.style.transition = `width ${DURATION}ms linear`;
                prog.style.width = '100%';
            }));
        }

        function toSlide(n) {
            if (n === cur) return;
            slides[cur].classList.remove('active');
            dots[cur].classList.remove('active');
            dots[cur].style.width = '12px';
            cur = n;
            slides[cur].classList.add('active');
            dots[cur].classList.add('active');
            dots[cur].style.width = '32px';
            startProgress();
            clearInterval(timer);
            timer = setInterval(() => toSlide((cur + 1) % slides.length), DURATION);
        }

        startProgress();
        timer = setInterval(() => toSlide((cur + 1) % slides.length), DURATION);


        // ── PRE-ORDER ROTATING PRODUCTS (from database) ──
        const preorderProducts = <?php
                                    // Reuse $featuredProducts already fetched at top of page — no second DB connection needed.
                                    // Get specs via the connection already open inside the $product object.
                                    $database = new Database();
                                    $db = $database->getConnection(); // PDO connection from the Product class

                                    $specStmt = $db->prepare("
            SELECT spec_name, spec_value
            FROM product_specifications
            WHERE product_id = ?
            ORDER BY display_order ASC
            LIMIT 4
        ");

                                    $output = [];
                                    foreach ($featuredProducts as $p) {
                                        // Fetch specs for this product
                                        $specStmt->execute([$p['product_id']]);
                                        $specs = $specStmt->fetchAll(PDO::FETCH_ASSOC);

                                        if (!empty($specs)) {
                                            $specParts = array_map(
                                                fn($s) => htmlspecialchars($s['spec_name']) . ': ' . htmlspecialchars($s['spec_value']),
                                                $specs
                                            );
                                            $specStr = implode(' &nbsp;·&nbsp; ', $specParts);
                                        } elseif (!empty($p['short_description'])) {
                                            $specStr = htmlspecialchars($p['short_description']);
                                        } else {
                                            $specStr = htmlspecialchars($p['category_name'] ?? '');
                                        }

                                        $img = !empty($p['main_image'])
                                            ? 'assets/images/products/' . $p['main_image']
                                            : 'assets/images/categories/default.png';

                                        $hasSale   = !empty($p['sale_price']) && $p['sale_price'] < $p['price'];
                                        $dispPrice = $hasSale ? $p['sale_price'] : $p['price'];
                                        $label     = !empty($p['brand_name']) ? $p['brand_name'] : ($p['category_name'] ?? 'Product');

                                        $output[] = [
                                            'label'    => htmlspecialchars($label),
                                            'name'     => htmlspecialchars($p['product_name']),
                                            'spec'     => $specStr,
                                            'price'    => 'Rs.' . number_format($dispPrice, 2),
                                            'oldPrice' => $hasSale ? 'Rs.' . number_format($p['price'], 2) : null,
                                            'discount' => $p['discount_percentage'] > 0 ? (int)$p['discount_percentage'] : null,
                                            'link'     => 'product_detail.php?id=' . (int)$p['product_id'],
                                            'img'      => $img,
                                            'thumb'    => $img,
                                        ];
                                    }
                                    echo json_encode($output);
                                    ?>;

        const thumbsEl = document.getElementById('poThumbs');
        const cardsEl = document.getElementById('poCards');
        let poCur = 0;
        let poTimer;
        const PO_DURATION = 4000;

        function buildPO() {
            // Thumbnails
            preorderProducts.forEach((p, i) => {
                const t = document.createElement('div');
                t.className = 'po-thumb' + (i === 0 ? ' active' : '');
                t.innerHTML = `<img src="${p.thumb}" alt="${p.name}">`;
                t.onclick = () => toPO(i);
                thumbsEl.appendChild(t);
            });

            // Cards
            preorderProducts.forEach((p, i) => {
                const c = document.createElement('div');
                c.className = 'po-item items-center gap-8 lg:gap-0' + (i === 0 ? ' active' : '');
                c.id = `po${i}`;
                c.innerHTML = `
                <div class="lg:w-52 flex-shrink-0 laptop-wrap flex justify-center">
                    <img src="${p.img}" alt="${p.name}" class="h-36 lg:h-44 object-contain" style="filter:drop-shadow(0 10px 30px rgba(182,26,168,0.15));">
                </div>
                <div class="po-divider hidden lg:block mx-10 my-2"></div>
                <div class="flex-1 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div>
                        <div class="po-eyebrow mb-1">${p.label}</div>
                        <div class="po-title mb-2">${p.name}</div>
                        <div class="po-spec">${p.spec}</div>
                    </div>
                    <div class="po-divider hidden lg:block mx-8 my-2"></div>
                    <div class="flex-shrink-0 text-right">
                        ${p.discount ? `<div style="display:inline-block;background:rgba(182,26,168,0.2);border:1px solid rgba(182,26,168,0.35);color:#e870df;font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;margin-bottom:8px;">−${p.discount}% OFF</div>` : ''}
                        <div class="po-price-from">${p.priceFrom ? 'From' : 'Price'}</div>
                        <div class="po-price">${p.price}</div>
                        ${p.oldPrice ? `<div style="font-size:12px;color:rgba(255,255,255,0.3);text-decoration:line-through;margin-bottom:12px;">${p.oldPrice}</div>` : '<div style="margin-bottom:16px;"></div>'}
                        <a href="${p.link}" class="po-cta">
                            Order Now
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            `;
                cardsEl.appendChild(c);
            });
        }

        function toPO(n) {
            const thumbs = thumbsEl.querySelectorAll('.po-thumb');
            thumbs[poCur].classList.remove('active');
            document.getElementById(`po${poCur}`).classList.remove('active');

            poCur = n;

            thumbs[poCur].classList.add('active');
            document.getElementById(`po${poCur}`).classList.add('active');

            clearInterval(poTimer);
            poTimer = setInterval(() => toPO((poCur + 1) % preorderProducts.length), PO_DURATION);
        }

        buildPO();
        poTimer = setInterval(() => toPO((poCur + 1) % preorderProducts.length), PO_DURATION);

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