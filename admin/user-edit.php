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

$user_id = $_GET['id'] ?? null;
$is_edit = !empty($user_id);

// Get user data if editing
$user = null;
if ($is_edit) {
    $query = "SELECT * FROM users WHERE user_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        $_SESSION['error'] = 'User not found';
        header('Location: users.php');
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $user_type = $_POST['user_type'] ?? 'customer';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $is_verified = isset($_POST['is_verified']) ? 1 : 0;
    $password = $_POST['password'] ?? '';
    $profile_photo_name = $is_edit ? $user['profile_photo'] : null;
    
    // Handle profile photo upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['profile_photo']['type'], $allowed_types)) {
            $_SESSION['error'] = 'Invalid file type. Only JPG, PNG and GIF allowed';
        } elseif ($_FILES['profile_photo']['size'] > $max_size) {
            $_SESSION['error'] = 'File too large. Maximum size is 5MB';
        } else {
            $upload_dir = '../assets/images/profiles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $profile_photo_name = 'user_' . ($is_edit ? $user_id : time()) . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $profile_photo_name;
            
            if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                $_SESSION['error'] = 'Failed to upload profile photo';
                $profile_photo_name = $is_edit ? $user['profile_photo'] : null;
            } else {
                // Delete old photo if exists
                if ($is_edit && !empty($user['profile_photo']) && file_exists($upload_dir . $user['profile_photo'])) {
                    unlink($upload_dir . $user['profile_photo']);
                }
            }
        }
    }
    
    // Handle photo removal
    if (isset($_POST['remove_photo']) && $_POST['remove_photo'] === '1' && $is_edit) {
        if (!empty($user['profile_photo']) && file_exists('../assets/images/profiles/' . $user['profile_photo'])) {
            unlink('../assets/images/profiles/' . $user['profile_photo']);
        }
        $profile_photo_name = null;
    }
    
    if ($is_edit) {
        // Update existing user
        $update_query = "UPDATE users SET 
                        email = :email,
                        first_name = :first_name,
                        last_name = :last_name,
                        phone = :phone,
                        profile_photo = :profile_photo,
                        user_type = :user_type,
                        is_active = :is_active,
                        is_verified = :is_verified";
        
        // Only update password if provided
        if (!empty($password)) {
            $update_query .= ", password_hash = :password";
        }
        
        $update_query .= " WHERE user_id = :id";
        
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':first_name', $first_name);
        $update_stmt->bindParam(':last_name', $last_name);
        $update_stmt->bindParam(':phone', $phone);
        $update_stmt->bindParam(':profile_photo', $profile_photo_name);
        $update_stmt->bindParam(':user_type', $user_type);
        $update_stmt->bindParam(':is_active', $is_active);
        $update_stmt->bindParam(':is_verified', $is_verified);
        
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $update_stmt->bindParam(':password', $password_hash);
        }
        
        $update_stmt->bindParam(':id', $user_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['success'] = 'User updated successfully';
            header('Location: users.php');
            exit;
        } else {
            $error = 'Failed to update user';
        }
    } else {
        // Insert new user
        if (empty($password)) {
            $error = 'Password is required for new users';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_query = "INSERT INTO users 
                            (email, password_hash, first_name, last_name, phone, profile_photo, user_type, is_active, is_verified, created_at) 
                            VALUES 
                            (:email, :password_hash, :first_name, :last_name, :phone, :profile_photo, :user_type, :is_active, :is_verified, NOW())";
            
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bindParam(':email', $email);
            $insert_stmt->bindParam(':password_hash', $password_hash);
            $insert_stmt->bindParam(':first_name', $first_name);
            $insert_stmt->bindParam(':last_name', $last_name);
            $insert_stmt->bindParam(':phone', $phone);
            $insert_stmt->bindParam(':profile_photo', $profile_photo_name);
            $insert_stmt->bindParam(':user_type', $user_type);
            $insert_stmt->bindParam(':is_active', $is_active);
            $insert_stmt->bindParam(':is_verified', $is_verified);
            
            if ($insert_stmt->execute()) {
                $_SESSION['success'] = 'User added successfully';
                header('Location: users.php');
                exit;
            } else {
                $error = 'Failed to add user. Email may already exist.';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="users.php" class="text-purple-custom hover:underline">&larr; Back to Users</a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">
                <?= $is_edit ? 'Edit User' : 'Add New User' ?>
            </h1>

            <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Profile Photo -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <?php if ($is_edit && !empty($user['profile_photo']) && file_exists('../assets/images/profiles/' . $user['profile_photo'])): ?>
                                <img id="profile-preview" src="../assets/images/profiles/<?= htmlspecialchars($user['profile_photo']) ?>" 
                                     alt="Profile" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-purple-200">
                            <?php else: ?>
                                <div id="profile-preview" class="w-24 h-24 rounded-full flex items-center justify-center text-white font-bold text-3xl border-4 border-purple-200" style="background-color: #8D4887;">
                                    <?= $is_edit ? strtoupper(substr($user['first_name'], 0, 1)) : '?' ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" 
                                   class="hidden" onchange="previewPhoto(this)">
                            <label for="profile_photo" 
                                   class="cursor-pointer bg-purple-custom text-white px-4 py-2 rounded-lg hover:bg-purple-700 inline-block">
                                <i class="fas fa-camera mr-2"></i><?= $is_edit ? 'Change Photo' : 'Upload Photo' ?>
                            </label>
                            <?php if ($is_edit && !empty($user['profile_photo'])): ?>
                                <button type="button" onclick="removePhoto()" 
                                        class="ml-2 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                    <i class="fas fa-trash mr-2"></i>Remove
                                </button>
                                <input type="hidden" name="remove_photo" id="remove_photo" value="0">
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF. Max 5MB</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" 
                               value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" 
                               value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"
                               required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" 
                           value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" 
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password <?= $is_edit ? '(leave blank to keep current)' : '*' ?>
                    </label>
                    <input type="password" name="password" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"
                           <?= $is_edit ? '' : 'required' ?>>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">User Type *</label>
                    <select name="user_type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom" required>
                        <option value="customer" <?= ($user['user_type'] ?? 'customer') == 'customer' ? 'selected' : '' ?>>Customer</option>
                        <option value="admin" <?= ($user['user_type'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="superadmin" <?= ($user['user_type'] ?? '') == 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                    </select>
                </div>

                <div class="flex gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" 
                               class="mr-2 w-4 h-4 text-purple-custom focus:ring-purple-custom"
                               <?= ($user['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label for="is_active" class="text-sm text-gray-700">Active</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_verified" id="is_verified" 
                               class="mr-2 w-4 h-4 text-purple-custom focus:ring-purple-custom"
                               <?= ($user['is_verified'] ?? 0) ? 'checked' : '' ?>>
                        <label for="is_verified" class="text-sm text-gray-700">Verified</label>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-purple-custom text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                        <?= $is_edit ? 'Update User' : 'Add User' ?>
                    </button>
                    <a href="users.php" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-preview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.id = 'profile-preview';
                img.src = e.target.result;
                img.className = 'w-24 h-24 rounded-full object-cover border-4 border-purple-200';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePhoto() {
    if (confirm('Are you sure you want to remove the profile photo?')) {
        document.getElementById('remove_photo').value = '1';
        const preview = document.getElementById('profile-preview');
        if (preview.tagName === 'IMG') {
            const div = document.createElement('div');
            div.id = 'profile-preview';
            div.className = 'w-24 h-24 rounded-full flex items-center justify-center text-white font-bold text-3xl border-4 border-purple-200';
            div.style.backgroundColor = '#8D4887';
            div.textContent = '?';
            preview.parentNode.replaceChild(div, preview);
        }
        // Hide the remove button
        event.target.style.display = 'none';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
