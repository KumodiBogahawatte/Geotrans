<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method'], 405);
}

$email = sanitizeInput($_POST['email'] ?? '');

// Validation
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['success' => false, 'message' => 'Please enter a valid email address'], 400);
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Check if email already subscribed
    $check_query = "SELECT newsletter_id FROM newsletter_subscribers WHERE email = :email";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    
    if ($check_stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'This email is already subscribed'], 400);
    }
    
    // Insert new subscriber
    $insert_query = "INSERT INTO newsletter_subscribers (email) VALUES (:email)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bindParam(':email', $email);
    
    if ($insert_stmt->execute()) {
        jsonResponse([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!'
        ]);
    } else {
        jsonResponse(['success' => false, 'message' => 'Failed to subscribe'], 500);
    }
    
} catch (Exception $e) {
    error_log("Newsletter subscription error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
}
?>
