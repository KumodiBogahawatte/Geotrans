<?php
session_start();
require_once 'config/database.php';
require_once 'classes/Cart.php';
require_once 'classes/Order.php';
require_once 'classes/User.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header('Location: login.php?message=Please login to proceed with checkout');
    exit;
}

// Check if cart is empty
$cart = new Cart();
$user_id = $_SESSION['user_id'];
$session_id = session_id();
$cart_items = $cart->getItems($user_id, $session_id);

if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}

// Calculate totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $price = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
    $subtotal += $price * $item['quantity'];
}

$shipping_cost = $subtotal >= 5000 ? 0 : 350; // Free shipping over Rs. 5000
$tax_amount = 0; // No tax for now
$total_amount = $subtotal + $shipping_cost + $tax_amount;

// Get user info if logged in
$user_info = null;
if ($user_id) {
    $user = new User();
    $user_info = $user->getById($user_id);
}

// Handle form submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $required_fields = ['full_name', 'email', 'phone', 'address', 'city', 'postal_code', 'payment_method'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }

    if (empty($errors)) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            // Insert shipping address
            $address_query = "INSERT INTO user_addresses (user_id, full_name, phone, address_line1, city, postal_code, is_default) 
                             VALUES (:user_id, :full_name, :phone, :address, :city, :postal_code, 0)";
            $address_stmt = $conn->prepare($address_query);
            $address_stmt->bindParam(':user_id', $user_id);
            $address_stmt->bindParam(':full_name', $_POST['full_name']);
            $address_stmt->bindParam(':phone', $_POST['phone']);
            $address_stmt->bindParam(':address', $_POST['address']);
            $address_stmt->bindParam(':city', $_POST['city']);
            $address_stmt->bindParam(':postal_code', $_POST['postal_code']);
            $address_stmt->execute();
            $shipping_address_id = $conn->lastInsertId();
            
            // Prepare order items
            $order_items = [];
            foreach ($cart_items as $item) {
                $price = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                $order_items[] = [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'sku' => 'SKU-' . $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $price,
                    'subtotal' => $price * $item['quantity']
                ];
            }
            
            // Create order
            $order = new Order();
            $order_data = [
                'user_id' => $user_id,
                'guest_email' => $_POST['email'],
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'shipping_cost' => $shipping_cost,
                'tax_amount' => $tax_amount,
                'total_amount' => $total_amount,
                'payment_method' => $_POST['payment_method'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'shipping_address_id' => $shipping_address_id,
                'billing_address_id' => $shipping_address_id,
                'items' => $order_items
            ];
            
            $order_id = $order->create($order_data);
            
            if ($order_id) {
                // Clear cart
                $cart->clearCart($user_id, $session_id);
                
                // Redirect to order confirmation
                $_SESSION['order_success'] = true;
                header('Location: order-confirmation.php?order=' . $order_id);
                exit;
            } else {
                $errors[] = 'Failed to create order. Please try again.';
            }
            
        } catch (Exception $e) {
            $errors[] = 'An error occurred: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | GeoTrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #680e68; }
        .bg-purple-custom { background-color: #680e68; }
        .hover\:bg-purple-custom:hover { background-color: #680e68; }
    </style>
</head>
<body class="bg-gray-50">

<?php include 'includes/header.php'; ?>

<main class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Shipping & Payment -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Shipping Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Shipping Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="full_name" 
                                   value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ($user_info['full_name'] ?? '') ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" 
                                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ($user_info['email'] ?? '') ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                            <input type="tel" name="phone" 
                                   value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent"
                                   required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                            <textarea name="address" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent"
                                      required><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" name="city" 
                                   value="<?= isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '' ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                            <input type="text" name="postal_code" 
                                   value="<?= isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code']) : '' ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent"
                                   required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order Notes (Optional)</label>
                            <textarea name="notes" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent"
                                      placeholder="Special delivery instructions..."><?= isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Method</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cod" class="mr-3" checked>
                            <div>
                                <div class="font-semibold text-gray-900">Cash on Delivery</div>
                                <div class="text-sm text-gray-600">Pay when you receive your order</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                            <div>
                                <div class="font-semibold text-gray-900">Bank Transfer</div>
                                <div class="text-sm text-gray-600">Transfer to our bank account</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="credit_card" class="mr-3">
                            <div>
                                <div class="font-semibold text-gray-900">Credit/Debit Card</div>
                                <div class="text-sm text-gray-600">Pay securely with your card</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
                    
                    <!-- Cart Items -->
                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                        <?php foreach ($cart_items as $item): 
                            $price = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                            $item_total = $price * $item['quantity'];
                        ?>
                        <div class="flex items-center space-x-3 py-2 border-b">
                            <img src="assets/images/products/<?= htmlspecialchars($item['main_image']) ?>" 
                                 alt="<?= htmlspecialchars($item['product_name']) ?>"
                                 class="w-16 h-16 object-contain">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900 line-clamp-2">
                                    <?= htmlspecialchars($item['product_name']) ?>
                                </div>
                                <div class="text-xs text-gray-600">Qty: <?= $item['quantity'] ?></div>
                            </div>
                            <div class="text-sm font-semibold text-gray-900">
                                Rs<?= number_format($item_total, 2) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Price Summary -->
                    <div class="space-y-2 border-t pt-4">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal</span>
                            <span>Rs<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Shipping</span>
                            <span><?= $shipping_cost == 0 ? 'FREE' : 'Rs' . number_format($shipping_cost, 2) ?></span>
                        </div>
                        <?php if ($tax_amount > 0): ?>
                        <div class="flex justify-between text-gray-700">
                            <span>Tax</span>
                            <span>Rs<?= number_format($tax_amount, 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t">
                            <span>Total</span>
                            <span>Rs<?= number_format($total_amount, 2) ?></span>
                        </div>
                    </div>

                    <?php if ($subtotal < 5000): ?>
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            Add Rs<?= number_format(5000 - $subtotal, 2) ?> more for FREE shipping!
                        </p>
                    </div>
                    <?php endif; ?>

                    <button type="submit" 
                            class="w-full mt-6 bg-purple-custom text-white py-3 rounded-lg font-semibold hover:bg-[#4f0a4f] transition-colors">
                        Place Order
                    </button>

                    <p class="text-xs text-gray-600 text-center mt-4">
                        By placing your order, you agree to our terms and conditions
                    </p>
                </div>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
