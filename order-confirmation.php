<?php
session_start();
require_once 'config/database.php';
require_once 'classes/Order.php';

// Check if order ID is provided
if (!isset($_GET['order'])) {
    header('Location: index.php');
    exit;
}

$order = new Order();
$order_data = $order->getById($_GET['order']);

if (!$order_data) {
    header('Location: index.php');
    exit;
}

// Verify order belongs to current user
if (isset($_SESSION['user_id']) && $order_data['user_id'] != $_SESSION['user_id']) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | GeoTrans</title>
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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="max-w-3xl mx-auto">
    <!-- Success Message -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h1>
            <p class="text-gray-600 mb-4">Thank you for your purchase. Your order has been received.</p>
            <p class="text-lg font-semibold text-purple-custom">Order #<?= htmlspecialchars($order_data['order_number']) ?></p>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Order Details</h2>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600">Order Date</p>
                    <p class="font-semibold"><?= date('F j, Y', strtotime($order_data['created_at'])) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Order Status</p>
                    <p class="font-semibold capitalize"><?= htmlspecialchars($order_data['order_status']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payment Method</p>
                    <p class="font-semibold capitalize"><?= str_replace('_', ' ', htmlspecialchars($order_data['payment_method'])) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payment Status</p>
                    <p class="font-semibold capitalize"><?= htmlspecialchars($order_data['payment_status']) ?></p>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="border-t pt-4">
                <h3 class="font-bold text-gray-900 mb-2">Shipping Address</h3>
                <p class="text-gray-700"><?= htmlspecialchars($order_data['shipping_name']) ?></p>
                <p class="text-gray-700"><?= htmlspecialchars($order_data['shipping_address1']) ?></p>
                <p class="text-gray-700"><?= htmlspecialchars($order_data['shipping_city']) ?>, <?= htmlspecialchars($order_data['shipping_postal']) ?></p>
                <p class="text-gray-700">Phone: <?= htmlspecialchars($order_data['shipping_phone']) ?></p>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Order Items</h2>
            
            <div class="space-y-4">
                <?php foreach ($order_data['items'] as $item): ?>
                <div class="flex items-center space-x-4 py-3 border-b">
                    <img src="assets/images/products/<?= htmlspecialchars($item['main_image'] ?? 'default.png') ?>" 
                         alt="<?= htmlspecialchars($item['product_name']) ?>"
                         class="w-20 h-20 object-contain rounded border border-gray-200">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($item['product_name']) ?></p>
                        <p class="text-sm text-gray-600">Quantity: <?= $item['quantity'] ?> × Rs<?= number_format($item['unit_price'], 2) ?></p>
                    </div>
                    <div class="font-semibold text-gray-900">
                        Rs<?= number_format($item['subtotal'], 2) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Order Total -->
            <div class="mt-6 pt-4 border-t space-y-2">
                <div class="flex justify-between text-gray-700">
                    <span>Subtotal</span>
                    <span>Rs<?= number_format($order_data['subtotal'], 2) ?></span>
                </div>
                <div class="flex justify-between text-gray-700">
                    <span>Shipping</span>
                    <span><?= $order_data['shipping_cost'] == 0 ? 'FREE' : 'Rs' . number_format($order_data['shipping_cost'], 2) ?></span>
                </div>
                <?php if ($order_data['tax_amount'] > 0): ?>
                <div class="flex justify-between text-gray-700">
                    <span>Tax</span>
                    <span>Rs<?= number_format($order_data['tax_amount'], 2) ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t">
                    <span>Total</span>
                    <span>Rs<?= number_format($order_data['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="index.php" class="flex-1 text-center bg-purple-custom text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition-colors">
                Continue Shopping
            </a>
            <?php if (isset($_SESSION['user_id'])): ?>
            <a href="account/orders.php" class="flex-1 text-center bg-white border-2 border-purple-custom text-purple-custom py-3 rounded-lg font-semibold hover:bg-purple-50 transition-colors">
                View My Orders
            </a>
            <?php endif; ?>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>What's Next?</strong><br>
                We've sent a confirmation email to your registered email address. You'll receive another email when your order ships.
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
