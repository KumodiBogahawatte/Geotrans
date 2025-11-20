<?php
session_start();
require_once 'includes/helpers.php';
require_once 'classes/Product.php';
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

// Debug: Check reviews (remove after testing)
// error_log("Product ID: " . $productData['product_id'] . " - Reviews count: " . count($reviews));


$currentPrice = getProductPrice($productData);
$discount = calculateDiscount($productData['price'], $productData['sale_price']);
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
        .text-purple-custom { color: #8D4887; }
        .bg-purple-custom { background-color: #8D4887; }
        .hover\:bg-purple-custom:hover { background-color: #8D4887; }
        .border-purple-custom { border-color: #8D4887; }
    </style>
</head>
<body class="bg-gray-50">

    <?php include 'includes/header.php'; ?>

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 py-4">
        <nav class="text-sm">
            <a href="index.php" class="text-gray-500 hover:text-purple-custom">Home</a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="category.php?slug=<?php echo $productData['category_slug']; ?>" class="text-gray-500 hover:text-purple-custom"><?php echo htmlspecialchars($productData['category_name']); ?></a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-900"><?php echo htmlspecialchars($productData['product_name']); ?></span>
        </nav>
    </div>

    <!-- Product Details -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            
            <!-- Product Images -->
            <div>
                <div class="bg-white rounded-lg p-4 mb-4">
                    <img id="main-image" src="assets/images/products/<?php echo htmlspecialchars($productData['main_image']); ?>" 
                         alt="<?php echo htmlspecialchars($productData['product_name']); ?>" 
                         class="w-full h-96 object-contain">
                </div>
                
                <?php if (!empty($images)): ?>
                <div class="grid grid-cols-4 gap-2">
                    <?php foreach ($images as $img): ?>
                    <div class="bg-white rounded-lg p-2 cursor-pointer hover:border-2 hover:border-purple-custom" 
                         onclick="document.getElementById('main-image').src='<?php echo htmlspecialchars($img['image_url']); ?>'">
                        <img src="<?php echo htmlspecialchars($img['image_url']); ?>" 
                             alt="Product image" 
                             class="w-full h-20 object-contain">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
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
                        <div class="flex items-center gap-3 mb-2">
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

                <!-- Stock Status -->
                <div class="mb-6">
                    <?php if ($productData['stock_quantity'] > 0): ?>
                        <span class="text-green-600 font-semibold"><i class="fas fa-check-circle mr-2"></i>In Stock (<?php echo $productData['stock_quantity']; ?> available)</span>
                    <?php else: ?>
                        <span class="text-red-600 font-semibold"><i class="fas fa-times-circle mr-2"></i>Out of Stock</span>
                    <?php endif; ?>
                </div>

                <!-- Quantity Selector -->
                <div class="flex items-center gap-4 mb-6">
                    <label class="text-gray-700 font-medium">Quantity:</label>
                    <div class="flex items-center border rounded">
                        <button onclick="decrementQty()" class="px-4 py-2 hover:bg-gray-100">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="<?php echo $productData['stock_quantity']; ?>" 
                               class="w-16 text-center border-x py-2" readonly>
                        <button onclick="incrementQty()" class="px-4 py-2 hover:bg-gray-100">+</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 mb-6">
                    <button class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-3 px-6 rounded-lg font-semibold transition"
                            onclick="buyNow()">
                        <i class="fas fa-credit-card mr-2"></i>Buy Now
                    </button>
                    <button class="add-to-cart-btn flex-1 bg-purple-custom hover:bg-purple-700 text-white py-3 px-6 rounded-lg font-semibold transition"
                            data-product-id="<?php echo $productData['product_id']; ?>"
                            onclick="addToCartWithQty()">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                    <button class="wishlist-btn border-2 border-purple-custom text-purple-custom hover:bg-purple-custom hover:text-white py-3 px-6 rounded-lg transition"
                            data-product-id="<?php echo $productData['product_id']; ?>">
                        <i class="far fa-heart"></i>
                    </button>
                </div>

                <!-- Product Meta -->
                <div class="border-t pt-4 space-y-2 text-sm">
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
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Summarize your review in one line">
                        </div>
                        
                        <!-- Review Text -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Your Review *</label>
                            <textarea name="review_text" required rows="5" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                      placeholder="Share your experience with this product..."></textarea>
                        </div>
                        
                        <div id="review-message" class="hidden text-sm"></div>
                        
                        <button type="submit" id="review-submit-btn" class="bg-purple-custom text-white px-6 py-3 rounded-lg hover:bg-purple-700 font-semibold">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                <?php
                $relatedPrice = getProductPrice($relatedProduct);
                $relatedDiscount = calculateDiscount($relatedProduct['price'], $relatedProduct['sale_price']);
                ?>
                <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                    <a href="product_detail.php?slug=<?php echo $relatedProduct['product_slug']; ?>">
                        <img src="assets/images/products/<?php echo htmlspecialchars($relatedProduct['main_image']); ?>" 
                             alt="<?php echo htmlspecialchars($relatedProduct['product_name']); ?>" 
                             class="w-full h-48 object-cover rounded mb-3">
                    </a>
                    <div class="text-xs text-gray-500 mb-1"><?php echo htmlspecialchars($relatedProduct['brand_name']); ?></div>
                    <a href="product_detail.php?slug=<?php echo $relatedProduct['product_slug']; ?>" 
                       class="text-sm font-medium text-gray-900 hover:text-purple-custom line-clamp-2 mb-2">
                        <?php echo htmlspecialchars($relatedProduct['product_name']); ?>
                    </a>
                    <div class="text-lg font-bold text-purple-custom"><?php echo formatPrice($relatedPrice); ?></div>
                    <button class="add-to-cart-btn w-full mt-3 bg-purple-custom hover:bg-purple-700 text-white py-2 px-4 rounded transition" 
                            data-product-id="<?php echo $relatedProduct['product_id']; ?>">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
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

        function decrementQty() {
            const qtyInput = document.getElementById('quantity');
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
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
                
                messageDiv.className = data.success 
                    ? 'text-green-600 text-sm mb-4'
                    : 'text-red-600 text-sm mb-4';
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
