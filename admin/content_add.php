<?php
include('session.php');
requireAccess('content', 'add');

$page_title = 'Add Content - School Management System';
include('header.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $slug = sanitize($_POST['slug'] ?? str_replace(' ', '-', strtolower($title)));
    $description = sanitize($_POST['description'] ?? '');
    $content = $_POST['content'] ?? ''; // Don't sanitize HTML content
    $status = sanitize($_POST['status'] ?? 'draft');
    
    // Validate
    if (empty($title)) {
        $error = 'Title is required';
    } elseif (empty($content)) {
        $error = 'Content is required';
    } else {
        // Handle featured image
        $featured_image = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['size'] > 0) {
            $uploadDir = '../uploads/content/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $filename = time() . '_' . basename($_FILES['featured_image']['name']);
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadPath)) {
                $featured_image = 'uploads/content/' . $filename;
            }
        }
        
        $query = "INSERT INTO content (title, slug, description, content, featured_image, status, created_by) 
                  VALUES ('$title', '$slug', '$description', '$content', '$featured_image', '$status', {$_SESSION['admin_id']})";
        
        if (executeQuery($query)) {
            $success = 'Content added successfully!';
            $_POST = []; // Clear form
        } else {
            $error = 'Failed to add content. Please try again.';
        }
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>

<div class="container">
    <div class="form-container">
        <h1>📝 Add New Content</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <a href="content.php" class="btn btn-primary">Back to Content List</a>
        <?php else: ?>
        
        <form method="POST" enctype="multipart/form-data" class="form-layout">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" class="form-input" placeholder="Content title" 
                       value="<?php echo $_POST['title'] ?? ''; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Slug (URL-friendly)</label>
                    <input type="text" name="slug" class="form-input" placeholder="content-slug" 
                           value="<?php echo $_POST['slug'] ?? ''; ?>">
                    <small>Auto-generated from title if left blank</small>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-input">
                        <option value="draft" <?php echo ($_POST['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo ($_POST['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Description (Short summary)</label>
                <textarea name="description" class="form-input" rows="3" 
                          placeholder="Brief description for preview"><?php echo $_POST['description'] ?? ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Featured Image</label>
                <input type="file" name="featured_image" class="form-input" accept="image/*">
                <small>Recommended size: 1200x600px</small>
            </div>

            <div class="form-group">
                <label>Content *</label>
                <textarea name="content" class="form-input richtext" rows="10" 
                          placeholder="Main content here" required><?php echo $_POST['content'] ?? ''; ?></textarea>
                <small>You can use HTML formatting</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Content</button>
                <a href="content.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
        
        <?php endif; ?>
    </div>
</div>

<style>
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .form-container {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-layout {
        margin-top: 30px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.1);
    }

    .form-input small {
        display: block;
        margin-top: 5px;
        color: #999;
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        display: inline-block;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-secondary {
        background: #ddd;
        color: #333;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<?php include('footer.php'); ?>
