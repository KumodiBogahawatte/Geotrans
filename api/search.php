<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../classes/Product.php';

header('Content-Type: application/json');

$product = new Product();
$search_term = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';

if (strlen($search_term) < 2) {
    jsonResponse(['success' => false, 'message' => 'Search term too short'], 400);
}

$results = $product->search($search_term, 10);

$formatted_results = array_map(function($item) {
    $imagePath = !empty($item['main_image']) 
        ? 'assets/images/products/' . $item['main_image'] 
        : 'assets/images/categories/default.png';
    
    return [
        'id' => $item['product_id'],
        'name' => $item['product_name'],
        'slug' => $item['product_slug'],
        'price' => getProductPrice($item),
        'image' => $imagePath,
        'brand' => $item['brand_name'],
        'category' => $item['category_name']
    ];
}, $results);

jsonResponse([
    'success' => true,
    'results' => $formatted_results
]);
?>
