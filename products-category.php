<?php
require_once 'includes/helpers.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
require_once 'classes/Brand.php';

$productModel = new Product();
$categoryModel = new Category();
$brandModel = new Brand();

// Get category slug from query
$category_slug = isset($_GET['category']) ? trim($_GET['category']) : null;
if (!$category_slug) {
    header('Location: products.php');
    exit;
}

$categoryData = $categoryModel->getById($category_slug);
if (!$categoryData) {
    header('Location: products.php');
    exit;
}
$category_id = $categoryData['category_id'];

// Get filter parameters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 20;
$brand_id = isset($_GET['brand']) ? intval($_GET['brand']) : null;
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : null;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : null;
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build filters array
$filters = ['category_id' => $category_id];
if ($brand_id) $filters['brand_id'] = $brand_id;
if ($min_price) $filters['min_price'] = $min_price;
if ($max_price) $filters['max_price'] = $max_price;
if ($search) $filters['search'] = $search;
if ($sort_by) $filters['sort'] = $sort_by;

$result = $productModel->getAll($page, $per_page, $filters);
$products = $result['products'];
$total_products = $result['total'];
$total_pages = $result['total_pages'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categoryData['category_name']) ?> Products - Geotrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #680e68; }
        .bg-purple-custom { background-color: #680e68; }
        .hover\:bg-purple-custom:hover { background-color: #680e68; }
        .hover\:text-purple-custom:hover { color: #680e68; }
        .border-purple-custom { border-color: #680e68; }
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
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <nav class="flex mb-6 text-sm text-gray-600">
            <a href="index.php" class="hover:text-purple-custom">Home</a>
            <span class="mx-2">/</span>
            <a href="products.php" class="hover:text-purple-custom">Products</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900"><?= htmlspecialchars($categoryData['category_name']) ?></span>
        </nav>
        <h2 class="text-2xl font-bold mb-6">Products in <?= htmlspecialchars($categoryData['category_name']) ?></h2>
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar -->
            <div class="w-full md:w-64 flex-shrink-0 space-y-6">
                <!-- Price Range Filter -->
                <div>
                    <h3 class="font-semibold mb-4">PRICE RANGE</h3>
                    <form id="priceFilterForm" method="GET" action="products-category.php">
                        <input type="hidden" name="category" value="<?= htmlspecialchars($categoryData['category_slug']) ?>">
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
                                <input type="number" name="min_price" value="<?= $min_price ?? '' ?>" placeholder="0" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600">Max Price</label>
                                <input type="number" name="max_price" value="<?= $max_price ?? '' ?>" placeholder="500000" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                            <button type="submit" class="w-full bg-purple-custom text-white py-2 rounded hover:bg-[#4f0a4f] text-sm">Apply</button>
                        </div>
                    </form>
                </div>
                <!-- Brands Filter -->
                <div>
                    <h3 class="font-semibold mb-4">BRANDS</h3>
                    <ul class="space-y-2 text-sm">
                        <?php foreach ($brands as $b): ?>
                        <li>
                            <label class="flex items-center cursor-pointer hover:text-purple-custom">
                                <input type="checkbox" class="brand-filter mr-2 rounded text-purple-custom focus:ring-purple-custom" value="<?= $b['brand_id'] ?>" <?= $brand_id == $b['brand_id'] ? 'checked' : '' ?>>
                                <span class="<?= $brand_id == $b['brand_id'] ? 'text-purple-custom font-semibold' : '' ?>">
                                    <?= htmlspecialchars($b['brand_name']) ?>
                                </span>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php if ($brand_id || $min_price || $max_price): ?>
                <!-- Clear Filters -->
                <div>
                    <a href="products-category.php?category=<?= htmlspecialchars($categoryData['category_slug']) ?>" class="text-sm text-red-600 hover:text-red-700 font-semibold">Clear All Filters</a>
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
                        <form method="GET" action="products-category.php" class="flex items-center gap-2">
                            <input type="hidden" name="category" value="<?= htmlspecialchars($categoryData['category_slug']) ?>">
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
                    <p class="text-gray-600 text-lg mb-4">No products found in this category.</p>
                    <a href="products-category.php?category=<?= $category_id ?>" class="text-purple-custom hover:underline">Clear filters and browse all products in this category</a>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    <?php foreach ($products as $p): ?>
                    <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group flex flex-col h-full">
                        <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="flex flex-col flex-grow">
                            <div class="flex items-center justify-center h-48 mb-4">
                                <?php
                                $mainImage = !empty($p['main_image']) ? 'assets/images/products/' . $p['main_image'] : 'assets/images/categories/default.png';
                                ?>
                                <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="max-h-full object-contain">
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">
                                <?= htmlspecialchars($p['product_name']) ?>
                            </h3>
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
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="flex justify-center items-center space-x-2">
                    <?php if ($page > 1): ?>
                    <a href="?category=<?= htmlspecialchars($categoryData['category_slug']) ?>&page=<?= $page - 1 ?><?= $brand_id ? '&brand=' . $brand_id : '' ?><?= $min_price ? '&min_price=' . $min_price : '' ?><?= $max_price ? '&max_price=' . $max_price : '' ?><?= $sort_by ? '&sort=' . $sort_by : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Previous</a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <a href="?category=<?= htmlspecialchars($categoryData['category_slug']) ?>&page=<?= $i ?><?= $brand_id ? '&brand=' . $brand_id : '' ?><?= $min_price ? '&min_price=' . $min_price : '' ?><?= $max_price ? '&max_price=' . $max_price : '' ?><?= $sort_by ? '&sort=' . $sort_by : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="px-4 py-2 border rounded <?= $i == $page ? 'bg-purple-custom text-white border-purple-custom' : 'border-gray-300 hover:bg-gray-100' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                    <a href="?category=<?= htmlspecialchars($categoryData['category_slug']) ?>&page=<?= $page + 1 ?><?= $brand_id ? '&brand=' . $brand_id : '' ?><?= $min_price ? '&min_price=' . $min_price : '' ?><?= $max_price ? '&max_price=' . $max_price : '' ?><?= $sort_by ? '&sort=' . $sort_by : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Next</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script>
        // Brand filter checkboxes
        document.querySelectorAll('.brand-filter').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const brandId = this.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('category', '<?= htmlspecialchars($categoryData['category_slug']) ?>');
                if (this.checked) {
                    urlParams.set('brand', brandId);
                } else {
                    urlParams.delete('brand');
                }
                window.location.href = 'products-category.php?' + urlParams.toString();
            });
        });
    </script>
</body>
</html>
