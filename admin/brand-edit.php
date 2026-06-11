<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';
require_once '../includes/helpers.php';

$database = new Database();
$conn = $database->getConnection();

// Get brand ID if editing
$brand_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$brand = null;

// Fetch brand data if editing
if ($brand_id) {
    $query = "SELECT * FROM brands WHERE brand_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $brand_id);
    $stmt->execute();
    $brand = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$brand) {
        setFlashMessage('error', 'Brand not found');
        header('Location: brands.php');
        exit;
    }
}

// Initialize variables
$brand_name = $brand['brand_name'] ?? '';
$brand_slug = $brand['brand_slug'] ?? '';
$brand_description = $brand['brand_description'] ?? '';
$is_active = $brand['is_active'] ?? 1;
$current_logo = $brand['brand_logo'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = trim($_POST['brand_name']);
    $brand_slug = trim($_POST['brand_slug']);
    $brand_description = trim($_POST['brand_description']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $errors = [];
    
    // Validation
    if (empty($brand_name)) {
        $errors[] = 'Brand name is required';
    }
    
    if (empty($brand_slug)) {
        $brand_slug = generateSlug($brand_name);
    }
    
    // Handle logo upload
    $logo_filename = $current_logo;
    if (isset($_FILES['brand_logo']) && $_FILES['brand_logo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['brand_logo']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = 'Logo must be an image (JPEG, PNG, GIF, or WebP)';
        } else {
            $upload_dir = '../assets/images/brands/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $extension = pathinfo($_FILES['brand_logo']['name'], PATHINFO_EXTENSION);
            $logo_filename = generateSlug($brand_name) . '-' . time() . '.' . $extension;
            $upload_path = $upload_dir . $logo_filename;
            
            if (!move_uploaded_file($_FILES['brand_logo']['tmp_name'], $upload_path)) {
                $errors[] = 'Failed to upload logo';
            } else {
                // Delete old logo if exists
                if ($current_logo && file_exists($upload_dir . $current_logo)) {
                    unlink($upload_dir . $current_logo);
                }
            }
        }
    }
    
    if (empty($errors)) {
        try {
            if ($brand_id) {
                // Update existing brand
                $query = "UPDATE brands SET 
                          brand_name = :name,
                          brand_slug = :slug,
                          brand_description = :description,
                          brand_logo = :logo,
                          is_active = :is_active,
                          updated_at = NOW()
                          WHERE brand_id = :id";
                
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id', $brand_id);
            } else {
                // Insert new brand
                $query = "INSERT INTO brands 
                          (brand_name, brand_slug, brand_description, brand_logo, is_active)
                          VALUES 
                          (:name, :slug, :description, :logo, :is_active)";
                
                $stmt = $conn->prepare($query);
            }
            
            $stmt->bindParam(':name', $brand_name);
            $stmt->bindParam(':slug', $brand_slug);
            $stmt->bindParam(':description', $brand_description);
            $stmt->bindParam(':logo', $logo_filename);
            $stmt->bindParam(':is_active', $is_active, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $message = $brand_id ? 'Brand updated successfully' : 'Brand created successfully';
                setFlashMessage('success', $message);
                header('Location: brands.php');
                exit;
            } else {
                $errors[] = 'Failed to save brand';
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = 'Brand slug already exists';
            } else {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <?= $brand_id ? 'Edit Brand' : 'Add New Brand' ?>
        </h1>
        <a href="brands.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Back to Brands
        </a>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Brand Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Brand Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="brand_name" value="<?= htmlspecialchars($brand_name) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       required>
            </div>

            <!-- Brand Slug -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Brand Slug
                </label>
                <input type="text" name="brand_slug" value="<?= htmlspecialchars($brand_slug) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="auto-generated-from-name">
                <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from name</p>
            </div>

            <!-- Brand Description -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Brand Description
                </label>
                <textarea name="brand_description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Brief description of the brand"><?= htmlspecialchars($brand_description) ?></textarea>
            </div>

            <!-- Logo Upload -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Brand Logo
                </label>
                
                <?php if ($current_logo): ?>
                <div class="mb-4">
                    <img src="../assets/images/brands/<?= htmlspecialchars($current_logo) ?>" 
                         alt="Current Logo" 
                         class="w-32 h-32 object-contain border border-gray-300 rounded p-2 bg-white">
                    <p class="text-sm text-gray-500 mt-1">Current logo</p>
                </div>
                <?php endif; ?>
                
                <input type="file" name="brand_logo" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Recommended: Square image, minimum 200x200px</p>
            </div>

            <!-- Is Active -->
            <div class="lg:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" <?= $is_active ? 'checked' : '' ?>
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">Inactive brands won't be shown on the website</p>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-4 mt-6 pt-6 border-t">
            <a href="brands.php" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i><?= $brand_id ? 'Update Brand' : 'Create Brand' ?>
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
