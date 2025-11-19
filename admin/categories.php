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
    $category_id = $_GET['delete'];
    $delete_query = "UPDATE categories SET is_active = 0 WHERE category_id = :id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':id', $category_id);
    if ($delete_stmt->execute()) {
        $_SESSION['success'] = 'Category deactivated successfully';
    }
    header('Location: categories.php');
    exit;
}

// Handle activate
if (isset($_GET['activate'])) {
    $category_id = $_GET['activate'];
    $activate_query = "UPDATE categories SET is_active = 1 WHERE category_id = :id";
    $activate_stmt = $conn->prepare($activate_query);
    $activate_stmt->bindParam(':id', $category_id);
    if ($activate_stmt->execute()) {
        $_SESSION['success'] = 'Category activated successfully';
    }
    header('Location: categories.php');
    exit;
}

// Get filters
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Get categories with filters
$query = "SELECT c.*, 
          COUNT(DISTINCT p.product_id) as product_count 
          FROM categories c 
          LEFT JOIN products p ON c.category_id = p.category_id AND p.is_active = 1
          WHERE 1=1";

if ($status_filter !== '') {
    $query .= " AND c.is_active = :status";
}
if ($search) {
    $query .= " AND (c.category_name LIKE :search OR c.description LIKE :search)";
}

$query .= " GROUP BY c.category_id ORDER BY c.category_name ASC";

$stmt = $conn->prepare($query);

if ($status_filter !== '') {
    $stmt->bindParam(':status', $status_filter);
}
if ($search) {
    $search_param = "%$search%";
    $stmt->bindParam(':search', $search_param);
}

$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <h1 class="text-2xl font-bold text-gray-900">Categories Management</h1>
        <a href="category-edit.php" class="bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            Add New Category
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search categories..." 
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
                <button type="submit" class="flex-1 bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    Filter
                </button>
                <a href="categories.php" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($categories)): ?>
        <div class="col-span-full text-center text-gray-500 py-8">
            No categories found
        </div>
        <?php else: ?>
        <?php foreach ($categories as $category): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="aspect-video bg-gray-200">
                <img src="../assets/images/categories/<?= htmlspecialchars($category['category_image']) ?>" 
                     alt="<?= htmlspecialchars($category['category_name']) ?>"
                     class="w-full h-full object-cover"
                     onerror="this.src='../assets/images/default.png'">
            </div>
            
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($category['category_name']) ?></h3>
                    <?php if ($category['is_active']): ?>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                    <?php else: ?>
                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                    <?php endif; ?>
                </div>
                
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    <?= htmlspecialchars($category['category_description'] ?? 'No description') ?>
                </p>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        <?= $category['product_count'] ?> product<?= $category['product_count'] != 1 ? 's' : '' ?>
                    </span>
                    
                    <div class="flex gap-2">
                        <a href="category-edit.php?id=<?= $category['category_id'] ?>" 
                           class="text-blue-600 hover:text-blue-900">Edit</a>
                        <?php if ($category['is_active']): ?>
                        <a href="categories.php?delete=<?= $category['category_id'] ?>" 
                           class="text-red-600 hover:text-red-900"
                           onclick="return confirm('Are you sure you want to deactivate this category?')">Deactivate</a>
                        <?php else: ?>
                        <a href="categories.php?activate=<?= $category['category_id'] ?>" 
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
        Showing <?= count($categories) ?> categor<?= count($categories) == 1 ? 'y' : 'ies' ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
