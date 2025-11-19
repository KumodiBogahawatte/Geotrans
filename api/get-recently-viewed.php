<?php
session_start();
require_once '../includes/recently-viewed.php';

header('Content-Type: application/json');

try {
    $recentlyViewed = new RecentlyViewed();
    $products = $recentlyViewed->getWithDetails(5);
    
    echo json_encode(['success' => true, 'products' => $products]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
