<?php
require_once 'includes/helpers.php';
require_once 'classes/Cart.php';

$cart = new Cart();
$user_id = getUserId();
$session_id = !$user_id ? getSessionId() : null;

$cartItems = $cart->getItems($user_id, $session_id);
$cartTotal = $cart->getCartTotal($user_id, $session_id);
$cartCount = $cart->getCount($user_id, $session_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart | GeoTrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #8D4887; }
        .bg-purple-custom { background-color: #8D4887; }
        .hover\:bg-purple-custom:hover { background-color: #8D4887; }
    </style>
</head>
<body class="bg-gray-50">

    <?php include 'includes/header.php'; ?>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Shopping Cart (<?php echo $cartCount; ?> items)</h1>

        <?php if (empty($cartItems)): ?>
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg p-12 text-center">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-700 mb-2">Your cart is empty</h2>
                <p class="text-gray-500 mb-6">Add some products to get started!</p>
                <a href="products.php" class="inline-block bg-purple-custom hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm">
                        <?php foreach ($cartItems as $index => $item): ?>
                        <?php
                        $itemPrice = getProductPrice($item);
                        $itemSubtotal = $itemPrice * $item['quantity'];
                        ?>
                        <div class="cart-item p-4 <?php echo $index < count($cartItems) - 1 ? 'border-b' : ''; ?>" data-cart-id="<?php echo $item['cart_id']; ?>">
                            <div class="flex gap-4">
                                <a href="product_detail.php?slug=<?php echo $item['product_slug']; ?>" class="flex-shrink-0">
                                    <img src="assets/images/products/<?php echo htmlspecialchars($item['main_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                         class="w-24 h-24 object-cover rounded">
                                </a>
                                
                                <div class="flex-1">
                                    <a href="product_detail.php?slug=<?php echo $item['product_slug']; ?>" 
                                       class="font-semibold text-gray-900 hover:text-purple-custom">
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                    </a>
                                    <div class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($item['brand_name']); ?></div>
                                    
                                    <?php if ($item['stock_quantity'] < $item['quantity']): ?>
                                    <div class="text-red-600 text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle"></i> Only <?php echo $item['stock_quantity']; ?> left in stock
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-center gap-4 mt-3">
                                        <!-- Quantity Selector -->
                                        <div class="flex items-center border rounded">
                                            <button onclick="updateCartQty(<?php echo $item['cart_id']; ?>, <?php echo $item['quantity'] - 1; ?>)" 
                                                    class="px-3 py-1 hover:bg-gray-100" 
                                                    <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>-</button>
                                            <input type="number" value="<?php echo $item['quantity']; ?>" 
                                                   class="w-16 text-center border-x py-1" 
                                                   min="1" max="<?php echo $item['stock_quantity']; ?>" 
                                                   onchange="updateCartQty(<?php echo $item['cart_id']; ?>, this.value)" 
                                                   readonly>
                                            <button onclick="updateCartQty(<?php echo $item['cart_id']; ?>, <?php echo $item['quantity'] + 1; ?>)" 
                                                    class="px-3 py-1 hover:bg-gray-100"
                                                    <?php echo $item['quantity'] >= $item['stock_quantity'] ? 'disabled' : ''; ?>>+</button>
                                        </div>
                                        
                                        <!-- Remove Button -->
                                        <button onclick="removeCartItem(<?php echo $item['cart_id']; ?>)" 
                                                class="text-red-600 hover:text-red-700 text-sm">
                                            <i class="fas fa-trash-alt mr-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="font-bold text-lg text-purple-custom item-subtotal">
                                        <?php echo formatPrice($itemSubtotal); ?>
                                    </div>
                                    <?php if ($item['sale_price'] && $item['sale_price'] < $item['price']): ?>
                                    <div class="text-sm text-gray-400 line-through"><?php echo formatPrice($item['price'] * $item['quantity']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                        <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold" id="cart-subtotal"><?php echo formatPrice($cartTotal); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping:</span>
                                <span class="font-semibold">Rs500.00</span>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-lg">
                                <span class="font-bold">Total:</span>
                                <span class="font-bold text-purple-custom" id="cart-total"><?php echo formatPrice($cartTotal + 500); ?></span>
                            </div>
                        </div>

                        <a href="checkout.php" class="block w-full bg-purple-custom hover:bg-purple-700 text-white text-center py-3 rounded-lg font-semibold transition mb-3">
                            Proceed to Checkout
                        </a>
                        
                        <a href="products.php" class="block w-full text-center border-2 border-gray-300 hover:border-purple-custom text-gray-700 hover:text-purple-custom py-3 rounded-lg font-semibold transition">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        async function updateCartQty(cartId, quantity) {
            if (quantity < 1) return;
            
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            const qtyInput = cartItem.querySelector('input[type="number"]');
            qtyInput.value = quantity;

            const result = await window.cartManager.updateQuantity(cartId, quantity);
            
            if (result) {
                // Update item subtotal
                location.reload(); // Simple reload for now
            }
        }

        async function removeCartItem(cartId) {
            const result = await window.cartManager.removeItem(cartId);
            
            if (result) {
                location.reload();
            }
        }
    </script>
</body>
</html>
