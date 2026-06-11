<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';
require_once '../includes/helpers.php';

$database = new Database();
$conn = $database->getConnection();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $review_id = intval($_POST['review_id']);
        
        if ($_POST['action'] === 'approve') {
            $stmt = $conn->prepare("UPDATE product_reviews SET is_approved = 1 WHERE review_id = ?");
            $stmt->execute([$review_id]);
            setFlashMessage('success', 'Review approved successfully');
        } elseif ($_POST['action'] === 'reject') {
            $stmt = $conn->prepare("UPDATE product_reviews SET is_approved = 0 WHERE review_id = ?");
            $stmt->execute([$review_id]);
            setFlashMessage('success', 'Review rejected successfully');
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $conn->prepare("DELETE FROM product_reviews WHERE review_id = ?");
            $stmt->execute([$review_id]);
            setFlashMessage('success', 'Review deleted successfully');
        }
        header('Location: reviews.php');
        exit();
    }
}

// Get filter parameters
$status_filter = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query
$query = "SELECT r.*, p.product_name, p.product_slug, u.first_name, u.last_name, u.email
          FROM product_reviews r
          LEFT JOIN products p ON r.product_id = p.product_id
          LEFT JOIN users u ON r.user_id = u.user_id
          WHERE 1=1";

if ($status_filter === 'pending') {
    $query .= " AND r.is_approved = 0";
} elseif ($status_filter === 'approved') {
    $query .= " AND r.is_approved = 1";
}

if (!empty($search)) {
    $query .= " AND (p.product_name LIKE :search OR u.first_name LIKE :search OR u.last_name LIKE :search OR r.review_text LIKE :search)";
}

$query .= " ORDER BY r.created_at DESC";

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $search_param = "%$search%";
    $stmt->bindParam(':search', $search_param);
}

$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stats_query = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN is_approved = 0 THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN is_approved = 1 THEN 1 ELSE 0 END) as approved
                FROM product_reviews";
$stats = $conn->query($stats_query)->fetch(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Product Reviews</h1>
    </div>

    <?php 
    $flash = getFlashMessage();
    if ($flash): 
    ?>
    <div class="bg-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-100 border border-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-400 text-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-gray-500 text-sm mb-1">Total Reviews</div>
            <div class="text-3xl font-bold text-gray-800"><?= $stats['total'] ?></div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-gray-500 text-sm mb-1">Pending Approval</div>
            <div class="text-3xl font-bold text-yellow-600"><?= $stats['pending'] ?></div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-gray-500 text-sm mb-1">Approved</div>
            <div class="text-3xl font-bold text-green-600"><?= $stats['approved'] ?></div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All Reviews</option>
                <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="approved" <?= $status_filter === 'approved' ? 'selected' : '' ?>>Approved</option>
            </select>
            
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Search by product, customer, or review text..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            
            <a href="reviews.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 text-center">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </form>
    </div>

    <!-- Reviews List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Review</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-star text-4xl mb-4"></i>
                            <p>No reviews found</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900"><?= htmlspecialchars($review['product_name']) ?></div>
                            <div class="text-sm text-gray-500">ID: <?= $review['product_id'] ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900"><?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?></div>
                            <div class="text-sm text-gray-500"><?= htmlspecialchars($review['email']) ?></div>
                            <?php if ($review['is_verified_purchase']): ?>
                            <span class="inline-block mt-1 px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center text-yellow-400">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-sm text-gray-600"><?= $review['rating'] ?>/5</span>
                        </td>
                        <td class="px-6 py-4 max-w-md">
                            <?php if ($review['review_title']): ?>
                            <div class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($review['review_title']) ?></div>
                            <?php endif; ?>
                            <p class="text-sm text-gray-600 line-clamp-3"><?= htmlspecialchars($review['review_text']) ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($review['is_approved']): ?>
                            <span class="inline-block px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                Approved
                            </span>
                            <?php else: ?>
                            <span class="inline-block px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                                Pending
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?= date('M j, Y', strtotime($review['created_at'])) ?><br>
                            <span class="text-xs text-gray-500"><?= date('g:i A', strtotime($review['created_at'])) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-3">
                                <!-- View Button -->
                                <button onclick="viewReview(<?= htmlspecialchars(json_encode($review)) ?>)" 
                                        class="text-blue-600 hover:text-blue-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <?php if (!$review['is_approved']): ?>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Unapprove">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                    <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Review Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Review Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Product</label>
                    <p id="modalProduct" class="text-gray-900 font-semibold"></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Customer</label>
                        <p id="modalCustomer" class="text-gray-900"></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Rating</label>
                        <div id="modalRating" class="flex"></div>
                    </div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-600">Review Title</label>
                    <p id="modalTitle" class="text-gray-900 font-medium"></p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-600">Review Text</label>
                    <p id="modalText" class="text-gray-700 whitespace-pre-wrap"></p>
                </div>
                
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <label class="text-xs font-medium text-gray-600">Status</label>
                        <p id="modalStatus"></p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600">Verified Purchase</label>
                        <p id="modalVerified"></p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600">Date</label>
                        <p id="modalDate" class="text-gray-700"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function viewReview(review) {
        document.getElementById('modalProduct').textContent = review.product_name;
        document.getElementById('modalCustomer').textContent = review.first_name + ' ' + review.last_name;
        document.getElementById('modalTitle').textContent = review.review_title;
        document.getElementById('modalText').textContent = review.review_text;
        
        // Rating stars
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fas fa-star ${i <= review.rating ? 'text-yellow-400' : 'text-gray-300'}"></i>`;
        }
        document.getElementById('modalRating').innerHTML = stars;
        
        // Status badge
        if (review.is_approved == 1) {
            document.getElementById('modalStatus').innerHTML = '<span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Approved</span>';
        } else {
            document.getElementById('modalStatus').innerHTML = '<span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Pending</span>';
        }
        
        // Verified purchase badge
        if (review.is_verified_purchase == 1) {
            document.getElementById('modalVerified').innerHTML = '<span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">Yes</span>';
        } else {
            document.getElementById('modalVerified').innerHTML = '<span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">No</span>';
        }
        
        // Date
        const date = new Date(review.created_at);
        document.getElementById('modalDate').textContent = date.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });
        
        document.getElementById('viewModal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('viewModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    document.getElementById('viewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
