<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Cart.php';

$cart = new Cart();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();

// Get request method and action
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET requests (for fetching cart data)
if ($method === 'GET') {
    $action = $_GET['action'] ?? 'get';
    
    switch ($action) {
        case 'get':
            // Get cart items
            $items = $cart->getItems($user_id, $session_id);
            $count = $cart->getCount($user_id, $session_id);
            $total = $cart->getCartTotal($user_id, $session_id);
            
            echo json_encode([
                'success' => true,
                'items' => $items,
                'count' => $count,
                'total' => $total
            ]);
            break;
            
        case 'count':
            // Get cart count
            $count = $cart->getCount($user_id, $session_id);
            echo json_encode([
                'success' => true,
                'count' => $count
            ]);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action'
            ]);
    }
    exit;
}

// Handle POST requests (for modifying cart)
if ($method === 'POST') {
    // Check if it's JSON or form data
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    
    if (strpos($contentType, 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
    } else {
        $input = $_POST;
        $action = $_POST['action'] ?? '';
    }
    
    switch ($action) {
        case 'add':
            // Add item to cart
            $product_id = $input['product_id'] ?? 0;
            $quantity = $input['quantity'] ?? 1;
            
            if ($product_id > 0) {
                $result = $cart->addItem($product_id, $quantity, $user_id, $session_id);
                $count = $cart->getCount($user_id, $session_id);
                
                echo json_encode([
                    'success' => $result,
                    'message' => $result ? 'Added to cart' : 'Failed to add to cart',
                    'cart_count' => $count
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid product ID'
                ]);
            }
            break;
            
        case 'update':
            // Update cart item quantity
            $cart_id = $input['cart_id'] ?? 0;
            $quantity = $input['quantity'] ?? 1;
            
            if ($cart_id > 0 && $quantity > 0) {
                $result = $cart->updateQuantity($cart_id, $quantity);
                $count = $cart->getCount($user_id, $session_id);
                $total = $cart->getCartTotal($user_id, $session_id);
                
                echo json_encode([
                    'success' => $result,
                    'message' => $result ? 'Cart updated' : 'Failed to update cart',
                    'cart_count' => $count,
                    'total' => $total
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid parameters'
                ]);
            }
            break;
            
        case 'remove':
            // Remove item from cart
            $cart_id = $input['cart_id'] ?? 0;
            
            if ($cart_id > 0) {
                $result = $cart->removeItem($cart_id);
                $count = $cart->getCount($user_id, $session_id);
                $total = $cart->getCartTotal($user_id, $session_id);
                
                echo json_encode([
                    'success' => $result,
                    'message' => $result ? 'Removed from cart' : 'Failed to remove from cart',
                    'cart_count' => $count,
                    'total' => $total
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid cart ID'
                ]);
            }
            break;
            
        case 'clear':
            // Clear entire cart
            $result = $cart->clearCart($user_id, $session_id);
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Cart cleared' : 'Failed to clear cart',
                'cart_count' => 0
            ]);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action'
            ]);
    }
    exit;
}

// Invalid request method
echo json_encode([
    'success' => false,
    'message' => 'Invalid request method'
]);

switch ($action) {
    case 'add':
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($product_id > 0) {
            // Check if product exists and has stock
            $productData = $product->getById($product_id);
            
            if ($productData && $productData['stock_quantity'] >= $quantity) {
                $user_id = getUserId();
                $session_id = !$user_id ? getSessionId() : null;
                
                if ($cart->addItem($product_id, $quantity, $user_id, $session_id)) {
                    $cart_count = $cart->getCount($user_id, $session_id);
                    jsonResponse([
                        'success' => true,
                        'message' => 'Product added to cart',
                        'cart_count' => $cart_count
                    ]);
                } else {
                    jsonResponse(['success' => false, 'message' => 'Failed to add product'], 500);
                }
            } else {
                jsonResponse(['success' => false, 'message' => 'Product out of stock'], 400);
            }
        } else {
            jsonResponse(['success' => false, 'message' => 'Invalid product'], 400);
        }
        break;
        
    case 'update':
        $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($cart_id > 0 && $quantity > 0) {
            if ($cart->updateQuantity($cart_id, $quantity)) {
                $user_id = getUserId();
                $session_id = !$user_id ? getSessionId() : null;
                $cart_total = $cart->getCartTotal($user_id, $session_id);
                
                jsonResponse([
                    'success' => true,
                    'message' => 'Cart updated',
                    'cart_total' => $cart_total
                ]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Failed to update cart'], 500);
            }
        } else {
            jsonResponse(['success' => false, 'message' => 'Invalid data'], 400);
        }
        break;
        
    case 'remove':
        $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
        
        if ($cart_id > 0) {
            if ($cart->removeItem($cart_id)) {
                $user_id = getUserId();
                $session_id = !$user_id ? getSessionId() : null;
                $cart_count = $cart->getCount($user_id, $session_id);
                $cart_total = $cart->getCartTotal($user_id, $session_id);
                
                jsonResponse([
                    'success' => true,
                    'message' => 'Product removed from cart',
                    'cart_count' => $cart_count,
                    'cart_total' => $cart_total
                ]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Failed to remove product'], 500);
            }
        } else {
            jsonResponse(['success' => false, 'message' => 'Invalid cart item'], 400);
        }
        break;
        
    case 'get':
        $user_id = getUserId();
        $session_id = !$user_id ? getSessionId() : null;
        
        $items = $cart->getItems($user_id, $session_id);
        $cart_total = $cart->getCartTotal($user_id, $session_id);
        $cart_count = $cart->getCount($user_id, $session_id);
        
        jsonResponse([
            'success' => true,
            'items' => $items,
            'cart_total' => $cart_total,
            'cart_count' => $cart_count
        ]);
        break;
        
    case 'clear':
        $user_id = getUserId();
        $session_id = !$user_id ? getSessionId() : null;
        
        if ($cart->clearCart($user_id, $session_id)) {
            jsonResponse([
                'success' => true,
                'message' => 'Cart cleared'
            ]);
        } else {
            jsonResponse(['success' => false, 'message' => 'Failed to clear cart'], 500);
        }
        break;
        
    default:
        jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
}
?>
