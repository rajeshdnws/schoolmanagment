<?php
// Chatbot API Endpoint
include('db_config.php');

header('Content-Type: application/json');

// Get user message
$user_message = isset($_POST['message']) ? trim($_POST['message']) : '';
$session_id = isset($_POST['session_id']) ? $_POST['session_id'] : md5(uniqid(rand(), true));

if (empty($user_message)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a message',
        'session_id' => $session_id
    ]);
    exit;
}

// Convert message to lowercase for matching
$message_lower = strtolower($user_message);

// Get all active FAQs
$faqs = getAllRows("SELECT * FROM chatbot_faqs WHERE status = 'active' ORDER BY priority DESC");

$best_match = null;
$best_score = 0;

// Simple keyword matching algorithm
foreach ($faqs as $faq) {
    $score = 0;
    
    // Check question match
    $question_lower = strtolower($faq['question']);
    if (strpos($question_lower, $message_lower) !== false || strpos($message_lower, $question_lower) !== false) {
        $score += 100;
    }
    
    // Check keywords
    if (!empty($faq['keywords'])) {
        $keywords = array_map('trim', explode(',', $faq['keywords']));
        foreach ($keywords as $keyword) {
            $keyword_lower = strtolower($keyword);
            if (strpos($message_lower, $keyword_lower) !== false) {
                $score += 50;
            }
        }
    }
    
    // Check individual words
    $words = explode(' ', $message_lower);
    $question_words = explode(' ', $question_lower);
    foreach ($words as $word) {
        if (strlen($word) > 3) { // Only consider words longer than 3 characters
            foreach ($question_words as $q_word) {
                if (strpos($q_word, $word) !== false || strpos($word, $q_word) !== false) {
                    $score += 10;
                }
            }
        }
    }
    
    if ($score > $best_score) {
        $best_score = $score;
        $best_match = $faq;
    }
}

// Determine confidence
$confidence = 0;
$response = "I'm not sure about that. Please try rephrasing your question or contact our office directly.";

if ($best_match && $best_score >= 10) {
    $response = $best_match['answer'];
    $faq_id = $best_match['id'];
    
    // Calculate confidence percentage
    if ($best_score >= 100) {
        $confidence = 95;
    } elseif ($best_score >= 50) {
        $confidence = 80;
    } else {
        $confidence = 60;
    }
} else {
    $faq_id = null;
}

// Store conversation
$user_ip = $_SERVER['REMOTE_ADDR'];
$user_message_escaped = mysqli_real_escape_string($conn, $user_message);
$response_escaped = mysqli_real_escape_string($conn, $response);

$insert_query = "INSERT INTO chatbot_conversations (session_id, user_message, bot_response, faq_id, confidence_score, user_ip) 
                 VALUES ('$session_id', '$user_message_escaped', '$response_escaped', " . ($faq_id ? $faq_id : 'NULL') . ", $confidence, '$user_ip')";

executeQuery($insert_query);

echo json_encode([
    'success' => true,
    'message' => $response,
    'session_id' => $session_id,
    'confidence' => $confidence,
    'faq_id' => $faq_id
]);
?>
