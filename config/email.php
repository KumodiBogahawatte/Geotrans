<?php
// Email Configuration
if (!defined('SMTP_HOST')) {
    define('SMTP_HOST', 'smtp.gmail.com');
    define('SMTP_PORT', 587);
    define('SMTP_USERNAME', 'kumodib@gmail.com'); // Replace with your email
    define('SMTP_PASSWORD', '');     // Replace with your app password
    define('SMTP_FROM_EMAIL', 'noreply@geotrans.com');
    define('SMTP_FROM_NAME', 'Geotrans');
    define('SMTP_ENCRYPTION', 'tls'); // tls or ssl
    define('ADMIN_EMAIL', 'admin@geotrans.com'); // Replace with admin email
}

/**
 * Send email using PHP mail function
 * For production, consider using PHPMailer or similar library
 */
function sendEmail($to, $subject, $message, $headers = '') {
    if (empty($headers)) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM_EMAIL . ">" . "\r\n";
    }
    
    return mail($to, $subject, $message, $headers);
}

/**
 * Send contact form notification to admin
 */
function sendContactNotification($name, $email, $subject, $message) {
    $email_subject = "New Contact Message: " . $subject;
    
    $email_body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #8D4887; color: white; padding: 20px; text-align: center; }
            .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
            .info-row { margin: 10px 0; }
            .label { font-weight: bold; color: #8D4887; }
            .message-box { background-color: white; padding: 15px; border-left: 4px solid #8D4887; margin-top: 15px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Contact Message</h2>
            </div>
            <div class='content'>
                <div class='info-row'>
                    <span class='label'>From:</span> {$name}
                </div>
                <div class='info-row'>
                    <span class='label'>Email:</span> {$email}
                </div>
                <div class='info-row'>
                    <span class='label'>Subject:</span> {$subject}
                </div>
                <div class='message-box'>
                    <p><strong>Message:</strong></p>
                    <p>" . nl2br(htmlspecialchars($message)) . "</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail(ADMIN_EMAIL, $email_subject, $email_body);
}

/**
 * Send auto-reply to contact form submitter
 */
function sendContactAutoReply($to_email, $name) {
    $subject = "Thank you for contacting Geotrans";
    
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #8D4887; color: white; padding: 20px; text-align: center; }
            .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Thank You for Contacting Us!</h2>
            </div>
            <div class='content'>
                <p>Dear {$name},</p>
                <p>Thank you for reaching out to Geotrans. We have received your message and will get back to you as soon as possible.</p>
                <p>Our team typically responds within 24-48 hours during business days.</p>
                <p>Best regards,<br>Geotrans Team</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($to_email, $subject, $message);
}

/**
 * Send order confirmation email
 */
function sendOrderConfirmation($to_email, $order_number, $order_details) {
    $subject = "Order Confirmation - Order #{$order_number}";
    
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #8D4887; color: white; padding: 20px; text-align: center; }
            .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Order Confirmation</h2>
            </div>
            <div class='content'>
                <p>Thank you for your order!</p>
                <p><strong>Order Number:</strong> {$order_number}</p>
                {$order_details}
                <p>We'll send you a shipping notification when your order is on its way.</p>
                <p>Best regards,<br>Geotrans Team</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($to_email, $subject, $message);
}
?>
