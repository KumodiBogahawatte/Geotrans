<?php
session_start();
require_once 'config/database.php';
require_once 'classes/Wishlist.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$wishlist = new Wishlist();
$items = $wishlist->getItems($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist | GeoTrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'purple-custom': '#7D1074',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-full mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">My Wishlist</h1>

        <?php if (empty($items)): ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Your wishlist is empty</h2>
            <p class="text-gray-600 mb-6">Save items you love for later</p>
            <a href="products.php" class="bg-purple-custom text-white px-8 py-3 rounded-lg hover:bg-purple-700 inline-block">
                Continue Shopping
            </a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            <?php foreach ($items as $item): 
                $price = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
            ?>
            <div class="bg-white rounded-lg shadow-md p-4 group relative" data-product-id="<?= $item['product_id'] ?>">
                <!-- Remove Button -->
                <button onclick="removeFromWishlist(<?= $item['product_id'] ?>)" 
                        class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 flex items-center justify-center z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <a href="product_detail.php?id=<?= $item['product_id'] ?>" class="block">
                    <div class="flex items-center justify-center h-48 mb-4">
                        <img src="assets/images/products/<?= htmlspecialchars($item['main_image']) ?>" 
                             alt="<?= htmlspecialchars($item['product_name']) ?>"
                             class="max-h-full object-contain">
                    </div>

                    <?php if ($item['discount_percentage'] > 0): ?>
                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-semibold inline-block mb-2">
                        -<?= $item['discount_percentage'] ?>% OFF
                    </span>
                    <?php endif; ?>

                    <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 h-10">
                        <?= htmlspecialchars($item['product_name']) ?>
                    </h3>

                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400 text-xs">
                            <?php 
                            $rating = $item['avg_rating'] ?? $item['rating'] ?? 0;
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '★' : '☆';
                            }
                            ?>
                        </div>
                        <span class="text-gray-500 text-xs ml-1">(<?= $item['review_count'] ?? 0 ?>)</span>
                    </div>

                    <div class="flex items-baseline space-x-2 mb-3">
                        <span class="text-purple-custom font-bold text-lg">
                            Rs<?= number_format($price, 2) ?>
                        </span>
                        <?php if (!empty($item['sale_price']) && $item['sale_price'] < $item['price']): ?>
                        <span class="text-gray-400 text-sm line-through">Rs<?= number_format($item['price'], 2) ?></span>
                        <?php endif; ?>
                    </div>
                </a>

                <button onclick="addToCart(<?= $item['product_id'] ?>)" 
                        class="w-full bg-purple-custom text-white py-2 rounded-lg hover:bg-purple-700 text-sm font-semibold">
                    Add to Cart
                </button>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        </div>
    </div>
</main>

<script>
function removeFromWishlist(productId) {
    fetch('api/wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'remove',
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the product card with animation
            const card = document.querySelector(`[data-product-id="${productId}"]`);
            card.style.transition = 'opacity 0.3s';
            card.style.opacity = '0';
            setTimeout(() => {
                card.remove();
                // Update wishlist count in header using the count from API
                if (data.wishlist_count !== undefined) {
                    const wishlistCounts = document.querySelectorAll('.wishlist-count');
                    wishlistCounts.forEach(element => {
                        element.textContent = data.wishlist_count;
                        if (data.wishlist_count === 0) {
                            element.classList.add('hidden');
                        }
                    });
                    
                    if (data.wishlist_count === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }
            }, 300);
        }
    });
}

function addToCart(productId) {
    fetch('api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count in header
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
            }
            // Show success message
            alert('Product added to cart!');
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>

</body>
</html>