<?php
session_start();
require_once '../config/database.php';

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    header('Location: users.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

// Get user data
$query = "SELECT * FROM users WHERE user_id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = 'User not found';
    header('Location: users.php');
    exit;
}

// Get user's orders
$orders_query = "SELECT * FROM orders WHERE user_id = :id ORDER BY created_at DESC LIMIT 10";
$orders_stmt = $conn->prepare($orders_query);
$orders_stmt->bindParam(':id', $user_id);
$orders_stmt->execute();
$orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's addresses
$addresses_query = "SELECT * FROM user_addresses WHERE user_id = :id ORDER BY is_default DESC, created_at DESC";
$addresses_stmt = $conn->prepare($addresses_query);
$addresses_stmt->bindParam(':id', $user_id);
$addresses_stmt->execute();
$addresses = $addresses_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get order statistics
$stats_query = "SELECT 
                COUNT(*) as total_orders,
                COALESCE(SUM(total_amount), 0) as total_spent,
                COALESCE(AVG(total_amount), 0) as avg_order_value
                FROM orders 
                WHERE user_id = :id";
$stats_stmt = $conn->prepare($stats_query);
$stats_stmt->bindParam(':id', $user_id);
$stats_stmt->execute();
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="p-6">
    <div class="mb-6">
        <a href="users.php" class="text-purple-custom hover:underline">&larr; Back to Users</a>
    </div>

    <!-- User Info Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                </h1>
                <p class="text-gray-600 mt-1">User ID: <?= $user['user_id'] ?></p>
            </div>
            <div class="flex gap-2">
                <a href="user-edit.php?id=<?= $user['user_id'] ?>" 
                   class="bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-[#4f0a4f]">
                    Edit User
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Contact Information</h3>
                <div class="space-y-2">
                    <p class="text-sm"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <?php if ($user['phone']): ?>
                    <p class="text-sm"><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Account Status</h3>
                <div class="space-y-2">
                    <p class="text-sm">
                        <strong>Type:</strong> 
                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                            <?= ucfirst($user['user_type']) ?>
                        </span>
                    </p>
                    <p class="text-sm">
                        <strong>Status:</strong> 
                        <?php if ($user['is_active']): ?>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                        <?php else: ?>
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                        <?php endif; ?>
                    </p>
                    <p class="text-sm">
                        <strong>Verified:</strong> 
                        <?php if ($user['is_verified']): ?>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Yes</span>
                        <?php else: ?>
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">No</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Account Dates</h3>
                <div class="space-y-2">
                    <p class="text-sm"><strong>Joined:</strong> <?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                    <?php if ($user['last_login']): ?>
                    <p class="text-sm"><strong>Last Login:</strong> <?= date('M d, Y H:i', strtotime($user['last_login'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm text-gray-600 mb-2">Total Orders</div>
            <div class="text-3xl font-bold text-gray-900"><?= $stats['total_orders'] ?></div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm text-gray-600 mb-2">Total Spent</div>
            <div class="text-3xl font-bold text-gray-900">Rs. <?= number_format($stats['total_spent'], 2) ?></div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm text-gray-600 mb-2">Avg Order Value</div>
            <div class="text-3xl font-bold text-gray-900">Rs. <?= number_format($stats['avg_order_value'], 2) ?></div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Orders</h2>
        <?php if (empty($orders)): ?>
        <p class="text-gray-500">No orders yet</p>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900"><?= htmlspecialchars($order['order_number']) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-500"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-900">Rs. <?= number_format($order['total_amount'], 2) ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                <?= ucfirst(str_replace('_', ' ', $order['order_status'])) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="orders.php?search=<?= urlencode($order['order_number']) ?>" 
                               class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Addresses -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Saved Addresses</h2>
        <?php if (empty($addresses)): ?>
        <p class="text-gray-500">No saved addresses</p>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($addresses as $address): ?>
            <div class="border rounded-lg p-4">
                <?php if ($address['is_default']): ?>
                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 mb-2 inline-block">Default</span>
                <?php endif; ?>
                <p class="font-medium text-gray-900"><?= htmlspecialchars($address['full_name']) ?></p>
                <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($address['address_line1']) ?></p>
                <?php if ($address['address_line2']): ?>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($address['address_line2']) ?></p>
                <?php endif; ?>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($address['city']) ?>, <?= htmlspecialchars($address['postal_code']) ?></p>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($address['phone']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
