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

$testimonial_obj = new Testimonial();
$isEdit = isset($_GET['id']);
$testimonial = null;

if ($isEdit) {
    $testimonial = $testimonial_obj->getById($_GET['id']);
    if (!$testimonial) {
        setFlashMessage('error', 'Testimonial not found');
        header('Location: testimonials.php');
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'customer_name' => trim($_POST['customer_name']),
        'customer_role' => trim($_POST['customer_role']),
        'rating' => intval($_POST['rating']),
        'feedback_text' => trim($_POST['feedback_text']),
        'feedback_date' => trim($_POST['feedback_date']),
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'is_verified' => isset($_POST['is_verified']) ? 1 : 0,
        'display_order' => intval($_POST['display_order'])
    ];

    if ($isEdit) {
        if ($testimonial_obj->update($_GET['id'], $data)) {
            setFlashMessage('success', 'Testimonial updated successfully');
            header('Location: testimonials.php');
            exit;
        } else {
            $error = 'Failed to update testimonial';
        }
    } else {
        if ($testimonial_obj->create($data)) {
            setFlashMessage('success', 'Testimonial created successfully');
            header('Location: testimonials.php');
            exit;
        } else {
            $error = 'Failed to create testimonial';
        }
    }
}

$maxOrder = $testimonial_obj->getMaxDisplayOrder();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit' : 'Add' ?> Testimonial - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #8D4887; }
        .bg-purple-custom { background-color: #8D4887; }
        .hover\:bg-purple-custom:hover { background-color: #8D4887; }
        .border-purple-custom { border-color: #8D4887; }
        .focus\:ring-purple-custom:focus { --tw-ring-color: #8D4887; }
    </style>
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center mb-2">
                <a href="testimonials.php" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    <?= $isEdit ? 'Edit' : 'Add New' ?> Testimonial
                </h1>
            </div>
            <p class="text-gray-600">Manage customer feedback displayed on the homepage</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="bg-white rounded-lg shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Customer Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Customer Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="customer_name" 
                           value="<?= htmlspecialchars($testimonial['customer_name'] ?? '') ?>"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent">
                </div>

                <!-- Customer Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Customer Role/Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="customer_role" 
                           value="<?= htmlspecialchars($testimonial['customer_role'] ?? '') ?>"
                           placeholder="e.g., Business Owner, IT Manager"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent">
                </div>
            </div>

            <!-- Feedback Text -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Feedback Text <span class="text-red-500">*</span>
                </label>
                <textarea name="feedback_text" 
                          rows="4" 
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent"><?= htmlspecialchars($testimonial['feedback_text'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rating <span class="text-red-500">*</span>
                    </label>
                    <select name="rating" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>" <?= ($testimonial['rating'] ?? 5) == $i ? 'selected' : '' ?>>
                                <?= $i ?> Star<?= $i > 1 ? 's' : '' ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Feedback Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date Label
                    </label>
                    <input type="text" 
                           name="feedback_date" 
                           value="<?= htmlspecialchars($testimonial['feedback_date'] ?? '1 week ago') ?>"
                           placeholder="e.g., 2 weeks ago"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent">
                </div>

                <!-- Display Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input type="number" 
                           name="display_order" 
                           value="<?= $testimonial['display_order'] ?? ($maxOrder + 1) ?>"
                           min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent">
                </div>
            </div>

            <!-- Active Status and Verified -->
            <div class="mb-6 space-y-3">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           <?= ($testimonial['is_active'] ?? 1) ? 'checked' : '' ?>
                           class="w-4 h-4 text-purple-custom focus:ring-purple-custom border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Active (Display on homepage)</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_verified" 
                           <?= ($testimonial['is_verified'] ?? 0) ? 'checked' : '' ?>
                           class="w-4 h-4 text-purple-custom focus:ring-purple-custom border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Verified Customer (Show verification badge)</span>
                </label>
            </div>

            <!-- Customer Info (if linked to user) -->
            <?php if (!empty($testimonial['user_id'])): ?>
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-user mr-2"></i>
                        <strong>Linked to User ID:</strong> <?= $testimonial['user_id'] ?>
                        <span class="ml-2 text-xs">(Profile photo will be used automatically)</span>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Buttons -->
            <div class="flex justify-end gap-3">
                <a href="testimonials.php" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-purple-custom hover:bg-purple-700 text-white rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>
                    <?= $isEdit ? 'Update' : 'Create' ?> Testimonial
                </button>
            </div>
        </form>
    </div>
</body>
</html>
