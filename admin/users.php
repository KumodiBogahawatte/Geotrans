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

// Handle deactivate
if (isset($_GET['deactivate'])) {
    $user_id = $_GET['deactivate'];
    if ($user_id != $_SESSION['admin_id']) { // Prevent self-deactivation
        $deactivate_query = "UPDATE users SET is_active = 0 WHERE user_id = :id";
        $deactivate_stmt = $conn->prepare($deactivate_query);
        $deactivate_stmt->bindParam(':id', $user_id);
        if ($deactivate_stmt->execute()) {
            $_SESSION['success'] = 'User deactivated successfully';
        }
    } else {
        $_SESSION['error'] = 'You cannot deactivate your own account';
    }
    header('Location: users.php');
    exit;
}

// Handle activate
if (isset($_GET['activate'])) {
    $user_id = $_GET['activate'];
    $activate_query = "UPDATE users SET is_active = 1 WHERE user_id = :id";
    $activate_stmt = $conn->prepare($activate_query);
    $activate_stmt->bindParam(':id', $user_id);
    if ($activate_stmt->execute()) {
        $_SESSION['success'] = 'User activated successfully';
    }
    header('Location: users.php');
    exit;
}

// Get filters
$type_filter = $_GET['type'] ?? '';
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Get users with filters
$query = "SELECT u.*, 
          COUNT(DISTINCT o.order_id) as order_count,
          COALESCE(SUM(o.total_amount), 0) as total_spent
          FROM users u 
          LEFT JOIN orders o ON u.user_id = o.user_id
          WHERE 1=1";

if ($type_filter) {
    $query .= " AND u.user_type = :type";
}
if ($status_filter !== '') {
    $query .= " AND u.is_active = :status";
}
if ($search) {
    $query .= " AND (CONCAT(u.first_name, ' ', u.last_name) LIKE :search OR u.email LIKE :search OR u.phone LIKE :search)";
}

$query .= " GROUP BY u.user_id ORDER BY u.created_at DESC LIMIT 100";

$stmt = $conn->prepare($query);

if ($type_filter) {
    $stmt->bindParam(':type', $type_filter);
}
if ($status_filter !== '') {
    $stmt->bindParam(':status', $status_filter);
}
if ($search) {
    $search_param = "%$search%";
    $stmt->bindParam(':search', $search_param);
}

$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="p-6">
    <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <?php unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
        <a href="user-edit.php" class="bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            Add New User
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search users..." 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
            </div>
            
            <div>
                <select name="type" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                    <option value="">All Types</option>
                    <option value="customer" <?= $type_filter == 'customer' ? 'selected' : '' ?>>Customer</option>
                    <option value="admin" <?= $type_filter == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="superadmin" <?= $type_filter == 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
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
                <button type="submit" class="flex-1 bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    Filter
                </button>
                <a href="users.php" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table - Desktop -->
    <div class="hidden lg:block bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        No users found
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                        </div>
                        <div class="text-sm text-gray-500">ID: <?= $user['user_id'] ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900"><?= htmlspecialchars($user['email']) ?></div>
                        <?php if ($user['phone']): ?>
                        <div class="text-sm text-gray-500"><?= htmlspecialchars($user['phone']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php
                        $type_colors = [
                            'customer' => 'bg-blue-100 text-blue-800',
                            'admin' => 'bg-purple-100 text-purple-800',
                            'superadmin' => 'bg-red-100 text-red-800'
                        ];
                        $color = $type_colors[$user['user_type']] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <span class="px-2 py-1 text-xs rounded-full <?= $color ?>">
                            <?= ucfirst($user['user_type']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <?= $user['order_count'] ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        Rs. <?= number_format($user['total_spent'], 2) ?>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <?php if ($user['is_active']): ?>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                            <?php else: ?>
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($user['is_verified']): ?>
                        <div class="mt-1">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Verified</span>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= date('M d, Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex flex-col gap-2">
                            <a href="user-view.php?id=<?= $user['user_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="user-edit.php?id=<?= $user['user_id'] ?>" 
                               class="text-purple-600 hover:text-purple-900">Edit</a>
                            <?php if ($user['user_id'] != $_SESSION['admin_id']): ?>
                                <?php if ($user['is_active']): ?>
                                <a href="users.php?deactivate=<?= $user['user_id'] ?>" 
                                   class="text-red-600 hover:text-red-900"
                                   onclick="return confirm('Are you sure you want to deactivate this user?')">Deactivate</a>
                                <?php else: ?>
                                <a href="users.php?activate=<?= $user['user_id'] ?>" 
                                   class="text-green-600 hover:text-green-900">Activate</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Users Cards - Mobile -->
    <div class="lg:hidden space-y-4">
        <?php if (empty($users)): ?>
        <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
            No users found
        </div>
        <?php else: ?>
        <?php foreach ($users as $user): ?>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h3>
                    <div class="text-sm text-gray-600"><?= htmlspecialchars($user['email']) ?></div>
                    <?php if ($user['phone']): ?>
                    <div class="text-sm text-gray-600"><?= htmlspecialchars($user['phone']) ?></div>
                    <?php endif; ?>
                </div>
                <span class="px-2 py-1 text-xs rounded-full <?= $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                    <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
            </div>
            
            <div class="space-y-1 text-sm mb-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Type:</span>
                    <span class="font-medium capitalize"><?= htmlspecialchars($user['user_type']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Orders:</span>
                    <span><?= $user['total_orders'] ?? 0 ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Spent:</span>
                    <span class="font-semibold">Rs<?= number_format($user['total_spent'] ?? 0, 2) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Joined:</span>
                    <span><?= date('M j, Y', strtotime($user['created_at'])) ?></span>
                </div>
            </div>
            
            <div class="flex gap-2 pt-3 border-t">
                <a href="user-view.php?id=<?= $user['user_id'] ?>" 
                   class="flex-1 text-center bg-purple-custom text-white px-3 py-2 rounded text-sm hover:bg-purple-700">
                    View
                </a>
                <a href="user-edit.php?id=<?= $user['user_id'] ?>" 
                   class="flex-1 text-center bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">
                    Edit
                </a>
                <?php if ($user['is_active']): ?>
                <a href="users.php?deactivate=<?= $user['user_id'] ?>" 
                   class="flex-1 text-center bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700"
                   onclick="return confirm('Deactivate this user?')">
                    Deactivate
                </a>
                <?php else: ?>
                <a href="users.php?activate=<?= $user['user_id'] ?>" 
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
        Showing <?= count($users) ?> user(s)
    </div>
</div>

<?php include 'includes/footer.php'; ?>
