<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../config/database.php';
require_once '../classes/User.php';

$user = new User();
$user_data = $user->getById($_SESSION['user_id']);

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($first_name)) {
        $errors[] = 'First name is required';
    }
    if (empty($last_name)) {
        $errors[] = 'Last name is required';
    }
    if (empty($email)) {
        $errors[] = 'Email is required';
    }
    
    // Check if password change requested
    if (!empty($current_password) || !empty($new_password)) {
        // Get user data with password hash for verification
        $user_with_password = $user->getByIdWithPassword($_SESSION['user_id']);
        
        if (empty($current_password)) {
            $errors[] = 'Current password is required';
        } elseif (!password_verify($current_password, $user_with_password['password_hash'])) {
            $errors[] = 'Current password is incorrect';
        }
        
        if (empty($new_password)) {
            $errors[] = 'New password is required';
        } elseif (strlen($new_password) < 6) {
            $errors[] = 'New password must be at least 6 characters';
        }
        
        if ($new_password !== $confirm_password) {
            $errors[] = 'Passwords do not match';
        }
    }
    
    if (empty($errors)) {
        $database = new Database();
        $conn = $database->getConnection();
        
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, password_hash = :password WHERE user_id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':password', $hashed_password);
        } else {
            $query = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email WHERE user_id = :id";
            $stmt = $conn->prepare($query);
        }
        
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $success = 'Profile updated successfully';
            $user_data = $user->getById($_SESSION['user_id']);
        } else {
            $errors[] = 'Failed to update profile';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | GeoTrans</title>
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
                            <a href="profile.php" class="block px-4 py-2 rounded bg-purple-custom text-white">
                                Profile
                            </a>
                            <a href="orders.php" class="block px-4 py-2 rounded hover:bg-gray-100">
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
                
                <!-- Profile Form -->
                <div class="md:col-span-3">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Profile Information</h2>
                        
                        <?php if ($success): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <?= htmlspecialchars($success) ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($errors)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" name="first_name" 
                                       value="<?= htmlspecialchars($user_data['first_name']) ?>"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom"
                                       required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" name="last_name" 
                                       value="<?= htmlspecialchars($user_data['last_name']) ?>"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom"
                                       required>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" 
                                       value="<?= htmlspecialchars($user_data['email']) ?>"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom"
                                       required>
                            </div>
                            
                            <hr class="my-6">
                            
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Change Password</h3>
                            <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password</p>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" name="current_password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" name="new_password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom">
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" name="confirm_password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom">
                            </div>
                            
                            <button type="submit" 
                                    class="bg-purple-custom text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700">
                                Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>

</body>
</html>