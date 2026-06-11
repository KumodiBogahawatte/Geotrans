<?php
session_start();
require_once '../config/database.php';
require_once '../classes/ProductColor.php';

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

$main_image_choice_initial = 0;
if ($is_edit && !empty($product['main_image']) && !empty($gallery_images)) {
    $mainBaseNorm = strtolower((string) $product['main_image']);
    foreach ($gallery_images as $gimg) {
        $gb = strtolower(basename(str_replace('\\', '/', (string) ($gimg['image_url'] ?? ''))));
        if ($gb !== '' && $gb === $mainBaseNorm) {
            $main_image_choice_initial = (int) $gimg['image_id'];
            break;
        }
    }
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
    $default_color_name = $_POST['default_color_name'] ?? 'Default';
    $default_color_hex = $_POST['default_color_hex'] ?? '#f0f0f0';
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
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
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
            $error = 'Invalid file type. Only JPG, PNG, GIF, WEBP, AVIF allowed';
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
                        product_pdf = :product_pdf,
                        default_color_name = :default_color_name,
                        default_color_hex = :default_color_hex
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
        $update_stmt->bindParam(':default_color_name', $default_color_name);
        $update_stmt->bindParam(':default_color_hex', $default_color_hex);
        $update_stmt->bindParam(':id', $product_id);

        if ($update_stmt->execute()) {
            // Handle specifications
            handleSpecifications($conn, $product_id);
            // Handle gallery images
            handleProductGallery($conn, $product_id);
            applyMainImageFromGallery($conn, $product_id);
            // Handle product colors
            handleProductColors($conn, $product_id, $category_id);

            $_SESSION['success'] = 'Product updated successfully';
            header('Location: products.php');
            exit;
        } else {
            $error = 'Failed to update product';
        }
    } elseif (!isset($error)) {
        // Insert new product
        $insert_query = "INSERT INTO products 
                        (product_name, product_slug, sku, category_id, brand_id, description, price, sale_price, stock_quantity, is_active, is_featured, is_bestseller, is_new_arrival, main_image, product_pdf, default_color_name, default_color_hex, created_at) 
                        VALUES 
                        (:name, :slug, :sku, :category_id, :brand_id, :description, :price, :sale_price, :stock_quantity, :is_active, :is_featured, :is_bestseller, :is_new_arrival, :main_image, :product_pdf, :default_color_name, :default_color_hex, NOW())";

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
        $insert_stmt->bindParam(':default_color_name', $default_color_name);
        $insert_stmt->bindParam(':default_color_hex', $default_color_hex);

        if ($insert_stmt->execute()) {
            $new_product_id = $conn->lastInsertId();

            // Handle specifications
            handleSpecifications($conn, $new_product_id);
            // Handle gallery images
            handleProductGallery($conn, $new_product_id);
            applyMainImageFromGallery($conn, $new_product_id);
            // Handle product colors
            handleProductColors($conn, $new_product_id, $category_id);

            $_SESSION['success'] = 'Product added successfully';
            header('Location: products.php');
            exit;
        } else {
            $error = 'Failed to add product';
        }
    }
}

// Function to handle specifications
function handleSpecifications($conn, $product_id)
{
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

/** Set products.main_image from a gallery row when admin selects it. */
function applyMainImageFromGallery(PDO $conn, $product_id)
{
    $choice = (int) ($_POST['main_image_choice'] ?? 0);
    if ($choice <= 0) {
        return;
    }
    $st = $conn->prepare('SELECT image_url FROM product_images WHERE image_id = :i AND product_id = :p LIMIT 1');
    $st->bindValue(':i', $choice, PDO::PARAM_INT);
    $st->bindValue(':p', (int) $product_id, PDO::PARAM_INT);
    $st->execute();
    $row = $st->fetch(PDO::FETCH_ASSOC);
    if (!$row || empty($row['image_url'])) {
        return;
    }
    $url = str_replace('\\', '/', (string) $row['image_url']);
    $base = basename($url);
    if ($base === '') {
        return;
    }
    $up = $conn->prepare('UPDATE products SET main_image = :m WHERE product_id = :p');
    $up->bindValue(':m', $base);
    $up->bindValue(':p', (int) $product_id, PDO::PARAM_INT);
    $up->execute();
}

// Function to handle product gallery images (order / remove / add)
function handleProductGallery($conn, $product_id)
{
    $product_id = (int) $product_id;

    // 1) Persist drag-and-drop order (display_order)
    $orderIds = [];
    if (!empty($_POST['gallery_order'])) {
        if (is_array($_POST['gallery_order'])) {
            $orderIds = array_map('intval', $_POST['gallery_order']);
        } else {
            $orderIds = array_filter(array_map('intval', explode(',', (string) $_POST['gallery_order'])));
        }
    }
    if ($orderIds !== []) {
        $u = $conn->prepare('UPDATE product_images SET display_order = :ord WHERE image_id = :id AND product_id = :pid');
        foreach ($orderIds as $ord => $image_id) {
            if ($image_id <= 0) {
                continue;
            }
            $u->bindValue(':ord', (int) $ord, PDO::PARAM_INT);
            $u->bindValue(':id', $image_id, PDO::PARAM_INT);
            $u->bindValue(':pid', $product_id, PDO::PARAM_INT);
            $u->execute();
        }
    }

    // 2) Delete selected gallery images
    if (!empty($_POST['remove_gallery_images']) && is_array($_POST['remove_gallery_images'])) {
        $delete_gallery_query = 'DELETE FROM product_images WHERE product_id = :product_id AND image_id = :image_id';
        $delete_gallery_stmt = $conn->prepare($delete_gallery_query);

        foreach ($_POST['remove_gallery_images'] as $image_id) {
            $image_id = (int) $image_id;
            if ($image_id <= 0) {
                continue;
            }
            $delete_gallery_stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $delete_gallery_stmt->bindValue(':image_id', $image_id, PDO::PARAM_INT);
            $delete_gallery_stmt->execute();
        }
    }

    // 3) Add new gallery images
    if (!isset($_FILES['gallery_images']) || empty($_FILES['gallery_images']['name']) || !is_array($_FILES['gallery_images']['name'])) {
        return;
    }

    $upload_dir = '../assets/images/products/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

    $count_query = $conn->prepare('SELECT COUNT(*) AS img_count FROM product_images WHERE product_id = :product_id');
    $count_query->bindValue(':product_id', $product_id, PDO::PARAM_INT);
    $count_query->execute();
    $current_count = (int) $count_query->fetch(PDO::FETCH_ASSOC)['img_count'];

    $max_new_images = max(0, 3 - $current_count);
    if ($max_new_images <= 0) {
        return;
    }

    $max_order_stmt = $conn->prepare('SELECT COALESCE(MAX(display_order), -1) AS max_order FROM product_images WHERE product_id = :product_id');
    $max_order_stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
    $max_order_stmt->execute();
    $max_order = (int) $max_order_stmt->fetch(PDO::FETCH_ASSOC)['max_order'];
    $display_order = $max_order + 1;

    $insert_gallery_query = 'INSERT INTO product_images (product_id, image_url, is_primary, display_order) VALUES (:product_id, :image_url, 0, :display_order)';
    $insert_gallery_stmt = $conn->prepare($insert_gallery_query);

    $images_added = 0;
    foreach ($_FILES['gallery_images']['name'] as $index => $original_name) {
        if ($images_added >= $max_new_images) {
            break;
        }

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
        $insert_gallery_stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $insert_gallery_stmt->bindValue(':image_url', $image_url);
        $insert_gallery_stmt->bindValue(':display_order', $display_order, PDO::PARAM_INT);
        $insert_gallery_stmt->execute();
        $display_order++;
        $images_added++;
    }
}

function handleProductColors($conn, $product_id, $category_id)
{
    // Check if this category should have colors (laptops, monitors, printers, desktop)
    $category_query = "SELECT category_name FROM categories WHERE category_id = :category_id";
    $category_stmt = $conn->prepare($category_query);
    $category_stmt->bindParam(':category_id', $category_id);
    $category_stmt->execute();
    $category = $category_stmt->fetch(PDO::FETCH_ASSOC);

    $colorCategories = ['laptops', 'monitors', 'printers', 'desktop'];
    $categorySlug = strtolower(str_replace(' ', '-', $category['category_name'] ?? ''));
    $hasColors = in_array($categorySlug, $colorCategories);

    if (!$hasColors) {
        return;
    }

    $productColorModel = new ProductColor($conn);
    $upload_dir = '../assets/images/products/';
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

    // Create upload dir if needed
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Delete marked color images (checkbox deletions)
    if (!empty($_POST['remove_color_images']) && is_array($_POST['remove_color_images'])) {
        foreach ($_POST['remove_color_images'] as $image_id) {
            $productColorModel->deleteColorImage((int)$image_id);
        }
    }

    // Delete removed colors
    if (!empty($_POST['remove_colors']) && is_array($_POST['remove_colors'])) {
        foreach ($_POST['remove_colors'] as $color_id) {
            $productColorModel->deleteColor((int)$color_id);
        }
    }

    // Add/update colors and handle image uploads
    if (!empty($_POST['color_names']) && is_array($_POST['color_names'])) {
        foreach ($_POST['color_names'] as $idx => $color_name) {
            $color_name = trim($color_name);
            $color_hex = $_POST['color_hexes'][$idx] ?? '#000000';
            $color_id = $_POST['color_ids'][$idx] ?? null;

            if (empty($color_name)) {
                continue;
            }

            // Add new color or update existing
            if (empty($color_id) || $color_id === 'new') {
                $color_id = $productColorModel->addColor($product_id, $color_name, $color_hex, $idx);
            } else {
                // Update existing color name/hex
                $productColorModel->updateColor($color_id, $color_name, $color_hex);
            }

            // Handle MAIN image upload for this color
            if (!empty($_FILES['color_main_image_files']['name'][$idx])) {
                $file_error = $_FILES['color_main_image_files']['error'][$idx] ?? UPLOAD_ERR_NO_FILE;

                if ($file_error === UPLOAD_ERR_OK) {
                    $tmp_file = $_FILES['color_main_image_files']['tmp_name'][$idx];
                    $orig_name = $_FILES['color_main_image_files']['name'][$idx];
                    $file_ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));

                    if (in_array($file_ext, $allowed_types)) {
                        $safe_name = preg_replace('/[^a-zA-Z0-9._-]/', '', pathinfo($orig_name, PATHINFO_FILENAME));
                        $new_filename = uniqid('color-main-') . '-' . $safe_name . '.' . $file_ext;
                        $upload_path = $upload_dir . $new_filename;

                        if (move_uploaded_file($tmp_file, $upload_path)) {
                            // Check if replacing existing main image
                            $existing_main_id = $_POST['color_main_image_ids'][$idx] ?? null;

                            if ($existing_main_id && $existing_main_id !== 'new') {
                                // Delete old main image
                                $productColorModel->deleteColorImage($existing_main_id);
                            }

                            // Add new main image (display_order = 0)
                            $productColorModel->addColorImage($color_id, $new_filename, 0);
                        }
                    }
                }
            }

            // Handle ADDITIONAL image uploads for this color (max 3)
            if (!empty($_FILES['color_image_files']['name'][$idx])) {
                $file_error = $_FILES['color_image_files']['error'][$idx] ?? UPLOAD_ERR_NO_FILE;

                if ($file_error === UPLOAD_ERR_OK) {
                    $tmp_file = $_FILES['color_image_files']['tmp_name'][$idx];
                    $orig_name = $_FILES['color_image_files']['name'][$idx];
                    $file_ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));

                    if (in_array($file_ext, $allowed_types)) {
                        $safe_name = preg_replace('/[^a-zA-Z0-9._-]/', '', pathinfo($orig_name, PATHINFO_FILENAME));
                        $new_filename = uniqid('color-') . '-' . $safe_name . '.' . $file_ext;
                        $upload_path = $upload_dir . $new_filename;

                        if (move_uploaded_file($tmp_file, $upload_path)) {
                            // Check if this is replacing an existing additional image
                            $existing_image_id = $_POST['color_image_ids'][$idx] ?? null;

                            if ($existing_image_id && $existing_image_id !== 'new') {
                                // Delete old additional image (replacement)
                                $old_images = $productColorModel->getColorImages($color_id);
                                foreach ($old_images as $img) {
                                    if ($img['image_id'] == $existing_image_id) {
                                        @unlink($upload_dir . basename($img['image_url']));
                                        $productColorModel->deleteColorImage($existing_image_id);
                                        break;
                                    }
                                }
                            } else {
                                // Adding new additional image - check limit (max 3 additional beyond main)
                                $existing_images = $productColorModel->getColorImages($color_id);
                                $additional_count = 0;
                                foreach ($existing_images as $img) {
                                    if ($img['display_order'] > 0) { // Not the main image
                                        $additional_count++;
                                    }
                                }
                                if ($additional_count >= 3) {
                                    @unlink($upload_path);
                                    continue; // Don't add more than 3 additional
                                }
                            }

                            // Add new additional image (find next display_order > 0)
                            $existing_images = $productColorModel->getColorImages($color_id);
                            $max_order = 0;
                            foreach ($existing_images as $img) {
                                if ($img['display_order'] > $max_order) {
                                    $max_order = $img['display_order'];
                                }
                            }
                            $display_order = $max_order + 1;
                            $productColorModel->addColorImage($color_id, $new_filename, $display_order);
                        }
                    }
                }
            }
        }
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

// Get existing colors (if editing)
$existing_colors = [];
$colorImages = [];
if ($is_edit) {
    $category_query = "SELECT c.category_name FROM categories c JOIN products p ON p.category_id = c.category_id WHERE p.product_id = :product_id";
    $cat_stmt = $conn->prepare($category_query);
    $cat_stmt->bindParam(':product_id', $product_id);
    $cat_stmt->execute();
    $cat_data = $cat_stmt->fetch(PDO::FETCH_ASSOC);

    $colorCategories = ['laptops', 'monitors', 'printers', 'desktop'];
    $categorySlug = strtolower(str_replace(' ', '-', $cat_data['category_name'] ?? ''));
    $hasColorsFeature = in_array($categorySlug, $colorCategories);

    if ($hasColorsFeature) {
        $productColorModel = new ProductColor($conn);
        $existing_colors = $productColorModel->getByProduct($product_id);

        // Fetch images for each color
        foreach ($existing_colors as $color) {
            $colorImages[$color['color_id']] = $productColorModel->getColorImages($color['color_id']);
        }
    }
}

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

            <form id="product-edit-form" method="POST" class="space-y-6" enctype="multipart/form-data">
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
                        <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, GIF, WEBP, AVIF (Max 5MB)</p>
                        <?php if ($is_edit && $product['main_image']): ?>
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                <div class="w-40 h-40 rounded border bg-gray-50 flex items-center justify-center p-2">
                                    <img src="../assets/images/products/<?= htmlspecialchars($product['main_image']) ?>"
                                        alt="<?= htmlspecialchars($product['product_name']) ?>"
                                        class="max-w-full max-h-full object-contain"
                                        onerror="this.src='../assets/images/default.png'">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Additional Product Images -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Product Images (Max 3)</h3>
                    <p class="text-sm text-gray-600 mb-4">Add up to three extra images. You can select several at once or <strong>add one image at a time</strong>—each pick appends to the preview. <strong>Drag</strong> preview cards or saved gallery cards to set the order used on the product page (first = first after the main image).</p>

                    <input type="hidden" name="gallery_order" id="gallery-order-input" value="">

                    <?php if ($is_edit && !empty($gallery_images)): ?>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Main image for storefront</p>
                            <p class="text-xs text-gray-600 mb-4">Shown on <strong>listing grids</strong>, as the <strong>large photo</strong> when the product page opens, and as the <strong>first</strong> thumbnail in the row. Either keep using the primary upload at the top of this form, or pick one of the three gallery pictures (the same shots as Thumb 1–3 below).</p>
                            <div class="space-y-3 text-sm">
                                <label class="flex items-start gap-3 cursor-pointer rounded-lg border border-gray-200 bg-white p-3 hover:border-purple-300 transition">
                                    <input type="radio" name="main_image_choice" value="0" class="text-purple-custom mt-1 flex-shrink-0" <?= $main_image_choice_initial === 0 ? 'checked' : '' ?>>
                                    <div class="w-14 h-14 flex-shrink-0 rounded border border-gray-200 bg-gray-50 flex items-center justify-center p-1">
                                        <?php if (!empty($product['main_image'])): ?>
                                            <img src="../assets/images/products/<?= htmlspecialchars($product['main_image']) ?>" alt="" draggable="false" class="max-w-full max-h-full object-contain pointer-events-none select-none">
                                        <?php else: ?>
                                            <span class="text-[10px] text-gray-400 text-center px-1">No main file yet</span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="min-w-0">
                                        <strong class="text-gray-900">Primary upload (main image field above)</strong>
                                        <span class="block text-xs text-gray-500 mt-1">Uses the file from the main image input at the top—or the current main photo if you leave that field unchanged. Choose this when the hero image should <em>not</em> be tied to a gallery thumb.</span>
                                    </span>
                                </label>
                                <?php foreach ($gallery_images as $idx => $gimg): ?>
                                    <label class="js-main-image-gallery-option flex items-start gap-3 cursor-pointer rounded-lg border border-gray-200 bg-white p-3 hover:border-purple-300 transition" data-image-id="<?= (int) $gimg['image_id'] ?>">
                                        <input type="radio" name="main_image_choice" value="<?= (int) $gimg['image_id'] ?>" class="text-purple-custom mt-1 flex-shrink-0" <?= $main_image_choice_initial === (int) $gimg['image_id'] ? 'checked' : '' ?>>
                                        <div class="w-14 h-14 flex-shrink-0 rounded border border-gray-200 bg-gray-50 flex items-center justify-center p-1">
                                            <img src="../<?= htmlspecialchars($gimg['image_url']) ?>" alt="" draggable="false" class="max-w-full max-h-full object-contain pointer-events-none select-none">
                                        </div>
                                        <span class="min-w-0">
                                            <strong class="js-main-choice-thumb-strong text-gray-900">Gallery thumb <?= $idx + 1 ?></strong>
                                            <span class="js-main-choice-thumb-sub block text-xs text-gray-500 mt-1">Same image as <strong class="font-medium text-gray-600">Thumb <?= $idx + 1 ?></strong> in the preview and in the draggable gallery cards below (left to right). Selecting it makes this picture the hero and listing image.</span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Gallery order (drag to reorder)</p>
                            <p class="text-xs text-gray-600 mb-3">On the product page, the large image is the <strong>main</strong> image (above). The small thumbnails under it are: <strong>main</strong> first, then <strong>Thumb 1</strong>, <strong>Thumb 2</strong>, <strong>Thumb 3</strong> in the order below. Drag cards to change Thumb 1–3 order.</p>

                            <div id="admin-storefront-thumbnail-preview" class="mb-4 p-3 bg-white border border-purple-100 rounded-lg">
                                <p class="text-xs font-semibold text-purple-900 mb-2">Preview: product page thumbnail row (left to right)</p>
                                <div class="flex flex-wrap items-end gap-3">
                                    <div class="text-center flex-shrink-0">
                                        <div class="w-16 h-16 mx-auto rounded-lg border-2 border-purple-custom bg-gray-50 flex items-center justify-center p-1">
                                            <?php if (!empty($product['main_image'])): ?>
                                                <img src="../assets/images/products/<?= htmlspecialchars($product['main_image']) ?>" alt="" class="max-w-full max-h-full object-contain">
                                            <?php else: ?>
                                                <span class="text-[10px] text-gray-400">—</span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-[10px] font-medium text-purple-800 block mt-1">Main</span>
                                    </div>
                                    <div id="mock-gallery-thumbs" class="flex flex-wrap gap-2 items-end">
                                        <?php foreach ($gallery_images as $idx => $gimg): ?>
                                            <div class="mock-gallery-slot text-center flex-shrink-0" data-mock-for-id="<?= (int) $gimg['image_id'] ?>">
                                                <div class="w-14 h-14 mx-auto rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center p-1">
                                                    <img src="../<?= htmlspecialchars($gimg['image_url']) ?>" alt="" class="max-w-full max-h-full object-contain">
                                                </div>
                                                <span class="mock-order-label text-[10px] text-gray-600 block mt-1">Thumb <?= $idx + 1 ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div id="gallery-sortable" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <?php foreach ($gallery_images as $idx => $gimg): ?>
                                    <div class="gallery-sort-item relative border rounded-lg p-3 bg-white shadow-sm hover:shadow cursor-grab active:cursor-grabbing" draggable="true" data-image-id="<?= (int) $gimg['image_id'] ?>">
                                        <span class="gallery-thumb-order-badge absolute top-2 left-2 z-10 inline-flex items-center justify-center min-w-[1.5rem] h-6 px-1 rounded-full bg-purple-custom text-white text-xs font-bold shadow"><?= $idx + 1 ?></span>
                                        <div class="h-36 w-full rounded bg-gray-50 flex items-center justify-center mb-2 border border-gray-100">
                                            <img src="../<?= htmlspecialchars($gimg['image_url']) ?>" alt="" draggable="false" class="max-h-full max-w-full object-contain pointer-events-none select-none">
                                        </div>
                                        <p class="text-[11px] text-gray-500 mb-1">Product page: <strong class="js-gallery-thumb-n-strong text-gray-700">Thumb <?= $idx + 1 ?></strong> (after main)</p>
                                        <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                                            <input type="checkbox" name="remove_gallery_images[]" value="<?= (int) $gimg['image_id'] ?>" class="rounded js-gallery-remove-cb">
                                            Remove
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="main_image_choice" value="0">
                    <?php endif; ?>

                    <!-- File input for new images -->
                    <div class="mb-6">
                        <input type="file" id="gallery-input" name="gallery_images[]" accept="image/*" multiple
                            class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-custom cursor-pointer hover:border-purple-custom transition"
                            onchange="handleGallerySelection(this)">
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF, WEBP, AVIF (max 5MB each). <span id="gallery-slots-hint"></span> Pending upload: <span id="selected-files-count">0</span>. <span class="text-gray-600">Drag preview cards to reorder before saving.</span></p>
                    </div>

                    <!-- Preview of newly selected images (append one-by-one; drag to reorder) -->
                    <div id="gallery-preview-container" class="mb-6 hidden">
                        <p class="text-sm font-semibold text-gray-700 mb-3">New images preview <span class="text-xs font-normal text-gray-500">(drag to set upload order)</span></p>
                        <div id="gallery-preview" class="grid grid-cols-1 sm:grid-cols-3 gap-4"></div>
                    </div>
                </div>

                <!-- Product PDF -->
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

                <!-- Default Color -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Default Color</h3>
                    <p class="text-sm text-gray-600 mb-4">Set the default color that displays when the product page loads</p>
                    
                    <div class="max-w-sm">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color Name</label>
                            <input type="text" name="default_color_name"
                                value="<?= htmlspecialchars($product['default_color_name'] ?? 'Default') ?>"
                                placeholder="e.g., Default, Space Gray, Silver"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-custom">
                        </div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Code</label>
                        <div class="flex gap-3 items-center">
                            <input type="color" name="default_color_hex"
                                value="<?= htmlspecialchars($product['default_color_hex'] ?? '#f0f0f0') ?>"
                                id="default-color-picker"
                                class="w-16 h-10 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-custom transition">
                            <input type="text" 
                                id="default-color-hex-input"
                                placeholder="#f0f0f0"
                                maxlength="7"
                                value="<?= htmlspecialchars($product['default_color_hex'] ?? '#f0f0f0') ?>"
                                class="font-mono text-sm px-3 py-2 border rounded-lg w-28 text-center focus:ring-2 focus:ring-purple-custom"
                                onchange="validateHexInput(this)">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Enter hex code (e.g., #f0f0f0) or use the color picker</p>
                    </div>
                </div>

                <!--Product Colors (for specific categories) -->
                <?php
                $show_colors = false;
                if ($is_edit) {
                    $show_colors = !empty($existing_colors) || $hasColorsFeature;
                } else {
                    $category_to_check = $_POST['category_id'] ?? null;
                    if ($category_to_check) {
                        $cat_q = $conn->prepare("SELECT category_name FROM categories WHERE category_id = :cid");
                        $cat_q->bindParam(':cid', $category_to_check);
                        $cat_q->execute();
                        $cat_r = $cat_q->fetch(PDO::FETCH_ASSOC);
                        $catSlug = strtolower(str_replace(' ', '-', $cat_r['category_name'] ?? ''));
                        $show_colors = in_array($catSlug, ['laptops', 'monitors', 'printers', 'desktop']);
                    }
                }
                if ($show_colors):
                ?>
                    <div class="border-t pt-6 mt-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Product Colors</h3>
                            <button type="button" onclick="addColorRow()" class="px-4 py-2 bg-gray-800 text-white rounded text-sm font-medium hover:bg-gray-900 transition">
                                Add Color
                            </button>
                        </div>

                        <div id="colors-container" class="space-y-4">
                            <?php if ($is_edit && !empty($existing_colors)):
                                foreach ($existing_colors as $idx => $color):
                                    $colorImagesList = $colorImages[$color['color_id']] ?? [];
                            ?>
                                    <div class="color-row border border-gray-300 rounded p-5 bg-white">
                                        <input type="hidden" name="color_ids[]" value="<?= $color['color_id'] ?>">

                                        <div class="flex items-center justify-between mb-5 pb-4 border-b">
                                            <div class="flex items-center gap-3 flex-1">
                                                <div class="w-10 h-10 rounded border-2 border-gray-300" style="background-color: <?= htmlspecialchars($color['color_hex']) ?>"></div>
                                                <input type="text" name="color_names[]" value="<?= htmlspecialchars($color['color_name']) ?>" placeholder="Color name"
                                                    class="text-sm font-medium text-gray-900 bg-transparent border-0 p-0 focus:ring-0 flex-1">
                                            </div>
                                            <button type="button" onclick="removeColorRow(this)" class="text-red-600 text-sm font-medium hover:text-red-700">
                                                Remove
                                            </button>
                                        </div>

                                        <div class="space-y-4">
                                            <div class="max-w-sm">
                                                <label class="block text-xs font-semibold text-gray-700 mb-2">Color Code</label>
                                                <div class="flex gap-2 items-center">
                                                    <input type="color" name="color_hexes[]" value="<?= $color['color_hex'] ?>"
                                                        class="color-hex-input w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                                    <span class="font-mono text-sm text-gray-600 bg-gray-100 px-3 py-2 rounded border border-gray-300 hex-display min-w-20 text-center">
                                                        <?= $color['color_hex'] ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="text-xs font-semibold text-gray-700 block mb-4">Product Images (1 Main + 3 Additional)</label>

                                                <!-- Main Image -->
                                                <div class="mb-6 pb-6 border-b border-gray-300">
                                                    <label class="block text-xs font-semibold text-purple-custom mb-3"> Main Image (Required)</label>
                                                    <div class="color-main-image-container space-y-3" data-color-index="<?= $idx ?>">
                                                        <?php
                                                        $mainImg = !empty($colorImagesList) ? $colorImagesList[0] : null;
                                                        if ($mainImg):
                                                        ?>
                                                            <div class="color-main-image-row bg-gray-50 border border-purple-200 rounded p-4">
                                                                <input type="hidden" name="color_main_image_ids[]" value="<?= $mainImg['image_id'] ?>">
                                                                <div class="grid grid-cols-3 gap-3 items-start">
                                                                    <div class="col-span-2">
                                                                        <input type="file" name="color_main_image_files[]" accept="image/*"
                                                                            class="w-full text-sm cursor-pointer">
                                                                        <p class="text-xs text-gray-500 mt-2">Current: <?= basename($mainImg['image_url']) ?></p>
                                                                    </div>
                                                                    <div class="border border-gray-300 rounded overflow-hidden bg-white w-24 h-24 flex items-center justify-center">
                                                                        <img src="../assets/images/products/<?= htmlspecialchars($mainImg['image_url']) ?>"
                                                                            alt="Preview" class="max-w-full max-h-full object-contain">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="color-main-image-row bg-gray-50 border border-purple-200 rounded p-4">
                                                                <input type="hidden" name="color_main_image_ids[]" value="new">
                                                                <input type="file" name="color_main_image_files[]" accept="image/*"
                                                                    class="w-full text-sm cursor-pointer">
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- Additional Images -->
                                                <div class="mb-6 pb-6 border-b border-gray-300">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <label class="text-xs font-semibold text-purple-custom"> Additional Images (Optional, Max 3)</label>
                                                        <button type="button" onclick="addColorImageRow(this)" class="text-blue-600 text-xs font-medium hover:text-blue-700" id="add-color-image-btn">
                                                            Add Image
                                                        </button>
                                                    </div>

                                                    <div class="color-images-container space-y-3" data-color-index="<?= $idx ?>">
                                                        <p class="text-xs text-gray-500 mb-2">Images: <span class="color-image-count"><?= max(0, min(count($colorImagesList) - 1, 3)) ?></span>/3</p>
                                                        <?php if (!empty($colorImagesList)):
                                                            $additionalImages = array_slice($colorImagesList, 1, 3);
                                                            foreach ($additionalImages as $img_idx => $img):
                                                        ?>
                                                                <div class="color-image-row bg-gray-50 border border-purple-200 rounded p-3">
                                                                    <input type="hidden" name="color_image_ids[]" value="<?= $img['image_id'] ?>">
                                                                    <div class="grid grid-cols-3 gap-3 items-start">
                                                                        <div class="col-span-2">
                                                                            <input type="file" name="color_image_files[]" accept="image/*"
                                                                                class="w-full text-sm cursor-pointer">
                                                                            <p class="text-xs text-gray-500 mt-1"><?= basename($img['image_url']) ?></p>
                                                                        </div>
                                                                        <div class="border border-gray-300 rounded overflow-hidden bg-white w-24 h-24 flex items-center justify-center">
                                                                            <img src="../assets/images/products/<?= htmlspecialchars($img['image_url']) ?>"
                                                                                alt="Preview" class="max-w-full max-h-full object-contain">
                                                                        </div>
                                                                    </div>
                                                                    <label class="flex items-center gap-2 mt-2">
                                                                        <input type="checkbox" name="remove_color_images[]" value="<?= $img['image_id'] ?>" class="w-4 h-4 rounded">
                                                                        <span class="text-xs text-gray-600">Delete</span>
                                                                    </label>
                                                                </div>
                                                        <?php endforeach;
                                                        endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;
                            else: ?>
                                <div class="color-row border border-gray-300 rounded p-5 bg-white">
                                    <input type="hidden" name="color_ids[]" value="new">

                                    <div class="flex items-center justify-between mb-5 pb-4 border-b">
                                        <input type="text" name="color_names[]" placeholder="Color name"
                                            class="text-sm font-medium text-gray-900 bg-transparent border-0 p-0 focus:ring-0 flex-1">
                                    </div>

                                    <div class="space-y-4">
                                        <div class="max-w-sm">
                                            <label class="block text-xs font-semibold text-gray-700 mb-2">Color Code</label>
                                            <div class="flex gap-2 items-center">
                                                <input type="color" name="color_hexes[]" value="#000000"
                                                    class="color-hex-input w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                                <span class="font-mono text-sm text-gray-600 bg-gray-100 px-3 py-2 rounded border border-gray-300 hex-display min-w-20 text-center">
                                                    #000000
                                                </span>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="text-xs font-semibold text-gray-700 block mb-4">Product Images (1 Main + 3 Additional)</label>

                                            <!-- Main Image -->
                                            <div class="mb-6 pb-6 border-b border-gray-300">
                                                <label class="block text-xs font-semibold text-purple-custom mb-3"> Main Image</label>
                                                <div class="color-main-image-container space-y-3">
                                                    <div class="color-main-image-row bg-gray-50 border border-purple-200 rounded p-4">
                                                        <input type="hidden" name="color_main_image_ids[]" value="new">
                                                        <input type="file" name="color_main_image_files[]" accept="image/*"
                                                            class="w-full text-sm cursor-pointer">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Additional Images -->
                                            <div class="mb-6 pb-6 border-b border-gray-300">
                                                <div class="flex items-center justify-between mb-3">
                                                    <label class="text-xs font-semibold text-purple-custom"> Additional Images (Optional, Max 3)</label>
                                                    <button type="button" onclick="addColorImageRow(this)" class="text-blue-600 text-xs font-medium hover:text-blue-700" id="add-color-image-btn">
                                                        Add Image
                                                    </button>
                                                </div>

                                                <div class="color-images-container space-y-3">
                                                    <p class="text-xs text-gray-500 mb-2">Images: <span class="color-image-count">0</span>/3</p>
                                                    <!-- Additional image rows added via JS -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

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

    // Add new color row
    function addColorRow() {
        const container = document.getElementById('colors-container');
        const newRow = document.createElement('div');
        newRow.className = 'color-row bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition';
        newRow.innerHTML = `
        <input type="hidden" name="color_ids[]" value="new">
        
        <!-- Color Header -->
        <div class="bg-gradient-to-r from-blue-50 to-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400">
                    C
                </div>
                <div class="flex-1">
                    <input type="text" name="color_names[]" placeholder="e.g., Space Gray"
                           class="text-lg font-semibold text-gray-700 bg-transparent border-0 p-0 focus:ring-0 w-full placeholder-gray-400" required>
                </div>
                <button type="button" onclick="removeColorRow(this)" class="text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg text-xs font-semibold transition">
                    Remove
                </button>
            </div>
        </div>
        
        <!-- Color Details -->
        <div class="px-6 py-6 space-y-6">
            <!-- Color Hex -->
            <div class="max-w-xs">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Color Hex Code</label>
                <div class="flex gap-3 items-center">
                    <div class="relative">
                        <input type="color" name="color_hexes[]" value="#000000"
                               class="color-hex-input w-16 h-12 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-custom transition">
                    </div>
                    <div class="px-4 py-2 bg-gray-100 rounded-lg font-mono text-sm font-semibold text-gray-700 border border-gray-300 hex-display min-w-24 text-center">
                        #000000
                    </div>
                </div>
            </div>
            
            <!-- Color Images Section -->
            <div class="border-t pt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-4">Product Images (1 Main + 3 Additional)</label>
                
                <!-- Main Image -->
                <div class="mb-6 pb-6 border-b border-gray-300">
                    <label class="block text-xs font-semibold text-purple-custom mb-3"> Main Image</label>
                    <div class="color-main-image-container space-y-3">
                        <div class="color-main-image-row bg-gray-50 border border-purple-200 rounded p-4">
                            <input type="hidden" name="color_main_image_ids[]" value="new">
                            <input type="file" name="color_main_image_files[]" accept="image/*"
                                   class="w-full text-sm cursor-pointer">
                        </div>
                    </div>
                </div>
                
                <!-- Additional Images -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700"> Additional Images (Optional, Max 3)</label>
                            <p class="text-xs text-gray-500 mt-1">Upload extra images showing this color variant</p>
                        </div>
                        <button type="button" onclick="addColorImageRow(this)" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-xs font-semibold transition">
                            + Add Image
                        </button>
                    </div>
                    
                    <div class="color-images-container space-y-3">
                        <p class="text-xs text-gray-500 mb-2">Images: <span class="color-image-count">0</span>/3</p>
                        <!-- Additional image rows added via JS -->
                    </div>
                </div>
            </div>
        </div>
    `;
        container.appendChild(newRow);
    }

    // Remove color row
    function removeColorRow(button) {
        const container = document.getElementById('colors-container');
        const rows = container.getElementsByClassName('color-row');

        if (rows.length > 1) {
            button.closest('.color-row').remove();
        }
    }

    // Add image to color (additional images only, max 3)
    function addColorImageRow(button) {
        const colorRow = button.closest('.color-row');
        const imagesContainer = colorRow.querySelector('.color-images-container');
        const currentCount = imagesContainer.querySelectorAll('.color-image-row').length;

        // Check limit (max 3 additional images per color)
        if (currentCount >= 3) {
            alert('Maximum 3 additional images per color reached');
            return;
        }

        const newRow = document.createElement('div');
        newRow.className = 'color-image-row bg-gray-50 border border-purple-200 rounded-lg p-4 hover:border-purple-300 transition';
        newRow.innerHTML = `
        <div class="flex-1">
            <input type="hidden" name="color_image_ids[]" value="new">
            <label class="block text-xs font-semibold text-gray-600 mb-3">Upload image</label>
            <input type="file" name="color_image_files[]" accept="image/*"
                   class="w-full px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg text-sm text-gray-600 hover:border-gray-400 transition cursor-pointer file:hidden">
        </div>
        <div class="flex justify-end mt-3">
            <button type="button" onclick="removeColorImageRow(this)" class="text-red-600 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg text-xs font-semibold transition">
                Remove
            </button>
        </div>
    `;
        imagesContainer.appendChild(newRow);
        updateColorImageCount(colorRow);
    }

    // Remove image from color (additional images only)
    function removeColorImageRow(button) {
        button.closest('.color-image-row').remove();
        const colorRow = button.closest('.color-row');
        updateColorImageCount(colorRow);
    }

    // Update the image count (for additional images only, excluding main image)
    function updateColorImageCount(colorRow) {
        const imagesContainer = colorRow.querySelector('.color-images-container');
        const additionalImageCount = imagesContainer.querySelectorAll('.color-image-row').length;
        const countDisplay = imagesContainer.querySelector('.color-image-count');
        const addBtn = colorRow.querySelector('button[onclick="addColorImageRow(this)"]');

        if (countDisplay) {
            countDisplay.textContent = additionalImageCount;
        }

        // Disable add button if at max (3 additional images)
        if (addBtn) {
            addBtn.disabled = additionalImageCount >= 3;
            addBtn.classList.toggle('opacity-50', additionalImageCount >= 3);
            addBtn.classList.toggle('cursor-not-allowed', additionalImageCount >= 3);
        }
    }

    // Update hex display when color picker changes
    document.addEventListener('change', function(e) {
        if (e.target.name === 'color_hexes[]' && e.target.classList.contains('color-hex-input')) {
            const hexDisplay = e.target.parentElement.querySelector('.hex-display');
            if (hexDisplay) {
                hexDisplay.textContent = e.target.value;
            }
        }
        // Update default color hex input when color picker changes
        if (e.target.id === 'default-color-picker') {
            const hexInput = document.getElementById('default-color-hex-input');
            if (hexInput) {
                hexInput.value = e.target.value;
            }
        }
    });

    // Update color picker when hex input changes
    const hexInput = document.getElementById('default-color-hex-input');
    if (hexInput) {
        hexInput.addEventListener('input', function(e) {
            const value = e.target.value.trim();
            // Allow typing without validation until change event
            if (value && /^#[0-9A-Fa-f]{6}$/.test(value)) {
                const picker = document.getElementById('default-color-picker');
                if (picker) {
                    picker.value = value;
                }
            }
        });
    }

    // Validate hex input on blur/change
    function validateHexInput(input) {
        let value = input.value.trim();
        
        // Add # if missing
        if (value && !value.startsWith('#')) {
            value = '#' + value;
        }
        
        // Validate hex format
        if (!value || !/^#[0-9A-Fa-f]{6}$/.test(value)) {
            alert('Invalid hex code. Use format: #f0f0f0');
            input.value = document.getElementById('default-color-picker').value;
            return false;
        }
        
        // Update picker and input
        document.getElementById('default-color-picker').value = value;
        input.value = value;
        return true;
    }

    // Pending gallery files: append each time user picks (one-by-one or multi); drag to reorder; synced to input on submit
    window.galleryPendingFiles = window.galleryPendingFiles || [];
    window.galleryFileUidCounter = window.galleryFileUidCounter || 0;
    window.galleryPendingObjectUrls = window.galleryPendingObjectUrls || [];

    function revokePendingObjectUrls() {
        (window.galleryPendingObjectUrls || []).forEach(function (u) {
            try { URL.revokeObjectURL(u); } catch (e) {}
        });
        window.galleryPendingObjectUrls = [];
    }

    function getExistingGalleryCountAfterRemovals() {
        const items = document.querySelectorAll('#gallery-sortable .gallery-sort-item');
        if (!items.length) {
            return 0;
        }
        var n = 0;
        items.forEach(function (row) {
            var cb = row.querySelector('input[name="remove_gallery_images[]"]');
            if (!cb || !cb.checked) {
                n++;
            }
        });
        return n;
    }

    function getMaxNewGallerySlots() {
        return Math.max(0, 3 - getExistingGalleryCountAfterRemovals());
    }

    function updateGallerySlotsHint() {
        var el = document.getElementById('gallery-slots-hint');
        if (!el) {
            return;
        }
        var max = getMaxNewGallerySlots();
        var pending = (window.galleryPendingFiles || []).length;
        el.textContent = 'Slots left for new images: ' + (max - pending) + ' of ' + max + ' (3 total per product).';
    }

    function galleryFileDedupeKey(file) {
        return file.name + '|' + file.size + '|' + file.lastModified;
    }

    window.__galleryPendingDragEl = null;

    function bindPendingPreviewDragSort() {
        var previewDiv = document.getElementById('gallery-preview');
        if (!previewDiv) {
            return;
        }
        previewDiv.querySelectorAll('.gallery-pending-item').forEach(function (el) {
            el.addEventListener('dragstart', function (e) {
                window.__galleryPendingDragEl = el;
                el.classList.add('opacity-50');
                try {
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', el.getAttribute('data-uid') || 'pending-gallery');
                } catch (err) {}
            });
            el.addEventListener('dragend', function () {
                el.classList.remove('opacity-50');
                window.__galleryPendingDragEl = null;
            });
        });
    }

    (function initPendingPreviewDropDelegate() {
        var previewDiv = document.getElementById('gallery-preview');
        if (!previewDiv || previewDiv.getAttribute('data-drop-delegate') === '1') {
            return;
        }
        previewDiv.setAttribute('data-drop-delegate', '1');
        previewDiv.addEventListener('dragover', function (e) {
            e.preventDefault();
            try {
                e.dataTransfer.dropEffect = 'move';
            } catch (err) {}
        });
        previewDiv.addEventListener('drop', function (e) {
            e.preventDefault();
            var dragged = window.__galleryPendingDragEl;
            if (!dragged) {
                return;
            }
            var target = e.target.closest('.gallery-pending-item');
            if (!target || target === dragged) {
                return;
            }
            var rect = target.getBoundingClientRect();
            var after = (e.clientY - rect.top) > rect.height / 2;
            if (after) {
                target.after(dragged);
            } else {
                target.before(dragged);
            }
            var order = [];
            previewDiv.querySelectorAll('.gallery-pending-item').forEach(function (node) {
                var uid = parseInt(node.getAttribute('data-uid'), 10);
                var f = (window.galleryPendingFiles || []).find(function (x) { return x.__uid === uid; });
                if (f) {
                    order.push(f);
                }
            });
            window.galleryPendingFiles = order;
            renderGalleryPendingPreviews();
        });
    })();

    function renderGalleryPendingPreviews() {
        var previewDiv = document.getElementById('gallery-preview');
        var previewContainer = document.getElementById('gallery-preview-container');
        var selectedCount = document.getElementById('selected-files-count');
        if (!previewDiv || !previewContainer) {
            return;
        }
        revokePendingObjectUrls();
        previewDiv.innerHTML = '';
        (window.galleryPendingFiles || []).forEach(function (file, index) {
            if (!file.__uid) {
                file.__uid = ++window.galleryFileUidCounter;
            }
            var url = URL.createObjectURL(file);
            window.galleryPendingObjectUrls.push(url);
            var wrap = document.createElement('div');
            wrap.className = 'gallery-pending-item border rounded-lg p-2 bg-white shadow-sm cursor-grab active:cursor-grabbing relative';
            wrap.draggable = true;
            wrap.setAttribute('data-uid', String(file.__uid));
            var imgBox = document.createElement('div');
            imgBox.className = 'h-36 w-full flex items-center justify-center bg-gray-50 rounded border border-gray-100 mb-2';
            var img = document.createElement('img');
            img.src = url;
            img.alt = '';
            img.draggable = false;
            img.setAttribute('draggable', 'false');
            img.className = 'max-h-full max-w-full object-contain pointer-events-none select-none';
            imgBox.appendChild(img);
            wrap.appendChild(imgBox);
            var nameEl = document.createElement('p');
            nameEl.className = 'text-xs font-medium text-gray-800 truncate px-1';
            nameEl.title = file.name;
            nameEl.textContent = file.name;
            wrap.appendChild(nameEl);
            var metaEl = document.createElement('p');
            metaEl.className = 'text-xs text-gray-500 px-1';
            metaEl.textContent = (file.size / 1024).toFixed(0) + ' KB · #' + (index + 1);
            wrap.appendChild(metaEl);
            var rm = document.createElement('button');
            rm.type = 'button';
            rm.className = 'remove-pending-gallery mt-2 w-full text-xs py-1 rounded bg-red-50 text-red-700 hover:bg-red-100';
            rm.textContent = 'Remove';
            wrap.appendChild(rm);
            rm.addEventListener('click', function () {
                window.galleryPendingFiles = (window.galleryPendingFiles || []).filter(function (x) {
                    return x.__uid !== file.__uid;
                });
                renderGalleryPendingPreviews();
                updateGallerySlotsHint();
            });
            previewDiv.appendChild(wrap);
        });
        bindPendingPreviewDragSort();
        if (selectedCount) {
            selectedCount.textContent = String((window.galleryPendingFiles || []).length);
        }
        previewContainer.classList.toggle('hidden', !(window.galleryPendingFiles || []).length);
        updateGallerySlotsHint();
    }

    function handleGallerySelection(input) {
        var files = Array.prototype.slice.call(input.files || [], 0);
        input.value = '';

        var allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
        var maxSize = 5 * 1024 * 1024;
        var maxSlots = getMaxNewGallerySlots();
        var pending = window.galleryPendingFiles || [];
        var existingKeys = {};
        pending.forEach(function (f) {
            existingKeys[galleryFileDedupeKey(f)] = true;
        });

        var slotFullMsg = false;
        for (var fi = 0; fi < files.length; fi++) {
            var file = files[fi];
            if (pending.length >= maxSlots) {
                if (!slotFullMsg && files.length) {
                    slotFullMsg = true;
                    alert('Maximum ' + maxSlots + ' new image(s) allowed (3 gallery images per product, minus images already saved). Remove a preview or mark a saved image for removal, then try again.');
                }
                break;
            }
            if (!allowedTypes.includes(file.type)) {
                alert('Invalid file type: ' + file.name + '. Allowed: JPG, PNG, GIF, WEBP, AVIF');
                continue;
            }
            if (file.size > maxSize) {
                alert('File too large: ' + file.name + ' exceeds 5MB limit');
                continue;
            }
            var key = galleryFileDedupeKey(file);
            if (existingKeys[key]) {
                continue;
            }
            existingKeys[key] = true;
            pending.push(file);
        }

        window.galleryPendingFiles = pending;
        renderGalleryPendingPreviews();
        return true;
    }

    // Saved gallery: drag-and-drop order (gallery_order hidden field)
    (function initGallerySortable() {
        var container = document.getElementById('gallery-sortable');
        var orderInput = document.getElementById('gallery-order-input');
        var form = document.getElementById('product-edit-form');
        if (!container || !orderInput) {
            return;
        }
        var dragged = null;
        function syncGalleryOrder() {
            var items = container.querySelectorAll('.gallery-sort-item');
            var ids = [];
            items.forEach(function (el, index) {
                var id = el.getAttribute('data-image-id');
                ids.push(id);
                var badge = el.querySelector('.gallery-thumb-order-badge');
                if (badge) {
                    badge.textContent = String(index + 1);
                }
                var thumbStrong = el.querySelector('.js-gallery-thumb-n-strong');
                if (thumbStrong) {
                    thumbStrong.textContent = 'Thumb ' + (index + 1);
                }
            });
            orderInput.value = ids.join(',');

            var mockParent = document.getElementById('mock-gallery-thumbs');
            if (mockParent) {
                ids.forEach(function (id) {
                    var slot = mockParent.querySelector('[data-mock-for-id="' + id + '"]');
                    if (slot) {
                        mockParent.appendChild(slot);
                    }
                });
                mockParent.querySelectorAll('.mock-gallery-slot').forEach(function (slot, i) {
                    var lab = slot.querySelector('.mock-order-label');
                    if (lab) {
                        lab.textContent = 'Thumb ' + (i + 1);
                    }
                });
            }

            document.querySelectorAll('.js-main-image-gallery-option').forEach(function (row) {
                var iid = row.getAttribute('data-image-id');
                var pos = ids.indexOf(iid);
                if (pos < 0) {
                    return;
                }
                var n = pos + 1;
                var strongEl = row.querySelector('.js-main-choice-thumb-strong');
                var subEl = row.querySelector('.js-main-choice-thumb-sub');
                if (strongEl) {
                    strongEl.textContent = 'Gallery thumb ' + n;
                }
                if (subEl) {
                    subEl.innerHTML = 'Same image as <strong class="font-medium text-gray-600">Thumb ' + n + '</strong> in the preview and in the draggable gallery cards below (left to right). Selecting it makes this picture the hero and listing image.';
                }
            });
        }
        container.querySelectorAll('.gallery-sort-item').forEach(function (el) {
            el.addEventListener('dragstart', function () {
                dragged = el;
                el.classList.add('opacity-50');
            });
            el.addEventListener('dragend', function () {
                el.classList.remove('opacity-50');
                dragged = null;
                syncGalleryOrder();
            });
        });
        container.addEventListener('dragover', function (e) {
            e.preventDefault();
        });
        container.addEventListener('drop', function (e) {
            e.preventDefault();
            if (!dragged) {
                return;
            }
            var target = e.target.closest('.gallery-sort-item');
            if (!target || target === dragged) {
                return;
            }
            var rect = target.getBoundingClientRect();
            var after = (e.clientY - rect.top) > rect.height / 2;
            if (after) {
                target.after(dragged);
            } else {
                target.before(dragged);
            }
            syncGalleryOrder();
        });
        syncGalleryOrder();
        if (form) {
            form.addEventListener('submit', syncGalleryOrder);
        }
    })();

    document.querySelectorAll('#gallery-sortable input[name="remove_gallery_images[]"]').forEach(function (cb) {
        cb.addEventListener('change', function () {
            var max = getMaxNewGallerySlots();
            while ((window.galleryPendingFiles || []).length > max) {
                window.galleryPendingFiles.pop();
            }
            renderGalleryPendingPreviews();
            updateGallerySlotsHint();
        });
    });

    (function initGalleryFormSubmit() {
        var form = document.getElementById('product-edit-form');
        if (!form) {
            return;
        }
        form.addEventListener('submit', function () {
            var input = document.getElementById('gallery-input');
            if (input && window.galleryPendingFiles && window.galleryPendingFiles.length > 0) {
                try {
                    var dt = new DataTransfer();
                    window.galleryPendingFiles.forEach(function (f) {
                        dt.items.add(f);
                    });
                    input.files = dt.files;
                } catch (err) {
                    console.error(err);
                }
            }
        });
        updateGallerySlotsHint();
    })();

    function validateGalleryImages(input) {
        return handleGallerySelection(input);
    }
</script>

<?php include 'includes/footer.php'; ?>