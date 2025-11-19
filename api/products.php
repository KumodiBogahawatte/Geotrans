<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../classes/Brand.php';

header('Content-Type: application/json');

$product = new Product();
$category = new Category();
$brand = new Brand();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 20;

$filters = [
    'category_id' => isset($_GET['category']) ? intval($_GET['category']) : null,
    'brand_id' => isset($_GET['brand']) ? intval($_GET['brand']) : null,
    'min_price' => isset($_GET['min_price']) ? floatval($_GET['min_price']) : null,
    'max_price' => isset($_GET['max_price']) ? floatval($_GET['max_price']) : null,
    'search' => isset($_GET['search']) ? sanitizeInput($_GET['search']) : null,
    'sort' => isset($_GET['sort']) ? sanitizeInput($_GET['sort']) : 'newest',
    'is_featured' => isset($_GET['featured']) ? 1 : null,
    'is_bestseller' => isset($_GET['bestseller']) ? 1 : null,
    'is_new_arrival' => isset($_GET['new']) ? 1 : null
];

$products = $product->getAll($page, $per_page, $filters);
$total_count = $product->getCount($filters);
$total_pages = ceil($total_count / $per_page);

$formatted_products = array_map(function($item) {
    $price = getProductPrice($item);
    $discount = calculateDiscount($item['price'], $item['sale_price']);
    
    return [
        'id' => $item['product_id'],
        'name' => $item['product_name'],
        'slug' => $item['product_slug'],
        'price' => $item['price'],
        'sale_price' => $item['sale_price'],
        'current_price' => $price,
        'discount' => $discount,
        'image' => $item['main_image'],
        'brand' => $item['brand_name'],
        'category' => $item['category_name'],
        'rating' => $item['avg_rating'] ?? 0,
        'review_count' => $item['review_count'] ?? 0,
        'stock' => $item['stock_quantity']
    ];
}, $products);

jsonResponse([
    'success' => true,
    'products' => $formatted_products,
    'pagination' => [
        'current_page' => $page,
        'total_pages' => $total_pages,
        'total_count' => $total_count,
        'per_page' => $per_page
    ]
]);
?>
