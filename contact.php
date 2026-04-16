<?php
// Contact Handler for Frontend
// This file processes contact form submissions

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    $name = isset($input['name']) ? sanitize($input['name']) : '';
    $email = isset($input['email']) ? sanitize($input['email']) : '';
    $subject = isset($input['subject']) ? sanitize($input['subject']) : 'Website Contact';
    $message = isset($input['message']) ? sanitize($input['message']) : '';
    
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required';
    }
    
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }
    
    // You can save to database or send email here
    // For now, we'll just log it
    
    // Example: Send email
    $to = 'admin@school.com'; // Change to your email
    $emailSubject = "New Contact Form Submission: " . $subject;
    $emailBody = "
    <h2>New Contact Form Submission</h2>
    <p><strong>Name:</strong> {$name}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Subject:</strong> {$subject}</p>
    <p><strong>Message:</strong></p>
    <p>{$message}</p>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: {$email}" . "\r\n";
    
    // Uncomment to send email (requires mail server configuration)
    // mail($to, $emailSubject, $emailBody, $headers);
    
    // Log to file as backup
    $logFile = __DIR__ . '/messages.log';
    $logEntry = date('Y-m-d H:i:s') . " | Name: {$name} | Email: {$email} | Subject: {$subject}\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Message received successfully'
    ]);
    
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
