<?php

session_start();
require_once '../config/database.php';

// Database connection (must be before any use of $conn)
$database = new Database();
$conn = $database->getConnection();

// Get filters (must be before any use)
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$brand_filter = isset($_GET['brand']) ? $_GET['brand'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Handle delete
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $delete_query = "UPDATE products SET is_active = 0 WHERE product_id = :id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':id', $product_id);
    if ($delete_stmt->execute()) {
        $_SESSION['success'] = 'Product deleted successfully';
    }
    header('Location: products.php');
    exit;
}

// Handle activate
if (isset($_GET['activate'])) {
    $product_id = $_GET['activate'];
    $activate_query = "UPDATE products SET is_active = 1 WHERE product_id = :id";
    $activate_stmt = $conn->prepare($activate_query);
    $activate_stmt->bindParam(':id', $product_id);
    if ($activate_stmt->execute()) {
        $_SESSION['success'] = 'Product activated successfully';
    }
    header('Location: products.php');
    exit;
}


// Pagination setup
$per_page = 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Count total products for pagination
$count_query = "SELECT COUNT(*) FROM products p 
    LEFT JOIN categories c ON p.category_id = c.category_id 
    LEFT JOIN brands b ON p.brand_id = b.brand_id 
    WHERE 1=1";
if ($category_filter) {
    $count_query .= " AND p.category_id = :category";
}
if ($brand_filter) {
    $count_query .= " AND p.brand_id = :brand";
}
if ($status_filter !== '') {
    $count_query .= " AND p.is_active = :status";
}
if ($search) {
    $count_query .= " AND (p.product_name LIKE :search OR p.sku LIKE :search OR p.description LIKE :search)";
}
$count_stmt = $conn->prepare($count_query);
if ($category_filter) {
    $count_stmt->bindParam(':category', $category_filter);
}
if ($brand_filter) {
    $count_stmt->bindParam(':brand', $brand_filter);
}
if ($status_filter !== '') {
    $count_stmt->bindParam(':status', $status_filter);
}
if ($search) {
    $search_param = "%$search%";
    $count_stmt->bindParam(':search', $search_param);
}
$count_stmt->execute();
$total_products = $count_stmt->fetchColumn();
$total_pages = ceil($total_products / $per_page);

// Get products with filters and pagination
$query = "SELECT p.*, 
          c.category_name, 
          b.brand_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          LEFT JOIN brands b ON p.brand_id = b.brand_id 
          WHERE 1=1";
if ($category_filter) {
    $query .= " AND p.category_id = :category";
}
if ($brand_filter) {
    $query .= " AND p.brand_id = :brand";
}
if ($status_filter !== '') {
    $query .= " AND p.is_active = :status";
}
if ($search) {
    $query .= " AND (p.product_name LIKE :search OR p.sku LIKE :search OR p.description LIKE :search)";
}
$query .= " ORDER BY p.created_at DESC LIMIT :per_page OFFSET :offset";

$stmt = $conn->prepare($query);
if ($category_filter) {
    $stmt->bindParam(':category', $category_filter);
}
if ($brand_filter) {
    $stmt->bindParam(':brand', $brand_filter);
}
if ($status_filter !== '') {
    $stmt->bindParam(':status', $status_filter);
}
if ($search) {
    $stmt->bindParam(':search', $search_param);
}
$stmt->bindValue(':per_page', (int)$per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories for filter
$categories_query = "SELECT * FROM categories WHERE is_active = 1 ORDER BY category_name";
$categories = $conn->query($categories_query)->fetchAll(PDO::FETCH_ASSOC);

// Get brands for filter
$brands_query = "SELECT * FROM brands WHERE is_active = 1 ORDER BY brand_name";
$brands = $conn->query($brands_query)->fetchAll(PDO::FETCH_ASSOC);

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
        <h1 class="text-2xl font-bold text-gray-900">Products Management</h1>
        <a href="product-edit.php" class="bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-[#4f0a4f]">
            Add New Product
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search products..." 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
            </div>
            
            <div>
                <select name="category" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= $category_filter == $cat['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <select name="brand" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                    <option value="">All Brands</option>
                    <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand['brand_id'] ?>" <?= $brand_filter == $brand['brand_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand['brand_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
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
                <a href="products.php" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table - Desktop -->
    <div class="hidden lg:block bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        No products found
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="px-6 py-4">
                        <div class="w-16 h-16 rounded border border-gray-100 bg-gray-50 flex items-center justify-center p-0.5">
                            <img src="../assets/images/products/<?= htmlspecialchars($product['main_image']) ?>" 
                                 alt="<?= htmlspecialchars($product['product_name']) ?>"
                                 class="max-w-full max-h-full object-contain"
                                 onerror="this.src='../assets/images/default.png'">
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($product['product_name']) ?></div>
                        <div class="text-sm text-gray-500">SKU: <?= htmlspecialchars($product['sku']) ?></div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <?= htmlspecialchars($product['category_name'] ?? 'N/A') ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <?= htmlspecialchars($product['brand_name'] ?? 'N/A') ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">Rs. <?= number_format($product['sale_price'], 2) ?></div>
                        <?php if ($product['price'] > $product['sale_price']): ?>
                        <div class="text-xs text-gray-500 line-through">Rs. <?= number_format($product['price'], 2) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm <?= $product['stock_quantity'] <= 10 ? 'text-red-600 font-bold' : 'text-gray-900' ?>">
                            <?= $product['stock_quantity'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($product['is_active']): ?>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                        <?php else: ?>
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <a href="product-edit.php?id=<?= $product['product_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900">Edit</a>
                            <?php if ($product['is_active']): ?>
                            <a href="products.php?delete=<?= $product['product_id'] ?>" 
                               class="text-red-600 hover:text-red-900"
                               onclick="return confirm('Are you sure you want to deactivate this product?')">Deactivate</a>
                            <?php else: ?>
                            <a href="products.php?activate=<?= $product['product_id'] ?>" 
                               class="text-green-600 hover:text-green-900">Activate</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Products Cards - Mobile -->
    <div class="lg:hidden space-y-4">
        <?php if (empty($products)): ?>
        <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
            No products found
        </div>
        <?php else: ?>
        <?php foreach ($products as $product): ?>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex gap-4">
                <div class="w-20 h-20 rounded border border-gray-100 bg-gray-50 flex items-center justify-center p-1 shrink-0">
                    <img src="../assets/images/products/<?= htmlspecialchars($product['main_image']) ?>" 
                         alt="<?= htmlspecialchars($product['product_name']) ?>"
                         class="max-w-full max-h-full object-contain"
                         onerror="this.src='../assets/images/default.png'">
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($product['product_name']) ?></h3>
                    <div class="text-sm space-y-1">
                        <div class="text-gray-600"><?= htmlspecialchars($product['category_name']) ?> • <?= htmlspecialchars($product['brand_name'] ?? 'N/A') ?></div>
                        <div class="font-semibold text-purple-custom">Rs<?= number_format($product['price'], 2) ?></div>
                        <div class="text-gray-600">Stock: <span class="<?= $product['stock_quantity'] <= 10 ? 'text-red-600 font-semibold' : '' ?>"><?= $product['stock_quantity'] ?></span></div>
                        <span class="inline-block px-2 py-1 text-xs rounded-full <?= $product['is_active'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                            <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2 mt-3 pt-3 border-t">
                <a href="product-edit.php?id=<?= $product['product_id'] ?>" 
                   class="flex-1 text-center bg-purple-custom text-white px-3 py-2 rounded text-sm hover:bg-[#4f0a4f]">
                    Edit
                </a>
                <?php if ($product['is_active']): ?>
                <a href="products.php?deactivate=<?= $product['product_id'] ?>" 
                   class="flex-1 text-center bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700"
                   onclick="return confirm('Deactivate this product?')">
                    Deactivate
                </a>
                <?php else: ?>
                <a href="products.php?activate=<?= $product['product_id'] ?>" 
                   class="flex-1 text-center bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700">
                    Activate
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="mt-4 text-sm text-gray-600">
        Showing <?= count($products) ?> of <?= $total_products ?> product(s)
    </div>

    <!-- Pagination Controls -->
    <?php if ($total_pages > 1): ?>
    <div class="mt-4 flex justify-center gap-2">
        <?php if ($page > 1): ?>
            <a href="<?= htmlspecialchars(preg_replace('/([&?])page=\d+/', '$1', $_SERVER['REQUEST_URI'])) . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') . 'page=' . ($page - 1) ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo; Prev</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="<?= htmlspecialchars(preg_replace('/([&?])page=\d+/', '$1', $_SERVER['REQUEST_URI'])) . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') . 'page=' . $i ?>"
               class="px-3 py-1 rounded <?= $i == $page ? 'bg-purple-custom text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
        <?php if ($page < $total_pages): ?>
            <a href="<?= htmlspecialchars(preg_replace('/([&?])page=\d+/', '$1', $_SERVER['REQUEST_URI'])) . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') . 'page=' . ($page + 1) ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
