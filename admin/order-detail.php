<?php

// Enable error reporting for debugging (remove or comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';
require_once '../classes/Order.php';

$database = new Database();
$conn = $database->getConnection();
$orderObj = new Order();

$order_id = $_GET['id'] ?? 0;

if (!$order_id) {
    header('Location: orders.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    try {
        $new_status = $_POST['order_status'];
        $comment = $_POST['comment'] ?? 'Status updated by admin';

        $update_query = "UPDATE orders SET order_status = :status WHERE order_id = :id";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':status', $new_status);
        $update_stmt->bindParam(':id', $order_id);
        $update_stmt->execute();

        // Add to history
        $history_query = "INSERT INTO order_status_history (order_id, new_status, comment, changed_by) 
                         VALUES (:id, :status, :comment, :admin_id)";
        $history_stmt = $conn->prepare($history_query);
        $history_stmt->bindParam(':id', $order_id);
        $history_stmt->bindParam(':status', $new_status);
        $history_stmt->bindParam(':comment', $comment);
        $history_stmt->bindParam(':admin_id', $_SESSION['admin_id']);
        $history_stmt->execute();

        $_SESSION['success'] = 'Order status updated successfully';
        header("Location: order-detail.php?id=$order_id");
        exit;
    } catch (Exception $e) {
        echo '<div style="color:red; padding:10px;">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        // Optionally log error to a file
        // error_log($e->getMessage());
    }
}

// Get order details
$order = $orderObj->getOrderById($order_id);

if (!$order) {
    header('Location: orders.php');
    exit;
}

// Get order items
$items_query = "SELECT oi.*, p.product_name, p.main_image 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.product_id 
                WHERE oi.order_id = :order_id";
$items_stmt = $conn->prepare($items_query);
$items_stmt->bindParam(':order_id', $order_id);
$items_stmt->execute();
$items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get order history
$history_query = "SELECT * FROM order_status_history WHERE order_id = :order_id ORDER BY created_at DESC";
$history_stmt = $conn->prepare($history_query);
$history_stmt->bindParam(':order_id', $order_id);
$history_stmt->execute();
$history = $history_stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="p-6">
    <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

    <!-- Back Button -->
    <div class="mb-4">
        <a href="orders.php" class="text-purple-custom hover:underline flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>

    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Order #<?= htmlspecialchars($order['order_number']) ?></h1>
                <p class="text-gray-600">Placed on <?= date('F d, Y \a\t h:i A', strtotime($order['created_at'])) ?></p>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                    <?php
                    switch($order['order_status']) {
                        case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                        case 'processing': echo 'bg-blue-100 text-blue-800'; break;
                        case 'shipped': echo 'bg-purple-100 text-purple-800'; break;
                        case 'delivered': echo 'bg-green-100 text-green-800'; break;
                        case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                    }
                    ?>">
                    <?= ucfirst(htmlspecialchars($order['order_status'])) ?>
                </span>
            </div>
        </div>
        
        <!-- Update Status Form -->
        <form method="POST" class="border-t pt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                    <select name="order_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="pending" <?= $order['order_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $order['order_status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="shipped" <?= $order['order_status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option value="delivered" <?= $order['order_status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                        <option value="cancelled" <?= $order['order_status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                    <input type="text" name="comment" placeholder="Optional note about status change"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
            <input type="hidden" name="update_status" value="1">
            <button type="submit" class="bg-purple-custom text-white px-6 py-2 rounded-lg hover:bg-[#4f0a4f]">
                Update Status
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Order Items</h2>
                
                <div class="space-y-4">
                    <?php foreach ($items as $item): ?>
                    <div class="flex items-center border-b pb-4">
                        <img src="../assets/images/products/<?= htmlspecialchars($item['main_image'] ?? 'default.png') ?>" 
                             alt="<?= htmlspecialchars($item['product_name']) ?>"
                             class="w-20 h-20 object-cover rounded mr-4">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($item['product_name']) ?></h3>
                            <p class="text-sm text-gray-600">Quantity: <?= $item['quantity'] ?></p>
                            <p class="text-sm text-gray-600">Price: Rs<?= number_format($item['unit_price'], 2) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">
                                Rs<?= number_format($item['subtotal'], 2) ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Order Summary -->
                <div class="mt-6 border-t pt-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">Rs<?= number_format($order['subtotal'], 2) ?></span>
                    </div>
                    <?php if ($order['discount_amount'] > 0): ?>
                    <div class="flex justify-between text-sm mb-2 text-green-600">
                        <span>Discount</span>
                        <span>-Rs<?= number_format($order['discount_amount'], 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <!--<div class="flex justify-between text-sm mb-2">-->
                    <!--    <span class="text-gray-600">Shipping</span>-->
                    <!--    <span class="font-semibold">Rs<?= number_format($order['shipping_cost'], 2) ?></span>-->
                    <!--</div>-->
                    <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">
                        <span>Total</span>
                        <span class="text-purple-custom">Rs<?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Order History</h2>
                
                <?php if (!empty($history)): ?>
                <div class="space-y-3">
                    <?php foreach ($history as $h): ?>
                    <div class="border-l-4 border-purple-custom pl-4 py-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900">
                                    Status changed to: <span class="text-purple-custom"><?= ucfirst($h['new_status']) ?></span>
                                </p>
                                <?php if (!empty($h['comment'])): ?>
                                <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($h['comment']) ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="text-xs text-gray-500">
                                <?= date('M d, Y h:i A', strtotime($h['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-gray-500">No status history available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Information</h2>
                
                <?php if ($order['user_id']): ?>
                <div class="space-y-2">
                    <p class="text-sm">
                        <span class="text-gray-600">Name:</span><br>
                        <span class="font-semibold"><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></span>
                    </p>
                    <p class="text-sm">
                        <span class="text-gray-600">Email:</span><br>
                        <span class="font-semibold"><?= htmlspecialchars($order['email']) ?></span>
                    </p>
                    <p class="text-sm">
                        <span class="text-gray-600">Phone:</span><br>
                        <span class="font-semibold"><?= htmlspecialchars($order['phone'] ?? 'N/A') ?></span>
                    </p>
                </div>
                <?php else: ?>
                <div class="space-y-2">
                    <p class="text-sm">
                        <span class="text-gray-600">Guest Order</span><br>
                        <span class="font-semibold"><?= htmlspecialchars($order['guest_name'] ?? 'N/A') ?></span>
                    </p>
                    <p class="text-sm">
                        <span class="text-gray-600">Email:</span><br>
                        <span class="font-semibold"><?= htmlspecialchars($order['guest_email'] ?? 'N/A') ?></span>
                    </p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Shipping Address</h2>
                
                <div class="text-sm space-y-1">
                    <p class="font-semibold"><?= htmlspecialchars($order['shipping_name']) ?></p>
                    <p><?= htmlspecialchars($order['shipping_address_line1']) ?></p>
                    <?php if (!empty($order['shipping_address_line2'])): ?>
                    <p><?= htmlspecialchars($order['shipping_address_line2']) ?></p>
                    <?php endif; ?>
                    <p><?= htmlspecialchars($order['shipping_city']) ?>, <?= htmlspecialchars($order['shipping_state']) ?> <?= htmlspecialchars($order['shipping_postal_code']) ?></p>
                    <p><?= htmlspecialchars($order['shipping_country']) ?></p>
                    <?php if (!empty($order['shipping_phone'])): ?>
                    <p class="mt-2">Phone: <?= htmlspecialchars($order['shipping_phone']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Information</h2>
                
                <div class="space-y-2">
                    <p class="text-sm">
                        <span class="text-gray-600">Payment Method:</span><br>
                        <span class="font-semibold capitalize"><?= str_replace('_', ' ', htmlspecialchars($order['payment_method'])) ?></span>
                    </p>
                    <p class="text-sm">
                        <span class="text-gray-600">Payment Status:</span><br>
                        <span class="font-semibold capitalize <?= $order['payment_status'] === 'paid' ? 'text-green-600' : 'text-yellow-600' ?>">
                            <?= ucfirst(htmlspecialchars($order['payment_status'])) ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
