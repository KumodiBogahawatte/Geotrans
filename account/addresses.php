<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../config/database.php';
$database = new Database();
$conn = $database->getConnection();

// Handle add/edit/delete
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_address'])) {
        $query = "INSERT INTO user_addresses (user_id, full_name, phone, address_line1, city, postal_code, is_default) 
                  VALUES (:user_id, :name, :phone, :address, :city, :postal, :is_default)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':name', $_POST['full_name']);
        $stmt->bindParam(':phone', $_POST['phone']);
        $stmt->bindParam(':address', $_POST['address']);
        $stmt->bindParam(':city', $_POST['city']);
        $stmt->bindParam(':postal', $_POST['postal_code']);
        $is_default = isset($_POST['is_default']) ? 1 : 0;
        $stmt->bindParam(':is_default', $is_default);
        
        if ($stmt->execute()) {
            $success = 'Address added successfully';
        } else {
            $errors[] = 'Failed to add address';
        }
    } elseif (isset($_POST['delete_address'])) {
        $query = "DELETE FROM user_addresses WHERE address_id = :id AND user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $_POST['address_id']);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $success = 'Address deleted successfully';
        }
    }
}

// Get user addresses
$query = "SELECT * FROM user_addresses WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Addresses | GeoTrans</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'purple-custom': '#680e68',
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
                            <a href="orders.php" class="block px-4 py-2 rounded hover:bg-gray-100">
                                My Orders
                            </a>
                            <a href="addresses.php" class="block px-4 py-2 rounded bg-purple-custom text-white">
                                Addresses
                            </a>
                            <a href="../logout.php" class="block px-4 py-2 rounded hover:bg-gray-100 text-red-600">
                                Logout
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- Addresses -->
                <div class="md:col-span-3">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Saved Addresses</h2>
                            <button onclick="document.getElementById('addAddressModal').classList.remove('hidden')"
                                    class="bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-[#4f0a4f]">
                                + Add New Address
                            </button>
                        </div>
                        
                        <?php if ($success): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <?= htmlspecialchars($success) ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (empty($addresses)): ?>
                        <div class="text-center py-8 text-gray-500">
                            No saved addresses yet
                        </div>
                        <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($addresses as $address): ?>
                            <div class="border rounded-lg p-4 relative">
                                <?php if ($address['is_default']): ?>
                                <span class="absolute top-2 right-2 bg-purple-custom text-white text-xs px-2 py-1 rounded">
                                    Default
                                </span>
                                <?php endif; ?>
                                
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    <?= htmlspecialchars($address['full_name']) ?>
                                </h3>
                                <p class="text-sm text-gray-600 mb-1">
                                    <?= htmlspecialchars($address['address_line1']) ?>
                                </p>
                                <p class="text-sm text-gray-600 mb-1">
                                    <?= htmlspecialchars($address['city']) ?>, <?= htmlspecialchars($address['postal_code']) ?>
                                </p>
                                <p class="text-sm text-gray-600 mb-3">
                                    Phone: <?= htmlspecialchars($address['phone']) ?>
                                </p>
                                
                                <form method="POST" class="inline">
                                    <input type="hidden" name="address_id" value="<?= $address['address_id'] ?>">
                                    <button type="submit" name="delete_address" 
                                            onclick="return confirm('Delete this address?')"
                                            class="text-red-600 hover:underline text-sm">
                                        Delete
                                    </button>
                                </form>
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

<!-- Add Address Modal -->
<div id="addAddressModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Add New Address</h3>
            <button onclick="document.getElementById('addAddressModal').classList.add('hidden')"
                    class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form method="POST">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="full_name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="tel" name="phone" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" rows="3" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom">
                </div>
            </div>
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_default" class="mr-2">
                    <span class="text-sm text-gray-700">Set as default address</span>
                </label>
            </div>
            
            <button type="submit" name="add_address"
                    class="w-full bg-purple-custom text-white py-3 rounded-lg hover:bg-[#4f0a4f]">
                Save Address
            </button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>