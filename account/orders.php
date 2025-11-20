<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../config/database.php';
require_once '../classes/Order.php';

$order = new Order();
$orders = $order->getByUserId($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | GeoTrans</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'purple-custom': '#8D4887',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<main class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">My Account</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Sidebar Navigation -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <nav class="space-y-2">
                            <a href="profile.php" class="block px-4 py-2 rounded hover:bg-gray-100">
                                Profile
                            </a>
                            <a href="orders.php" class="block px-4 py-2 rounded bg-purple-custom text-white">
                                My Orders
                            </a>
                            <a href="addresses.php" class="block px-4 py-2 rounded hover:bg-gray-100">
                                Addresses
                            </a>
                            <a href="../logout.php" class="block px-4 py-2 rounded hover:bg-gray-100 text-red-600">
                                Logout
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- Orders List -->
                <div class="md:col-span-3">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Order History</h2>
                        
                        <?php if (empty($orders)): ?>
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <p class="text-gray-600 mb-4">You haven't placed any orders yet</p>
                            <a href="../products.php" class="bg-purple-custom text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                                Start Shopping
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($orders as $order): ?>
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">
                                            Order #<?= htmlspecialchars($order['order_number']) ?>
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            Placed on <?= date('F j, Y', strtotime($order['created_at'])) ?>
                                        </p>
                                    </div>
                                    <div class="mt-2 md:mt-0">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            <?php
                                            switch($order['order_status']) {
                                                case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'processing': echo 'bg-blue-100 text-blue-800'; break;
                                                case 'shipped': echo 'bg-purple-100 text-purple-800'; break;
                                                case 'delivered': echo 'bg-green-100 text-green-800'; break;
                                                case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                            }
                                            ?>">
                                            <?= ucfirst($order['order_status']) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between border-t pt-3">
                                    <div>
                                        <p class="text-sm text-gray-600">Total Amount</p>
                                        <p class="text-lg font-bold text-gray-900">Rs<?= number_format($order['total_amount'], 2) ?></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="../order-tracking.php?order=<?= urlencode($order['order_number']) ?>" 
                                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                                            <i class="fas fa-truck"></i>
                                            Track Order
                                        </a>
                                        <a href="../order-confirmation.php?order=<?= $order['order_id'] ?>" 
                                           class="bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>

</body>
</html>