<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method'], 405);
}

// Check if user is logged in
if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Please login to submit a review'], 401);
}

$user_id = getUserId();
$product_id = intval($_POST['product_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$review_title = sanitizeInput($_POST['review_title'] ?? '');
$review_text = sanitizeInput($_POST['review_text'] ?? '');

// Validation
if ($product_id <= 0) {
    jsonResponse(['success' => false, 'message' => 'Invalid product'], 400);
}

if ($rating < 1 || $rating > 5) {
    jsonResponse(['success' => false, 'message' => 'Rating must be between 1 and 5 stars'], 400);
}

if (empty($review_text)) {
    jsonResponse(['success' => false, 'message' => 'Please write a review'], 400);
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Check if user already reviewed this product
    $check_query = "SELECT review_id FROM product_reviews 
                    WHERE product_id = :product_id AND user_id = :user_id";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bindParam(':product_id', $product_id);
    $check_stmt->bindParam(':user_id', $user_id);
    $check_stmt->execute();
    
    if ($check_stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'You have already reviewed this product'], 400);
    }
    
    // Check if user purchased this product (verified purchase)
    $purchase_query = "SELECT COUNT(*) as count FROM order_items oi
                      INNER JOIN orders o ON oi.order_id = o.order_id
                      WHERE o.user_id = :user_id 
                      AND oi.product_id = :product_id
                      AND o.order_status IN ('Delivered', 'Completed')";
    $purchase_stmt = $conn->prepare($purchase_query);
    $purchase_stmt->bindParam(':user_id', $user_id);
    $purchase_stmt->bindParam(':product_id', $product_id);
    $purchase_stmt->execute();
    $purchase_result = $purchase_stmt->fetch(PDO::FETCH_ASSOC);
    $is_verified_purchase = $purchase_result['count'] > 0 ? 1 : 0;
    
    // Insert review
    $insert_query = "INSERT INTO product_reviews 
                    (product_id, user_id, rating, review_title, review_text, is_verified_purchase, is_approved) 
                    VALUES (:product_id, :user_id, :rating, :review_title, :review_text, :is_verified_purchase, 0)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bindParam(':product_id', $product_id);
    $insert_stmt->bindParam(':user_id', $user_id);
    $insert_stmt->bindParam(':rating', $rating);
    $insert_stmt->bindParam(':review_title', $review_title);
    $insert_stmt->bindParam(':review_text', $review_text);
    $insert_stmt->bindParam(':is_verified_purchase', $is_verified_purchase);
    
    if ($insert_stmt->execute()) {
        jsonResponse([
            'success' => true,
            'message' => 'Thank you! Your review has been submitted and is awaiting approval.'
        ]);
    } else {
        jsonResponse(['success' => false, 'message' => 'Failed to submit review'], 500);
    }
    
} catch (Exception $e) {
    error_log("Review submission error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
}
