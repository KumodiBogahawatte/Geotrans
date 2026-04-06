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

$product_id = $_GET['id'] ?? null;
$is_edit = !empty($product_id);

// Get product data if editing
$product = null;
$specifications = [];
$gallery_images = [];
if ($is_edit) {
    $query = "SELECT * FROM products WHERE product_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        $_SESSION['error'] = 'Product not found';
        header('Location: products.php');
        exit;
    }
    
    // Get existing specifications
    $spec_query = "SELECT * FROM product_specifications WHERE product_id = :id ORDER BY display_order";
    $spec_stmt = $conn->prepare($spec_query);
    $spec_stmt->bindParam(':id', $product_id);
    $spec_stmt->execute();
    $specifications = $spec_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get existing gallery images
    $gallery_query = "SELECT * FROM product_images WHERE product_id = :id ORDER BY display_order, image_id";
    $gallery_stmt = $conn->prepare($gallery_query);
    $gallery_stmt->bindParam(':id', $product_id);
    $gallery_stmt->execute();
    $gallery_images = $gallery_stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $sku = $_POST['sku'] ?? '';
    $category_id = $_POST['category_id'] ?? null;
    $brand_id = $_POST['brand_id'] ?? null;
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $sale_price = (isset($_POST['sale_price']) && $_POST['sale_price'] !== '') ? $_POST['sale_price'] : null;
    $stock_quantity = $_POST['stock_quantity'] ?? 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    $main_image = $product['main_image'] ?? 'default.png'; // Keep existing or default
    $product_pdf = $product['product_pdf'] ?? null; // Keep existing PDF if no new upload
    
    // Generate slug from product name
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product_name)));
    
    // Handle file upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/products/';
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_name = $_FILES['image_file']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($file_ext, $allowed_types)) {
            // Generate unique filename
            $new_filename = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file_name);
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $main_image = $new_filename;
            } else {
                $error = 'Failed to upload image';
            }
        } else {
            $error = 'Invalid file type. Only JPG, PNG, GIF, WEBP allowed';
        }
    }

    // Handle PDF upload
    if (!isset($error) && isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $pdf_upload_dir = '../assets/files/products/';
        if (!is_dir($pdf_upload_dir)) {
            mkdir($pdf_upload_dir, 0755, true);
        }

        $pdf_tmp = $_FILES['pdf_file']['tmp_name'];
        $pdf_name = $_FILES['pdf_file']['name'];
        $pdf_ext = strtolower(pathinfo($pdf_name, PATHINFO_EXTENSION));

        if ($pdf_ext === 'pdf') {
            $pdf_filename = uniqid('pdf-') . '-' . preg_replace('/[^a-zA-Z0-9._-]/', '', $pdf_name);
            $pdf_path = $pdf_upload_dir . $pdf_filename;

            if (move_uploaded_file($pdf_tmp, $pdf_path)) {
                $product_pdf = $pdf_filename;
            } else {
                $error = 'Failed to upload PDF file';
            }
        } else {
            $error = 'Invalid PDF file. Only .pdf is allowed';
        }
    }
    
    if (!isset($error) && $is_edit) {
        // Update existing product
        $update_query = "UPDATE products SET 
                        product_name = :name,
                        product_slug = :slug,
                        sku = :sku,
                        category_id = :category_id,
                        brand_id = :brand_id,
                        description = :description,
                        price = :price,
                        sale_price = :sale_price,
                        stock_quantity = :stock_quantity,
                        is_active = :is_active,
                        is_featured = :is_featured,
                        is_bestseller = :is_bestseller,
                        is_new_arrival = :is_new_arrival,
                        main_image = :main_image,
                        product_pdf = :product_pdf
                        WHERE product_id = :id";
        
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':name', $product_name);
        $update_stmt->bindParam(':slug', $slug);
        $update_stmt->bindParam(':sku', $sku);
        $update_stmt->bindParam(':category_id', $category_id);
        $update_stmt->bindParam(':brand_id', $brand_id);
        $update_stmt->bindParam(':description', $description);
        $update_stmt->bindParam(':price', $price);
        $update_stmt->bindParam(':sale_price', $sale_price);
        $update_stmt->bindParam(':stock_quantity', $stock_quantity);
        $update_stmt->bindParam(':is_active', $is_active);
        $update_stmt->bindParam(':is_featured', $is_featured);
        $update_stmt->bindParam(':is_bestseller', $is_bestseller);
        $update_stmt->bindParam(':is_new_arrival', $is_new_arrival);
        $update_stmt->bindParam(':main_image', $main_image);
        $update_stmt->bindParam(':product_pdf', $product_pdf);
        $update_stmt->bindParam(':id', $product_id);
        
        if ($update_stmt->execute()) {
            // Handle specifications
            handleSpecifications($conn, $product_id);
            // Handle gallery images
            handleProductGallery($conn, $product_id);
            
            $_SESSION['success'] = 'Product updated successfully';
            header('Location: products.php');
            exit;
        } else {
            $error = 'Failed to update product';
        }
    } elseif (!isset($error)) {
        // Insert new product
        $insert_query = "INSERT INTO products 
                        (product_name, product_slug, sku, category_id, brand_id, description, price, sale_price, stock_quantity, is_active, is_featured, is_bestseller, is_new_arrival, main_image, product_pdf, created_at) 
                        VALUES 
                        (:name, :slug, :sku, :category_id, :brand_id, :description, :price, :sale_price, :stock_quantity, :is_active, :is_featured, :is_bestseller, :is_new_arrival, :main_image, :product_pdf, NOW())";
        
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bindParam(':name', $product_name);
        $insert_stmt->bindParam(':slug', $slug);
        $insert_stmt->bindParam(':sku', $sku);
        $insert_stmt->bindParam(':category_id', $category_id);
        $insert_stmt->bindParam(':brand_id', $brand_id);
        $insert_stmt->bindParam(':description', $description);
        $insert_stmt->bindParam(':price', $price);
        $insert_stmt->bindParam(':sale_price', $sale_price);
        $insert_stmt->bindParam(':stock_quantity', $stock_quantity);
        $insert_stmt->bindParam(':is_active', $is_active);
        $insert_stmt->bindParam(':is_featured', $is_featured);
        $insert_stmt->bindParam(':is_bestseller', $is_bestseller);
        $insert_stmt->bindParam(':is_new_arrival', $is_new_arrival);
        $insert_stmt->bindParam(':main_image', $main_image);
        $insert_stmt->bindParam(':product_pdf', $product_pdf);
        
        if ($insert_stmt->execute()) {
            $new_product_id = $conn->lastInsertId();
            
            // Handle specifications
            handleSpecifications($conn, $new_product_id);
            // Handle gallery images
            handleProductGallery($conn, $new_product_id);
            
            $_SESSION['success'] = 'Product added successfully';
            header('Location: products.php');
            exit;
        } else {
            $error = 'Failed to add product';
        }
    }
}

// Function to handle specifications
function handleSpecifications($conn, $product_id) {
    // Delete existing specifications
    $delete_query = "DELETE FROM product_specifications WHERE product_id = :product_id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':product_id', $product_id);
    $delete_stmt->execute();
    
    // Insert new specifications
    if (isset($_POST['spec_names']) && is_array($_POST['spec_names'])) {
        $spec_names = $_POST['spec_names'];
        $spec_values = $_POST['spec_values'] ?? [];
        
        $insert_spec_query = "INSERT INTO product_specifications (product_id, spec_name, spec_value, display_order) 
                             VALUES (:product_id, :spec_name, :spec_value, :display_order)";
        $insert_spec_stmt = $conn->prepare($insert_spec_query);
        
        foreach ($spec_names as $index => $spec_name) {
            $spec_name = trim($spec_name);
            $spec_value = trim($spec_values[$index] ?? '');
            
            if (!empty($spec_name) && !empty($spec_value)) {
                $insert_spec_stmt->bindParam(':product_id', $product_id);
                $insert_spec_stmt->bindParam(':spec_name', $spec_name);
                $insert_spec_stmt->bindParam(':spec_value', $spec_value);
                $insert_spec_stmt->bindParam(':display_order', $index);
                $insert_spec_stmt->execute();
            }
        }
    }
}

// Function to handle product gallery images (add/remove)
function handleProductGallery($conn, $product_id) {
    // Delete selected gallery images (edit mode)
    if (!empty($_POST['remove_gallery_images']) && is_array($_POST['remove_gallery_images'])) {
        $delete_gallery_query = "DELETE FROM product_images WHERE product_id = :product_id AND image_id = :image_id";
        $delete_gallery_stmt = $conn->prepare($delete_gallery_query);

        foreach ($_POST['remove_gallery_images'] as $image_id) {
            $image_id = (int) $image_id;
            if ($image_id <= 0) {
                continue;
            }
            $delete_gallery_stmt->bindParam(':product_id', $product_id);
            $delete_gallery_stmt->bindParam(':image_id', $image_id);
            $delete_gallery_stmt->execute();
        }
    }

    // Add new gallery images
    if (!isset($_FILES['gallery_images']) || empty($_FILES['gallery_images']['name']) || !is_array($_FILES['gallery_images']['name'])) {
        return;
    }

    $upload_dir = '../assets/images/products/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $max_order_stmt = $conn->prepare("SELECT COALESCE(MAX(display_order), -1) AS max_order FROM product_images WHERE product_id = :product_id");
    $max_order_stmt->bindParam(':product_id', $product_id);
    $max_order_stmt->execute();
    $max_order = (int) $max_order_stmt->fetch(PDO::FETCH_ASSOC)['max_order'];
    $display_order = $max_order + 1;

    $insert_gallery_query = "INSERT INTO product_images (product_id, image_url, is_primary, display_order) VALUES (:product_id, :image_url, 0, :display_order)";
    $insert_gallery_stmt = $conn->prepare($insert_gallery_query);

    foreach ($_FILES['gallery_images']['name'] as $index => $original_name) {
        $error_code = $_FILES['gallery_images']['error'][$index] ?? UPLOAD_ERR_NO_FILE;
        if ($error_code !== UPLOAD_ERR_OK) {
            continue;
        }

        $tmp_name = $_FILES['gallery_images']['tmp_name'][$index] ?? '';
        $file_ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_types, true)) {
            continue;
        }

        $safe_name = preg_replace('/[^a-zA-Z0-9._-]/', '', $original_name);
        $new_filename = uniqid('gallery-') . '-' . $safe_name;
        $upload_path = $upload_dir . $new_filename;

        if (!move_uploaded_file($tmp_name, $upload_path)) {
            continue;
        }

        $image_url = 'assets/images/products/' . $new_filename;
        $insert_gallery_stmt->bindParam(':product_id', $product_id);
        $insert_gallery_stmt->bindParam(':image_url', $image_url);
        $insert_gallery_stmt->bindParam(':display_order', $display_order);
        $insert_gallery_stmt->execute();
        $display_order++;
    }
}

// Ensure $product is array for add form (avoids null array access)
$product = $product ?? [];

// Get categories
$categories_query = "SELECT * FROM categories WHERE is_active = 1 ORDER BY category_name";
$categories = $conn->query($categories_query)->fetchAll(PDO::FETCH_ASSOC);

// Get brands
$brands_query = "SELECT * FROM brands WHERE is_active = 1 ORDER BY brand_name";
$brands = $conn->query($brands_query)->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="products.php" class="text-purple-custom hover:underline">&larr; Back to Products</a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">
                <?= $is_edit ? 'Edit Product' : 'Add New Product' ?>
            </h1>

            <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                        <input type="text" name="product_name" 
                               value="<?= htmlspecialchars($product['product_name'] ?? '') ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                        <input type="text" name="sku" 
                               value="<?= htmlspecialchars($product['sku'] ?? '') ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>" 
                                    <?= ($product['category_id'] ?? '') == $cat['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select name="brand_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                            <option value="">Select Brand</option>
                            <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['brand_id'] ?>" 
                                    <?= ($product['brand_id'] ?? '') == $brand['brand_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($brand['brand_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                        <input type="number" step="0.01" name="price" 
                               value="<?= htmlspecialchars($product['price'] ?? '') ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                        <input type="number" step="0.01" min="0" name="sale_price" 
                               value="<?= (array_key_exists('sale_price', $product) && $product['sale_price'] !== null && $product['sale_price'] !== '') ? htmlspecialchars((string)$product['sale_price']) : '' ?>"
                               placeholder="Leave blank if no sale price"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" 
                               value="<?= htmlspecialchars($product['stock_quantity'] ?? '') ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
                        <input type="file" name="image_file" accept="image/*"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                        <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, GIF, WEBP (Max 5MB)</p>
                        <?php if ($is_edit && $product['main_image']): ?>
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                            <img src="../assets/images/products/<?= htmlspecialchars($product['main_image']) ?>" 
                                 alt="<?= htmlspecialchars($product['product_name']) ?>"
                                 class="w-32 h-32 object-cover rounded border"
                                 onerror="this.src='../assets/images/default.png'">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product PDF (Datasheet / Manual)</label>
                        <input type="file" name="pdf_file" accept="application/pdf"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                        <p class="text-xs text-gray-500 mt-1">Allowed: PDF</p>
                        <?php if ($is_edit && !empty($product['product_pdf'])): ?>
                        <div class="mt-2">
                            <a href="../assets/files/products/<?= htmlspecialchars($product['product_pdf']) ?>" target="_blank" class="text-sm text-blue-600 hover:underline">
                                View current PDF
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Additional Product Images -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Product Images</h3>
                    <input type="file" name="gallery_images[]" accept="image/*" multiple
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                    <p class="text-xs text-gray-500 mt-1">Upload multiple images (JPG, PNG, GIF, WEBP)</p>

                    <?php if ($is_edit && !empty($gallery_images)): ?>
                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <?php foreach ($gallery_images as $gimg): ?>
                        <label class="border rounded-lg p-2 cursor-pointer">
                            <img src="../<?= htmlspecialchars($gimg['image_url']) ?>" alt="Gallery image" class="w-full h-24 object-cover rounded mb-2">
                            <span class="flex items-center text-xs text-gray-600">
                                <input type="checkbox" name="remove_gallery_images[]" value="<?= (int) $gimg['image_id'] ?>" class="mr-2">
                                Remove
                            </span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>

                <div class="flex gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" 
                               class="mr-2 w-4 h-4 text-purple-custom focus:ring-purple-custom"
                               <?= ($product['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label for="is_active" class="text-sm text-gray-700">Active</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" 
                               class="mr-2 w-4 h-4 text-purple-custom focus:ring-purple-custom"
                               <?= ($product['is_featured'] ?? 0) ? 'checked' : '' ?>>
                        <label for="is_featured" class="text-sm text-gray-700">Featured Product</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_bestseller" id="is_bestseller" 
                               class="mr-2 w-4 h-4 text-purple-custom focus:ring-purple-custom"
                               <?= ($product['is_bestseller'] ?? 0) ? 'checked' : '' ?>>
                        <label for="is_bestseller" class="text-sm text-gray-700">Best Seller</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_new_arrival" id="is_new_arrival" 
                               class="mr-2 w-4 h-4 text-purple-custom focus:ring-purple-custom"
                               <?= ($product['is_new_arrival'] ?? 0) ? 'checked' : '' ?>>
                        <label for="is_new_arrival" class="text-sm text-gray-700">New Arrival</label>
                    </div>
                </div>

                <!-- Product Specifications Section -->
                <div class="border-t pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Product Specifications</h3>
                        <button type="button" onclick="addSpecRow()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 text-sm">
                            <i class="fas fa-plus mr-2"></i>Add Specification
                        </button>
                    </div>
                    
                    <div id="specifications-container" class="space-y-3">
                        <?php if (!empty($specifications)): ?>
                            <?php foreach ($specifications as $index => $spec): ?>
                            <div class="spec-row flex gap-3 items-start">
                                <div class="flex-1">
                                    <input type="text" name="spec_names[]" 
                                           value="<?= htmlspecialchars($spec['spec_name']) ?>"
                                           placeholder="e.g., Processor, RAM, Screen Size"
                                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                                </div>
                                <div class="flex-1">
                                    <input type="text" name="spec_values[]" 
                                           value="<?= htmlspecialchars($spec['spec_value']) ?>"
                                           placeholder="e.g., Intel i7, 16GB, 15.6 inches"
                                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                                </div>
                                <button type="button" onclick="removeSpecRow(this)" 
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="spec-row flex gap-3 items-start">
                                <div class="flex-1">
                                    <input type="text" name="spec_names[]" 
                                           placeholder="e.g., Processor, RAM, Screen Size"
                                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                                </div>
                                <div class="flex-1">
                                    <input type="text" name="spec_values[]" 
                                           placeholder="e.g., Intel i7, 16GB, 15.6 inches"
                                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                                </div>
                                <button type="button" onclick="removeSpecRow(this)" 
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <p class="text-sm text-gray-500 mt-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Add technical specifications that will be displayed on the product detail page. Leave fields empty to skip.
                    </p>
                </div>

                <div class="flex gap-6">
                    <button type="submit" class="bg-purple-custom text-white px-6 py-2 rounded-lg hover:bg-[#4f0a4f]">
                        <?= $is_edit ? 'Update Product' : 'Add Product' ?>
                    </button>
                    <a href="products.php" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Add new specification row
function addSpecRow() {
    const container = document.getElementById('specifications-container');
    const newRow = document.createElement('div');
    newRow.className = 'spec-row flex gap-3 items-start';
    newRow.innerHTML = `
        <div class="flex-1">
            <input type="text" name="spec_names[]" 
                   placeholder="e.g., Processor, RAM, Screen Size"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
        </div>
        <div class="flex-1">
            <input type="text" name="spec_values[]" 
                   placeholder="e.g., Intel i7, 16GB, 15.6 inches"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
        </div>
        <button type="button" onclick="removeSpecRow(this)" 
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newRow);
}

// Remove specification row
function removeSpecRow(button) {
    const container = document.getElementById('specifications-container');
    const rows = container.getElementsByClassName('spec-row');
    
    // Keep at least one row
    if (rows.length > 1) {
        button.closest('.spec-row').remove();
    } else {
        // Clear the inputs instead of removing
        const row = button.closest('.spec-row');
        row.querySelectorAll('input').forEach(input => input.value = '');
    }
}
</script>

<?php include 'includes/footer.php'; ?>
