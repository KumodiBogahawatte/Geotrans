<?php
require_once 'includes/helpers.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
require_once 'classes/Brand.php';

$productModel = new Product();
$categoryModel = new Category();
$brandModel = new Brand();

// Get filter parameters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 20;
$category_id = null;
$category_param = isset($_GET['category']) ? $_GET['category'] : null;
if ($category_param) {
    if (is_numeric($category_param)) {
        $category_id = intval($category_param);
    } else {
        $cat = $categoryModel->getById($category_param);
        if ($cat && isset($cat['category_id'])) {
            $category_id = $cat['category_id'];
        }
    }
}
$brand_id = isset($_GET['brand']) ? intval($_GET['brand']) : null;
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : null;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : null;
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build filters array
$filters = [];
if ($category_id) $filters['category_id'] = $category_id;
if ($brand_id) $filters['brand_id'] = $brand_id;
if ($min_price) $filters['min_price'] = $min_price;
if ($max_price) $filters['max_price'] = $max_price;
if ($search) $filters['search'] = $search;
if ($sort_by) $filters['sort_by'] = $sort_by;

// Get products and pagination info
$result = $productModel->getAll($page, $per_page, $filters);
$products = $result['products'];
$total_products = $result['total'];
$total_pages = $result['total_pages'];

// Get categories and brands for filters
$categories = $categoryModel->getAll();
$brands = $brandModel->getAll();
$wishlistIdSet = getWishlistProductIdSet();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Geotrans</title>
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
            background-color: #4f0a4f;
        }

        .hover\:text-purple-custom:hover {
            color: #4f0a4f;
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

        /* Category icons: smaller circles (fits products grid) */
        .products-cat-icon-wrap {
            position: relative;
            width: min(100%, 4rem);
            aspect-ratio: 1;
            max-width: 100%;
            overflow: hidden;
            border-radius: 50%;
            background: rgb(243 244 246);
            box-shadow: 0 3px 14px rgba(15, 23, 42, 0.08);
        }

        .products-cat-icon-wrap .products-cat-img {
            position: absolute;
            inset: 0;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.35s ease;
        }

        .products-cat-link:hover .products-cat-img {
            transform: scale(1.1);
        }

        @media (min-width: 640px) and (max-width: 767px) {
            .products-cat-icon-wrap {
                width: min(100%, 4.75rem);
            }
        }

        @media (min-width: 768px) {
            .products-cat-icon-wrap {
                width: min(100%, 5.5rem);
                box-shadow: 0 4px 16px rgba(15, 23, 42, 0.1);
            }
        }

        @media (min-width: 1024px) {
            .products-cat-icon-wrap {
                width: min(100%, 6rem);
            }
        }

        @media (min-width: 1280px) {
            .products-cat-icon-wrap {
                width: min(100%, 6.5rem);
            }
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-gray-600">
            <a href="index.php" class="hover:text-purple-custom">Home</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Products</span>
        </nav>

        <!-- Top Banners -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-gray-800 rounded-lg p-8 flex items-center justify-between" style="background-color: #A1A4AD; background-image: url('assets/images/pages/P1.png'); background-size: cover; background-position: center;">
                <div class="text-white">
                    <p class="text-sm uppercase mb-2">Power Your Productivity</p>
                    <h2 class="text-2xl font-bold mb-4">High Performance<br>Laptops</h2>
                    <a href="products.php?category=15" class="bg-white text-black px-6 py-2 rounded-full text-sm font-medium inline-block">SHOP NOW</a>
                </div>
            </div>
            <div class="bg-red-700 rounded-lg p-8 flex items-center justify-between" style="background-image: url('assets/images/pages/P2.png'); background-size: cover; background-position: center;">
                <div class="text-white">
                    <p class="text-sm uppercase mb-2">Print with Excellence</p>
                    <h2 class="text-2xl font-bold mb-4">Office Printers<br>Solutions</h2>
                    <a href="products.php?brand=1" class="bg-white text-black px-6 py-2 rounded-full text-sm font-medium inline-block">SHOP NOW</a>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold uppercase mb-4">Categories</h3>
            <div class="grid grid-cols-5 md:grid-cols-10 gap-4">
                <?php
                $homeCats = $categoryModel->getHomeCategoriesDisplay();
                foreach ($homeCats as $cat):
                ?>
                    <a href="products.php?category=<?= $cat['category_id'] ?>" class="products-cat-link text-center group flex flex-col items-center">
                        <div class="products-cat-icon-wrap mb-2">
                            <img src="<?= !empty($cat['category_image']) ? 'assets/images/categories/' . htmlspecialchars($cat['category_image']) : 'assets/images/categories/default.png' ?>"
                                alt="<?= htmlspecialchars($cat['display_name'] ?? $cat['category_name']) ?>"
                                class="products-cat-img"
                                width="200"
                                height="200"
                                loading="lazy"
                                decoding="async">
                        </div>
                        <p class="text-xs group-hover:text-purple-custom"><?= htmlspecialchars($cat['display_name'] ?? $cat['category_name']) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar -->
            <div class="w-full md:w-64 flex-shrink-0 space-y-6">
                <!-- Categories Filter -->
                <!-- <div>
                    <div class="flex justify-between items-center mb-4 bg">
                        <h3 class="font-semibold">CATEGORIES</h3>
                    </div>
                    <ul class="space-y-2 text-sm bg-gray-200 rounded-lg p-4">
                        <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="products.php?category=<?= $cat['category_id'] ?>" 
                               class="flex justify-between items-center hover:text-purple-custom <?= $category_id == $cat['category_id'] ? 'text-purple-custom font-semibold' : '' ?>">
                                <span><?= htmlspecialchars($cat['category_name']) ?></span>
                                <span class="text-gray-400">→</span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div> -->

                <!-- Price + Brands: desktop in sidebar; mobile = slide-over (opened via toolbar) -->
                <div id="product-filters-drawer"
                    class="fixed top-0 right-0 z-[60] h-full w-full max-w-sm bg-white shadow-2xl overflow-y-auto transition-transform duration-300 ease-out translate-x-full md:translate-x-0 md:static md:z-auto md:h-auto md:max-w-none md:shadow-none md:overflow-visible">
                    <div class="sticky top-0 z-10 flex items-center justify-between gap-3 border-b border-gray-200 bg-white px-4 py-3 md:hidden">
                        <h2 class="text-base font-semibold text-gray-900">Filters</h2>
                        <button type="button" id="mobile-filters-close" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-600 hover:bg-gray-100" aria-label="Close filters">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    <div class="space-y-6 p-4 md:p-0">
                        <!-- Price Range Filter -->
                        <div>
                            <h3 class="font-semibold mb-4">PRICE RANGE</h3>
                            <form id="priceFilterForm" method="GET" action="products.php">
                                <?php if ($category_id): ?>
                                    <input type="hidden" name="category" value="<?= $category_id ?>">
                                <?php endif; ?>
                                <?php if ($brand_id): ?>
                                    <input type="hidden" name="brand" value="<?= $brand_id ?>">
                                <?php endif; ?>
                                <?php if ($search): ?>
                                    <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                                <?php endif; ?>
                                <?php if ($sort_by): ?>
                                    <input type="hidden" name="sort" value="<?= $sort_by ?>">
                                <?php endif; ?>

                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs text-gray-600">Min Price</label>
                                        <input type="number" name="min_price" value="<?= $min_price ?? '' ?>"
                                            placeholder="0"
                                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600">Max Price</label>
                                        <input type="number" name="max_price" value="<?= $max_price ?? '' ?>"
                                            placeholder="500000"
                                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                    </div>
                                    <button type="submit" class="w-full bg-purple-custom text-white py-2 rounded hover:bg-[#4f0a4f] text-sm">
                                        Apply
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Brands Filter -->
                        <div>
                            <h3 class="font-semibold mb-4">BRANDS</h3>
                            <ul class="space-y-2 text-sm bg-gray-200 rounded-lg p-4 max-h-[50vh] overflow-y-auto md:max-h-none">
                                <?php foreach ($brands as $b): ?>
                                    <li>
                                        <label class="flex items-center cursor-pointer hover:text-purple-custom">
                                            <input type="checkbox"
                                                class="brand-filter mr-2 rounded text-purple-custom focus:ring-purple-custom"
                                                value="<?= $b['brand_id'] ?>"
                                                <?= $brand_id == $b['brand_id'] ? 'checked' : '' ?>>
                                            <span class="<?= $brand_id == $b['brand_id'] ? 'text-purple-custom font-semibold' : '' ?>">
                                                <?= htmlspecialchars($b['brand_name']) ?>
                                            </span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <?php if ($category_id || $brand_id || $min_price || $max_price): ?>
                            <div class="md:hidden">
                                <a href="products.php" class="text-sm text-red-600 hover:text-red-700 font-semibold inline-block">
                                    Clear all filters
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="mobile-filters-backdrop" class="fixed inset-0 z-[55] bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden" aria-hidden="true"></div>

                <!-- Product Page Banners -->
                <?php
                // Fetch banners for product page
                require_once 'config/database.php';
                $bannerDb = new Database();
                $bannerConn = $bannerDb->getConnection();
                $stmt = $bannerConn->prepare("SELECT * FROM banners WHERE banner_type='product' ORDER BY banner_order ASC, updated_at DESC LIMIT 2");
                $stmt->execute();
                $productBanners = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="space-y-6 mt-8">
                    <?php foreach ($productBanners as $banner): ?>
                        <?php
                        $bannerMediaUrl = !empty($banner['banner_media']) ? bannerMediaUrl($banner['banner_media']) : '';
                        ?>
                        <div class="w-full flex flex-col">
                            <?php if ($bannerMediaUrl && preg_match('/\.(mp4|webm|ogg)$/i', (string) $banner['banner_media'])): ?>
                                <video src="<?= htmlspecialchars($bannerMediaUrl, ENT_QUOTES, 'UTF-8') ?>" controls class="w-full h-auto max-w-full"></video>
                                <button type="button"
                                    class="js-media-lightbox-trigger mt-2 text-sm font-medium text-purple-custom hover:underline w-full text-center"
                                    data-lightbox-type="video"
                                    data-lightbox-src="<?= htmlspecialchars($bannerMediaUrl, ENT_QUOTES, 'UTF-8') ?>">
                                    View larger
                                </button>
                            <?php elseif ($bannerMediaUrl): ?>
                                <div class="js-media-lightbox-trigger cursor-pointer rounded-sm focus:outline-none focus:ring-2 focus:ring-purple-custom focus:ring-offset-2"
                                    role="button"
                                    tabindex="0"
                                    aria-label="View banner larger"
                                    data-lightbox-type="image"
                                    data-lightbox-src="<?= htmlspecialchars($bannerMediaUrl, ENT_QUOTES, 'UTF-8') ?>"
                                    data-lightbox-alt="<?= htmlspecialchars($banner['banner_title'] ?? 'Banner', ENT_QUOTES, 'UTF-8') ?>">
                                    <img src="<?= htmlspecialchars($bannerMediaUrl, ENT_QUOTES, 'UTF-8') ?>" alt="" class="block w-full h-auto max-w-full object-contain pointer-events-none">
                                </div>
                            <?php else: ?>
                                <div class="w-full min-h-[120px] bg-gray-100 flex items-center justify-center text-gray-400 text-sm">No banner image</div>
                            <?php endif; ?>
                            <div class="text-center mt-3 px-1">
                                <div class="font-semibold text-lg mb-1"><?= htmlspecialchars($banner['banner_title']) ?></div>
                                <?php if ($banner['banner_link']): ?>
                                    <a href="<?= htmlspecialchars($banner['banner_link']) ?>" class="text-purple-custom hover:underline text-sm">Visit Link</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($category_id || $brand_id || $min_price || $max_price): ?>
                    <!-- Clear Filters -->
                    <div>
                        <a href="products.php" class="text-sm text-red-600 hover:text-red-700 font-semibold">
                            Clear All Filters
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Toolbar -->
                <div class="flex flex-col gap-4 mb-6">
                    <p class="text-sm text-gray-600">
                        Showing <?= min(($page - 1) * $per_page + 1, $total_products) ?>–<?= min($page * $per_page, $total_products) ?> of <?= $total_products ?> results
                    </p>
                    <div class="flex flex-wrap items-center justify-between gap-3 sm:justify-end">
                        <form method="GET" action="products.php" class="flex flex-wrap items-center gap-2 min-w-0 flex-1 sm:flex-initial">
                            <?php if ($category_id): ?>
                                <input type="hidden" name="category" value="<?= $category_id ?>">
                            <?php endif; ?>
                            <?php if ($brand_id): ?>
                                <input type="hidden" name="brand" value="<?= $brand_id ?>">
                            <?php endif; ?>
                            <?php if ($min_price): ?>
                                <input type="hidden" name="min_price" value="<?= $min_price ?>">
                            <?php endif; ?>
                            <?php if ($max_price): ?>
                                <input type="hidden" name="max_price" value="<?= $max_price ?>">
                            <?php endif; ?>
                            <?php if ($search): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                            <?php endif; ?>

                            <label class="text-sm text-gray-600 shrink-0">Sort by:</label>
                            <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-2 text-sm min-w-0 flex-1 sm:flex-initial sm:min-w-[11rem]">
                                <option value="newest" <?= $sort_by == 'newest' ? 'selected' : '' ?>>Newest</option>
                                <option value="price_low" <?= $sort_by == 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="price_high" <?= $sort_by == 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                                <option value="bestsellers" <?= $sort_by == 'bestsellers' ? 'selected' : '' ?>>Best Sellers</option>
                                <option value="rating" <?= $sort_by == 'rating' ? 'selected' : '' ?>>Top Rated</option>
                            </select>
                        </form>
                        <button type="button" id="mobile-filters-open" class="md:hidden inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 shrink-0">
                            <i class="fas fa-sliders-h text-purple-custom"></i>
                            Filters
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <?php if (empty($products)): ?>
                    <div class="text-center py-12">
                        <p class="text-gray-600 text-lg mb-4">No products found</p>
                        <a href="products.php" class="text-purple-custom hover:underline">Clear filters and browse all products</a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8 items-stretch">
                        <?php foreach ($products as $p): ?>
                            <?php
                            $pid = (int) $p['product_id'];
                            $inWish = isset($wishlistIdSet[$pid]);
                            ?>
                            <div class="bg-white rounded-2xl p-5 relative shadow-xl hover:shadow-2xl transition-shadow group flex flex-col h-full min-h-0 border border-gray-200 hover:border-[#4f0a4f]">
                                <button type="button"
                                    class="wishlist-btn wishlist-btn--icon absolute top-3 right-3 w-8 h-8 bg-white border border-gray-400 text-gray-600 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white hover:border-red-500 transition-colors z-10<?= $inWish ? ' in-wishlist' : '' ?>"
                                    data-product-id="<?= $pid ?>"
                                    aria-label="<?= $inWish ? 'Remove from wishlist' : 'Add to wishlist' ?>"
                                    aria-pressed="<?= $inWish ? 'true' : 'false' ?>">
                                    <?php if ($inWish): ?>
                                        <i class="fas fa-heart text-red-500" aria-hidden="true"></i>
                                    <?php else: ?>
                                        <i class="far fa-heart text-base" aria-hidden="true"></i>
                                    <?php endif; ?>
                                </button>

                                <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="block flex flex-col flex-1 min-h-0">
                                    <!-- Badge Section - Fixed Height -->
                                    <div class="h-6 mb-2">
                                        <?php if ($p['discount_percentage'] > 0): ?>
                                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block">
                                                -<?= $p['discount_percentage'] ?>% OFF
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Product Image -->
                                    <div class="flex items-center justify-center h-48 mb-4 w-full shrink-0">
                                        <?php
                                        $mainImage = !empty($p['main_image']) ? 'assets/images/products/' . $p['main_image'] : 'assets/images/categories/default.png';
                                        ?>
                                        <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="max-h-full w-full object-contain">
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
                                </a>
                                <div class="mt-auto flex flex-row gap-2 pt-2">
                                    <button type="button" onclick="addToCart(<?= $p['product_id'] ?>)"
                                        class="w-1/2 text-purple-custom py-2 rounded font-semibold text-sm hover:text-gray-700 transition-colors border border-purple-custom"
                                        title="Add to Cart"
                                        aria-label="Add to Cart">
                                        Add to cart
                                    </button>
                                    <button type="button" onclick="buyNowFromList(<?= $p['product_id'] ?>)"
                                        class="w-1/2 flex items-center justify-center bg-purple-custom text-white py-2 rounded font-semibold text-sm hover:bg-[#4f0a4f] transition-colors">
                                        Buy Now
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="flex justify-center items-center space-x-2">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?><?= $category_id ? '&category=' . $category_id : '' ?><?= $brand_id ? '&brand=' . $brand_id : '' ?><?= $min_price ? '&min_price=' . $min_price : '' ?><?= $max_price ? '&max_price=' . $max_price : '' ?><?= $sort_by ? '&sort=' . $sort_by : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                                    class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                                    Previous
                                </a>
                            <?php endif; ?>

                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                <a href="?page=<?= $i ?><?= $category_id ? '&category=' . $category_id : '' ?><?= $brand_id ? '&brand=' . $brand_id : '' ?><?= $min_price ? '&min_price=' . $min_price : '' ?><?= $max_price ? '&max_price=' . $max_price : '' ?><?= $sort_by ? '&sort=' . $sort_by : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                                    class="px-4 py-2 border rounded <?= $i == $page ? 'bg-purple-custom text-white border-purple-custom' : 'border-gray-300 hover:bg-gray-100' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?= $page + 1 ?><?= $category_id ? '&category=' . $category_id : '' ?><?= $brand_id ? '&brand=' . $brand_id : '' ?><?= $min_price ? '&min_price=' . $min_price : '' ?><?= $max_price ? '&max_price=' . $max_price : '' ?><?= $sort_by ? '&sort=' . $sort_by : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                                    class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                                    Next
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Media lightbox (banners + product images) -->
    <div id="mediaLightbox" class="fixed inset-0 z-[200] hidden items-center justify-center p-4" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="mediaLightboxTitle">
        <div class="absolute inset-0 bg-black/75 backdrop-blur-sm js-lightbox-backdrop" aria-hidden="true"></div>
        <div class="relative z-[201] flex max-h-[min(92vh,100%)] max-w-[min(96vw,100%)] flex-col items-center">
            <button type="button" class="js-lightbox-close mb-2 self-end flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-gray-800 shadow-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-custom" aria-label="Close">
                <i class="fas fa-times text-lg" aria-hidden="true"></i>
            </button>
            <div class="relative overflow-hidden rounded-lg bg-black/20 shadow-2xl">
                <span id="mediaLightboxTitle" class="sr-only">Enlarged image</span>
                <img id="lightboxImage" src="" alt="" class="hidden max-h-[85vh] max-w-[92vw] w-auto h-auto object-contain">
                <video id="lightboxVideo" src="" controls playsinline class="hidden max-h-[85vh] max-w-[92vw] w-auto"></video>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        function addToCart(productId) {
            if (window.cartManager) {
                window.cartManager.addToCart(productId, 1);
            }
        }

        // Buy Now - Add to cart and redirect to checkout
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

        // Brand filter checkboxes
        document.querySelectorAll('.brand-filter').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const brandId = this.value;
                const urlParams = new URLSearchParams(window.location.search);

                if (this.checked) {
                    urlParams.set('brand', brandId);
                } else {
                    urlParams.delete('brand');
                }

                window.location.href = 'products.php?' + urlParams.toString();
            });
        });

        (function() {
            const drawer = document.getElementById('product-filters-drawer');
            const backdrop = document.getElementById('mobile-filters-backdrop');
            const openBtn = document.getElementById('mobile-filters-open');
            const closeBtn = document.getElementById('mobile-filters-close');
            if (!drawer || !backdrop || !openBtn || !closeBtn) return;

            function isMobileFilters() {
                return window.matchMedia('(max-width: 767px)').matches;
            }

            function openMobileFilters() {
                if (!isMobileFilters()) return;
                drawer.classList.remove('translate-x-full');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100');
                backdrop.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
            }

            function closeMobileFilters() {
                drawer.classList.add('translate-x-full');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-100');
                backdrop.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            }

            openBtn.addEventListener('click', openMobileFilters);
            closeBtn.addEventListener('click', closeMobileFilters);
            backdrop.addEventListener('click', closeMobileFilters);

            window.addEventListener('resize', function() {
                if (!isMobileFilters()) {
                    closeMobileFilters();
                }
            });

            document.getElementById('priceFilterForm')?.addEventListener('submit', function() {
                if (isMobileFilters()) closeMobileFilters();
            });
        })();

        (function() {
            var box = document.getElementById('mediaLightbox');
            var imgEl = document.getElementById('lightboxImage');
            var vidEl = document.getElementById('lightboxVideo');
            var backdrop = box && box.querySelector('.js-lightbox-backdrop');
            var closeBtn = box && box.querySelector('.js-lightbox-close');
            if (!box || !imgEl || !vidEl) return;

            function closeLightbox() {
                box.classList.add('hidden');
                box.classList.remove('flex');
                box.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
                vidEl.pause();
                vidEl.removeAttribute('src');
                vidEl.classList.add('hidden');
                imgEl.removeAttribute('src');
                imgEl.classList.add('hidden');
            }

            function openLightbox(type, src, alt) {
                if (!src) return;
                vidEl.classList.add('hidden');
                imgEl.classList.add('hidden');
                if (type === 'video') {
                    vidEl.src = src;
                    vidEl.classList.remove('hidden');
                } else {
                    imgEl.src = src;
                    imgEl.alt = alt || '';
                    imgEl.classList.remove('hidden');
                }
                box.classList.remove('hidden');
                box.classList.add('flex');
                box.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
                if (type === 'video') {
                    vidEl.play().catch(function() {});
                }
            }

            document.querySelectorAll('.js-media-lightbox-trigger').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var t = el.getAttribute('data-lightbox-type') || 'image';
                    var src = el.getAttribute('data-lightbox-src');
                    var alt = el.getAttribute('data-lightbox-alt') || '';
                    openLightbox(t, src, alt);
                });
                el.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        el.click();
                    }
                });
            });

            closeBtn && closeBtn.addEventListener('click', closeLightbox);
            backdrop && backdrop.addEventListener('click', closeLightbox);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !box.classList.contains('hidden')) {
                    closeLightbox();
                }
            });
        })();
    </script>

</body>

</html>