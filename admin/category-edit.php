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

$category_id = $_GET['id'] ?? null;
$is_edit = !empty($category_id);

// Get category data if editing
$category = null;
if ($is_edit) {
    $query = "SELECT * FROM categories WHERE category_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $category_id);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$category) {
        $_SESSION['error'] = 'Category not found';
        header('Location: categories.php');
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $image = $category['category_image'] ?? 'default.png';
    $recommendedImageSize = 1200;
    
    // Generate slug from category name
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $category_name)));
    
    // Handle file upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/categories/';
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_name = $_FILES['image_file']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($file_ext, $allowed_types)) {
            $image_info = @getimagesize($file_tmp);
            if (!$image_info) {
                $error = 'Invalid image file';
            } else {
                $img_width = (int)($image_info[0] ?? 0);
                $img_height = (int)($image_info[1] ?? 0);

                // Homepage category photos are circular; square uploads keep framing consistent.
                if ($img_width !== $img_height) {
                    $error = 'Please upload a square image (1:1 ratio) for best category circle display.';
                } elseif ($img_width < $recommendedImageSize || $img_height < $recommendedImageSize) {
                    $error = 'Image is too small. Recommended minimum size is 1200 x 1200 px.';
                } else {
                    $new_filename = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file_name);
                    $upload_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($file_tmp, $upload_path)) {
                        $image = $new_filename;
                    } else {
                        $error = 'Failed to upload image';
                    }
                }
            }
        } else {
            $error = 'Invalid file type. Only JPG, PNG, GIF, WEBP allowed';
        }
    }
    
    if (!isset($error) && $is_edit) {
        // Update existing category
        $update_query = "UPDATE categories SET 
                        category_name = :name,
                        category_slug = :slug,
                        category_description = :description,
                        is_active = :is_active,
                        category_image = :image
                        WHERE category_id = :id";
        
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':name', $category_name);
        $update_stmt->bindParam(':slug', $slug);
        $update_stmt->bindParam(':description', $description);
        $update_stmt->bindParam(':is_active', $is_active);
        $update_stmt->bindParam(':image', $image);
        $update_stmt->bindParam(':id', $category_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['success'] = 'Category updated successfully';
            header('Location: categories.php');
            exit;
        } else {
            $error = 'Failed to update category';
        }
    } elseif (!isset($error)) {
        // Insert new category
        $insert_query = "INSERT INTO categories 
                        (category_name, category_slug, category_description, is_active, category_image, created_at) 
                        VALUES 
                        (:name, :slug, :description, :is_active, :image, NOW())";
        
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bindParam(':name', $category_name);
        $insert_stmt->bindParam(':slug', $slug);
        $insert_stmt->bindParam(':description', $description);
        $insert_stmt->bindParam(':is_active', $is_active);
        $insert_stmt->bindParam(':image', $image);
        
        if ($insert_stmt->execute()) {
            $_SESSION['success'] = 'Category added successfully';
            header('Location: categories.php');
            exit;
        } else {
            $error = 'Failed to add category';
        }
    }
}

include 'includes/header.php';
?>

<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="categories.php" class="text-purple-custom hover:underline">&larr; Back to Categories</a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">
                <?= $is_edit ? 'Edit Category' : 'Add New Category' ?>
            </h1>

            <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6" enctype="multipart/form-data">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                    <input type="text" name="category_name" 
                           value="<?= htmlspecialchars($category['category_name'] ?? '') ?>"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                    <input type="file" name="image_file" accept="image/*"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                    <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, GIF, WEBP (Max 5MB)</p>
                    <p class="text-xs text-gray-500 mt-1">Recommended: square image 1200 x 1200 px (required ratio: 1:1)</p>
                </div>

                <?php if ($is_edit && $category['category_image']): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <img src="../assets/images/categories/<?= htmlspecialchars($category['category_image']) ?>" 
                         alt="<?= htmlspecialchars($category['category_name']) ?>"
                         class="w-48 h-32 object-cover rounded border"
                         onerror="this.src='../assets/images/default.png'">
                </div>
                <?php endif; ?>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" 
                           class="mr-2 w-4 h-4 text-purple-custom focus:ring-purple-custom"
                           <?= ($category['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <label for="is_active" class="text-sm text-gray-700">Active</label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-purple-custom text-white px-6 py-2 rounded-lg hover:bg-[#4f0a4f]">
                        <?= $is_edit ? 'Update Category' : 'Add Category' ?>
                    </button>
                    <a href="categories.php" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
