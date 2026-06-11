<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

// Load email config but don't fail if it errors
@include_once __DIR__ . '/../config/email.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get form data
$first_name = sanitizeInput($_POST['first_name'] ?? '');
$last_name = sanitizeInput($_POST['last_name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$phone = sanitizeInput($_POST['phone'] ?? '');
$subject = sanitizeInput($_POST['subject'] ?? 'General Inquiry');
$message = sanitizeInput($_POST['message'] ?? '');

// Validation
$errors = [];

if (empty($first_name)) {
    $errors[] = 'First name is required';
}

if (empty($last_name)) {
    $errors[] = 'Last name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        throw new Exception('Database connection failed');
    }
    
    $full_name = $first_name . ' ' . $last_name;
    
    // Insert into database
    $query = "INSERT INTO contact_messages (name, email, phone, subject, message) 
              VALUES (:name, :email, :phone, :subject, :message)";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $full_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':message', $message);
    
    if ($stmt->execute()) {
        // Send email notifications (optional - don't fail if emails can't be sent)
        if (function_exists('sendContactNotification') && function_exists('sendContactAutoReply')) {
            try {
                @sendContactNotification($full_name, $email, $subject, $message);
                @sendContactAutoReply($email, $first_name);
            } catch (Exception $e) {
                error_log("Email sending failed: " . $e->getMessage());
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for contacting us! We will get back to you soon.'
        ]);
        exit;
    } else {
        $errorInfo = $stmt->errorInfo();
        error_log("Contact form SQL error: " . print_r($errorInfo, true));
        echo json_encode(['success' => false, 'message' => 'Failed to submit message. Please try again.']);
        exit;
    }
    
} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
?>
