<?php
include('session.php');
requireAccess('chatbot', 'add');

$page_title = 'Add FAQ - School Management System';
include('header.php');

$error = '';
$success = '';

// Get categories for dropdown
$categories = array('general', 'admission', 'fees', 'attendance', 'academics', 'transport', 'contact', 'other');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = sanitize($_POST['question'] ?? '');
    $answer = $_POST['answer'] ?? ''; // Allow HTML
    $keywords = sanitize($_POST['keywords'] ?? '');
    $category = sanitize($_POST['category'] ?? 'general');
    $priority = (int)($_POST['priority'] ?? 1);
    $status = sanitize($_POST['status'] ?? 'active');
    
    // Validate
    if (empty($question)) {
        $error = 'Question is required';
    } elseif (empty($answer)) {
        $error = 'Answer is required';
    } else {
        $query = "INSERT INTO chatbot_faqs (question, answer, keywords, category, priority, status, created_by) 
                  VALUES ('$question', '$answer', '$keywords', '$category', $priority, '$status', {$_SESSION['admin_id']})";
        
        if (executeQuery($query)) {
            $success = 'FAQ added successfully!';
            $_POST = [];
        } else {
            $error = 'Failed to add FAQ. Please try again.';
        }
    }
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}
?>

<div class="container">
    <div class="form-header">
        <h1>➕ Add New FAQ</h1>
        <a href="chatbot.php" class="btn btn-secondary">Back</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
            <br><a href="chatbot.php" class="btn btn-sm btn-primary" style="margin-top: 10px;">View All FAQs</a>
        </div>
    <?php endif; ?>

    <form method="POST" class="faq-form">
        <div class="form-group">
            <label for="question">Question *</label>
            <input type="text" id="question" name="question" value="<?php echo htmlspecialchars($_POST['question'] ?? ''); ?>" required>
            <small>This is the question customers might ask the chatbot</small>
        </div>

        <div class="form-group">
            <label for="answer">Answer *</label>
            <textarea id="answer" name="answer" rows="8" required><?php echo htmlspecialchars($_POST['answer'] ?? ''); ?></textarea>
            <small>Detailed answer to the question. HTML is supported.</small>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="keywords">Keywords</label>
                <input type="text" id="keywords" name="keywords" value="<?php echo htmlspecialchars($_POST['keywords'] ?? ''); ?>" placeholder="keyword1, keyword2, keyword3">
                <small>Comma-separated keywords for better matching</small>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo (isset($_POST['category']) && $_POST['category'] === $cat) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="priority">Priority</label>
                <input type="number" id="priority" name="priority" value="<?php echo htmlspecialchars($_POST['priority'] ?? '1'); ?>" min="1" max="100">
                <small>Higher number = higher priority (shown first)</small>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active" <?php echo (isset($_POST['status']) && $_POST['status'] === 'active') ? 'selected' : 'selected'; ?>>Active</option>
                    <option value="inactive" <?php echo isset($_POST['status']) && $_POST['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">➕ Add FAQ</button>
            <a href="chatbot.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
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

    .form-group input[type="text"],
    .form-group input[type="number"],
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

    .form-group input[type="text"]:focus,
    .form-group input[type="number"]:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 150px;
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #999;
        font-size: 12px;
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

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php include('footer.php'); ?>
