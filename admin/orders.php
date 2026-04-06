<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';
$database = new Database();
$conn = $database->getConnection();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];
    
    $update_query = "UPDATE orders SET order_status = :status WHERE order_id = :id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':status', $new_status);
    $update_stmt->bindParam(':id', $order_id);
    $update_stmt->execute();
    
    // Add to history
    $history_query = "INSERT INTO order_status_history (order_id, new_status, comment) 
                     VALUES (:id, :status, :comment)";
    $history_stmt = $conn->prepare($history_query);
    $history_stmt->bindParam(':id', $order_id);
    $history_stmt->bindParam(':status', $new_status);
    $comment = 'Status updated by admin';
    $history_stmt->bindParam(':comment', $comment);
    $history_stmt->execute();
    
    $_SESSION['success'] = 'Order status updated successfully';
    header('Location: orders.php');
    exit;
}

// Get orders with filters
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT o.*, 
          CONCAT(u.first_name, ' ', u.last_name) as full_name, 
          u.email 
          FROM orders o 
          LEFT JOIN users u ON o.user_id = u.user_id 
          WHERE 1=1";

if ($status_filter) {
    $query .= " AND o.order_status = :status";
}
if ($search) {
    $query .= " AND (o.order_number LIKE :search OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search OR u.email LIKE :search)";
}

$query .= " ORDER BY o.created_at DESC LIMIT 100";

$stmt = $conn->prepare($query);

if ($status_filter) {
    $stmt->bindParam(':status', $status_filter);
}
if ($search) {
    $search_param = "%$search%";
    $stmt->bindParam(':search', $search_param);
}

$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="p-6">
    <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0">Orders</h1>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Status</option>
                <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="processing" <?= $status_filter === 'processing' ? 'selected' : '' ?>>Processing</option>
                <option value="shipped" <?= $status_filter === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                <option value="delivered" <?= $status_filter === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
            
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Search orders..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
            
            <button type="submit" class="bg-purple-custom text-white px-6 py-2 rounded-lg hover:bg-[#4f0a4f]">
                Filter
            </button>
        </form>
    </div>

    <!-- Orders Table -->
    <!-- Desktop View -->
    <div class="hidden lg:block bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Order #</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Customer</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Date</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Total</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Payment</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Status</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($orders as $order): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-purple-custom">
                        <?= htmlspecialchars($order['order_number']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div><?= htmlspecialchars($order['full_name'] ?? 'Guest') ?></div>
                        <div class="text-gray-500 text-xs"><?= htmlspecialchars($order['guest_email'] ?? $order['email'] ?? '') ?></div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= date('M d, Y', strtotime($order['created_at'])) ?>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold">
                        Rs<?= number_format($order['total_amount'], 2) ?>
                    </td>
                    <td class="px-6 py-4 text-sm capitalize">
                        <?= str_replace('_', ' ', htmlspecialchars($order['payment_method'])) ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <form method="POST" class="inline">
                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                            <select name="order_status" onchange="if(confirm('Update order status?')) this.form.submit();"
                                    class="px-2 py-1 rounded text-xs border-0 
                                    <?php
                                    switch($order['order_status']) {
                                        case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                        case 'processing': echo 'bg-blue-100 text-blue-800'; break;
                                        case 'shipped': echo 'bg-purple-100 text-purple-800'; break;
                                        case 'delivered': echo 'bg-green-100 text-green-800'; break;
                                        case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                    }
                                    ?>">
                                <option value="pending" <?= $order['order_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $order['order_status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['order_status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['order_status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $order['order_status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="order-detail.php?id=<?= $order['order_id'] ?>"
                           class="text-purple-custom hover:underline">View Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($orders)): ?>
        <div class="text-center py-8 text-gray-500">
            No orders found
        </div>
        <?php endif; ?>
    </div>

    <!-- Mobile View - Cards -->
    <div class="lg:hidden space-y-4">
        <?php if (empty($orders)): ?>
        <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
            No orders found
        </div>
        <?php else: ?>
        <?php foreach ($orders as $order): ?>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <span class="text-sm text-gray-600">Order #</span>
                    <div class="font-semibold text-purple-custom"><?= htmlspecialchars($order['order_number']) ?></div>
                </div>
                <span class="px-2 py-1 text-xs rounded-full font-medium
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
                    <?= ucfirst(htmlspecialchars($order['order_status'])) ?>
                </span>
            </div>
            
            <div class="space-y-2 text-sm mb-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Customer:</span>
                    <span class="font-medium"><?= htmlspecialchars($order['full_name'] ?? 'Guest') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Date:</span>
                    <span><?= date('M j, Y', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-semibold">Rs<?= number_format($order['total_amount'], 2) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Payment:</span>
                    <span><?= ucfirst(htmlspecialchars($order['payment_method'])) ?></span>
                </div>
            </div>
            
            <div class="flex gap-2 pt-3 border-t">
                <a href="order-detail.php?id=<?= $order['order_id'] ?>"
                   class="flex-1 text-center bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-[#4f0a4f] text-sm">
                    View Details
                </a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
