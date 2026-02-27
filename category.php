<?php
require_once 'includes/helpers.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
require_once 'classes/Brand.php';

$productModel = new Product();
$categoryModel = new Category();
$brandModel = new Brand();

// Get category from URL
$category_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$currentCategory = null;

if ($category_id) {
    $currentCategory = $categoryModel->getById($category_id);
    if (!$currentCategory) {
        header('Location: products.php');
        exit;
    }
}

// Get filter parameters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 20;
$brand_id = isset($_GET['brand']) ? intval($_GET['brand']) : null;
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : null;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : null;
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build filters array
$filters = [];
if ($category_id) $filters['category_id'] = $category_id;
if ($brand_id) $filters['brand_id'] = $brand_id;
if ($min_price) $filters['min_price'] = $min_price;
if ($max_price) $filters['max_price'] = $max_price;
if ($sort_by) $filters['sort_by'] = $sort_by;

// Get products and pagination info
$result = $productModel->getAll($page, $per_page, $filters);
$products = $result['products'];
$total_products = $result['total'];
$total_pages = $result['total_pages'];

// Get all categories and brands for filters
$categories = $categoryModel->getAll();
$brands = $brandModel->getAll();
$popularBrands = $brandModel->getPopular(6);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $currentCategory ? htmlspecialchars($currentCategory['name']) : 'All Categories' ?> | GeoTrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #7D1074; }
        .bg-purple-custom { background-color: #7D1074; }
        .hover\:bg-purple-custom:hover { background-color: #7D1074; }
        .hover\:text-purple-custom:hover { color: #7D1074; }
        .border-purple-custom { border-color: #7D1074; }
        
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

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-purple-900 via-pink-800 to-blue-900 text-white overflow-hidden" style="background-image: url('assets/images/pages/asuz-banner.jpg'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div class="relative max-w-full mx-auto px-4 py-10 sm:py-16 text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">
                <?= $currentCategory ? htmlspecialchars($currentCategory['category_name']) : 'All Products' ?>
            </h1>
            <p class="text-base md:text-xl mb-8">
                <?= $currentCategory && !empty($currentCategory['category_description']) ? htmlspecialchars($currentCategory['category_description']) : 'Discover amazing products at unbeatable prices' ?>
            </p>
        </div>
    </div>

    <!-- Brand Logos -->
    <div class="flex flex-wrap justify-center items-center gap-6 mt-8 px-4">
        <?php foreach ($popularBrands as $b): ?>
        <a href="?id=<?= $category_id ?>&brand=<?= $b['brand_id'] ?>" class="transform hover:scale-110 transition-transform">
            <img src="<?= !empty($b['brand_logo']) ? 'assets/images/brands/' . htmlspecialchars($b['brand_logo']) : 'assets/images/home/hp-300x300-1 1.png' ?>" 
                 alt="<?= htmlspecialchars($b['brand_name']) ?>" 
                 class="h-20 md:h-32 p-2 rounded">
        </a>
        <?php endforeach; ?>
    </div>

    <hr class="max-w-full mx-auto">

    <div class="max-w-full mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside id="sidebarFilters" class="hidden md:block w-64 bg-white rounded-lg shadow-sm p-6 h-fit sticky top-4">
                <h3 class="font-bold text-lg mb-4">
                    <?= $currentCategory ? htmlspecialchars($currentCategory['category_name']) : 'All Products' ?>
                </h3>

                <!-- Categories Filter -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-3">CATEGORIES</h4>
                    <div class="space-y-2 text-sm">
                        <a href="products.php" class="flex items-center hover:text-purple-custom <?= !$category_id ? 'text-purple-custom font-semibold' : '' ?>">
                            All Categories
                        </a>
                        <?php foreach ($categories as $cat): ?>
                        <a href="?id=<?= $cat['category_id'] ?>" 
                           class="flex items-center hover:text-purple-custom <?= $category_id == $cat['category_id'] ? 'text-purple-custom font-semibold' : '' ?>">
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-3">BRAND</h4>
                    <div class="space-y-2 text-sm">
                        <?php foreach ($brands as $b): ?>
                        <label class="flex items-center cursor-pointer hover:text-purple-custom">
                            <input type="checkbox" 
                                   class="mr-2" 
                                   <?= $brand_id == $b['brand_id'] ? 'checked' : '' ?>
                                   onchange="window.location.href='?id=<?= $category_id ?>&brand=<?= $brand_id == $b['brand_id'] ? '' : $b['brand_id'] ?>'">
                            <?= htmlspecialchars($b['brand_name']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-3">PRICE RANGE</h4>
                    <form method="GET" action="">
                        <?php if ($category_id): ?>
                        <input type="hidden" name="id" value="<?= $category_id ?>">
                        <?php endif; ?>
                        <?php if ($brand_id): ?>
                        <input type="hidden" name="brand" value="<?= $brand_id ?>">
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
                            <button type="submit" class="w-full bg-purple-custom text-white py-2 rounded hover:bg-purple-700 text-sm">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>

                <?php if ($brand_id || $min_price || $max_price): ?>
                <!-- Clear Filters -->
                <div class="mb-6">
                    <a href="?id=<?= $category_id ?>" class="text-sm text-red-600 hover:text-red-700 font-semibold">
                        Clear All Filters
                    </a>
                </div>
                <?php endif; ?>
            </aside>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Mobile Filter Button -->
                <div class="md:hidden mb-4">
                    <button id="mobileFilterBtn" class="w-full bg-purple-custom text-white py-3 px-4 rounded-lg font-semibold flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filters
                    </button>
                </div>

                <!-- Toolbar -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-600">
                        Showing <?= min(($page - 1) * $per_page + 1, $total_products) ?>–<?= min($page * $per_page, $total_products) ?> of <?= $total_products ?> results
                    </p>
                    <div class="flex items-center gap-4">
                        <form method="GET" action="" class="flex items-center gap-2">
                            <?php if ($category_id): ?>
                            <input type="hidden" name="id" value="<?= $category_id ?>">
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
                            
                            <label class="text-sm text-gray-600">Sort:</label>
                            <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-2 text-sm">
                                <option value="newest" <?= $sort_by == 'newest' ? 'selected' : '' ?>>Newest</option>
                                <option value="price_low" <?= $sort_by == 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="price_high" <?= $sort_by == 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                                <option value="bestsellers" <?= $sort_by == 'bestsellers' ? 'selected' : '' ?>>Best Sellers</option>
                                <option value="rating" <?= $sort_by == 'rating' ? 'selected' : '' ?>>Top Rated</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                <?php if (empty($products)): ?>
                <div class="text-center py-12 bg-white rounded-lg">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-600 text-lg mb-4">No products found in this category</p>
                    <a href="products.php" class="text-purple-custom hover:underline font-semibold">Browse all products</a>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    <?php foreach ($products as $p): ?>
                    <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group">
                        <button onclick="addToCart(<?= $p['product_id'] ?>)" 
                                class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>

                        <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="block">
                            <div class="flex items-center justify-center h-48 mb-4">
                                <?php
                                $mainImage = !empty($p['main_image']) ? 'assets/images/products/' . $p['main_image'] : 'assets/images/categories/default.png';
                                ?>
                                <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="max-h-full object-contain">
                            </div>

                            <?php if ($p['discount_percentage'] > 0): ?>
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-2">
                                -<?= $p['discount_percentage'] ?>% OFF
                            </span>
                            <?php endif; ?>

                            <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">
                                <?= htmlspecialchars($p['product_name']) ?>
                            </h3>

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

                            <div class="flex items-baseline flex-wrap gap-2">
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
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="flex justify-center items-center space-x-2">
                    <?php if ($page > 1): ?>
                    <a href="?id=<?= $category_id ?>&page=<?= $page - 1 ?><?= $brand_id ? '&brand=' . $brand_id : '' ?><?= $min_price ? '&min_price=' . $min_price : '' ?><?= $max_price ? '&max_price=' . $max_price : '' ?><?= $sort_by ? '&sort=' . $sort_by : '' ?>" 
                       class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                        Previous
                    </a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <a href="?id=<?= $category_id ?>&page=<?= $i ?><?= $brand_id ? '&brand=' . $brand_id : '' ?><?= $min_price ? '&min_price=' . $min_price : '' ?><?= $max_price ? '&max_price=' . $max_price : '' ?><?= $sort_by ? '&sort=' . $sort_by : '' ?>" 
                       class="px-4 py-2 border rounded <?= $i == $page ? 'bg-purple-custom text-white border-purple-custom' : 'border-gray-300 hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                    <a href="?id=<?= $category_id ?>&page=<?= $page + 1 ?><?= $brand_id ? '&brand=' . $brand_id : '' ?><?= $min_price ? '&min_price=' . $min_price : '' ?><?= $max_price ? '&max_price=' . $max_price : '' ?><?= $sort_by ? '&sort=' . $sort_by : '' ?>" 
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

    <!-- Mobile Filter Drawer -->
    <div id="mobileFilterDrawer" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="absolute right-0 top-0 h-full w-80 bg-white shadow-xl overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Filters</h3>
                    <button id="closeMobileFilter" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Same filters as sidebar -->
                <div class="space-y-6">
                    <!-- Categories -->
                    <div>
                        <h4 class="font-semibold mb-3">CATEGORIES</h4>
                        <div class="space-y-2 text-sm">
                            <a href="products.php" class="block hover:text-purple-custom">All Categories</a>
                            <?php foreach ($categories as $cat): ?>
                            <a href="?id=<?= $cat['category_id'] ?>" class="block hover:text-purple-custom">
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Brands -->
                    <div>
                        <h4 class="font-semibold mb-3">BRAND</h4>
                        <div class="space-y-2 text-sm">
                            <?php foreach ($brands as $b): ?>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2" <?= $brand_id == $b['brand_id'] ? 'checked' : '' ?>
                                       onchange="window.location.href='?id=<?= $category_id ?>&brand=<?= $brand_id == $b['brand_id'] ? '' : $b['brand_id'] ?>'">
                                <?= htmlspecialchars($b['name']) ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
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

        // Mobile filter drawer
        const mobileFilterBtn = document.getElementById('mobileFilterBtn');
        const mobileFilterDrawer = document.getElementById('mobileFilterDrawer');
        const closeMobileFilter = document.getElementById('closeMobileFilter');

        if (mobileFilterBtn) {
            mobileFilterBtn.addEventListener('click', () => {
                mobileFilterDrawer.classList.remove('hidden');
            });
        }

        if (closeMobileFilter) {
            closeMobileFilter.addEventListener('click', () => {
                mobileFilterDrawer.classList.add('hidden');
            });
        }

        mobileFilterDrawer?.addEventListener('click', (e) => {
            if (e.target === mobileFilterDrawer) {
                mobileFilterDrawer.classList.add('hidden');
            }
        });
    </script>

</body>
</html>
