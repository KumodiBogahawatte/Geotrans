<?php
session_start();
require_once '../config/database.php';

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

// Handle delete
if (isset($_GET['delete'])) {
    $brand_id = $_GET['delete'];
    $delete_query = "UPDATE brands SET is_active = 0 WHERE brand_id = :id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':id', $brand_id);
    if ($delete_stmt->execute()) {
        $_SESSION['success'] = 'Brand deactivated successfully';
    }
    header('Location: brands.php');
    exit;
}

// Handle activate
if (isset($_GET['activate'])) {
    $brand_id = $_GET['activate'];
    $activate_query = "UPDATE brands SET is_active = 1 WHERE brand_id = :id";
    $activate_stmt = $conn->prepare($activate_query);
    $activate_stmt->bindParam(':id', $brand_id);
    if ($activate_stmt->execute()) {
        $_SESSION['success'] = 'Brand activated successfully';
    }
    header('Location: brands.php');
    exit;
}

// Get filters
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Get brands with filters
$query = "SELECT b.*, 
          COUNT(DISTINCT p.product_id) as product_count 
          FROM brands b 
          LEFT JOIN products p ON b.brand_id = p.brand_id AND p.is_active = 1
          WHERE 1=1";

if ($status_filter !== '') {
    $query .= " AND b.is_active = :status";
}
if ($search) {
    $query .= " AND (b.brand_name LIKE :search OR b.brand_description LIKE :search)";
}

$query .= " GROUP BY b.brand_id ORDER BY b.brand_name ASC LIMIT 18446744073709551615";

$stmt = $conn->prepare($query);

if ($status_filter !== '') {
    $stmt->bindParam(':status', $status_filter);
}
if ($search) {
    $search_param = "%$search%";
    $stmt->bindParam(':search', $search_param);
}

$stmt->execute();
$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="p-6">
    <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Brands Management</h1>
        <a href="brand-edit.php" class="bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-[#4f0a4f]">
            Add New Brand
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search brands..." 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
            </div>
            
            <div>
                <select name="status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                    <option value="">All Status</option>
                    <option value="1" <?= $status_filter === '1' ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= $status_filter === '0' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-[#4f0a4f]">
                    Filter
                </button>
                <a href="brands.php" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Brands Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php if (empty($brands)): ?>
        <div class="col-span-full text-center text-gray-500 py-8">
            No brands found
        </div>
        <?php else: ?>
        <?php foreach ($brands as $brand): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="aspect-square bg-gray-100 flex items-center justify-center p-4 border border-gray-200">
                <img src="../assets/images/brands/<?= htmlspecialchars($brand['brand_logo']) ?>" 
                     alt="<?= htmlspecialchars($brand['brand_name']) ?>"
                     class="max-w-full max-h-full object-contain"
                     onerror="this.src='../assets/images/default.png'">
            </div>
            
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($brand['brand_name']) ?></h3>
                    <?php if ($brand['is_active']): ?>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                    <?php else: ?>
                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                    <?php endif; ?>
                </div>
                
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    <?= htmlspecialchars($brand['brand_description'] ?? 'No description') ?>
                </p>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        <?= $brand['product_count'] ?> product<?= $brand['product_count'] != 1 ? 's' : '' ?>
                    </span>
                    
                    <div class="flex gap-2">
                        <a href="brand-edit.php?id=<?= $brand['brand_id'] ?>" 
                           class="text-blue-600 hover:text-blue-900">Edit</a>
                        <?php if ($brand['is_active']): ?>
                        <a href="brands.php?delete=<?= $brand['brand_id'] ?>" 
                           class="text-red-600 hover:text-red-900"
                           onclick="return confirm('Are you sure you want to deactivate this brand?')">Deactivate</a>
                        <?php else: ?>
                        <a href="brands.php?activate=<?= $brand['brand_id'] ?>" 
                           class="text-green-600 hover:text-green-900">Activate</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="mt-6 text-sm text-gray-600">
        Showing <?= count($brands) ?> brand<?= count($brands) != 1 ? 's' : '' ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
