<?php
require_once 'includes/helpers.php';
require_once 'classes/User.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $profile_photo_name = null;
    
    // Handle profile photo upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['profile_photo']['type'], $allowed_types)) {
            $error = 'Invalid file type. Only JPG, PNG and GIF allowed';
        } elseif ($_FILES['profile_photo']['size'] > $max_size) {
            $error = 'File too large. Maximum size is 5MB';
        } else {
            $upload_dir = 'assets/images/profiles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $profile_photo_name = 'user_' . time() . '_' . uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $profile_photo_name;
            
            if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                $error = 'Failed to upload profile photo';
                $profile_photo_name = null;
            }
        }
    }
    
    // Validation
    if (empty($error) && (empty($first_name) || empty($last_name) || empty($email) || empty($password))) {
        $error = 'Please fill in all required fields';
    } elseif (empty($error) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (empty($error) && strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif (empty($error) && $password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (empty($error)) {
        $user = new User();
        
        // Check if email already exists
        if ($user->emailExists($email)) {
            $error = 'Email address is already registered';
        } else {
            $user_id = $user->register($email, $password, $first_name, $last_name, $phone, $profile_photo_name);
            
            if ($user_id) {
                $success = 'Registration successful! You can now login.';
                // Auto-login after registration
                $userData = $user->getById($user_id);
                setUserSession($userData);
                
                // Merge cart
                require_once 'classes/Cart.php';
                $cart = new Cart();
                $cart->mergeCart($user_id, getSessionId());
                
                // Redirect after 2 seconds
                header('Refresh: 2; url=index.php');
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | GeoTrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #8D4887; }
        .bg-purple-custom { background-color: #8D4887; }
        .hover\:bg-purple-custom:hover { background-color: #8D4887; }
    </style>
</head>
<body class="bg-gray-50">

    <?php include 'includes/header.php'; ?>

    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-2">Create Account</h2>
                <p class="text-gray-600 text-center mb-8">Join GeoTrans today</p>

                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">First Name *</label>
                            <input type="text" name="first_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-custom"
                                   placeholder="John">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Last Name *</label>
                            <input type="text" name="last_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-custom"
                                   placeholder="Doe">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Email Address *</label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-custom"
                               placeholder="your@email.com">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                        <input type="tel" name="phone" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-custom"
                               placeholder="+94 77 123 4567">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Profile Photo (Optional)</label>
                        <input type="file" name="profile_photo" accept="image/*" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-custom">
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF. Max 5MB</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Password *</label>
                        <input type="password" name="password" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-custom"
                               placeholder="Minimum 6 characters">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Confirm Password *</label>
                        <input type="password" name="confirm_password" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-custom"
                               placeholder="Re-enter password">
                    </div>

                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" required class="mt-1 mr-2">
                            <span class="text-sm text-gray-600">
                                I agree to the <a href="#" class="text-purple-custom hover:underline">Terms & Conditions</a> 
                                and <a href="#" class="text-purple-custom hover:underline">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full bg-purple-custom hover:bg-purple-700 text-white py-3 rounded-lg font-semibold transition">
                        Create Account
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-600">Already have an account? 
                        <a href="login.php" class="text-purple-custom hover:underline font-semibold">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
