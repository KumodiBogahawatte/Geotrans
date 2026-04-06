<?php
require_once 'includes/helpers.php';
require_once 'config/database.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
require_once 'classes/Brand.php';
require_once 'classes/Testimonial.php';

// ── Single DB connection for the whole page ──
$database = new Database();
$db = $database->getConnection();

$product    = new Product();
$category   = new Category();
$brand      = new Brand();
$testimonial = new Testimonial();

// Categories strip — fixed order & labels (see Category::getHomeCategoriesDisplay)
$categories = $category->getHomeCategoriesDisplay();
$categoryProductCounts = $category->getProductCountsByCategoryIds(array_column($categories, 'category_id'));
$featuredProducts = $product->getFeatured(6);
$bestsellers     = $product->getBestsellers(20);
$newArrivals     = $product->getNewArrivals(20);
$brands          = $brand->getPopular(6);
$testimonials    = $testimonial->getActive();

/** Optional full-bleed backgrounds per hero slide — place files in assets/images/home/ */
$heroBgFile = static function (array $candidates) {
    foreach ($candidates as $p) {
        if (file_exists(__DIR__ . '/' . $p)) {
            return $p;
        }
    }
    return null;
};
$jkHeroBgFile   = $heroBgFile(['assets/images/home/hero-background-1.jpg', 'assets/images/home/john-keells-hero-bg.png', 'assets/images/home/john-keells-hero-bg.jpg', 'assets/images/home/john-keells-hero-bg.webp']);
$printHeroBgFile = $heroBgFile(['assets/images/home/hero-background-2.jpg', 'assets/images/home/hero-slide-print-bg.png', 'assets/images/home/hero-slide-print-bg.jpg', 'assets/images/home/hero-slide-print-bg.webp']);
$laptopHeroBgFile = $heroBgFile(['assets/images/home/hero-background-3.jpg', 'assets/images/home/hero-slide-laptop-bg.png', 'assets/images/home/hero-slide-laptop-bg.jpg', 'assets/images/home/hero-slide-laptop-bg.webp']);
$tonerHeroBgFile = $heroBgFile(['assets/images/home/hero-background-4.jpg', 'assets/images/home/hero-slide-toner-bg.jpg', 'assets/images/home/hero-slide-toner-bg.webp']);

/** Distinct default photo per slide when no custom hero-bg asset is uploaded */
$jkHeroBgUrl = $jkHeroBgFile ?: 'assets/images/home/hero-background-1.jpg';
$printHeroBgUrl = $printHeroBgFile ?: 'assets/images/home/hero-background-2.jpg';
$laptopHeroBgUrl = $laptopHeroBgFile ?: 'assets/images/home/hero-background-3.jpg';
$tonerHeroBgUrl = $tonerHeroBgFile ?: 'assets/images/home/hero-background-4.jpg';

$preorderBannerBgFile = $heroBgFile([
    'assets/images/home/Section - Pre Order Banner.png',
]);
$preorderBannerBgUrl = $preorderBannerBgFile ?: 'assets/images/home/Section - Pre Order Banner.png';

// ── FEATURED BANNER PRODUCTS ──
$bannerStmt = $db->prepare("
    SELECT 
        p.product_id,
        p.product_name,
        p.short_description,
        p.main_image,
        p.stock_quantity,
        p.price,
        p.sale_price,
        p.discount_percentage,
        b.brand_name,
        c.category_name,
        c.category_slug
    FROM products p
    LEFT JOIN brands b ON p.brand_id = b.brand_id
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE p.is_active = 1
      AND c.category_slug IN (
          'copiers-printers','laptops', 'desktops', 'monitors', 'toners-cartridges', 'toners-cartriges', 'accessories'
      )
    ORDER BY c.category_id ASC, p.sold_count DESC
");
$bannerStmt->execute();
$bannerRows = $bannerStmt->fetchAll(PDO::FETCH_ASSOC);

$bannerByCategory = [];
foreach ($bannerRows as $row) {
    $slug = $row['category_slug'];
    $img  = !empty($row['main_image'])
        ? 'assets/images/products/' . $row['main_image']
        : null;

    $hasSale   = !empty($row['sale_price']) && $row['sale_price'] < $row['price'];
    $dispPrice = $hasSale ? $row['sale_price'] : $row['price'];

    $displaySlug = ($slug === 'printer') ? 'copiers-printers' : $slug;
    $displaySlug = ($slug === 'toners-cartriges') ? 'toners-cartridges' : $displaySlug;
    $displaySlug = in_array($slug, ['keyboards', 'mouse', 'webcams']) ? 'accessories' : $displaySlug;
    // Raw strings: consumed via json_encode → JS textContent (htmlspecialchars would show "&amp;" literally)
    $bannerByCategory[$displaySlug][] = [
        'product_name'      => $row['product_name'] ?? '',
        'brand_name'        => $row['brand_name'] ?? '',
        'short_description' => $row['short_description'] ?? '',
        'in_stock'          => (int)$row['stock_quantity'] > 0,
        'category_name'     => $row['category_name'] ?? '',
        'category_slug'     => $slug,
        'image'             => $img,
        'price'             => 'Rs.' . number_format($dispPrice, 2),
        'discount'          => $row['discount_percentage'] > 0 ? (int)$row['discount_percentage'] : null,
        'link'              => 'product_detail.php?id=' . (int)$row['product_id'],
    ];
}

// ── PRE-ORDER specs (reuse same $db) ──
$specStmt = $db->prepare("
    SELECT spec_name, spec_value
    FROM product_specifications
    WHERE product_id = ?
    ORDER BY display_order ASC
    LIMIT 4
");
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        .text-purple-custom {
            color: #680e68;
        }

        .bg-purple-custom {
            background-color: #680e68;
        }

        .hover\:bg-purple-custom:hover {
            background-color: #680e68;
        }

        .hover\:text-purple-custom:hover {
            color: #680e68;
        }

        .border-purple-custom {
            border-color: #680e68;
        }

        .from-purple-custom {
            --tw-gradient-from: #680e68;
        }

        .to-purple-custom {
            --tw-gradient-to: #680e68;
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
            --purple: #680e68;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f8f5f8;
        }

        /* Swiper hero slides */
        .swiper-slide-active .anim-up {
            animation: up 0.65s cubic-bezier(.22, 1, .36, 1) both;
        }

        .swiper-slide-active .delay-1 {
            animation-delay: 0.1s;
        }

        .swiper-slide-active .delay-2 {
            animation-delay: 0.22s;
        }

        .swiper-slide-active .delay-3 {
            animation-delay: 0.34s;
        }

        .swiper-slide-active .delay-4 {
            animation-delay: 0.46s;
        }

        .swiper-slide-active .anim-img {
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
            line-height: 1.45;
            color: #e870df;
            background: rgba(182, 26, 168, 0.15);
            border: 1px solid rgba(182, 26, 168, 0.25);
            border-radius: 999px;
            padding: 5px 14px;
            margin-bottom: 14px;
        }

        .hero-title {
            font-size: clamp(1.5rem, 5vw, 4.2rem);
            font-weight: 800;
            line-height: 1.40;
            color: #fff;
            letter-spacing: -0.02em;
        }

        .hero-title .hl {
            background: linear-gradient(135deg, #e870df, #680e68);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            line-height: 1.8;
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
            background: linear-gradient(135deg,rgb(180, 39, 14),rgb(80, 20, 1));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .spec-chip span.blue {
            background: #7dd3fc;
        }

        /* ── TOP SWIPER HERO ── */
        .banner-hero-wrap {
            position: relative;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        .banner-swiper.hero-swiper {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            height: clamp(420px, 55vw, 600px);
        }

        .banner-swiper.hero-swiper .swiper-slide {
            height: 100%;
        }

        @media (min-width: 1024px) {
            /* Extra horizontal inset so hero content (and product stacks) sit off the viewport edge */
            .banner-swiper.hero-swiper .hero-slide-bg > .max-w-full {
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }
        }

        .hero-slide-bg {
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .hero-slide-bg::before {
            display: none;
        }

        /* Hero photo only — no gradient overlay */
        .hero-slide-bg[style*='--hero-bg'] {
            background-image: var(--hero-bg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero-slide-bg.hero-slide-bg--jk {
            background-color: #0a1628;
        }

        .hero-slide-bg.hero-slide-bg--print {
            background-color: #140c04;
        }

        .hero-slide-bg.hero-slide-bg--laptop {
            background-color: #140606;
        }

        .hero-slide-bg.hero-slide-bg--toner {
            background-color: #051a1c;
        }

        /* Unified hero: center copy + product wings (all banner slides) */
        .hero-unified-grid {
            display: grid;
            grid-template-areas:
                'jk-center'
                'jk-left'
                'jk-right';
            grid-template-columns: 1fr;
            gap: 1.25rem;
            align-items: center;
            width: 100%;
        }

        .hero-unified-wing--left {
            grid-area: jk-left;
        }

        .hero-unified-center {
            grid-area: jk-center;
        }

        .hero-unified-wing--right {
            grid-area: jk-right;
        }

        @media (min-width: 1024px) {
            .hero-unified-grid {
                grid-template-areas: 'jk-left jk-center jk-right';
                grid-template-columns: minmax(0, 1.08fr) minmax(0, 1.28fr) minmax(0, 1.08fr);
                gap: 0.35rem 0.45rem;
                align-items: stretch;
                min-height: 100%;
            }
        }

        .hero-unified-wing {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 140px;
        }

        @media (min-width: 1024px) {
            .hero-unified-wing {
                align-items: stretch;
                min-height: 0;
            }

            .hero-unified-wing--left {
                justify-content: flex-end;
            }

            .hero-unified-wing--right {
                justify-content: flex-start;
            }
        }

        .hero-unified-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
            max-width: none;
            min-width: 0;
            margin-left: auto;
            margin-right: auto;
            container-type: inline-size;
            container-name: hero-center;
        }

        @media (min-width: 1024px) {
            .hero-unified-center {
                justify-content: center;
                min-height: 100%;
            }
        }

        /* JK slide: pill without .blue/.emerald/.rose — light blue */
        .hero-slide-bg--jk .hero-unified-center .tag-pill:not(.blue):not(.emerald):not(.rose) {
            margin-left: auto;
            margin-right: auto;
            color: #e0f2fe;
            background: rgba(14, 165, 233, 0.22);
            border: 1px solid rgba(125, 211, 252, 0.45);
        }

        .hero-unified-center .hero-desc {
            margin-left: auto;
            margin-right: auto;
            max-width: none;
        }

        .hero-swiper .hero-unified-center .hero-title {
            font-family: 'Montserrat', system-ui, -apple-system, sans-serif;
            font-weight: 800;
            font-size: clamp(1.05rem, 2.65vw, 2.65rem);
            line-height: 1.40;
            white-space: nowrap;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (min-width: 1024px) {
            .hero-swiper .hero-unified-center .hero-title {
                font-size: clamp(1.35rem, 3.15vw, 3.35rem);
                line-height: 1.28;
            }
        }

        @media (min-width: 1280px) {
            .hero-swiper .hero-unified-center .hero-title {
                font-size: clamp(1.5rem, 3.4vw, 3.65rem);
            }
        }

        .hero-swiper .hero-unified-center .hero-desc {
            font-size: clamp(0.8rem, 1.35vw, 1rem);
            line-height: 1.65;
            max-width: 100%;
        }

        .hero-slide-bg--jk .hero-unified-center .hero-desc {
            color: rgba(186, 230, 253, 0.58);
        }

        .hero-title .hl-jk {
            background: linear-gradient(135deg, #bae6fd 0%, #7dd3fc 45%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: #7dd3fc;
        }

        .jk-title-rest {
            color: #e0f2fe;
        }

        .hero-unified-center .hero-chip-row {
            display: flex;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
            gap: 3px;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            margin-top: 0.75rem;
            margin-bottom: 0;
            overflow: visible;
        }

        .hero-unified-center .hero-chip {
            flex: 0 0 auto;
            white-space: nowrap;
            font-size: clamp(8px, 1.85cqi, 11px);
            font-size: clamp(8px, 0.72vw, 11px);
            font-weight: 600;
            line-height: 1.4;
            letter-spacing: -0.015em;
            padding: 3px 6px;
            color: rgba(255, 255, 255, 0.94);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.28);
        }

        .hero-slide-bg--jk .hero-unified-center .hero-chip.is-lit {
            color: #fff;
            background: rgba(14, 165, 233, 0.38);
            border-color: rgba(125, 211, 252, 0.6);
            box-shadow: 0 0 0 1px rgba(2, 132, 199, 0.32);
        }

        .hero-slide-bg--jk .hero-unified-center .hero-chip:hover {
            border-color: rgba(125, 211, 252, 0.5);
            background: rgba(255, 255, 255, 0.14);
        }

        .hero-slide-bg--jk .hero-unified-center .hero-chip.is-lit:hover {
            border-color: rgba(186, 230, 253, 0.85);
            background: rgba(14, 165, 233, 0.52);
        }

        .hero-slide-bg--print .hero-unified-center .hero-chip.is-lit {
            color: #fff;
            background: rgba(217, 119, 6, 0.42);
            border-color: rgba(252, 211, 77, 0.55);
            box-shadow: 0 0 0 1px rgba(180, 83, 9, 0.35);
        }

        .hero-slide-bg--print .hero-unified-center .hero-chip:hover {
            border-color: rgba(252, 211, 77, 0.5);
            background: rgba(255, 255, 255, 0.12);
        }

        .hero-slide-bg--print .hero-unified-center .hero-chip.is-lit:hover {
            border-color: rgba(252, 211, 77, 0.8);
            background: rgba(217, 119, 6, 0.55);
        }

        .hero-slide-bg--laptop .hero-unified-center .hero-chip.is-lit {
            color: #fff;
            background: rgba(220, 38, 38, 0.45);
            border-color: rgba(252, 165, 165, 0.6);
            box-shadow: 0 0 0 1px rgba(153, 27, 27, 0.35);
        }

        .hero-slide-bg--laptop .hero-unified-center .hero-chip:hover {
            border-color: rgba(252, 165, 165, 0.5);
            background: rgba(255, 255, 255, 0.12);
        }

        .hero-slide-bg--laptop .hero-unified-center .hero-chip.is-lit:hover {
            border-color: rgba(252, 165, 165, 0.85);
            background: rgba(220, 38, 38, 0.58);
        }

        .hero-slide-bg--toner .hero-unified-center .hero-chip.is-lit {
            color: #fff;
            background: rgba(13, 148, 136, 0.42);
            border-color: rgba(94, 234, 212, 0.55);
            box-shadow: 0 0 0 1px rgba(15, 118, 110, 0.35);
        }

        .hero-slide-bg--toner .hero-unified-center .hero-chip:hover {
            border-color: rgba(94, 234, 212, 0.45);
            background: rgba(255, 255, 255, 0.12);
        }

        .hero-slide-bg--toner .hero-unified-center .hero-chip.is-lit:hover {
            border-color: rgba(94, 234, 212, 0.8);
            background: rgba(13, 148, 136, 0.55);
        }

        .hero-slide-bg--jk .hero-unified-center .hero-rotator-label {
            color: rgba(125, 211, 252, 0.55);
        }

        .hero-slide-bg--print .hero-unified-center .hero-rotator-label {
            color: rgba(252, 211, 77, 0.48);
        }

        .hero-slide-bg--laptop .hero-unified-center .hero-rotator-label {
            color: rgba(252, 165, 165, 0.5);
        }

        /* Printers slide: amber/gold (overrides .blue utilities in this slide only) */
        .hero-slide-bg--print .hero-unified-center .tag-pill.blue {
            color: #fde68a;
            background: rgba(217, 119, 6, 0.22);
            border-color: rgba(252, 211, 77, 0.4);
        }

        .hero-slide-bg--print .hero-unified-center .hero-title .hl.blue {
            background: linear-gradient(135deg, #fde68a, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Laptops slide: red (overrides .emerald utilities in this slide only) */
        .hero-slide-bg--laptop .hero-unified-center .tag-pill.emerald {
            color: #fecaca;
            background: rgba(220, 38, 38, 0.28);
            border-color: rgba(248, 113, 113, 0.45);
        }

        .hero-slide-bg--laptop .hero-unified-center .hero-title .hl.emerald {
            background: linear-gradient(135deg, #fca5a5, #b91c1c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Toners slide: cyan/teal (overrides .rose utilities in this slide only) */
        .hero-slide-bg--toner .hero-unified-center .tag-pill.rose {
            color: #ccfbf1;
            background: rgba(13, 148, 136, 0.26);
            border-color: rgba(45, 212, 191, 0.42);
        }

        .hero-slide-bg--toner .hero-unified-center .hero-title .hl.rose {
            background: linear-gradient(135deg, #5eead4, #0d9488);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-slide-bg--toner .hero-unified-center .hero-rotator-label {
            color: rgba(94, 234, 212, 0.48);
        }

        /* Toners slide: title is two lines + longer words — don't single-line ellipsis on desktop */
        @media (min-width: 1024px) {
            .hero-slide-bg--toner .hero-unified-center .hero-title {
                white-space: normal;
                overflow: visible;
                text-overflow: clip;
                text-align: center;
            }

            .hero-slide-bg--toner .hero-unified-center .tag-pill.rose {
                max-width: 100%;
                white-space: normal;
                text-align: center;
                line-height: 1.35;
            }
        }

        .hero-unified-center .hero-rotator-wrap {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 0.5rem 0.65rem;
            margin-top: 0.75rem;
            margin-bottom: 0;
            min-height: 0;
            max-width: 100%;
        }

        .hero-unified-center .hero-rotator-label {
            margin-bottom: 0;
            flex-shrink: 0;
        }

        .hero-unified-center .hero-rotator-inner {
            margin: 0;
            text-align: center;
            max-width: 100%;
            white-space: normal;
            line-height: 1.55;
            font-size: clamp(0.9rem, 2.2cqi, 1.2rem);
            font-size: clamp(0.9rem, 2.4vw, 1.2rem);
        }

        .hero-unified-wing .hero-img-stack {
            right: auto;
            max-width: min(100%, 17rem);
            min-height: 140px;
        }

        .hero-unified-wing .hero-img-stack .stack-img {
            max-height: min(200px, 31vw);
            width: auto;
            max-width: 100%;
            padding: 12px;
            filter: drop-shadow(0 12px 28px rgba(182, 26, 168, 0.22));
        }

        .hero-slide-bg--jk .hero-unified-wing .hero-img-stack .stack-img {
            filter: drop-shadow(0 12px 28px rgba(56, 189, 248, 0.28));
        }

        /* Small inset from viewport edges (no negative pull) */
        @media (min-width: 1024px) {
            .hero-unified-wing--left .hero-img-stack {
                margin-left: 0.35rem;
                margin-right: auto;
            }

            .hero-unified-wing--right .hero-img-stack {
                margin-right: 0.35rem;
                margin-left: auto;
            }

            .hero-unified-wing .hero-img-stack {
                right: auto;
                max-width: min(100%, 20rem);
                min-height: min(30vh, 260px);
                align-self: center;
            }

            .hero-unified-wing .hero-img-stack .stack-img {
                max-height: min(275px, 38vh);
                padding: 12px;
            }

            .hero-slide-bg--jk .hero-unified-wing .hero-img-stack .stack-img {
                filter: drop-shadow(0 14px 32px rgba(56, 189, 248, 0.32));
            }

            .hero-slide-bg--print .hero-unified-wing .hero-img-stack .stack-img {
                filter: drop-shadow(0 14px 32px rgba(217, 119, 6, 0.28));
            }

            .hero-slide-bg--laptop .hero-unified-wing .hero-img-stack .stack-img {
                filter: drop-shadow(0 14px 32px rgba(220, 38, 38, 0.28));
            }

            .hero-slide-bg--toner .hero-unified-wing .hero-img-stack .stack-img {
                filter: drop-shadow(0 14px 32px rgba(13, 148, 136, 0.3));
            }
        }

        @media (min-width: 1280px) {
            .hero-unified-grid {
                grid-template-columns: minmax(0, 1.1fr) minmax(0, 1.22fr) minmax(0, 1.1fr);
                gap: 0.35rem 0.4rem;
            }
        }

        .hero-swiper-nav-desktop {
            display: none;
        }

        @media (min-width: 1024px) {
            .hero-swiper-nav-desktop {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                width: 100%;
                max-width: 100%;
                margin-top: 6.5rem;
                gap: 10px;
            }

            .banner-swiper.hero-swiper .hero-swiper-nav-desktop .swiper-button-next,
            .banner-swiper.hero-swiper .hero-swiper-nav-desktop .swiper-button-prev {
                position: static;
                margin: 0;
                top: auto;
                left: auto;
                right: auto;
                bottom: auto;
            }
        }

        /* Inside .hero-slide-bg (mobile) so arrows sit on the hero image, not below the swiper */
        .banner-swiper.hero-swiper .hero-slide-bg > .hero-swiper-nav-mobile {
            position: absolute;
            top: auto;
            bottom: max(0.75rem, env(safe-area-inset-bottom, 0px));
            left: 50%;
            right: auto;
            transform: translateX(-50%);
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 8px;
            z-index: 20;
            width: max-content;
            max-width: calc(100% - 2rem);
            pointer-events: auto;
        }

        @media (min-width: 1024px) {
            .banner-swiper.hero-swiper .hero-slide-bg > .hero-swiper-nav-mobile {
                display: none !important;
            }
        }

        .banner-swiper.hero-swiper .swiper-button-next,
        .banner-swiper.hero-swiper .swiper-button-prev {
            color: rgba(255, 255, 255, 0.85);
            width: 34px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: rgba(18, 7, 26, 0.55);
            backdrop-filter: blur(6px);
            z-index: 14;
        }

        .banner-swiper.hero-swiper .hero-slide-bg > .hero-swiper-nav-mobile .swiper-button-next,
        .banner-swiper.hero-swiper .hero-slide-bg > .hero-swiper-nav-mobile .swiper-button-prev {
            position: static;
            top: auto;
            bottom: auto;
            left: auto;
            right: auto;
        }

        .banner-swiper.hero-swiper .swiper-button-next::after,
        .banner-swiper.hero-swiper .swiper-button-prev::after {
            font-size: 12px;
            font-weight: 900;
        }

        .hero-slide-bg--jk .swiper-button-next,
        .hero-slide-bg--jk .swiper-button-prev {
            color: #e0f2fe;
            border-color: rgba(125, 211, 252, 0.55);
            background: rgba(8, 47, 73, 0.52);
        }

        .banner-swiper.hero-swiper .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.35);
            opacity: 1;
            border: 1px solid rgba(255, 255, 255, 0.6);
            width: 8px;
            height: 8px;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.22);
        }

        .banner-swiper.hero-swiper .swiper-pagination-bullet-active {
            background: var(--purple);
            border-color: rgba(232, 112, 223, 0.95);
            width: 18px;
            border-radius: 999px;
            box-shadow: 0 0 0 2px rgba(182, 26, 168, 0.3);
        }

        /* Pagination vertically at top-right of slider (inset matches page lg:px-8) */
        .banner-swiper.hero-swiper .swiper-pagination {
            top: 8px !important;
            right: 2rem !important;
            left: auto !important;
            bottom: auto !important;
            width: auto !important;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 12;
        }

        .hero-rotator-wrap {
            margin-bottom: 0;
            min-height: 0;
        }

        /* Keep text area tighter so product area feels larger */
        .hero-swiper .swiper-hero-grid {
            grid-template-columns: 1fr;
        }

        @media (min-width: 1024px) {
            .hero-swiper .swiper-hero-grid {
                grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
            }
        }

        .hero-swiper .swiper-hero-grid>div:first-child {
            max-width: 520px;
        }

        /* Slide 1: place Explore button at right side of image */
        .explore-cta-right {
            position: absolute;
            right: -30px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 8;
        }

        .hero-rotator-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.38);
            margin-bottom: 6px;
        }

        .hero-rotator-inner {
            font-size: clamp(1rem, 2vw, 1.35rem);
            font-weight: 700;
            color: #fff;
            line-height: 1.55;
            transition: opacity 0.35s ease, transform 0.35s ease;
        }

        .hero-rotator-inner.is-out {
            opacity: 0;
            transform: translateY(6px);
        }

        .hero-chip-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 0;
        }

        .hero-chip {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            padding: 6px 12px;
            transition: border-color 0.25s, background 0.25s;
            cursor: pointer;
        }

        .hero-chip:hover {
            border-color: rgba(104, 14, 104, 0.55);
            background: rgba(79, 10, 79, 0.35);
        }

        .hero-chip.is-lit {
            border-color: #680e68;
            background: rgba(104, 14, 104, 0.25);
            color: #fff;
            box-shadow: 0 0 0 1px rgba(104, 14, 104, 0.4);
        }

        .hero-img-stack {
            position: relative;
            width: 100%;
            max-width: 26rem;
            min-height: 340px;
            margin: 0 auto;
            right: 0;
        }

        @media (min-width: 1024px) {
            .hero-img-stack {
                right: 120px;
            }
        }

        .hero-img-stack .stack-img {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-height: 340px;
            object-fit: contain;
            opacity: 0;
            transition: opacity 0.65s cubic-bezier(.4, 0, .2, 1), transform 0.65s cubic-bezier(.4, 0, .2, 1);
            filter: drop-shadow(0 24px 50px rgba(182, 26, 168, 0.28));
            /* border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 14px; */
            /* background: rgba(255, 255, 255, 0.05); */
            padding: 8px;
        }

        .hero-img-stack .stack-img.active {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .hero-img-stack .stack-img.behind {
            transform: translate(-50%, -50%) scale(0.96);
        }

        .hero-title .hl.emerald {
            background: linear-gradient(135deg, #6ee7b7, #059669);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .hero-title .hl.amber {
            background: linear-gradient(135deg, #fcd34d, #d97706);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .hero-title .hl.rose {
            background: linear-gradient(135deg, #fda4af, #e11d48);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .cta-btn.emerald {
            background: #059669;
            box-shadow: 0 4px 20px rgba(5, 150, 105, 0.35);
        }

        .cta-btn.emerald:hover {
            background: #047857;
            box-shadow: 0 8px 28px rgba(5, 150, 105, 0.45);
        }

        .cta-btn.amber {
            background: #d97706;
            box-shadow: 0 4px 20px rgba(217, 119, 6, 0.35);
        }

        .cta-btn.amber:hover {
            background: #b45309;
        }

        .cta-btn.rose {
            background: #e11d48;
            box-shadow: 0 4px 20px rgba(225, 29, 72, 0.35);
        }

        .cta-btn.rose:hover {
            background: #be123c;
        }

        /* Per-slide accents: .tag-pill.blue / .hl.blue / .cta-btn.blue etc. apply from base rules */

        /* ── PRE-ORDER BANNER ── */
        .preorder-wrap {
            --preorder-bg: url('assets/images/home/Section - Pre Order Banner.png');
            background-image:
                var(--preorder-bg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            /* border: 1px solid rgba(153, 151, 153, 0.12); */
            position: relative;
            overflow: hidden;
        }

        /* .preorder-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 0;
            background: radial-gradient(ellipse 60% 80% at 70% 50%, rgba(182, 26, 168, 0.08) 0%, transparent 65%);
            pointer-events: none;
        } */

        .preorder-wrap .max-w-full {
            position: relative;
            z-index: 1;
        }

        .po-item {
            display: none;
            animation: poFade 0.5s ease both;
        }

        .po-item.active {
            display: flex;
            flex-direction: column;
            width: 100%;
            align-items: stretch;
        }

        /* Copy left (name, then price + CTA below name) | image + thumbs on the right */
        .po-item-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 1.5rem 2rem;
            width: 100%;
        }

        .po-item-copy {
            grid-column: 1;
            justify-self: start;
            min-width: 0;
            text-align: left;
        }

        .po-item-copy .po-title {
            margin-bottom: 0;
            min-height: 2.2em;
        }

        .po-item-copy .po-spec {
            margin-top: 0.25rem;
            line-height: 1.45;
        }

        .po-item-copy .po-item-price {
            margin-top: 0.25rem;
            margin-bottom: 0.75rem;
        }

        .po-item-right {
            grid-column: 2;
            justify-self: end;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.75rem;
            min-width: 0;
        }

        .po-item-thumb-mount {
            width: 100%;
            max-width: 22rem;
            display: flex;
            justify-content: center;
            align-self: flex-end;
        }

        .po-item-price .po-price-actions {
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            width: auto;
        }

        .po-item-price .po-price-stack {
            text-align: left;
            width: auto;
        }

        /* Thumbs sit below product image (inside .po-item-thumb-mount), not full section width */
        .po-thumbs-row {
            margin-top: 0.25rem;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(17, 24, 39, 0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            width: 100%;
        }

        .po-thumbs-row:empty {
            display: none;
            margin-top: 0;
            padding-top: 0;
            border-top: none;
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
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color:rgb(255, 255, 255);
        }

        .po-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0);
            border: 1px solid rgba(255, 255, 255, 1);
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
            font-size: clamp(1.6rem, 2vw, 1.9rem);
            font-weight: 800;
            color:rgb(255, 255, 255);
            letter-spacing: -0.01em;
            line-height: 1.2;
            min-height: 2.4em;
            max-width: 100%;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            overflow: hidden;
        }

        .po-spec {
            font-size: 12px;
            font-weight: 500;
            color:rgb(255, 255, 255);
            line-height: 1.7;
        }

        .po-price-from {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color:rgb(255, 255, 255);
        }

        .po-price {
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            font-weight: 800;
            color:rgb(255, 255, 255);
            letter-spacing: -0.02em;
        }

        .po-label {
            font-size: 13px;
            color:rgb(255, 255, 255);
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
            background: var(--white);
            border: 1px solid rgb(255, 255, 255);
            color: rgb(255, 255, 255);
            white-space: nowrap;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 4px 16px rgba(17, 24, 39, 0.15);
        }

        .po-cta:hover {
            background:rgb(161, 33, 151);
            color: #fff;
            border: 1px solid #8a1280;
            transform: translateY(-1px);
        }

        /* thumbnail nav */
        .po-thumb {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.07);
            border: 2px solid rgba(154, 111, 167, 0.38);
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

        /* ── Featured Banner Slots — Pre-Order Style ── */
        .fb-wrap {
            background: linear-gradient(135deg, #ffffff 0%, rgba(253, 252, 252, 0.82) 55%, rgb(255, 255, 255) 100%);
            border: 4px solid rgba(0, 0, 0, 0.48);
            border-radius: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: border-color 0.3s;
        }

        .fb-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 60% 80% at 70% 50%, rgba(182, 26, 168, 0.08) 0%, transparent 65%);
            pointer-events: none;
        }

        /* .fb-wrap:hover {
            border-color: rgba(182, 26, 168, 0.35);
        } */

        .fb-wrap .fb-name {
            color: #111827 !important;
        }

        .fb-wrap .fb-brand {
            color:rgb(85, 89, 97) !important;
            font-size: 12px;
        }

        /* Fade transition */
        .fb-content {
            transition: opacity 0.35s ease;
        }

        .fb-content.fading {
            opacity: 0;
        }

        /* Product image float animation */
        .fb-img-wrap img {
            animation: fbHover 5s ease-in-out infinite alternate;
            border: 3px solid rgba(27, 27, 27, 0.55);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.75);
            padding: 6px;
        }

        @keyframes fbHover {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-8px);
            }
        }

        /* Compact pager for many products */
        .fb-pager {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #6b7280;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(182, 26, 168, 0.2);
            border-radius: 999px;
            padding: 4px 10px;
            min-width: 52px;
            text-align: center;
        }

        /* Eyebrow label */
        .fb-eyebrow {
            font-size: 12px;
            font-weight: 750;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color:rgb(73, 6, 68);
        }

        /* Discount pill */
        .fb-discount {
            display: inline-block;
            background: rgba(182, 26, 168, 0.2);
            border: 1px solid rgba(182, 26, 168, 0.35);
            color: #e870df;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
        }

        /* Divider */
        .fb-divider {
            width: 1px;
            align-self: stretch;
            background: linear-gradient(180deg, transparent, rgba(107, 114, 128, 0.18) 30%, rgba(107, 114, 128, 0.18) 70%, transparent);
            flex-shrink: 0;
        }

        /* CTA button */
        .fb-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            background: transparent;
            border: 1px solid rgb(121, 121, 121);
            color: #111827;
            white-space: nowrap;
            transition: border-color 0.2s, color 0.2s, transform 0.2s;
        }

        .fb-cta:hover {
            border-color: #8a1280;
            color: #8a1280;
            transform: translateY(-1px);
        }

        /* ── Responsive: .banner-swiper.hero-swiper, .hero-wrap, .preorder-wrap ── */
        @media (max-width: 1023px) {

            /* Top Swiper: grow with content (requires autoHeight in banner-slider.js) */
            .banner-swiper.hero-swiper {
                height: auto;
                min-height: 0;
                position: relative;
            }

            .banner-swiper.hero-swiper .swiper-slide:not(.swiper-slide-active) .hero-swiper-nav-mobile {
                display: none !important;
                pointer-events: none;
            }

            .banner-swiper.hero-swiper .hero-slide-bg > .hero-swiper-nav-mobile .swiper-button-next,
            .banner-swiper.hero-swiper .hero-slide-bg > .hero-swiper-nav-mobile .swiper-button-prev {
                width: 32px;
                height: 32px;
            }

            .banner-swiper.hero-swiper .swiper-pagination {
                top: calc(10px + 32px + 8px + env(safe-area-inset-top, 0px)) !important;
                bottom: auto !important;
                left: auto !important;
                right: 1rem !important;
                width: auto !important;
                flex-direction: column !important;
                justify-content: flex-start;
                align-items: center;
                gap: 8px;
                padding: 0;
                transform: none;
            }

            .banner-swiper.hero-swiper .swiper-pagination-bullet {
                width: 5px;
                height: 5px;
                margin: 0 !important;
            }

            .banner-swiper.hero-swiper .swiper-pagination-bullet-active {
                width: 8px;
                height: 18px;
                border-radius: 999px;
            }

            @media (min-width: 640px) {
                .banner-swiper.hero-swiper .swiper-pagination {
                    right: 1.5rem !important;
                }
            }

            .banner-swiper.hero-swiper .swiper-wrapper {
                align-items: stretch;
            }

            .banner-swiper.hero-swiper .swiper-slide {
                height: auto;
            }

            .banner-swiper.hero-swiper .hero-slide-bg {
                height: auto;
                min-height: 0;
                overflow: visible;
            }

            .banner-swiper.hero-swiper .hero-slide-bg>.max-w-full {
                align-items: flex-start;
                min-height: 0;
                padding-top: calc(5.75rem + env(safe-area-inset-top, 0px));
                /* Space for prev/next overlaid at bottom of hero (inside .hero-slide-bg) */
                padding-bottom: calc(3rem + env(safe-area-inset-bottom, 0px));
            }

            /* CTA under product image with clear gap */
            .hero-swiper .explore-cta-right {
                position: static;
                transform: none;
                margin-top: 1.5rem;
                padding-top: 0.5rem;
                align-self: center;
                width: 100%;
                max-width: 22rem;
            }

            .hero-swiper .explore-cta-right .cta-btn {
                width: 100%;
                justify-content: center;
            }

            .hero-swiper .anim-img {
                width: 100%;
                align-items: stretch;
            }

            .hero-img-stack {
                min-height: 180px;
                max-width: 100%;
            }

            .hero-img-stack .stack-img {
                max-height: min(50vw, 248px);
                padding: 12px;
            }

            .hero-unified-wing .hero-img-stack {
                min-height: 130px;
                max-width: min(100%, 16rem);
            }

            .hero-unified-wing .hero-img-stack .stack-img {
                max-height: min(196px, 42vw);
                padding: 12px;
            }

            /* Mobile: one product stack (left wing); chips may wrap to multiple rows */
            .banner-swiper.hero-swiper .hero-unified-wing--right {
                display: none;
            }

            .banner-swiper.hero-swiper .hero-unified-wing--left {
                width: 100%;
                max-width: min(100%, 21rem);
                margin-left: auto;
                margin-right: auto;
            }

            .banner-swiper.hero-swiper .hero-unified-wing--left .hero-img-stack {
                margin-left: 0;
                margin-right: 0;
                max-width: 100%;
            }

            .banner-swiper.hero-swiper .hero-unified-center .hero-chip-row {
                flex-wrap: wrap;
                justify-content: center;
                align-content: center;
                gap: 6px 8px;
                row-gap: 8px;
            }

            .banner-swiper.hero-swiper .hero-unified-center .hero-chip {
                font-size: clamp(10px, 2.8vw, 12px);
                line-height: 1.42;
                padding: 5px 9px;
            }

            .banner-swiper.hero-swiper .hero-unified-center .hero-desc {
                line-height: 1.72;
            }

            .hero-swiper .swiper-hero-grid>div:first-child {
                max-width: none;
            }

            .hero-swiper .swiper-hero-grid {
                gap: 1rem;
            }

            .hero-rotator-wrap {
                min-height: 3rem;
            }

            /* Premium secondary hero */
            .hero-wrap .banner-slide>.max-w-full {
                align-items: flex-start;
                min-height: 0;
            }

            .hero-wrap .hero-desc {
                max-width: 100%;
            }

            .hero-wrap .spec-row {
                gap: 8px;
            }

            .preorder-wrap .po-item.active {
                gap: 0;
            }

            .preorder-wrap .po-item-layout {
                grid-template-columns: 1fr;
                align-items: stretch;
            }

            .preorder-wrap .po-item-copy {
                grid-column: 1;
            }

            .preorder-wrap .po-item-right {
                grid-column: 1;
                justify-self: stretch;
                align-items: center;
                width: 100%;
            }

            .preorder-wrap .po-item-thumb-mount {
                max-width: none;
                align-self: center;
            }

            /* Center product image (h-36 / object-contain) on mobile */
            .preorder-wrap .po-item-right .po-item-media {
                width: 100%;
                max-width: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                align-self: center;
            }

            .preorder-wrap .po-item-right .po-item-media img {
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
        }

        /* Pre-order: price + CTA sits under product name inside .po-item-copy */
        .po-price-actions {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: center;
            gap: 0.75rem 1rem;
            width: auto;
        }

        .po-price-stack {
            text-align: left;
            min-width: 0;
        }

        @media (max-width: 767px) {
            .hero-wrap .banner-slide>.max-w-full {
                padding-bottom: 3.5rem;
            }
        }

        @media (max-width: 479px) {
            .banner-swiper.hero-swiper .hero-slide-bg>.max-w-full {
                padding-left: 0.875rem;
                padding-right: 0.875rem;
                padding-top: calc(5.5rem + env(safe-area-inset-top, 0px));
            }

            .hero-chip {
                font-size: 10px;
                padding: 5px 10px;
            }

            .banner-swiper.hero-swiper .hero-unified-center .hero-chip {
                font-size: clamp(10px, 2.5vw, 11px);
                line-height: 1.42;
                padding: 4px 8px;
            }

            .hero-swiper .hero-unified-center .hero-title {
                font-size: clamp(0.95rem, 4.2vw, 1.65rem);
                line-height: 1.32;
            }

            .hero-wrap .hero-title {
                font-size: clamp(1.35rem, 7vw, 2.35rem);
            }

            .hero-wrap .price-note {
                font-size: 11px;
                line-height: 1.45;
            }

            .hero-wrap .cta-btn {
                width: 100%;
                max-width: 20rem;
                justify-content: center;
            }
        }

        @media (max-width: 1023px) {
            .hero-wrap {
                min-height: clamp(520px, 92svh, 760px);
                overflow-x: hidden;
            }
        }

        @media (max-width: 639px) {
            .hero-wrap {
                min-height: clamp(580px, 108svh, 900px);
            }
        }

        .hero-wrap .hero-webcam-stage {
            position: relative;
            width: 100%;
            max-width: 100%;
        }

        @media (max-width: 1023px) {
            .hero-wrap .hero-webcam-stage .stat-badge.badge-a {
                top: 0.5rem;
                right: 0.5rem;
            }

            .hero-wrap .hero-webcam-stage .stat-badge.badge-b {
                bottom: 0.5rem;
                left: 0.5rem;
            }
        }

        @media (max-width: 639px) {
            .hero-wrap .stat-badge {
                padding: 8px 12px;
                min-width: 0;
            }

            .hero-wrap .stat-val {
                font-size: 1.1rem;
            }
        }

        /* Homepage categories: mobile = centered flex wrap (original); md+ = full-width grid + larger */
        .home-categories-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 1.25rem 1.25rem;
            width: 100%;
        }

        .home-categories-grid .home-cat-card {
            flex: 0 1 auto;
            width: auto;
            min-width: min(100%, 7.5rem);
            max-width: 11rem;
            gap: 0.65rem;
            transition: transform 0.25s ease;
        }

        .home-cat-icon-wrap {
            position: relative;
            flex: 0 0 auto;
            align-self: center;
            width: 5.75rem;
            aspect-ratio: 1;
            max-width: 100%;
            overflow: hidden;
            border-radius: 50%;
            background: rgb(243 244 246);
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.1);
        }

        .home-cat-card .home-cat-img {
            position: absolute;
            inset: 0;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.35s ease;
        }

        .home-cat-card:hover {
            transform: translateY(-2px);
        }

        .home-cat-card:hover .home-cat-img {
            transform: scale(1.1);
        }

        .home-cat-title {
            font-weight: 700;
            color: rgb(17 24 39);
            line-height: 1.25;
            font-size: clamp(0.9375rem, 2.4vw, 1.125rem);
        }

        .home-cat-count {
            font-size: clamp(0.8125rem, 2vw, 0.9375rem);
            font-weight: 500;
            color: rgb(107 114 128);
            line-height: 1.2;
        }

        @media (min-width: 640px) and (max-width: 767px) {
            .home-cat-icon-wrap {
                width: 7rem;
            }
        }

        @media (min-width: 768px) {
            .home-categories-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 2rem 1.5rem;
                align-items: start;
            }

            .home-categories-grid .home-cat-card {
                width: 100%;
                min-width: 0;
                max-width: none;
                gap: 0.85rem;
            }

            .home-cat-icon-wrap {
                width: min(100%, 12rem);
                box-shadow: 0 6px 22px rgba(15, 23, 42, 0.12);
            }

            .home-cat-title {
                font-size: clamp(1rem, 2.8vw, 1.25rem);
            }

            .home-cat-count {
                font-size: clamp(0.875rem, 2.2vw, 1rem);
            }
        }

        @media (min-width: 1024px) {
            .home-categories-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }

            .home-cat-icon-wrap {
                width: min(100%, 13rem);
            }
        }

        @media (min-width: 1280px) {
            .home-cat-icon-wrap {
                width: min(100%, 14rem);
            }
        }

        /* Promo trio: 3 dark cards, eyebrow + title + Buy Now + product image zoom */
        .promo-trio-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .promo-trio-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 1.25rem;
            }
        }

        .promo-box {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 27.5rem;
            padding: 1.35rem 1.25rem 0;
            border-radius: 1.25rem;
            overflow: hidden;
            isolation: isolate;
            text-decoration: none;
            color: inherit;
            background-image:   
                var(--promo-bg, linear-gradient(135deg, #1a1025 0%, #0f0a14 100%));
            background-size: cover;
            background-position: 100% center;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .promo-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
        }

        .promo-box::after {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 0;
            background: radial-gradient(ellipse 90% 70% at 85% 85%, rgba(182, 26, 168, 0.14) 0%, transparent 55%);
            pointer-events: none;
        }

        .promo-box-inner {
            position: relative;
            z-index: 1;
        }

        .promo-eyebrow {
            display: block;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #fbbf24;
            margin-bottom: 1.25rem;
            margin-left: 1rem;
            margin-top: 1rem;
        }

        .promo-title {
            font-size: clamp(1.2rem, 3.2vw, 1.6rem);
            font-weight: 800;
            line-height: 1.2;
            color: #f9fafb;
            letter-spacing: -0.02em;
            margin: 0 0 1.25rem;
            margin-left: 1rem;
            max-width: 14rem;
        }

        .promo-buy {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #fff;
            text-decoration: underline;
            text-underline-offset: 4px;
            transition: color 0.2s ease;
            margin: 0 0 1.25rem;
            margin-left: 1rem;
        }

        .promo-box:hover .promo-buy {
            color: #e9d5ff;
        }

        .promo-img-wrap {
            position: relative;
            z-index: 1;
            margin-top: auto;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            min-height: 8.5rem;
            padding: 0.75rem 0.25rem 1rem;
        }

        .promo-img {
            max-height: 11rem;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            filter: drop-shadow(0 12px 28px rgba(0, 0, 0, 0.45));
            transform: translateZ(0) scale(1);
            transition: transform 0.35s ease;
        }

        .promo-box:hover .promo-img {
            transform: translateZ(0) scale(1.11);
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Swiper hero: 4 product slides -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <div class="banner-hero-wrap">
        <!-- Swiper stack imgs: drop your files as assets/images/home/swiper/slide1-01.jpg … slide4-05.jpg (or edit src below). -->
        <div class="banner-swiper swiper hero-swiper">
            <div class="swiper-wrapper">
                <!-- Slide 1 — John Keells Products -->
                <div class="swiper-slide">
                    <div class="hero-slide-bg hero-slide-bg--jk" style="--hero-bg: url('<?= htmlspecialchars($jkHeroBgUrl, ENT_QUOTES, 'UTF-8') ?>');">
                        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4 lg:py-5 min-h-0 lg:h-full flex items-start lg:items-stretch relative z-10">
                            <div class="hero-unified-grid w-full">
                                <div class="hero-unified-wing hero-unified-wing--left anim-img">
                                    <div class="hero-img-stack" data-hero-stack>
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-01.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img behind" data-i="0">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-02.jpg', 'assets/images/products/69c0c07700714-Picture68.png')) ?>" alt="" class="stack-img active" data-i="1">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-03.jpg', 'assets/images/products/gallery-69bb8a7f39c4d-Picture69.png')) ?>" alt="" class="stack-img behind" data-i="2">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-04.jpg', 'assets/images/home/banner1.jpeg')) ?>" alt="" class="stack-img behind" data-i="3">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-05.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img behind" data-i="4">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-06.jpg', 'assets/images/home/banner2.jpeg')) ?>" alt="" class="stack-img behind" data-i="5">
                                    </div>
                                </div>
                                <div class="hero-unified-center">
                                    <div class="tag-pill anim-up">John Keells Products</div>
                                    <h1 class="hero-title anim-up delay-1 mb-3">
                                        <span class="hl hl-jk">John Keells</span> <span class="jk-title-rest">Range</span>
                                    </h1>
                                    <p class="hero-desc anim-up delay-2 mb-4">
                                        Projectors, copiers, laptops, desktops &amp; more — hand-picked lines we stand behind.
                                    </p>
                                    <div class="hero-chip-row anim-up delay-3" data-hero-chips>
                                        <span class="hero-chip" data-i="0">ViewSonic PA700W</span>
                                        <span class="hero-chip is-lit" data-i="1">Toshiba e-STUDIO 2829A</span>
                                        <span class="hero-chip" data-i="2">ASUS ExpertBook</span>
                                        <span class="hero-chip" data-i="3">LT65S982EA</span>
                                        <span class="hero-chip" data-i="4">RISO Digital Duplicator A3 SF9390</span>
                                        <span class="hero-chip" data-i="5">Cassida ARTEMIS</span>
                                    </div>
                                    <div class="hero-rotator-wrap anim-up delay-4">
                                        <div class="hero-rotator-label">Now showcasing</div>
                                        <p class="hero-rotator-inner" data-hero-rot>Toshiba e-STUDIO 2829A</p>
                                    </div>
                                    <div class="hero-swiper-nav-desktop">
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-button-next"></div>
                                    </div>
                                </div>
                                <div class="hero-unified-wing hero-unified-wing--right anim-img">
                                    <div class="hero-img-stack" data-hero-stack>
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-02.jpg', 'assets/images/products/69c0c07700714-Picture68.png')) ?>" alt="" class="stack-img behind" data-i="0">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-03.jpg', 'assets/images/products/gallery-69bb8a7f39c4d-Picture69.png')) ?>" alt="" class="stack-img active" data-i="1">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-04.jpg', 'assets/images/home/banner1.jpeg')) ?>" alt="" class="stack-img behind" data-i="2">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-05.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img behind" data-i="3">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-06.jpg', 'assets/images/home/banner2.jpeg')) ?>" alt="" class="stack-img behind" data-i="4">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide1-01.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img behind" data-i="5">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hero-swiper-nav-mobile">
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 — Printers & Copiers -->
                <div class="swiper-slide">
                    <div class="hero-slide-bg hero-slide-bg--print" style="--hero-bg: url('<?= htmlspecialchars($printHeroBgUrl, ENT_QUOTES, 'UTF-8') ?>');">
                        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4 lg:py-5 min-h-0 lg:h-full flex items-start lg:items-stretch relative z-10">
                            <div class="hero-unified-grid w-full">
                                <div class="hero-unified-wing hero-unified-wing--left anim-img">
                                    <div class="hero-img-stack" data-hero-stack>
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide2-01.jpg', 'assets/images/home/banner2.jpeg')) ?>" alt="" class="stack-img active" data-i="0">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide2-02.jpg', 'assets/images/home/webcam.png')) ?>" alt="" class="stack-img behind" data-i="1">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide2-03.jpg', 'assets/images/home/banner3.jpeg')) ?>" alt="" class="stack-img behind" data-i="2">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide2-04.jpg', 'assets/images/home/banner1.jpeg')) ?>" alt="" class="stack-img behind" data-i="3">
                                    </div>
                                </div>
                                <div class="hero-unified-center">
                                    <div class="tag-pill blue anim-up">Printers &amp; Copiers</div>
                                    <h1 class="hero-title anim-up delay-1 mb-3">
                                    Premium <span class="hl blue"><br>Printers &amp; Copiers</span>
                                    </h1>
                                    <p class="hero-desc anim-up delay-2 mb-4">
                                        Ink tank, laser, and multifunction devices for home desks and busy offices.
                                    </p>
                                    <div class="hero-chip-row anim-up delay-3" data-hero-chips>
                                        <span class="hero-chip is-lit" data-i="0">HP Smart Tank 580</span>
                                        <span class="hero-chip" data-i="1">Epson Perfection V39 II</span>
                                        <span class="hero-chip" data-i="2">Canon imageCLASS MF641Cw</span>
                                        <span class="hero-chip" data-i="3">HP Color LaserJet Pro 3303sdw</span>
                                    </div>
                                    <div class="hero-rotator-wrap anim-up delay-4">
                                        <div class="hero-rotator-label">Featured</div>
                                        <p class="hero-rotator-inner" data-hero-rot>HP Smart Tank 580</p>
                                    </div>
                                    <div class="hero-swiper-nav-desktop">
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-button-next"></div>
                                    </div>
                                </div>
                                <div class="hero-unified-wing hero-unified-wing--right anim-img">
                                    <div class="hero-img-stack" data-hero-stack>
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide2-02.jpg', 'assets/images/home/webcam.png')) ?>" alt="" class="stack-img active" data-i="0">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide2-03.jpg', 'assets/images/home/banner3.jpeg')) ?>" alt="" class="stack-img behind" data-i="1">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide2-04.jpg', 'assets/images/home/banner1.jpeg')) ?>" alt="" class="stack-img behind" data-i="2">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide2-01.jpg', 'assets/images/home/banner2.jpeg')) ?>" alt="" class="stack-img behind" data-i="3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hero-swiper-nav-mobile">
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 — Laptops -->
                <div class="swiper-slide">
                    <div class="hero-slide-bg hero-slide-bg--laptop" style="--hero-bg: url('<?= htmlspecialchars($laptopHeroBgUrl, ENT_QUOTES, 'UTF-8') ?>');">
                        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4 lg:py-5 min-h-0 lg:h-full flex items-start lg:items-stretch relative z-10">
                            <div class="hero-unified-grid w-full">
                                <div class="hero-unified-wing hero-unified-wing--left anim-img">
                                    <div class="hero-img-stack" data-hero-stack>
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-01.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img active" data-i="0">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-02.jpg', 'assets/images/products/69c0c07700714-Picture68.png')) ?>" alt="" class="stack-img behind" data-i="1">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-03.jpg', 'assets/images/products/gallery-69bb8a7f39c4d-Picture69.png')) ?>" alt="" class="stack-img behind" data-i="2">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-04.jpg', 'assets/images/products/gallery-69bb8a7f3ad79-Picture68.png')) ?>" alt="" class="stack-img behind" data-i="3">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-05.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img behind" data-i="4">
                                    </div>
                                </div>
                                <div class="hero-unified-center">
                                    <div class="tag-pill emerald anim-up">Laptops &amp; All-in-Ones</div>
                                    <h1 class="hero-title anim-up delay-1 mb-3">
                                        All <span class="hl emerald">laptops</span>
                                    </h1>
                                    <p class="hero-desc anim-up delay-2 mb-4">
                                        Business notebooks, all-in-ones, and convertibles from brands you trust.
                                    </p>
                                    <div class="hero-chip-row anim-up delay-3" data-hero-chips>
                                        <span class="hero-chip is-lit" data-i="0">HP 23.8″ All-in-One 24-cr0073d PC</span>
                                        <span class="hero-chip" data-i="1">HP ProBook 460 G11</span>
                                        <span class="hero-chip" data-i="2">Lenovo ThinkPad L16 Gen 2</span>
                                        <span class="hero-chip" data-i="3">MSI Modern 15 F13MG</span>
                                        <span class="hero-chip" data-i="4">LENOVO 2-IN-1 / YOGA</span>
                                    </div>
                                    <div class="hero-rotator-wrap anim-up delay-4">
                                        <div class="hero-rotator-label">In the spotlight</div>
                                        <p class="hero-rotator-inner" data-hero-rot>HP 23.8″ All-in-One 24-cr0073d PC</p>
                                    </div>
                                    <div class="hero-swiper-nav-desktop">
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-button-next"></div>
                                    </div>
                                </div>
                                <div class="hero-unified-wing hero-unified-wing--right anim-img">
                                    <div class="hero-img-stack" data-hero-stack>
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-02.jpg', 'assets/images/products/69c0c07700714-Picture68.png')) ?>" alt="" class="stack-img active" data-i="0">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-03.jpg', 'assets/images/products/gallery-69bb8a7f39c4d-Picture69.png')) ?>" alt="" class="stack-img behind" data-i="1">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-04.jpg', 'assets/images/products/gallery-69bb8a7f3ad79-Picture68.png')) ?>" alt="" class="stack-img behind" data-i="2">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-05.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img behind" data-i="3">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide3-01.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img behind" data-i="4">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hero-swiper-nav-mobile">
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                </div>

                <!-- Slide 4 — Toners -->
                <div class="swiper-slide">
                    <div class="hero-slide-bg hero-slide-bg--toner" style="--hero-bg: url('<?= htmlspecialchars($tonerHeroBgUrl, ENT_QUOTES, 'UTF-8') ?>');">
                        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4 lg:py-5 min-h-0 lg:h-full flex items-start lg:items-stretch relative z-10">
                            <div class="hero-unified-grid w-full">
                                <div class="hero-unified-wing hero-unified-wing--left anim-img">
                                    <div class="hero-img-stack" data-hero-stack>
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-01.jpg', 'assets/images/home/banner3.jpeg')) ?>" alt="" class="stack-img active" data-i="0">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-02.jpg', 'assets/images/home/banner1.jpeg')) ?>" alt="" class="stack-img behind" data-i="1">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-03.jpg', 'assets/images/home/banner2.jpeg')) ?>" alt="" class="stack-img behind" data-i="2">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-04.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img behind" data-i="3">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-05.jpg', 'assets/images/home/banner3.jpeg')) ?>" alt="" class="stack-img behind" data-i="4">
                                    </div>
                                </div>
                                <div class="hero-unified-center">
                                    <div class="tag-pill rose anim-up">Toners &amp; Cartridges</div>
                                    <h1 class="hero-title anim-up delay-1 mb-3">
                                        Genuine <span class="hl rose"><br>toner &amp; Cartridges</span> 
                                    </h1>
                                    <p class="hero-desc anim-up delay-2 mb-4">
                                        OEM and compatible cartridges — crisp prints, predictable yields, less downtime.
                                    </p>
                                    <div class="hero-chip-row anim-up delay-3" data-hero-chips>
                                        <span class="hero-chip is-lit" data-i="0">Epson T664 Ink Bottles</span>
                                        <span class="hero-chip" data-i="1">Epson 141 Ink Cartridges</span>
                                        <span class="hero-chip" data-i="2">HP 682 Ink Cartridge Series</span>
                                        <span class="hero-chip" data-i="3">HP GT52 Magenta Ink Bottle</span>
                                        <span class="hero-chip" data-i="4">Canon 045 Cyan / Magenta / Yellow</span>
                                    </div>
                                    <div class="hero-rotator-wrap anim-up delay-4">
                                        <div class="hero-rotator-label">Rotating picks</div>
                                        <p class="hero-rotator-inner" data-hero-rot>Epson T664 Ink Bottles</p>
                                    </div>
                                    <div class="hero-swiper-nav-desktop">
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-button-next"></div>
                                    </div>
                                </div>
                                <div class="hero-unified-wing hero-unified-wing--right anim-img">
                                    <div class="hero-img-stack" data-hero-stack>
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-02.jpg', 'assets/images/home/banner1.jpeg')) ?>" alt="" class="stack-img active" data-i="0">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-03.jpg', 'assets/images/home/banner2.jpeg')) ?>" alt="" class="stack-img behind" data-i="1">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-04.jpg', 'assets/images/products/design.jpeg')) ?>" alt="" class="stack-img behind" data-i="2">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-05.jpg', 'assets/images/home/banner3.jpeg')) ?>" alt="" class="stack-img behind" data-i="3">
                                        <img src="<?= htmlspecialchars(swiperAsset('assets/images/home/swiper/slide4-01.jpg', 'assets/images/home/banner3.jpeg')) ?>" alt="" class="stack-img behind" data-i="4">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hero-swiper-nav-mobile">
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="swiper-pagination"></div> -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="assets/js/banner-slider.js"></script>

    <!-- Categories Section -->
    <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-8 sm:py-12">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Categories</h2>
            <a href="category.php" class="text-gray-600 hover:text-purple-custom font-medium">View All</a>
        </div>

        <div class="home-categories-grid mb-8 sm:mb-12">
            <?php foreach ($categories as $cat): ?>
                <?php
                $catId = (int)($cat['category_id'] ?? 0);
                $cnt = (int)($categoryProductCounts[$catId] ?? 0);
                $cntLabel = $cnt === 1 ? 'Item' : 'Items';
                ?>
                <a href="category.php?id=<?= $catId ?>" class="home-cat-card flex flex-col items-center justify-center group cursor-pointer py-4 px-2 md:py-6 md:px-3 transition-colors hover:bg-purple-50/90">
                    <div class="home-cat-icon-wrap">
                        <img src="<?= !empty($cat['category_image']) ? 'assets/images/categories/' . htmlspecialchars($cat['category_image']) : 'assets/images/categories/default.png' ?>"
                            alt="<?= htmlspecialchars($cat['display_name'] ?? $cat['category_name']) ?>"
                            class="home-cat-img"
                            width="200"
                            height="200"
                            decoding="async"
                            loading="lazy">
                    </div>
                    <span class="home-cat-title text-center px-1 group-hover:text-purple-custom transition-colors"><?= htmlspecialchars($cat['display_name'] ?? $cat['category_name']) ?></span>
                    <span class="home-cat-count"><?= $cnt ?> <?= $cntLabel ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Featured Product Banners — 6 cards, 3 columns × 2 rows -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

            <!-- 1. Copiers & Printers -->
            <div id="banner-copiers-printers" class="fb-wrap h-56 sm:h-64 flex items-center px-6 sm:px-10 gap-6">
                <div class="fb-img-wrap w-32 sm:w-40 flex-shrink-0 flex justify-center">
                    <img class="fb-img h-28 sm:h-36 object-contain" src="" alt="">
                </div>
                <div class="fb-divider hidden sm:block mx-2 my-3"></div>
                <div class="fb-content flex-1 flex flex-col gap-1 min-w-0">
                    <div class="fb-eyebrow fb-category mb-0.5"></div>
                    <div class="text-white font-bold text-sm sm:text-lg leading-tight fb-name"></div>
                    <div class="text-white/60 text-xs font-medium fb-brand"></div>
                    <!--<div class="text-white/50 text-xs line-clamp-2 fb-desc mt-0.5"></div>-->
                    <div class="flex items-center gap-3 mt-2 flex-wrap">
                        <span class="fb-stock text-[10px] font-semibold"></span>
                        <span class="fb-discount-badge fb-discount hidden"></span>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-4">
                        <a class="fb-cta" href="#">
                            Shop Now
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <!-- <div class="fb-pager shrink-0">1 / 1</div>      -->
                    </div>
                </div>
            </div>

            <!-- 2. Laptops -->
            <div id="banner-laptops" class="fb-wrap h-56 sm:h-64 flex items-center px-6 sm:px-10 gap-6">
                <div class="fb-img-wrap w-32 sm:w-40 flex-shrink-0 flex justify-center">
                    <img class="fb-img h-28 sm:h-36 object-contain" src="" alt="">
                </div>
                <div class="fb-divider hidden sm:block mx-2 my-3"></div>
                <div class="fb-content flex-1 flex flex-col gap-1 min-w-0">
                    <div class="fb-eyebrow fb-category mb-0.5"></div>
                    <div class="text-white font-bold text-sm sm:text-lg leading-tight fb-name"></div>
                    <div class="text-white/60 text-xs font-medium fb-brand"></div>
                    <!--<div class="text-white/50 text-xs line-clamp-2 fb-desc mt-0.5"></div>-->
                    <div class="flex items-center gap-3 mt-2 flex-wrap">
                        <span class="fb-stock text-[10px] font-semibold"></span>
                        <span class="fb-discount-badge fb-discount hidden"></span>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-4">
                        <a class="fb-cta" href="#">
                            Shop Now
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <!-- <div class="fb-pager shrink-0">1 / 1</div>   -->
                    </div>
                </div>
            </div>

            <!-- 3. Desktops -->
            <div id="banner-desktops" class="fb-wrap h-56 sm:h-64 flex items-center px-6 sm:px-10 gap-6">
                <div class="fb-img-wrap w-32 sm:w-40 flex-shrink-0 flex justify-center">
                    <img class="fb-img h-28 sm:h-36 object-contain" src="" alt="">
                </div>
                <div class="fb-divider hidden sm:block mx-2 my-3"></div>
                <div class="fb-content flex-1 flex flex-col gap-1 min-w-0">
                    <div class="fb-eyebrow fb-category mb-0.5"></div>
                    <div class="text-white font-bold text-sm sm:text-lg leading-tight fb-name"></div>
                    <div class="text-white/60 text-xs font-medium fb-brand"></div>
                    <!--<div class="text-white/50 text-xs line-clamp-2 fb-desc mt-0.5"></div>-->
                    <div class="flex items-center gap-3 mt-2 flex-wrap">
                        <span class="fb-stock text-[10px] font-semibold"></span>
                        <span class="fb-discount-badge fb-discount hidden"></span>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-4">
                        <a class="fb-cta" href="#">
                            Shop Now
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <!-- <div class="fb-pager shrink-0">1 / 1</div> -->
                    </div>
                </div>
            </div>

            <!-- 4. Monitors -->
            <div id="banner-monitors" class="fb-wrap h-56 sm:h-64 flex items-center px-6 sm:px-10 gap-6">
                <div class="fb-img-wrap w-32 sm:w-40 flex-shrink-0 flex justify-center">
                    <img class="fb-img h-28 sm:h-36 object-contain" src="" alt="">
                </div>
                <div class="fb-divider hidden sm:block mx-2 my-3"></div>
                <div class="fb-content flex-1 flex flex-col gap-1 min-w-0">
                    <div class="fb-eyebrow fb-category mb-0.5"></div>
                    <div class="text-white font-bold text-sm sm:text-lg leading-tight fb-name"></div>
                    <div class="text-white/60 text-xs font-medium fb-brand"></div>
                    <!--<div class="text-white/50 text-xs line-clamp-2 fb-desc mt-0.5"></div>-->
                    <div class="flex items-center gap-3 mt-2 flex-wrap">
                        <span class="fb-stock text-[10px] font-semibold"></span>
                        <span class="fb-discount-badge fb-discount hidden"></span>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-4">
                        <a class="fb-cta" href="#">
                            Shop Now
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <!-- <div class="fb-pager shrink-0">1 / 1</div> -->
                    </div>
                </div>
            </div>

            <!-- 5. Toners & Cartridges -->
            <div id="banner-toners-cartridges" class="fb-wrap h-56 sm:h-64 flex items-center px-6 sm:px-10 gap-6">
                <div class="fb-img-wrap w-32 sm:w-40 flex-shrink-0 flex justify-center">
                    <img class="fb-img h-28 sm:h-36 object-contain" src="" alt="">
                </div>
                <div class="fb-divider hidden sm:block mx-2 my-3"></div>
                <div class="fb-content flex-1 flex flex-col gap-1 min-w-0">
                    <div class="fb-eyebrow fb-category mb-0.5"></div>
                    <div class="text-white font-bold text-sm sm:text-lg leading-tight fb-name"></div>
                    <div class="text-white/60 text-xs font-medium fb-brand"></div>
                    <!--<div class="text-white/50 text-xs line-clamp-2 fb-desc mt-0.5"></div>-->
                    <div class="flex items-center gap-3 mt-2 flex-wrap">
                        <span class="fb-stock text-[10px] font-semibold"></span>
                        <span class="fb-discount-badge fb-discount hidden"></span>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-4">
                        <a class="fb-cta" href="#">
                            Shop Now
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <!-- <div class="fb-pager shrink-0">1 / 1</div> -->
                    </div>
                </div>
            </div>

            <!-- 6. Accessories -->
            <div id="banner-accessories" class="fb-wrap h-56 sm:h-64 flex items-center px-6 sm:px-10 gap-6">
                <div class="fb-img-wrap w-32 sm:w-40 flex-shrink-0 flex justify-center">
                    <img class="fb-img h-28 sm:h-36 object-contain" src="" alt="">
                </div>
                <div class="fb-divider hidden sm:block mx-2 my-3"></div>
                <div class="fb-content flex-1 flex flex-col gap-1 min-w-0">
                    <div class="fb-eyebrow fb-category mb-0.5"></div>
                    <div class="text-white font-bold text-sm sm:text-lg leading-tight fb-name"></div>
                    <div class="text-white/60 text-xs font-medium fb-brand"></div>
                    <!--<div class="text-white/50 text-xs line-clamp-2 fb-desc mt-0.5"></div>-->
                    <div class="flex items-center gap-3 mt-2 flex-wrap">
                        <span class="fb-stock text-[10px] font-semibold"></span>
                        <span class="fb-discount-badge fb-discount hidden"></span>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-4">
                        <a class="fb-cta" href="#">
                            Shop Now
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <!-- <div class="fb-pager shrink-0">1 / 1</div> -->
                    </div>
                </div>
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

    <!-- Promo trio: 3 promo cards (place before Categories section) -->
    <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-6 sm:pt-6 sm:pb-10 bg-gray-50" aria-label="Featured offers">
        <div class="promo-trio-grid">
            <a href="products.php" class="promo-box" style="--promo-bg: url('assets/images/home/Geotrans Card 2.png');">
                <div class="promo-box-inner">
                    <span class="promo-eyebrow">New arrival</span>
                    <h3 class="promo-title">Latest laptops for your work</h3>
                    <span class="promo-buy">Buy Now</span>
                </div>
                <!-- <div class="promo-img-wrap">
                    <img src="assets/images/products/design.jpeg" alt="" class="promo-img" width="320" height="280" loading="lazy" decoding="async">
                </div> -->
            </a>

            <a href="products.php" class="promo-box" style="--promo-bg: url('assets/images/home/Geotrans Card 3.png');">
                <div class="promo-box-inner">
                    <span class="promo-eyebrow">Get up to 35% off</span>
                    <h3 class="promo-title">Get up to 35% off on your favorite products</h3>
                    <span class="promo-buy">Buy Now</span>
                </div>
                <!-- <div class="promo-img-wrap">
                    <img src="assets/images/products/69c0c07700714-Picture68.png" alt="" class="promo-img" width="320" height="280" loading="lazy" decoding="async">
                </div> -->
            </a>

            <a href="products.php" class="promo-box" style="--promo-bg: url('assets/images/home/Geotrans Card 1.png');">
                <div class="promo-box-inner">
                    <span class="promo-eyebrow">Hurry up!</span>
                    <h3 class="promo-title">Modern &amp; style accessories</h3>
                    <span class="promo-buy">Buy Now</span>
                </div>
                <!-- <div class="promo-img-wrap">
                    <img src="assets/images/home/webcam.png" alt="" class="promo-img" width="320" height="280" loading="lazy" decoding="async">
                </div> -->
            </a>
        </div>
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
                    <div class="bg-white rounded-2xl p-5 relative shadow-xl hover:shadow-lg transition-shadow group flex flex-col">
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

                            <hr><br>

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
    <section class="preorder-wrap py-8 sm:py-12" style="--preorder-bg: url('<?= htmlspecialchars($preorderBannerBgUrl, ENT_QUOTES, 'UTF-8') ?>');">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header row -->
            <div class="flex flex-wrap items-center gap-3 min-w-0 mb-6 sm:mb-7">
                <div class="po-badge">
                    <div class="po-badge-dot">
                        <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24" class="text-white">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span style="font-family:'Outfit',sans-serif;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgb(255, 255, 255);">Pre Order</span>
                </div>
                <span class="po-label text-xs sm:text-[13px] leading-snug">Be the first to own the latest arrivals</span>
            </div>

            <!-- Product cards (one visible at a time); #poThumbs is created in JS and mounted under the active image -->
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

        // ── PRE-ORDER ROTATING PRODUCTS (from database) ──
        const preorderProducts = <?php
                                    // Reuse $featuredProducts already fetched at top of page — no second DB connection needed.
                                    // Get specs via the connection already open inside the $product object.
                                    // PDO connection from the Product class

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

        const cardsEl = document.getElementById('poCards');
        let thumbsEl = document.getElementById('poThumbs');
        let poCur = 0;
        let poTimer;
        const PO_DURATION = 4000;

        function ensurePoThumbsEl() {
            if (!thumbsEl) {
                thumbsEl = document.createElement('div');
                thumbsEl.id = 'poThumbs';
                thumbsEl.className = 'po-thumbs-row';
            }
            return thumbsEl;
        }

        function mountPoThumbsToActive() {
            const active = document.querySelector('.po-item.active');
            if (!active || !thumbsEl) return;
            const mount = active.querySelector('.po-item-thumb-mount');
            if (mount && thumbsEl.parentElement !== mount) {
                mount.appendChild(thumbsEl);
            }
        }

        function buildPO() {
            const te = ensurePoThumbsEl();
            te.innerHTML = '';

            preorderProducts.forEach((p, i) => {
                const t = document.createElement('div');
                t.className = 'po-thumb' + (i === 0 ? ' active' : '');
                t.innerHTML = `<img src="${p.thumb}" alt="${p.name}">`;
                t.onclick = () => toPO(i);
                te.appendChild(t);
            });

            preorderProducts.forEach((p, i) => {
                const c = document.createElement('div');
                c.className = 'po-item' + (i === 0 ? ' active' : '');
                c.id = `po${i}`;
                c.innerHTML = `
                <div class="po-item-layout">
                    <div class="po-item-copy">
                        <div class="po-eyebrow mb-1">${p.label}</div>
                        <div class="po-title">${p.name}</div>
                        <div class="po-spec">${p.spec}</div>
                        <br>
                        <div class="po-item-price">
                            <div class="po-price-actions">
                                <div class="po-price-stack">
                                    ${p.discount ? `<div style="display:inline-block;background:rgba(182,26,168,0.2);border:1px solid rgba(182,26,168,0.35);color:#e870df;font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;margin-bottom:8px;">−${p.discount}% OFF</div>` : ''}
                                    <div class="po-price-from">${p.priceFrom ? 'From' : 'Price'}</div>
                                    <div class="po-price">${p.price}</div>
                                    ${p.oldPrice ? `<div style="font-size:12px;color:rgb(255, 255, 255);text-decoration:line-through;margin-bottom:0;">${p.oldPrice}</div>` : ''}
                                </div>
                                <a href="${p.link}" class="po-cta flex-shrink-0">
                                    Order Now
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="po-item-right">
                        <div class="po-item-media laptop-wrap flex justify-center flex-shrink-0">
                            <img src="${p.img}" alt="${p.name}" class="h-48 sm:h-52 lg:h-60 object-contain" style="filter:drop-shadow(0 10px 30px rgba(182,26,168,0.15));">
                        </div>
                        <div class="po-item-thumb-mount"></div>
                    </div>
                </div>
            `;
                cardsEl.appendChild(c);
            });

            mountPoThumbsToActive();
        }

        function toPO(n) {
            if (!thumbsEl || preorderProducts.length === 0) return;
            const thumbs = thumbsEl.querySelectorAll('.po-thumb');
            if (thumbs.length) {
                thumbs[poCur].classList.remove('active');
            }
            const prevEl = document.getElementById(`po${poCur}`);
            if (prevEl) prevEl.classList.remove('active');

            poCur = n;

            if (thumbs.length) {
                thumbs[poCur].classList.add('active');
            }
            const nextEl = document.getElementById(`po${poCur}`);
            if (nextEl) nextEl.classList.add('active');

            mountPoThumbsToActive();

            clearInterval(poTimer);
            poTimer = setInterval(() => toPO((poCur + 1) % preorderProducts.length), PO_DURATION);
        }

        if (preorderProducts.length > 0) {
            buildPO();
            poTimer = setInterval(() => toPO((poCur + 1) % preorderProducts.length), PO_DURATION);
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

    <script>
        const featuredBanners = <?php echo json_encode($bannerByCategory); ?>;
    </script>

    <script>
        (function() {
            const INTERVAL = 5000;
            const SLOTS = ['copiers-printers', 'monitors', 'laptops', 'toners-cartridges', 'desktops', 'accessories'];
            const state = {};

            // Fallback product images per slot (shown if DB product has no image)
            const imgFallbacks = {
                'copiers-printers': 'assets/images/home/home-printer-based-toner.jpg',
                'monitors': 'assets/images/home/modern-tv-screen-isolated 1.png',
                'laptops': 'assets/images/home/freepik__sleek-laptop-on-tidy-modern-desk-tiny-potted-succu__24669.png',
                'toners-cartridges': 'assets/images/home/home-printer-based-toner.jpg',
                'desktops': 'assets/images/home/view-computer-video-display-monitor.jpg',
                'accessories': 'assets/images/home/wireless-mouse-wheel-scrolling-data-input-tool-generated-by-ai.jpg',
            };

            function initSlot(slug) {
                const products = featuredBanners[slug];
                const el = document.getElementById('banner-' + slug);
                if (!el) return;

                // No products — hide the whole card
                if (!products || products.length === 0) {
                    el.style.display = 'none';
                    return;
                }

                state[slug] = {
                    cur: 0,
                    timer: null
                };

                applyRender(slug, 0);

                if (products.length > 1) {
                    state[slug].timer = setInterval(() => {
                        goTo(slug, (state[slug].cur + 1) % products.length);
                    }, INTERVAL);
                }
            }

            function applyRender(slug, idx) {
                const el = document.getElementById('banner-' + slug);
                const p = featuredBanners[slug][idx];
                if (!el || !p) return;

                // Product image — fallback to slot static image
                const imgEl = el.querySelector('.fb-img');
                imgEl.src = p.image || imgFallbacks[slug] || '';
                imgEl.alt = p.product_name;

                // Text fields
                el.querySelector('.fb-category').textContent = p.category_name;
                el.querySelector('.fb-name').textContent = p.product_name;
                el.querySelector('.fb-brand').textContent = p.brand_name || '';

                // desc — only large cards have it
                const descEl = el.querySelector('.fb-desc');
                if (descEl) descEl.textContent = p.short_description || '';

                // Stock status
                const stockEl = el.querySelector('.fb-stock');
                stockEl.textContent = p.in_stock ? '✦ IN STOCK' : '✦ OUT OF STOCK';
                stockEl.style.color = p.in_stock ? '#166534' : '#fca5a5';

                // Discount badge
                const badge = el.querySelector('.fb-discount-badge');
                if (p.discount) {
                    badge.textContent = '−' + p.discount + '% OFF';
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }

                // CTA link → specific product detail page
                el.querySelector('.fb-cta').href = p.link;

                // Compact pager for many products
                const pagerEl = el.querySelector('.fb-pager');
                if (pagerEl) {
                    pagerEl.textContent = (idx + 1) + ' / ' + featuredBanners[slug].length;
                }
            }

            function goTo(slug, idx) {
                const el = document.getElementById('banner-' + slug);
                const content = el.querySelector('.fb-content');
                const imgEl = el.querySelector('.fb-img');
                // Fade out content + image
                content.classList.add('fading');
                imgEl.style.transition = 'opacity 0.35s ease';
                imgEl.style.opacity = '0';

                setTimeout(() => {
                    state[slug].cur = idx;
                    applyRender(slug, idx);

                    // Fade back in
                    content.classList.remove('fading');
                    imgEl.style.opacity = '1';
                }, 350);

                // Reset timer
                clearInterval(state[slug].timer);
                state[slug].timer = setInterval(() => {
                    goTo(slug, (state[slug].cur + 1) % featuredBanners[slug].length);
                }, INTERVAL);
            }

            SLOTS.forEach(initSlot);
        })();
    </script>

</body>

</html>