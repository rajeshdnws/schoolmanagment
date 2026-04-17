<?php
include('session.php');
requireAccess('chatbot', 'delete');
include('../db_config.php');

$faq_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($faq_id <= 0) {
    header("Location: chatbot.php?error=Invalid FAQ ID");
    exit;
}

// Delete the FAQ
$query = "DELETE FROM chatbot_faqs WHERE id = $faq_id";

if (executeQuery($query)) {
    // Also delete related conversations
    executeQuery("DELETE FROM chatbot_conversations WHERE faq_id = $faq_id");
    header("Location: chatbot.php?success=FAQ deleted successfully");
} else {
    header("Location: chatbot.php?error=Failed to delete FAQ");
}
exit;
?>
