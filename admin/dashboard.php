<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';

// Get dashboard statistics
$database = new Database();
$conn = $database->getConnection();

// Total orders
$orders_query = "SELECT COUNT(*) as total, SUM(total_amount) as revenue FROM orders";
$orders_stmt = $conn->query($orders_query);
$orders_data = $orders_stmt->fetch(PDO::FETCH_ASSOC);

// Pending orders
$pending_query = "SELECT COUNT(*) as total FROM orders WHERE order_status = 'pending'";
$pending_stmt = $conn->query($pending_query);
$pending_data = $pending_stmt->fetch(PDO::FETCH_ASSOC);

// Total products
$products_query = "SELECT COUNT(*) as total FROM products WHERE is_active = 1";
$products_stmt = $conn->query($products_query);
$products_data = $products_stmt->fetch(PDO::FETCH_ASSOC);

// Total users
$users_query = "SELECT COUNT(*) as total FROM users WHERE is_active = 1";
$users_stmt = $conn->query($users_query);
$users_data = $users_stmt->fetch(PDO::FETCH_ASSOC);

// Unread contact messages
$messages_query = "SELECT COUNT(*) as total FROM contact_messages WHERE is_read = 0";
$messages_stmt = $conn->query($messages_query);
$messages_data = $messages_stmt->fetch(PDO::FETCH_ASSOC);

// Recent orders
$recent_orders_query = "SELECT o.*, 
                        CONCAT(u.first_name, ' ', u.last_name) as full_name 
                        FROM orders o 
                        LEFT JOIN users u ON o.user_id = u.user_id 
                        ORDER BY o.created_at DESC LIMIT 10";
$recent_orders_stmt = $conn->query($recent_orders_query);
$recent_orders = $recent_orders_stmt->fetchAll(PDO::FETCH_ASSOC);

// Low stock products
$low_stock_query = "SELECT p.*, c.category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.category_id 
                    WHERE p.stock_quantity <= 10 AND p.is_active = 1 
                    ORDER BY p.stock_quantity ASC LIMIT 10";
$low_stock_stmt = $conn->query($low_stock_query);
$low_stock_products = $low_stock_stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="p-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">Rs<?= number_format($orders_data['revenue'] ?? 0, 2) ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $orders_data['total'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Orders</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $pending_data['total'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Products</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $products_data['total'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $users_data['total'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Unread Messages</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $messages_data['total'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Orders</h2>
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Order #</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Customer</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Total</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 text-sm">
                                <a href="orders.php?id=<?= $order['order_id'] ?>" class="text-purple-custom hover:underline">
                                    <?= htmlspecialchars($order['order_number']) ?>
                                </a>
                            </td>
                            <td class="py-3 text-sm"><?= htmlspecialchars($order['full_name'] ?? 'Guest') ?></td>
                            <td class="py-3 text-sm font-semibold">Rs<?= number_format($order['total_amount'], 2) ?></td>
                            <td class="py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    <?php
                                    switch($order['order_status']) {
                                        case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                        case 'processing': echo 'bg-blue-100 text-blue-800'; break;
                                        case 'shipped': echo 'bg-purple-100 text-purple-800'; break;
                                        case 'delivered': echo 'bg-green-100 text-green-800'; break;
                                        case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                        default: echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>">
                                    <?= ucfirst($order['order_status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <a href="orders.php" class="text-purple-custom hover:underline text-sm font-medium">View All Orders →</a>
            </div>
        </div>

        <!-- Contact Messages -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Contact Messages</h2>
            <?php
            // Get recent contact messages
            $recent_messages_query = "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5";
            $recent_messages_stmt = $conn->query($recent_messages_query);
            $recent_messages = $recent_messages_stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="space-y-3">
                <?php foreach ($recent_messages as $msg): ?>
                <div class="border-l-4 <?= $msg['is_read'] ? 'border-gray-300' : 'border-blue-500' ?> pl-4 py-2">
                    <div class="flex justify-between items-start mb-1">
                        <p class="font-semibold text-sm <?= $msg['is_read'] ? 'text-gray-700' : 'text-gray-900' ?>">
                            <?= htmlspecialchars($msg['name']) ?>
                        </p>
                        <?php if (!$msg['is_read']): ?>
                        <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-gray-500 mb-1"><?= htmlspecialchars($msg['email']) ?></p>
                    <p class="text-sm text-gray-600 line-clamp-2"><?= htmlspecialchars(substr($msg['message'], 0, 80)) ?>...</p>
                    <p class="text-xs text-gray-400 mt-1"><?= date('M d, Y h:i A', strtotime($msg['created_at'])) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4 text-center">
                <a href="contact-messages.php" class="text-purple-custom hover:underline text-sm font-medium">
                    View All Messages →
                </a>
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Low Stock Alert</h2>
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Product</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Category</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($low_stock_products as $product): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 text-sm">
                                <a href="products.php?id=<?= $product['product_id'] ?>" class="text-purple-custom hover:underline">
                                    <?= htmlspecialchars($product['product_name']) ?>
                                </a>
                            </td>
                            <td class="py-3 text-sm"><?= htmlspecialchars($product['category_name']) ?></td>
                            <td class="py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    <?= $product['stock_quantity'] <= 5 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?= $product['stock_quantity'] ?> units
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <a href="products.php" class="text-purple-custom hover:underline text-sm font-medium">Manage Products →</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
