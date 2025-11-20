<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';
require_once '../classes/Testimonial.php';
require_once '../includes/helpers.php';

$testimonial = new Testimonial();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $testimonial_id = intval($_POST['testimonial_id']);
        
        if ($_POST['action'] === 'toggle_status') {
            $testimonial->toggleStatus($testimonial_id);
            setFlashMessage('success', 'Testimonial status updated successfully');
        } elseif ($_POST['action'] === 'delete') {
            $testimonial->delete($testimonial_id);
            setFlashMessage('success', 'Testimonial deleted successfully');
        }
        header('Location: testimonials.php');
        exit();
    }
}

// Get filter parameters
$status_filter = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';

// Get testimonials
$testimonials = $testimonial->getAll($status_filter, $search);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Testimonials - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #8D4887; }
        .bg-purple-custom { background-color: #8D4887; }
        .hover\:bg-purple-custom:hover { background-color: #8D4887; }
        .border-purple-custom { border-color: #8D4887; }
    </style>
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Customer Testimonials</h1>
                <p class="text-gray-600 mt-1">Manage customer feedback and reviews</p>
            </div>
            <a href="testimonial-edit.php" class="mt-4 sm:mt-0 bg-purple-custom hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add New Testimonial
            </a>
        </div>

        <?php 
        $flashMessage = getFlashMessage();
        if ($flashMessage): 
        ?>
            <div class="mb-6 bg-<?= $flashMessage['type'] === 'success' ? 'green' : 'red' ?>-100 border border-<?= $flashMessage['type'] === 'success' ? 'green' : 'red' ?>-400 text-<?= $flashMessage['type'] === 'success' ? 'green' : 'red' ?>-700 px-4 py-3 rounded">
                <?= htmlspecialchars($flashMessage['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="<?= htmlspecialchars($search) ?>"
                           placeholder="Search by name, role, or feedback..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent">
                </div>
                <div>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent">
                        <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All Status</option>
                        <option value="active" <?= $status_filter === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $status_filter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </form>
        </div>

        <!-- Testimonials List -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($testimonials)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-comments text-4xl mb-4 text-gray-300"></i>
                                <p>No testimonials found</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($testimonials as $t): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= $t['display_order'] ?>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <?php if (!empty($t['customer_image'])): ?>
                                            <img src="../assets/images/testimonials/<?= htmlspecialchars($t['customer_image']) ?>" 
                                                 alt="<?= htmlspecialchars($t['customer_name']) ?>"
                                                 class="w-10 h-10 rounded-full object-cover mr-3">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-purple-custom text-white flex items-center justify-center mr-3 font-semibold">
                                                <?= strtoupper(substr($t['customer_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($t['customer_name']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($t['customer_role']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex text-yellow-400 text-sm">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?= $i <= $t['rating'] ? '★' : '☆' ?>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $t['is_active'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= $t['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $t['feedback_date'] ?? 'N/A' ?>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="viewFeedback(<?= $t['testimonial_id'] ?>)" 
                                            class="text-purple-600 hover:text-purple-900 mr-3" 
                                            title="View Feedback">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="testimonial-edit.php?id=<?= $t['testimonial_id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900 mr-3"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" class="inline" onsubmit="return confirm('Toggle status?')">
                                        <input type="hidden" name="testimonial_id" value="<?= $t['testimonial_id'] ?>">
                                        <input type="hidden" name="action" value="toggle_status">
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3" title="<?= $t['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                            <i class="fas fa-toggle-<?= $t['is_active'] ? 'on' : 'off' ?>"></i>
                                        </button>
                                    </form>
                                    <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
                                        <input type="hidden" name="testimonial_id" value="<?= $t['testimonial_id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for viewing feedback -->
    <div id="feedbackModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Customer Feedback</h3>
                <button onclick="closeFeedbackModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="feedbackContent" class="mt-4">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        const testimonials = <?= json_encode($testimonials) ?>;

        function viewFeedback(id) {
            const testimonial = testimonials.find(t => t.testimonial_id == id);
            if (!testimonial) return;

            const content = `
                <div class="space-y-4">
                    <div class="flex items-center gap-4 pb-4 border-b">
                        ${testimonial.customer_image 
                            ? `<img src="../assets/images/testimonials/${testimonial.customer_image}" 
                                   alt="${testimonial.customer_name}" 
                                   class="w-16 h-16 rounded-full object-cover">`
                            : `<div class="w-16 h-16 rounded-full bg-purple-custom text-white flex items-center justify-center text-2xl font-semibold">
                                   ${testimonial.customer_name.charAt(0).toUpperCase()}
                               </div>`
                        }
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">${testimonial.customer_name}</h4>
                            <p class="text-sm text-gray-600">${testimonial.customer_role}</p>
                            <div class="flex text-yellow-400 mt-1">
                                ${'★'.repeat(testimonial.rating)}${'☆'.repeat(5 - testimonial.rating)}
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-700 leading-relaxed">${testimonial.feedback_text}</p>
                    </div>
                    <div class="pt-4 border-t text-sm text-gray-500">
                        <p><strong>Date:</strong> ${testimonial.feedback_date || 'N/A'}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 rounded-full ${testimonial.is_active == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">${testimonial.is_active == 1 ? 'Active' : 'Inactive'}</span></p>
                        <p><strong>Display Order:</strong> #${testimonial.display_order}</p>
                    </div>
                </div>
            `;

            document.getElementById('feedbackContent').innerHTML = content;
            document.getElementById('feedbackModal').classList.remove('hidden');
        }

        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('feedbackModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeFeedbackModal();
            }
        });
    </script>
</body>
</html>
