<?php
session_start();
require_once 'includes/recently-viewed.php';

$recentlyViewed = new RecentlyViewed();
$products = $recentlyViewed->getWithDetails(12);

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Viewed - Geotrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Recently Viewed Products</h1>
    
    <?php if (empty($products)): ?>
    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
        <i class="fas fa-eye-slash text-6xl text-gray-300 mb-4"></i>
        <h2 class="text-xl font-semibold text-gray-700 mb-2">No Recently Viewed Products</h2>
        <p class="text-gray-500 mb-6">Start browsing our products to see your viewing history here</p>
        <a href="products.php" class="inline-block bg-purple-custom text-white px-6 py-3 rounded-lg hover:bg-purple-700">
            Browse Products
        </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php foreach ($products as $product): ?>
        <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow p-4 relative group">
            <!-- Wishlist Button -->
            <button class="wishlist-btn absolute top-6 right-6 w-8 h-8 bg-white border border-gray-200 text-gray-600 rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-500 transition-colors z-10"
                    data-product-id="<?= $product['product_id'] ?>">
                <i class="far fa-heart"></i>
            </button>

            <a href="product_detail.php?slug=<?= $product['product_slug'] ?>" class="block">
                <div class="aspect-square mb-4 flex items-center justify-center">
                    <img src="assets/images/products/<?= !empty($product['main_image']) ? $product['main_image'] : 'default.png' ?>" 
                         alt="<?= htmlspecialchars($product['product_name']) ?>"
                         class="max-w-full max-h-full object-contain">
                </div>
                
                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">
                    <?= htmlspecialchars($product['product_name']) ?>
                </h3>
                
                <div class="text-xs text-gray-500 mb-2">
                    <?= htmlspecialchars($product['brand_name'] ?? '') ?>
                </div>
                
                <div class="flex items-baseline space-x-2">
                    <?php
                    $price = $product['sale_price'] ?? $product['price'];
                    $originalPrice = $product['price'];
                    ?>
                    <span class="text-purple-custom font-bold text-lg">
                        Rs<?= number_format($price, 2) ?>
                    </span>
                    <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                    <span class="text-gray-400 text-sm line-through">
                        Rs<?= number_format($originalPrice, 2) ?>
                    </span>
                    <?php endif; ?>
                </div>
            </a>
            
            <button onclick="addToCart(<?= $product['product_id'] ?>)" 
                    class="w-full mt-4 bg-purple-custom text-white py-2 rounded-lg hover:bg-purple-700 text-sm font-semibold transition-colors">
                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
            </button>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="mt-8 text-center">
        <button onclick="clearHistory()" class="text-red-600 hover:text-red-700 font-semibold">
            <i class="fas fa-trash mr-2"></i>Clear Viewing History
        </button>
    </div>
    <?php endif; ?>
</div>

<script>
function clearHistory() {
    if (confirm('Are you sure you want to clear your viewing history?')) {
        fetch('api/clear-recently-viewed.php', {
            method: 'POST'
        }).then(() => {
            window.location.reload();
        });
    }
}
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
