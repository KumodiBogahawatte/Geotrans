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
$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Geotrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #8D4887; }
        .bg-purple-custom { background-color: #8D4887; }
        .hover\:bg-purple-custom:hover { background-color: #8D4887; }
        .hover\:text-purple-custom:hover { color: #8D4887; }
        .border-purple-custom { border-color: #8D4887; }
        
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-gray-600">
            <a href="index.php" class="hover:text-purple-custom">Home</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Products</span>
        </nav>

        <!-- Top Banners -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-gray-800 rounded-lg p-8 flex items-center justify-between" style="background-color: #A1A4AD; background-image: url('assets/images/pages/noice-cancel-headphone.png'); background-size: cover; background-position: center;">
                <div class="text-white">
                    <p class="text-sm uppercase mb-2">The best place to play</p>
                    <h2 class="text-2xl font-bold mb-4">Noise Cancelling<br>Headphones</h2>
                    <a href="products.php?category=15" class="bg-white text-black px-6 py-2 rounded-full text-sm font-medium inline-block">SHOP NOW</a>
                </div>
            </div>
            <div class="bg-red-700 rounded-lg p-8 flex items-center justify-between" style="background-image: url('assets/images/pages/homepod-mini.jpeg'); background-size: cover; background-position: center;">
                <div class="text-white">
                    <p class="text-sm uppercase mb-2">Introducing New</p>
                    <h2 class="text-2xl font-bold mb-4">Apple Homepod<br>Mini</h2>
                    <a href="products.php?brand=1" class="bg-white text-black px-6 py-2 rounded-full text-sm font-medium inline-block">SHOP NOW</a>
                </div>
            </div>
        </div>

        <!-- Popular Categories -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold uppercase mb-4">Popular Categories</h3>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                <?php 
                $popularCats = $categoryModel->getPopular(6);
                foreach ($popularCats as $cat): 
                ?>
                <a href="products.php?category=<?= $cat['category_id'] ?>" class="text-center group">
                    <div class="bg-gray-100 rounded-lg p-4 mb-2 group-hover:bg-purple-100 transition-colors">
                        <img src="<?= !empty($cat['category_image']) ? 'assets/images/categories/' . htmlspecialchars($cat['category_image']) : 'assets/images/categories/default.png' ?>" 
                             alt="<?= htmlspecialchars($cat['category_name']) ?>" 
                             class="w-12 h-12 mx-auto object-contain">
                    </div>
                    <p class="text-xs group-hover:text-purple-custom"><?= htmlspecialchars($cat['category_name']) ?></p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar -->
            <div class="w-full md:w-64 flex-shrink-0 space-y-6">
                <!-- Categories Filter -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold">CATEGORIES</h3>
                    </div>
                    <ul class="space-y-2 text-sm">
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
                </div>

                <!-- Price Range Filter -->
                <div>
                    <h3 class="font-semibold mb-4">PRICE RANGE</h3>
                    <form id="priceFilterForm" method="GET" action="products.php">
                        <!-- Preserve existing filters -->
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
                            <button type="submit" class="w-full bg-purple-custom text-white py-2 rounded hover:bg-purple-700 text-sm">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Brands Filter -->
                <div>
                    <h3 class="font-semibold mb-4">BRANDS</h3>
                    <ul class="space-y-2 text-sm">
                        <?php foreach ($brands as $b): ?>
                        <li>
                            <a href="products.php?brand=<?= $b['brand_id'] ?><?= $category_id ? '&category=' . $category_id : '' ?>" 
                               class="flex items-center hover:text-purple-custom <?= $brand_id == $b['brand_id'] ? 'text-purple-custom font-semibold' : '' ?>">
                                <span><?= htmlspecialchars($b['brand_name']) ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
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
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <p class="text-sm text-gray-600">
                        Showing <?= min(($page - 1) * $per_page + 1, $total_products) ?>–<?= min($page * $per_page, $total_products) ?> of <?= $total_products ?> results
                    </p>
                    <div class="flex items-center gap-4">
                        <form method="GET" action="products.php" class="flex items-center gap-2">
                            <!-- Preserve filters -->
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
                            
                            <label class="text-sm text-gray-600">Sort by:</label>
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
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg mb-4">No products found</p>
                    <a href="products.php" class="text-purple-custom hover:underline">Clear filters and browse all products</a>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    <?php foreach ($products as $p): ?>
                    <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group flex flex-col h-full">
                        <!-- Wishlist Button -->
                        <button class="wishlist-btn absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-500 transition-colors z-10"
                                data-product-id="<?= $p['product_id'] ?>">
                            <i class="far fa-heart"></i>
                        </button>

                        <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="flex flex-col flex-grow">
                            <!-- Product Image - Fixed Height -->
                            <div class="flex items-center justify-center h-48 mb-4">
                                <?php
                                $mainImage = !empty($p['main_image']) ? 'assets/images/products/' . $p['main_image'] : 'assets/images/categories/default.png';
                                ?>
                                <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="max-h-full object-contain">
                            </div>

                            <!-- Discount Badge - Fixed Height -->
                            <div class="h-6 mb-2">
                                <?php if ($p['discount_percentage'] > 0): ?>
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block">
                                    -<?= $p['discount_percentage'] ?>% OFF
                                </span>
                                <?php endif; ?>
                            </div>

                            <!-- Product Name - Fixed Height -->
                            <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">
                                <?= htmlspecialchars($p['product_name']) ?>
                            </h3>

                            <!-- Rating - Fixed Height -->
                            <div class="flex items-center mb-3 h-5">
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

                            <!-- Price - Fixed Height -->
                            <div class="flex items-baseline flex-wrap gap-2 mb-3 min-h-[1.75rem]">
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
                        
                        <!-- Add to Cart Button - Pushed to bottom -->
                        <button onclick="addToCart(<?= $p['product_id'] ?>)" 
                                class="w-full bg-purple-custom text-white py-2 rounded-lg hover:bg-purple-700 text-sm font-semibold transition-colors mt-auto">
                            <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                        </button>
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

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        function addToCart(productId) {
            if (window.cartManager) {
                window.cartManager.addToCart(productId, 1);
            }
        }
    </script>

</body>
</html>
