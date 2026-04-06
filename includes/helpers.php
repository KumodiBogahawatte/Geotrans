<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper functions for session management

function getSessionId() {
    return session_id();
}

function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserData($key = null) {
    if (!isLoggedIn()) {
        return null;
    }
    
    if ($key) {
        return isset($_SESSION['user_data'][$key]) ? $_SESSION['user_data'][$key] : null;
    }
    
    return isset($_SESSION['user_data']) ? $_SESSION['user_data'] : null;
}

function setUserSession($user) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_data'] = [
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'user_type' => $user['user_type'],
        'profile_photo' => $user['profile_photo'] ?? null
    ];
}

function destroyUserSession() {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_data']);
    session_destroy();
}

function isAdmin() {
    return isLoggedIn() && getUserData('user_type') === 'admin';
}

function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

function formatPrice($price, $currency = null) {
    // require_once __DIR__ . '/currency.php';
    
    // if ($currency === null) {
    //     $currency = CurrencyConverter::getSelectedCurrency();
    // }
    
    // $converter = new CurrencyConverter();
    // return $converter->formatPrice($price, $currency);
    
    return 'Rs' . number_format($price, 2);
}

function calculateDiscount($original_price, $sale_price) {
    if ($sale_price && $sale_price < $original_price) {
        return round((($original_price - $sale_price) / $original_price) * 100);
    }
    return 0;
}

function getProductPrice($product) {
    if (isset($product['sale_price']) && $product['sale_price'] > 0 && $product['sale_price'] < $product['price']) {
        return $product['sale_price'];
    }
    return $product['price'];
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

function timeAgo($timestamp) {
    $time = strtotime($timestamp);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $time);
    }
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

/** Use Swiper image if present under project root, else fallback (homepage carousel stacks). */
function swiperAsset($preferredPath, $fallbackPath) {
    $full = __DIR__ . '/../' . $preferredPath;
    return file_exists($full) ? $preferredPath : $fallbackPath;
}

/**
 * Web URL for banner media stored as a project-relative path (e.g. assets/images/banners/foo.jpg).
 * Uses $GLOBALS['base_url'] when set (see includes/header.php), else /Geotrans/.
 */
function bannerMediaUrl($path) {
    if ($path === null || $path === '') {
        return '';
    }
    $path = str_replace('\\', '/', trim((string) $path));
    if ($path === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    $path = ltrim($path, '/');
    $base = '/Geotrans/';
    global $base_url;
    if (!empty($base_url)) {
        $base = (string) $base_url;
    }
    return rtrim($base, '/') . '/' . $path;
}
?>
