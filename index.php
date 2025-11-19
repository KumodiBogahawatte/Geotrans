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
$featuredProducts = $product->getFeatured(6);
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
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #8D4887; }
        .bg-purple-custom { background-color: #8D4887; }
        .hover\:bg-purple-custom:hover { background-color: #8D4887; }
        .hover\:text-purple-custom:hover { color: #8D4887; }
        .border-purple-custom { border-color: #8D4887; }
        .from-purple-custom { --tw-gradient-from: #8D4887; }
        .to-purple-custom { --tw-gradient-to: #8D4887; }
        
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        
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

    <!-- Best Sellers Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 bg-gray-50">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Best Sellers</h2>
            <a href="products.php?sort=bestsellers" class="text-gray-600 hover:text-purple-custom font-medium">View All</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            <?php foreach ($bestsellers as $p): ?>
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

                    <?php if (isset($p['stock_quantity']) && $p['stock_quantity'] > 0): ?>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                        <div class="bg-purple-custom h-1.5 rounded-full" style="width: <?= min(100, ($p['stock_quantity'] / 100) * 100) ?>%"></div>
                    </div>
                    <p class="text-xs text-gray-500"><?= $p['stock_quantity'] ?> in stock</p>
                    <?php endif; ?>
                </a>
            </div>
            <?php endforeach; ?>
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
            <div class="bg-white rounded-2xl p-5 relative shadow-sm hover:shadow-lg transition-shadow group">
                <button onclick="addToCart(<?= $p['product_id'] ?>)" 
                        class="absolute top-3 right-3 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-purple-custom hover:text-white hover:border-purple-custom transition-colors z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>

                <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="block">
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="bg-cyan-400 text-white text-xs px-2 py-1 rounded font-semibold">NEW</span>
                    </div>

                    <div class="flex items-center justify-center h-48 mb-4">
                        <?php
                        $mainImage = !empty($p['main_image']) ? 'assets/images/products/' . $p['main_image'] : 'assets/images/categories/default.png';
                        ?>
                        <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="max-h-full object-contain">
                    </div>

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
    </section>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
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
    </script>

</body>
</html>
