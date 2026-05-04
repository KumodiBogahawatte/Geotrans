<?php
session_start();
require_once 'includes/helpers.php';
require_once 'includes/recently-viewed.php';

$recentlyViewed = new RecentlyViewed();
$products = $recentlyViewed->getWithDetails(12);
$wishlistIdSet = getWishlistProductIdSet();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Viewed | Geotrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(bannerMediaUrl('assets/css/wishlist-buttons.css')) ?>">
    <style>
        .text-purple-custom { color: #680e68; }
        .bg-purple-custom { background-color: #680e68; }
        .hover\:bg-purple-custom:hover { background-color: #4f0a4f; }
        .hover\:text-purple-custom:hover { color: #4f0a4f; }
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
            <span class="text-gray-900">Recently Viewed</span>
        </nav>

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">Recently Viewed Products</h1>

        <?php if (empty($products)): ?>
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-200 shadow-sm">
                <i class="fas fa-eye-slash text-6xl text-gray-300 mb-4"></i>
                <h2 class="text-xl font-semibold text-gray-700 mb-2">No Recently Viewed Products</h2>
                <p class="text-gray-500 mb-6">Start browsing our products to see your viewing history here</p>
                <a href="products.php" class="inline-block bg-purple-custom text-white px-6 py-3 rounded-lg hover:bg-[#4f0a4f] font-semibold">
                    Browse Products
                </a>
            </div>
        <?php else: ?>
            <!-- auto-fill + minmax keeps card width similar to products.php main column (no ultra-wide cards) -->
            <div class="grid w-full gap-6 mb-8 items-stretch [grid-template-columns:repeat(auto-fill,minmax(min(100%,260px),1fr))]">
                <?php foreach ($products as $p): ?>
                    <?php
                    $pid = (int) $p['product_id'];
                    $inWish = isset($wishlistIdSet[$pid]);
                    ?>
                    <div class="bg-white rounded-2xl p-5 relative shadow-xl hover:shadow-2xl transition-shadow group flex flex-col h-full min-h-0 min-w-0 border border-gray-200 hover:border-[#4f0a4f]">
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

                        <a href="product_detail.php?id=<?= $pid ?>" class="block flex flex-col flex-1 min-h-0">
                            <div class="h-6 mb-2">
                                <?php if (!empty($p['discount_percentage']) && (int) $p['discount_percentage'] > 0): ?>
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block">
                                        -<?= (int) $p['discount_percentage'] ?>% OFF
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center justify-center h-48 mb-4 w-full shrink-0">
                                <?php
                                $mainImage = !empty($p['main_image']) ? 'assets/images/products/' . $p['main_image'] : 'assets/images/categories/default.png';
                                ?>
                                <img src="<?= htmlspecialchars($mainImage) ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="max-h-full w-full object-contain">
                            </div>

                            <hr><br>

                            <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">
                                <?= htmlspecialchars($p['product_name']) ?>
                            </h3>

                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400 text-xs">
                                    <?php
                                    $rating = (float) ($p['avg_rating'] ?? 0);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                                <span class="text-gray-500 text-xs ml-1">(<?= (int) ($p['review_count'] ?? 0) ?>)</span>
                            </div>

                            <div class="flex items-baseline flex-wrap gap-2 mb-3">
                                <?php if (!empty($p['sale_price']) && $p['sale_price'] < $p['price']): ?>
                                    <span class="text-purple-custom font-bold text-lg">
                                        Rs<?= number_format((float) $p['sale_price'], 2) ?>
                                    </span>
                                    <span class="text-gray-400 text-sm line-through">
                                        Rs<?= number_format((float) $p['price'], 2) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-purple-custom font-bold text-lg">
                                        Rs<?= number_format((float) $p['price'], 2) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="mt-auto flex flex-row gap-2 pt-2">
                            <button type="button" onclick="addToCart(<?= $pid ?>)"
                                class="w-1/2 text-purple-custom py-2 rounded font-semibold text-sm hover:text-gray-700 transition-colors border border-purple-custom"
                                title="Add to Cart"
                                aria-label="Add to Cart">
                                Add to cart
                            </button>
                            <button type="button" onclick="buyNowFromList(<?= $pid ?>)"
                                class="w-1/2 flex items-center justify-center bg-purple-custom text-white py-2 rounded font-semibold text-sm hover:bg-[#4f0a4f] transition-colors">
                                Buy Now
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-8 text-center">
                <button type="button" onclick="clearHistory()" class="text-red-600 hover:text-red-700 font-semibold">
                    <i class="fas fa-trash mr-2"></i>Clear Viewing History
                </button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function addToCart(productId) {
            if (window.cartManager) {
                window.cartManager.addToCart(productId, 1);
            }
        }

        function buyNowFromList(productId) {
            var formData = new FormData();
            formData.append('action', 'add');
            formData.append('product_id', productId);
            formData.append('quantity', 1);

            fetch('api/cart.php', { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        window.location.href = 'checkout.php';
                    } else if (window.cartManager) {
                        window.cartManager.showNotification(data.message || 'Failed to add product', 'error');
                    } else {
                        alert(data.message || 'Failed to add product');
                    }
                })
                .catch(function() {
                    if (window.cartManager) {
                        window.cartManager.showNotification('An error occurred', 'error');
                    } else {
                        alert('An error occurred');
                    }
                });
        }

        function clearHistory() {
            if (confirm('Are you sure you want to clear your viewing history?')) {
                fetch('api/clear-recently-viewed.php', { method: 'POST' })
                    .then(function() { window.location.reload(); });
            }
        }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
