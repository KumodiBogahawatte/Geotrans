<?php
session_start();
require_once 'includes/helpers.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=submit-feedback.php');
    exit;
}

require_once 'config/database.php';
require_once 'classes/User.php';
require_once 'classes/Testimonial.php';

$database = new Database();
$conn = $database->getConnection();
$user = new User();
$testimonial = new Testimonial();

$user_id = $_SESSION['user_id'];
$userData = $user->getById($user_id);

// Check if user has already submitted feedback
$stmt = $conn->prepare("SELECT * FROM testimonials WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$user_id]);
$existingFeedback = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $feedback_text = trim($_POST['feedback_text']);
    
    if (empty($feedback_text) || $rating < 1 || $rating > 5) {
        $error = 'Please provide valid rating and feedback.';
    } else {
        $data = [
            'user_id' => $user_id,
            'customer_name' => $userData['first_name'] . ' ' . $userData['last_name'],
            'customer_role' => trim($_POST['customer_role']),
            'customer_image' => $userData['profile_photo'] ?? null,
            'rating' => $rating,
            'feedback_text' => $feedback_text,
            'feedback_date' => 'Recently',
            'is_active' => 0, // Inactive until admin approves
            'is_verified' => 0,
            'display_order' => 0
        ];
        
        if ($testimonial->create($data)) {
            setFlashMessage('success', 'Thank you for your feedback! It will be reviewed by our team.');
            header('Location: account/profile.php');
            exit;
        } else {
            $error = 'Failed to submit feedback. Please try again.';
        }
    }
}

$base_url = '/Geotrans/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - GeoTrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
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
<body class="bg-gray-50">
    <?php include 'includes/header.php'; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Share Your Experience</h1>
                <p class="text-gray-600">Help others make informed decisions by sharing your feedback</p>
            </div>

        <?php if (isset($error)): ?>
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($existingFeedback): ?>
            <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg">
                <i class="fas fa-info-circle mr-2"></i>
                You previously submitted feedback on <?= date('M d, Y', strtotime($existingFeedback['created_at'])) ?>.
                <?php if ($existingFeedback['is_active']): ?>
                    <span class="font-semibold">Status: Published</span>
                <?php else: ?>
                    <span class="font-semibold">Status: Pending Review</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Feedback Form -->
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
            <!-- User Info Display -->
            <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                <?php if (!empty($userData['profile_photo'])): ?>
                    <img src="assets/images/profiles/<?= htmlspecialchars($userData['profile_photo']) ?>" 
                         alt="<?= htmlspecialchars($userData['first_name']) ?>"
                         class="w-16 h-16 rounded-full object-cover border-2 border-purple-200">
                <?php else: ?>
                    <div class="w-16 h-16 rounded-full bg-purple-custom text-white flex items-center justify-center text-2xl font-semibold">
                        <?= strtoupper(substr($userData['first_name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        <?= htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']) ?>
                    </h3>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($userData['email']) ?></p>
                </div>
            </div>

            <form method="POST">
                <!-- Role/Title -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Your Role/Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="customer_role" 
                           value="<?= htmlspecialchars($_POST['customer_role'] ?? $existingFeedback['customer_role'] ?? '') ?>"
                           placeholder="e.g., Business Owner, IT Manager, Entrepreneur"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">This will be displayed with your feedback</p>
                </div>

                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Overall Rating <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="<?= $i ?>" required class="hidden peer">
                                <div class="rating-star w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-lg hover:border-purple-custom hover:bg-purple-50 peer-checked:bg-purple-custom peer-checked:border-purple-custom transition-all">
                                    <span class="text-2xl peer-checked:text-white">★</span>
                                    <span class="text-xs ml-1 peer-checked:text-white"><?= $i ?></span>
                                </div>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Feedback Text -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Your Feedback <span class="text-red-500">*</span>
                    </label>
                    <textarea name="feedback_text" 
                              rows="6" 
                              required
                              placeholder="Share your experience with GeoTrans. What did you like? How can we improve?"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom focus:border-transparent"><?= htmlspecialchars($_POST['feedback_text'] ?? '') ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimum 50 characters</p>
                </div>

                <!-- Notice -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex gap-3">
                        <i class="fas fa-info-circle text-purple-custom mt-1"></i>
                        <div class="text-sm text-gray-600">
                            <p class="font-semibold mb-1">Review Process</p>
                            <p>Your feedback will be reviewed by our team before being published on the website. We appreciate honest and constructive feedback.</p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <a href="<?= $base_url ?>account/profile.php" 
                       class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold text-center">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-purple-custom hover:bg-purple-700 text-white rounded-lg font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>

        <!-- Previous Feedback -->
        <?php if ($existingFeedback): ?>
            <div class="mt-8 bg-white rounded-2xl shadow-lg p-6 sm:p-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Previous Feedback</h3>
                <div class="border-l-4 border-purple-custom pl-4">
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= $existingFeedback['rating'] ? '★' : '☆' ?>
                            <?php endfor; ?>
                        </div>
                        <span class="ml-2 text-sm text-gray-500">
                            <?= date('F d, Y', strtotime($existingFeedback['created_at'])) ?>
                        </span>
                    </div>
                    <p class="text-gray-700 leading-relaxed"><?= htmlspecialchars($existingFeedback['feedback_text']) ?></p>
                    <div class="mt-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $existingFeedback['is_active'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                            <?= $existingFeedback['is_active'] ? 'Published' : 'Pending Review' ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
