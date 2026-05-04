<?php
session_start();
require_once 'includes/helpers.php';
require_once 'classes/Product.php';
require_once 'classes/ProductColor.php';
require_once 'includes/recently-viewed.php';

$product = new Product();

// Get product by slug or ID
$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : (isset($_GET['id']) ? intval($_GET['id']) : '');

if (empty($slug)) {
    header('Location: products.php');
    exit();
}

$productData = $product->getById($slug);

if (!$productData) {
    header('Location: products.php');
    exit();
}

// Add to recently viewed
$recentlyViewed = new RecentlyViewed();
$recentlyViewed->add($productData['product_id']);

// Increment view count
$product->incrementViewCount($productData['product_id']);

// Get additional product data
$images = $product->getImages($productData['product_id']);
$specifications = $product->getSpecifications($productData['product_id']);
$reviews = $product->getReviews($productData['product_id']);
$relatedProducts = $product->getRelated($productData['product_id'], $productData['category_id'], 4);

$wishlistIdSet = getWishlistProductIdSet();
$productInWishlist = isset($wishlistIdSet[(int) $productData['product_id']]);

// Get product colors (only for specific categories)
$productColors = [];
$colorImages = [];
$detailImageCategorySlugs = [
    'smart-boards',
    'projectors',
    'laptops',
    'desktops',
    'desktop',
    'monitors',
    'copiers-printers',
    'printers-and-copiers',
    'printers',
];
$categorySlug = strtolower(trim((string) ($productData['category_slug'] ?? '')));
if ($categorySlug === '') {
    $categorySlug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', (string) ($productData['category_name'] ?? '')), '-'));
}
$categoryNameLower = strtolower((string) ($productData['category_name'] ?? ''));
$matchesDetailImageCategory = in_array($categorySlug, $detailImageCategorySlugs, true);
if (!$matchesDetailImageCategory) {
    foreach (['smart board', 'projector', 'laptop', 'desktop', 'monitor', 'printer', 'copier'] as $kw) {
        if ($categoryNameLower !== '' && strpos($categoryNameLower, $kw) !== false) {
            $matchesDetailImageCategory = true;
            break;
        }
    }
}

// Thumbnail strip under main image
$showThumbnails = $matchesDetailImageCategory;

// ProductColor / gallery sync: keep legacy laptop–monitor–printer–desktop matching AND full detail-image set
$nameOnlySlug = strtolower(str_replace(' ', '-', trim((string) ($productData['category_name'] ?? ''))));
$legacyColorSlugs = ['laptops', 'monitors', 'printers', 'desktop', 'desktops', 'copiers-printers', 'printers-and-copiers', 'printers'];
$showColors = $matchesDetailImageCategory
    || in_array($categorySlug, $legacyColorSlugs, true)
    || in_array($nameOnlySlug, $legacyColorSlugs, true);
if (!$showColors && $categoryNameLower !== '') {
    foreach (['laptop', 'monitor', 'desktop', 'printer', 'copier'] as $kw) {
        if (strpos($categoryNameLower, $kw) !== false) {
            $showColors = true;
            break;
        }
    }
}

if ($showColors) {
    require_once 'config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    
    $productColorModel = new ProductColor($conn);
    $productColors = $productColorModel->getByProduct($productData['product_id']);
    
    // Fetch images for each color
    foreach ($productColors as $color) {
        $colorImages[$color['color_id']] = $productColorModel->getColorImages($color['color_id']);
    }
}

// Debug: Check reviews (remove after testing)
// error_log("Product ID: " . $productData['product_id'] . " - Reviews count: " . count($reviews));


$currentPrice = getProductPrice($productData);
$discount = calculateDiscount($productData['price'], $productData['sale_price']);

// Normalize product gallery image URLs for rendering (skip duplicates of main_image).
$normalizedImages = [];
$mainBaseNorm = strtolower(basename(str_replace('\\', '/', (string) ($productData['main_image'] ?? ''))));
foreach ($images as $img) {
    $rawUrl = trim($img['image_url'] ?? '');
    if ($rawUrl === '') {
        continue;
    }

    $renderUrl = $rawUrl;
    if (!preg_match('/^(https?:)?\/\//', $rawUrl) && strpos($rawUrl, 'assets/') !== 0) {
        $renderUrl = 'assets/images/products/' . ltrim($rawUrl, '/');
    }

    $thumbBase = strtolower(basename(str_replace('\\', '/', parse_url($renderUrl, PHP_URL_PATH) ?: $renderUrl)));
    if ($mainBaseNorm !== '' && $thumbBase === $mainBaseNorm) {
        continue;
    }

    $normalizedImages[] = [
        'image_url' => $renderUrl
    ];
}

$mainImageUrl = !empty($productData['main_image'])
    ? 'assets/images/products/' . $productData['main_image']
    : 'assets/images/default.png';

// Limit gallery images to 3
$normalizedImages = array_slice($normalizedImages, 0, 3);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($productData['product_name']); ?> | GeoTrans</title>
    <meta name="description" content="<?php echo htmlspecialchars($productData['short_description']); ?>">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .border-purple-custom {
            border-color: #680e68;
        }

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

    <?php include 'includes/header.php'; ?>

    <!-- Breadcrumb -->
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="text-sm">
            <a href="index.php" class="text-gray-500 hover:text-purple-custom">Home</a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="category.php?slug=<?php echo $productData['category_slug']; ?>" class="text-gray-500 hover:text-purple-custom"><?php echo htmlspecialchars($productData['category_name']); ?></a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-900"><?php echo htmlspecialchars($productData['product_name']); ?></span>
        </nav>
    </div>

    <!-- Product Details -->
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

            <!-- Product Images -->
            <div>
                <div class="flex flex-col gap-3">
                    <div class="bg-white rounded-lg p-4 border">
                        <img id="main-image" src="<?php echo htmlspecialchars($mainImageUrl); ?>"
                            alt="<?php echo htmlspecialchars($productData['product_name']); ?>"
                            class="w-full h-96 object-contain">
                    </div>

                    <?php if ($showThumbnails): ?>
                    <div class="thumbnail-gallery w-full flex gap-2 sm:gap-3 overflow-x-auto pb-2 px-0">
                        <button type="button" class="h-20 sm:h-24 lg:h-40 w-20 sm:w-24 lg:w-40 min-w-20 sm:min-w-24 lg:min-w-32 flex-shrink-0 bg-white rounded-lg p-2 border hover:border-purple-custom overflow-hidden" onclick="setMainImage('<?php echo htmlspecialchars($mainImageUrl); ?>')">
                            <img src="<?php echo htmlspecialchars($mainImageUrl); ?>" alt="Main product image" class="w-full h-full object-contain">
                        </button>
                        <?php foreach ($normalizedImages as $img): ?>
                            <button type="button" class="h-20 sm:h-24 lg:h-40 w-20 sm:w-24 lg:w-40 min-w-20 sm:min-w-24 lg:min-w-32 flex-shrink-0 bg-white rounded-lg p-2 border hover:border-purple-custom overflow-hidden" onclick="setMainImage('<?php echo htmlspecialchars($img['image_url']); ?>')">
                                <img src="<?php echo htmlspecialchars($img['image_url']); ?>" alt="Product gallery image" class="w-full h-full object-contain">
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-lg p-6">
                <div class="text-sm text-gray-500 mb-2"><?php echo htmlspecialchars($productData['brand_name']); ?></div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($productData['product_name']); ?></h1>

                <!-- Rating -->
                <div class="flex items-center mb-4">
                    <div class="flex items-center text-yellow-400">
                        <?php
                        $rating = floatval($productData['rating']);
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= floor($rating)) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ($i - 0.5 <= $rating) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <span class="ml-2 text-sm text-gray-600"><?php echo number_format($rating, 1); ?> (<?php echo $productData['review_count']; ?> reviews)</span>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <?php if ($productData['sale_price'] && $productData['sale_price'] < $productData['price']): ?>
                        <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-3 mb-2">
                            <span class="text-4xl font-bold text-purple-custom"><?php echo formatPrice($productData['sale_price']); ?></span>
                            <span class="text-2xl text-gray-400 line-through"><?php echo formatPrice($productData['price']); ?></span>
                        </div>
                        <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Save <?php echo $discount; ?>%
                        </span>
                    <?php else: ?>
                        <span class="text-4xl font-bold text-purple-custom"><?php echo formatPrice($productData['price']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Short Description -->
                <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($productData['short_description']); ?></p>

                <?php if (!empty($productData['product_pdf'])): ?>
                    <div class="mb-6">
                        <a href="assets/files/products/<?php echo rawurlencode($productData['product_pdf']); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                            <i class="fas fa-file-pdf text-red-600"></i>
                            View Full Specifications (PDF)
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Stock Status -->
                <div class="mb-6">
                    <?php if ($productData['stock_quantity'] > 0): ?>
                        <span class="text-green-600 font-semibold"><i class="fas fa-check-circle mr-2"></i>In Stock (<?php echo $productData['stock_quantity']; ?> available)</span>
                    <?php else: ?>
                        <span class="text-red-600 font-semibold"><i class="fas fa-times-circle mr-2"></i>Out of Stock</span>
                    <?php endif; ?>
                </div>

                <!-- Color Selector -->
                <div class="mb-6 pb-6 border-b">
                    <label class="text-gray-700 font-medium block mb-3">Available Colors:</label>
                    <div class="flex flex-wrap gap-3">
                        <button type="button" 
                            class="color-selector-btn group relative w-10 h-10 rounded-full border-2 transition-all hover:scale-110 shadow-sm"
                            data-color-id="default"
                            data-color-name="<?= htmlspecialchars($productData['default_color_name'] ?? 'Default') ?>"
                            data-color-hex="<?= htmlspecialchars($productData['default_color_hex'] ?? '#f0f0f0') ?>"
                            style="background-color: <?= htmlspecialchars($productData['default_color_hex'] ?? '#f0f0f0') ?>; border-color: #680e68; box-shadow: 0 0 0 3px rgba(104, 14, 104, 0.2);"
                            title="<?= htmlspecialchars($productData['default_color_name'] ?? 'Default') ?>"
                            onclick="selectColor(this)">
                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10 shadow-lg">
                                <div class="font-semibold"><?= htmlspecialchars($productData['default_color_name'] ?? 'Default') ?></div>
                                <!-- <div class="text-gray-300"><?= htmlspecialchars($productData['default_color_hex'] ?? '#f0f0f0') ?></div> -->
                            </span>
                        </button>
                        <?php foreach ($productColors as $color): ?>
                            <button type="button" 
                                class="color-selector-btn group relative w-10 h-10 rounded-full border-2 transition-all hover:scale-110 shadow-sm"
                                data-color-id="<?= $color['color_id'] ?>"
                                data-color-name="<?= htmlspecialchars($color['color_name']) ?>"
                                data-color-hex="<?= htmlspecialchars($color['color_hex']) ?>"
                                style="background-color: <?= $color['color_hex'] ?? '#ccc' ?>; border-color: #ddd;"
                                title="<?= htmlspecialchars($color['color_name']) ?>"
                                onclick="selectColor(this)">
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10 shadow-lg">
                                    <div class="font-semibold"><?= htmlspecialchars($color['color_name']) ?></div>
                                    <!-- <div class="text-gray-300"><?= htmlspecialchars($color['color_hex']) ?></div> -->
                                </span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Quantity & Share -->
                <div class="flex flex-col lg:flex-row lg:items-center gap-6 mb-6">
                    <!-- Quantity Selector -->
                    <div class="flex items-center gap-4">
                        <label class="text-gray-700 font-medium">Quantity:</label>
                        <div class="flex items-center border rounded">
                            <button onclick="decrementQty()" class="px-4 py-2 hover:bg-gray-100">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="<?php echo $productData['stock_quantity']; ?>"
                                class="w-16 text-center border-x py-2" readonly>
                            <button onclick="incrementQty()" class="px-4 py-2 hover:bg-gray-100">+</button>
                        </div>
                    </div>

                    <!-- Share Options -->
                    <div class="flex items-center gap-3">
                        <label class="text-gray-700 font-medium text-sm">Share:</label>
                        <div class="flex gap-2">
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                                target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white hover:scale-110 transition-transform shadow-sm"
                                title="Share on Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            
                            <!-- WhatsApp -->
                            <a href="https://wa.me/?text=<?php echo urlencode($productData['product_name'] . ' - ' . ((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])); ?>" 
                                target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-green-400 to-green-500 text-white hover:scale-110 transition-transform shadow-sm"
                                title="Share on WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>

                            <!-- Instagram -->
                            <a href="https://www.instagram.com/?url=<?php echo urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                                target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-pink-500 to-purple-600 text-white hover:scale-110 transition-transform shadow-sm"
                                title="Share on Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            </a>
                            
                            <!-- TikTok -->
                            <a href="https://www.tiktok.com/share?url=<?php echo urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                                target="_blank" rel="noopener noreferrer"
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-gray-900 to-black text-white hover:scale-110 transition-transform shadow-sm"
                                title="Share on TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            
                            <!-- Email -->
                            <a href="mailto:?subject=<?php echo urlencode($productData['product_name']); ?>&body=<?php echo urlencode('Check out this product: ' . ((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])); ?>" 
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-600 hover:bg-gray-700 text-white hover:scale-110 transition-transform shadow-sm"
                                title="Share via Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            
                            <!-- Copy Link -->
                            <button onclick="copyProductLink()" 
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-400 hover:bg-gray-500 text-white hover:scale-110 transition-transform shadow-sm"
                                title="Copy Link" id="copy-link-btn">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 mb-6">
                    <button class="add-to-cart-btn flex-1 border-2 border-purple-custom bg-transparent text-purple-custom hover:bg-purple-50 py-3 px-6 rounded-lg font-semibold transition-colors whitespace-nowrap text-sm"
                        data-product-id="<?php echo $productData['product_id']; ?>"
                        onclick="addToCartWithQty()">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                    <button class="flex-1 bg-purple-custom hover:bg-[#4f0a4f] text-white py-3 px-6 rounded-lg font-semibold transition-colors whitespace-nowrap text-sm"
                        onclick="buyNow()">
                        <i class="fas fa-credit-card mr-2"></i>Buy Now
                    </button>
                    <button type="button" class="wishlist-btn wishlist-btn--detail border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white py-2 px-3 rounded-lg transition<?php echo $productInWishlist ? ' in-wishlist' : ''; ?>"
                        data-product-id="<?php echo (int) $productData['product_id']; ?>"
                        aria-label="<?php echo $productInWishlist ? 'Remove from wishlist' : 'Add to wishlist'; ?>"
                        aria-pressed="<?php echo $productInWishlist ? 'true' : 'false'; ?>">
                        <?php if ($productInWishlist): ?>
                            <i class="fas fa-heart"></i>
                        <?php else: ?>
                            <i class="far fa-heart"></i>
                        <?php endif; ?>
                    </button>
                </div>
<br>
                <!-- Product Meta -->
                <div class="border-t pt-4 flex gap-8 text-sm">
                    <div><span class="text-gray-600">SKU:</span> <span class="font-semibold"><?php echo htmlspecialchars($productData['sku']); ?></span></div>
                    <div><span class="text-gray-600">Category:</span> <a href="category.php?slug=<?php echo $productData['category_slug']; ?>" class="text-purple-custom hover:underline"><?php echo htmlspecialchars($productData['category_name']); ?></a></div>
                    <div><span class="text-gray-600">Brand:</span> <a href="products.php?brand=<?php echo $productData['brand_id']; ?>" class="text-purple-custom hover:underline"><?php echo htmlspecialchars($productData['brand_name']); ?></a></div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="bg-white rounded-lg p-6 mb-12">
            <div class="border-b mb-6">
                <nav class="flex gap-8">
                    <button class="tab-button active py-2 border-b-2 border-purple-custom font-semibold text-purple-custom" onclick="showTab('description')">Description</button>
                    <button class="tab-button py-2 border-b-2 border-transparent hover:border-gray-300" onclick="showTab('specifications')">Specifications</button>
                    <button class="tab-button py-2 border-b-2 border-transparent hover:border-gray-300" onclick="showTab('reviews')">Reviews (<?php echo count($reviews); ?>)</button>
                </nav>
            </div>

            <!-- Description Tab -->
            <div id="description-tab" class="tab-content">
                <div class="prose max-w-none">
                    <?php echo nl2br(htmlspecialchars($productData['description'])); ?>
                </div>
            </div>

            <!-- Specifications Tab -->
            <div id="specifications-tab" class="tab-content hidden">
                <?php if (!empty($specifications)): ?>
                    <table class="w-full">
                        <?php foreach ($specifications as $spec): ?>
                            <tr class="border-b">
                                <td class="py-3 px-4 bg-gray-50 font-semibold w-1/3"><?php echo htmlspecialchars($spec['spec_name']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($spec['spec_value']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p class="text-gray-500">No specifications available.</p>
                <?php endif; ?>
            </div>

            <!-- Reviews Tab -->
            <div id="reviews-tab" class="tab-content hidden">
                <!-- Write Review Form -->
                <?php if (isLoggedIn()): ?>
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-bold mb-4">Write a Review</h3>
                        <form id="review-form" class="space-y-4">
                            <input type="hidden" name="product_id" value="<?= $productData['product_id'] ?>">

                            <!-- Rating -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Your Rating *</label>
                                <div class="flex items-center space-x-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <button type="button" class="rating-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none" data-rating="<?= $i ?>">
                                            <i class="far fa-star"></i>
                                        </button>
                                    <?php endfor; ?>
                                </div>
                                <input type="hidden" name="rating" id="rating-input" required>
                            </div>

                            <!-- Review Title -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Review Title</label>
                                <input type="text" name="review_title" maxlength="255"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#680e68] focus:border-transparent"
                                    placeholder="Summarize your review in one line">
                            </div>

                            <!-- Review Text -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Your Review *</label>
                                <textarea name="review_text" required rows="5"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#680e68] focus:border-transparent"
                                    placeholder="Share your experience with this product..."></textarea>
                            </div>

                            <div id="review-message" class="hidden text-sm"></div>

                            <button type="submit" id="review-submit-btn" class="bg-purple-custom text-white px-6 py-3 rounded-lg hover:bg-[#4f0a4f] font-semibold">
                                Submit Review
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-lg mb-6">
                        <i class="fas fa-info-circle mr-2"></i>
                        Please <a href="<?= $base_url ?>account/login.php" class="font-semibold underline">login</a> to write a review.
                    </div>
                <?php endif; ?>

                <!-- Existing Reviews -->
                <h3 class="text-lg font-bold mb-4">Customer Reviews (<?php echo count($reviews); ?>)</h3>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="border-b pb-4 mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <span class="font-semibold"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></span>
                                    <?php if ($review['is_verified_purchase']): ?>
                                        <span class="text-green-600 text-sm ml-2"><i class="fas fa-check-circle"></i> Verified Purchase</span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-sm text-gray-500"><?php echo timeAgo($review['created_at']); ?></span>
                            </div>
                            <div class="flex items-center text-yellow-400 mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="<?php echo $i <= $review['rating'] ? 'fas' : 'far'; ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <?php if ($review['review_title']): ?>
                                <h4 class="font-semibold mb-2"><?php echo htmlspecialchars($review['review_title']); ?></h4>
                            <?php endif; ?>
                            <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                    <?php foreach ($relatedProducts as $p): ?>
                        <?php
                        $rpid = (int) $p['product_id'];
                        $rInWish = isset($wishlistIdSet[$rpid]);
                        ?>
                        <div class="bg-white rounded-2xl p-5 relative shadow-lg hover:shadow-lg transition-shadow group flex flex-col h-full border border-gray-200 hover:border-[#4f0a4f]">
                            <button type="button" class="wishlist-btn wishlist-btn--icon absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-500 transition-colors z-10<?= $rInWish ? ' in-wishlist' : '' ?>"
                                data-product-id="<?= $rpid ?>"
                                aria-label="<?= $rInWish ? 'Remove from wishlist' : 'Add to wishlist' ?>"
                                aria-pressed="<?= $rInWish ? 'true' : 'false' ?>">
                                <?php if ($rInWish): ?>
                                    <i class="fas fa-heart text-red-500"></i>
                                <?php else: ?>
                                    <i class="far fa-heart"></i>
                                <?php endif; ?>
                            </button>
                            <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="flex flex-col flex-grow">
                                <div class="flex items-center justify-center h-48 mb-4">
                                    <?php
                                    $mainImage = !empty($p['main_image']) ? 'assets/images/products/' . $p['main_image'] : 'assets/images/categories/default.png';
                                    ?>
                                    <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="max-h-full object-contain">
                                </div>
                                <hr>
                                <div class="h-6 mb-2">
                                    <?php if (!empty($p['discount_percentage']) && $p['discount_percentage'] > 0): ?>
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block">
                                            -<?= (int) $p['discount_percentage'] ?>% OFF
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">
                                    <?= htmlspecialchars($p['product_name']) ?>
                                </h3>
                                <div class="flex items-center mb-3 h-5">
                                    <div class="flex text-yellow-400 text-xs">
                                        <?php
                                        $relRating = floatval($p['avg_rating'] ?? $p['rating'] ?? 0);
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $relRating ? '★' : '☆';
                                        }
                                        ?>
                                    </div>
                                    <span class="text-gray-500 text-xs ml-1">(<?= $p['review_count'] ?? 0 ?>)</span>
                                </div>
                                <div class="flex items-baseline flex-wrap gap-2 mb-3 min-h-[1.75rem]">
                                    <?php if (!empty($p['sale_price']) && $p['sale_price'] < $p['price']): ?>
                                        <span class="text-purple-custom font-bold text-lg">
                                            Rs. <?= number_format($p['sale_price'], 2) ?>
                                        </span>
                                        <span class="text-gray-400 text-sm line-through">
                                            Rs. <?= number_format($p['price'], 2) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-purple-custom font-bold text-lg">
                                            Rs. <?= number_format($p['price'], 2) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="flex gap-2 mt-auto">
                                <button onclick="addToCart(<?= $p['product_id'] ?>)"
                                    class="w-1/2 text-purple-custom py-1 rounded font-semibold text-sm hover:text-gray-700 transition-colors border border-purple-custom"
                                    title="Add to Cart"
                                    aria-label="Add to Cart">
                                    Add to cart
                                </button>
                                <button onclick="buyNowFromList(<?= $p['product_id'] ?>)"
                                    class="w-1/2 flex items-center justify-center bg-purple-custom text-white py-1 rounded font-semibold text-sm hover:bg-[#4f0a4f] transition-colors">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Color images data
        window.mainProductImageUrl = '<?php echo htmlspecialchars($mainImageUrl); ?>';
        
        window.colorImagesData = <?php 
            $colorData = [];
            foreach ($colorImages as $colorId => $images) {
                $imagePaths = [];
                // Limit to 3 images per color
                $limitedImages = array_slice($images, 0, 3);
                foreach ($limitedImages as $img) {
                    if (strpos($img['image_url'], 'assets/') !== 0 && !preg_match('/^https?:/', $img['image_url'])) {
                        $imagePaths[] = 'assets/images/products/' . $img['image_url'];
                    } else {
                        $imagePaths[] = $img['image_url'];
                    }
                }
                $colorData[$colorId] = $imagePaths;
            }
            echo json_encode($colorData);
        ?>;
        
        // Default gallery images
        window.defaultGalleryImages = <?php echo json_encode(array_map(function($img) { return $img['image_url']; }, $normalizedImages)); ?>;

        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-purple-custom', 'text-purple-custom');
                btn.classList.add('border-transparent');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');

            // Add active class to selected button
            event.target.classList.add('active', 'border-purple-custom', 'text-purple-custom');
            event.target.classList.remove('border-transparent');
        }

        function incrementQty() {
            const qtyInput = document.getElementById('quantity');
            const max = parseInt(qtyInput.max);
            if (parseInt(qtyInput.value) < max) {
                qtyInput.value = parseInt(qtyInput.value) + 1;
            }
        }

        function setMainImage(imageUrl) {
            const mainImage = document.getElementById('main-image');
            if (mainImage && imageUrl) {
                mainImage.src = imageUrl;
            }
        }

        function selectColor(btn) {
            const colorId = btn.getAttribute('data-color-id');
            const colorName = btn.getAttribute('data-color-name');
            
            // Update selection styling
            document.querySelectorAll('.color-selector-btn').forEach(b => {
                b.style.borderColor = '#ddd';
                b.style.boxShadow = 'none';
            });
            
            btn.style.borderColor = '#680e68';
            btn.style.boxShadow = '0 0 0 3px rgba(104, 14, 104, 0.2)';
            
            // Get color images data
            const colorImagesData = window.colorImagesData || {};
            const defaultGalleryImages = window.defaultGalleryImages || [];
            const mainImageUrl = window.mainProductImageUrl || '';
            
            // Update main and thumbnail gallery
            const thumbnailContainer = document.querySelector('.thumbnail-gallery');
            if (thumbnailContainer) {
                // Clear existing thumbnails
                thumbnailContainer.innerHTML = '';
                
                if (colorId === 'default') {
                    // Show default gallery
                    const mainBtn = document.createElement('button');
                    mainBtn.type = 'button';
                    mainBtn.className = 'h-20 sm:h-24 lg:h-40 w-20 sm:w-24 lg:w-40 min-w-20 sm:min-w-24 lg:min-w-32 flex-shrink-0 bg-white rounded-lg p-2 border hover:border-purple-custom overflow-hidden';
                    mainBtn.onclick = function() { setMainImage(mainImageUrl); };
                    mainBtn.innerHTML = `<img src="${mainImageUrl}" alt="Main image" class="w-full h-full object-contain">`;
                    thumbnailContainer.appendChild(mainBtn);
                    
                    // Add gallery images as thumbnails
                    defaultGalleryImages.forEach((img, idx) => {
                        const thumbBtn = document.createElement('button');
                        thumbBtn.type = 'button';
                        thumbBtn.className = 'h-20 sm:h-24 lg:h-40 w-20 sm:w-24 lg:w-40 min-w-20 sm:min-w-24 lg:min-w-32 flex-shrink-0 bg-white rounded-lg p-2 border hover:border-purple-custom overflow-hidden';
                        thumbBtn.onclick = function() { setMainImage(img); };
                        thumbBtn.innerHTML = `<img src="${img}" alt="Gallery image" class="w-full h-full object-contain">`;
                        thumbnailContainer.appendChild(thumbBtn);
                    });
                    
                    // Set main image back to default
                    setMainImage(mainImageUrl);
                } else {
                    // Show color-specific images
                    let colorImages = colorImagesData[colorId] || [];
                    
                    if (colorImages.length > 0) {
                        // Add color images as thumbnails
                        colorImages.forEach((img, idx) => {
                            const thumbBtn = document.createElement('button');
                            thumbBtn.type = 'button';
                            thumbBtn.className = 'h-20 sm:h-24 lg:h-40 w-20 sm:w-24 lg:w-40 min-w-20 sm:min-w-24 lg:min-w-32 flex-shrink-0 bg-white rounded-lg p-2 border hover:border-purple-custom overflow-hidden';
                            thumbBtn.onclick = function() { setMainImage(img); };
                            thumbBtn.innerHTML = `<img src="${img}" alt="Color image" class="w-full h-full object-contain">`;
                            thumbnailContainer.appendChild(thumbBtn);
                        });
                        
                        // Set main image to first color image
                        setMainImage(colorImages[0]);
                    }
                }
            }
        }

        function decrementQty() {
            const qtyInput = document.getElementById('quantity');
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        }

        function copyProductLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                const btn = document.getElementById('copy-link-btn');
                const originalIcon = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.classList.add('bg-green-500');
                btn.classList.remove('bg-gray-400');
                
                setTimeout(() => {
                    btn.innerHTML = originalIcon;
                    btn.classList.remove('bg-green-500');
                    btn.classList.add('bg-gray-400');
                }, 2000);
            }).catch(() => {
                alert('Failed to copy link');
            });
        }

        function addToCartWithQty() {
            const quantity = document.getElementById('quantity').value;
            const addBtn = document.querySelector('.add-to-cart-btn[data-product-id]');
            addBtn.dataset.quantity = quantity;

            // Trigger cart add
            if (window.cartManager) {
                window.cartManager.addToCart(addBtn);
            }
        }

        function addToCart(productId) {
            if (window.cartManager) {
                window.cartManager.addToCart(productId, 1);
            }
        }

        function buyNowFromList(productId) {
            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('product_id', productId);
            formData.append('quantity', 1);

            fetch('api/cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'checkout.php';
                    } else {
                        if (window.cartManager) {
                            window.cartManager.showNotification(data.message || 'Failed to add product', 'error');
                        } else {
                            alert(data.message || 'Failed to add product');
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

        // Buy Now - Add to cart and redirect to checkout
        function buyNow() {
            const productId = <?php echo $productData['product_id']; ?>;
            const quantity = document.getElementById('quantity').value;

            // Add to cart via API
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
                        // Redirect to checkout page
                        window.location.href = 'checkout.php';
                    } else {
                        if (window.cartManager) {
                            window.cartManager.showNotification(data.message || 'Failed to add product', 'error');
                        } else {
                            alert(data.message || 'Failed to add product');
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

        // Review Form - Star Rating
        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                document.getElementById('rating-input').value = rating;

                // Update star display
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    const icon = s.querySelector('i');
                    if (index < rating) {
                        icon.classList.remove('far', 'text-gray-300');
                        icon.classList.add('fas', 'text-yellow-400');
                    } else {
                        icon.classList.remove('fas', 'text-yellow-400');
                        icon.classList.add('far', 'text-gray-300');
                    }
                });
            });

            // Hover effect
            star.addEventListener('mouseenter', function() {
                const rating = this.dataset.rating;
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    const icon = s.querySelector('i');
                    if (index < rating) {
                        icon.classList.add('text-yellow-400');
                    }
                });
            });

            star.addEventListener('mouseleave', function() {
                const currentRating = document.getElementById('rating-input').value;
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    const icon = s.querySelector('i');
                    if (!currentRating || index >= currentRating) {
                        icon.classList.remove('text-yellow-400');
                    }
                });
            });
        });

        // Review Form Submission
        document.getElementById('review-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('review-submit-btn');
            const messageDiv = document.getElementById('review-message');
            const formData = new FormData(this);

            // Validate rating
            if (!formData.get('rating')) {
                messageDiv.className = 'text-red-600 text-sm mb-4';
                messageDiv.textContent = 'Please select a rating';
                messageDiv.classList.remove('hidden');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            try {
                const response = await fetch('api/submit-review.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                messageDiv.className = data.success ?
                    'text-green-600 text-sm mb-4' :
                    'text-red-600 text-sm mb-4';
                messageDiv.textContent = data.message;
                messageDiv.classList.remove('hidden');

                if (data.success) {
                    this.reset();
                    document.getElementById('rating-input').value = '';
                    document.querySelectorAll('.rating-star i').forEach(icon => {
                        icon.classList.remove('fas', 'text-yellow-400');
                        icon.classList.add('far', 'text-gray-300');
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }

            } catch (error) {
                messageDiv.className = 'text-red-600 text-sm mb-4';
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Review';
            }
        });
    </script>
</body>

</html>