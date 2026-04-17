<?php
include('session.php');
requireAccess('chatbot', 'edit');

$page_title = 'Edit FAQ - School Management System';
include('header.php');

$error = '';
$success = '';
$faq_item = null;

// Get FAQ ID
$faq_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($faq_id <= 0) {
    $error = 'Invalid FAQ ID';
} else {
    $faq_item = getRow("SELECT * FROM chatbot_faqs WHERE id = $faq_id");
    
    if (!$faq_item) {
        $error = 'FAQ not found';
    }
}

$categories = array('general', 'admission', 'fees', 'attendance', 'academics', 'transport', 'contact', 'other');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error && $faq_item) {
    $question = sanitize($_POST['question'] ?? '');
    $answer = $_POST['answer'] ?? '';
    $keywords = sanitize($_POST['keywords'] ?? '');
    $category = sanitize($_POST['category'] ?? 'general');
    $priority = (int)($_POST['priority'] ?? 1);
    $status = sanitize($_POST['status'] ?? 'active');
    
    if (empty($question)) {
        $error = 'Question is required';
    } elseif (empty($answer)) {
        $error = 'Answer is required';
    } else {
        $query = "UPDATE chatbot_faqs SET 
                  question = '$question', 
                  answer = '$answer', 
                  keywords = '$keywords', 
                  category = '$category', 
                  priority = $priority, 
                  status = '$status',
                  updated_at = NOW()
                  WHERE id = $faq_id";
        
        if (executeQuery($query)) {
            $success = 'FAQ updated successfully!';
            $faq_item = getRow("SELECT * FROM chatbot_faqs WHERE id = $faq_id");
        } else {
            $error = 'Failed to update FAQ. Please try again.';
        }
    }
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}
?>

<div class="container">
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
            <br><a href="chatbot.php" class="btn btn-sm btn-primary" style="margin-top: 10px;">Back to FAQs</a>
        </div>
    <?php elseif ($faq_item): ?>
        <div class="form-header">
            <h1>✎ Edit FAQ</h1>
            <a href="chatbot.php" class="btn btn-secondary">Back</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="faq-form">
            <div class="form-group">
                <label for="question">Question *</label>
                <input type="text" id="question" name="question" value="<?php echo htmlspecialchars($faq_item['question']); ?>" required>
            </div>

            <div class="form-group">
                <label for="answer">Answer *</label>
                <textarea id="answer" name="answer" rows="8" required><?php echo htmlspecialchars($faq_item['answer']); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="keywords">Keywords</label>
                    <input type="text" id="keywords" name="keywords" value="<?php echo htmlspecialchars($faq_item['keywords']); ?>" placeholder="keyword1, keyword2">
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php echo $faq_item['category'] === $cat ? 'selected' : ''; ?>>
                                <?php echo ucfirst($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="priority">Priority</label>
                    <input type="number" id="priority" name="priority" value="<?php echo $faq_item['priority']; ?>" min="1" max="100">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="active" <?php echo $faq_item['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $faq_item['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Update FAQ</button>
                <a href="chatbot.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<style>
    .container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }

    .form-header h1 {
        margin: 0;
        color: #333;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #666;
        color: white;
    }

    .btn-secondary:hover {
        background: #555;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }

    .faq-form {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-group textarea {
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #eee;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background: #fee;
        border-left: 4px solid #f33;
        color: #c33;
    }

    .alert-success {
        background: #efe;
        border-left: 4px solid #3f3;
        color: #333;
    }
</style>

<?php include('footer.php'); ?>
