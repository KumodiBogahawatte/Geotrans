<?php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../classes/Wishlist.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$wishlist = new Wishlist();
$user_id = $_SESSION['user_id'];

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$product_id = $input['product_id'] ?? 0;

switch ($action) {
    case 'add':
        $result = $wishlist->add($user_id, $product_id);
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Added to wishlist' : 'Already in wishlist',
            'wishlist_count' => $wishlist->getCount($user_id)
        ]);
        break;

    case 'remove':
        $result = $wishlist->remove($user_id, $product_id);
        echo json_encode([
            'success' => $result,
            'message' => 'Removed from wishlist',
            'wishlist_count' => $wishlist->getCount($user_id)
        ]);
        break;

    case 'check':
        $exists = $wishlist->exists($user_id, $product_id);
        echo json_encode([
            'success' => true,
            'in_wishlist' => $exists
        ]);
        break;

    case 'count':
        $count = $wishlist->getCount($user_id);
        echo json_encode([
            'success' => true,
            'wishlist_count' => $count
        ]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
