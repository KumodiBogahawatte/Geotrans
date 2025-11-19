<?php
session_start();
require_once 'config/database.php';

$database = new Database();
$conn = $database->getConnection();

$order = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_number = $_POST['order_number'] ?? '';
    $email = $_POST['email'] ?? '';
    
    if (!empty($order_number) && !empty($email)) {
        $query = "SELECT o.order_id, o.order_number, o.user_id, o.total_amount, 
                  o.order_status, o.payment_method, o.payment_status, o.shipping_address_id,
                  o.created_at as order_date, o.order_status as status,
                  u.email, u.first_name, u.last_name,
                  CONCAT(sa.address_line1, ', ', sa.city, ', ', sa.state, ' ', sa.postal_code) as shipping_address
                  FROM orders o
                  LEFT JOIN users u ON o.user_id = u.user_id
                  LEFT JOIN user_addresses sa ON o.shipping_address_id = sa.address_id
                  WHERE o.order_number = :order_number
                  AND u.email = :email";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':order_number', $order_number);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            $error = "Order not found. Please check your order number and email address.";
        }
    } else {
        $error = "Please enter both order number and email address.";
    }
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking - Geotrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .purple-custom { background-color: #8D4887; }
        .purple-custom:hover { background-color: #6d3667; }
    </style>
    <style>
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }
        .timeline-item:last-child {
            padding-bottom: 0;
        }
        .timeline-dot {
            position: absolute;
            left: -1.625rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: white;
            border: 3px solid #e5e7eb;
        }
        .timeline-dot.active {
            border-color: #9333ea;
            background: #9333ea;
        }
        .timeline-dot.completed {
            border-color: #10b981;
            background: #10b981;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Track Your Order</h1>
    
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Number</label>
                <input type="text" name="order_number" required 
                       value="<?= htmlspecialchars($_POST['order_number'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       placeholder="e.g., ORD-20240115-001">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" required 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       placeholder="Enter your email address">
            </div>
            
            <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <button type="submit" class="w-full purple-custom text-white py-3 rounded-lg font-semibold transition-colors">
                <i class="fas fa-search mr-2"></i>Track Order
            </button>
        </form>
    </div>
    
    <?php if ($order): ?>
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Order #<?= htmlspecialchars($order['order_number']) ?></h2>
                <p class="text-sm text-gray-500">Placed on <?= date('F j, Y', strtotime($order['order_date'])) ?></p>
            </div>
            <div class="text-right">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                    <?php 
                    $statusColors = [
                        'Pending' => 'bg-yellow-100 text-yellow-800',
                        'Processing' => 'bg-blue-100 text-blue-800',
                        'Shipped' => 'bg-purple-100 text-purple-800',
                        'Delivered' => 'bg-green-100 text-green-800',
                        'Cancelled' => 'bg-red-100 text-red-800'
                    ];
                    $displayStatus = $order['status'] ?? $order['order_status'] ?? 'Pending';
                    echo $statusColors[$displayStatus] ?? 'bg-gray-100 text-gray-800';
                    ?>">
                    <?= htmlspecialchars($displayStatus) ?>
                </span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Customer</h3>
                <p class="text-sm text-gray-600">
                    <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?><br>
                    <?= htmlspecialchars($order['email']) ?>
                </p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Shipping Address</h3>
                <p class="text-sm text-gray-600">
                    <?= htmlspecialchars($order['shipping_address']) ?>
                </p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Order Total</h3>
                <p class="text-2xl font-bold text-purple-custom">
                    Rs<?= number_format($order['total_amount'], 2) ?>
                </p>
            </div>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Timeline</h3>
        <div class="timeline">
            <?php
            // Get order status history
            $historyQuery = "SELECT * FROM order_status_history 
                           WHERE order_id = :order_id 
                           ORDER BY created_at ASC";
            $historyStmt = $conn->prepare($historyQuery);
            $historyStmt->bindParam(':order_id', $order['order_id']);
            $historyStmt->execute();
            $history = $historyStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Define status order
            $statusOrder = ['Pending', 'Processing', 'Shipped', 'Delivered'];
            $currentStatus = $order['status'] ?? $order['order_status'] ?? 'Pending';
            $currentStatusIndex = array_search($currentStatus, $statusOrder);
            
            foreach ($statusOrder as $index => $status):
                $completed = $index < $currentStatusIndex;
                $active = $status === $currentStatus;
                $historyItem = null;
                
                foreach ($history as $item) {
                    $itemStatus = $item['status'] ?? $item['order_status'] ?? '';
                    if ($itemStatus === $status) {
                        $historyItem = $item;
                        break;
                    }
                }
            ?>
            <div class="timeline-item">
                <div class="timeline-dot <?= $completed ? 'completed' : ($active ? 'active' : '') ?>"></div>
                <div>
                    <h4 class="font-semibold text-gray-900"><?= $status ?></h4>
                    <?php if ($historyItem): ?>
                    <p class="text-sm text-gray-500">
                        <?= date('F j, Y g:i A', strtotime($historyItem['created_at'])) ?>
                    </p>
                    <?php if ($historyItem['comments']): ?>
                    <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($historyItem['comments']) ?></p>
                    <?php endif; ?>
                    <?php else: ?>
                    <p class="text-sm text-gray-400">Not yet processed</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
        <?php
        $itemsQuery = "SELECT oi.*, p.product_name, p.main_image
                      FROM order_items oi
                      LEFT JOIN products p ON oi.product_id = p.product_id
                      WHERE oi.order_id = :order_id";
        $itemsStmt = $conn->prepare($itemsQuery);
        $itemsStmt->bindParam(':order_id', $order['order_id']);
        $itemsStmt->execute();
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <div class="space-y-4">
            <?php foreach ($items as $item): ?>
            <div class="flex items-center space-x-4 border-b border-gray-200 pb-4">
                <img src="assets/images/products/<?= $item['main_image'] ?? 'default.png' ?>" 
                     alt="<?= htmlspecialchars($item['product_name']) ?>"
                     class="w-20 h-20 object-contain">
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($item['product_name']) ?></h4>
                    <p class="text-sm text-gray-500">Quantity: <?= $item['quantity'] ?></p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">Rs<?= number_format($item['subtotal'], 2) ?></p>
                    <p class="text-sm text-gray-500">Rs<?= number_format($item['unit_price'], 2) ?> each</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
