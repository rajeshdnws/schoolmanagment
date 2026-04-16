<?php
include('session.php');
requireAccess('content', 'edit');

$page_title = 'Edit Content - School Management System';
include('header.php');

$error = '';
$success = '';
$content_item = null;

// Get content ID from URL
$content_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($content_id <= 0) {
    $error = 'Invalid content ID';
} else {
    // Fetch content
    $query = "SELECT * FROM content WHERE id = $content_id";
    $content_item = getRow($query);
    
    if (!$content_item) {
        $error = 'Content not found';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error && $content_item) {
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
        $featured_image = $content_item['featured_image'];
        
        // Handle featured image update
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['size'] > 0) {
            // Delete old image if exists
            if (!empty($featured_image) && file_exists('../' . $featured_image)) {
                unlink('../' . $featured_image);
            }
            
            $uploadDir = '../uploads/content/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $filename = time() . '_' . basename($_FILES['featured_image']['name']);
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadPath)) {
                $featured_image = 'uploads/content/' . $filename;
            }
        }
        
        $query = "UPDATE content SET 
                  title = '$title', 
                  slug = '$slug', 
                  description = '$description', 
                  content = '$content', 
                  featured_image = '$featured_image', 
                  status = '$status',
                  updated_at = NOW()
                  WHERE id = $content_id";
        
        if (executeQuery($query)) {
            $success = 'Content updated successfully!';
            // Refresh content item
            $content_item = getRow("SELECT * FROM content WHERE id = $content_id");
        } else {
            $error = 'Failed to update content. Please try again.';
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
            <br><a href="content.php" class="btn btn-sm btn-primary" style="margin-top: 10px;">Back to Content</a>
        </div>
    <?php elseif ($content_item): ?>
        <div class="form-header">
            <h1>✎ Edit Content</h1>
            <a href="content.php" class="btn btn-secondary">Back</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="content-form">
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($content_item['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($content_item['slug']); ?>">
                <small>URL-friendly version of the title. Auto-generated if left empty.</small>
            </div>

            <div class="form-group">
                <label for="description">Short Description</label>
                <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($content_item['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="content">Content *</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($content_item['content']); ?></textarea>
                <small>HTML is supported. You can include formatting, links, and inline images.</small>
            </div>

            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <?php if (!empty($content_item['featured_image'])): ?>
                    <div class="current-image">
                        <img src="<?php echo htmlspecialchars($content_item['featured_image']); ?>" alt="Current">
                        <p>Current: <?php echo basename($content_item['featured_image']); ?></p>
                    </div>
                <?php endif; ?>
                <input type="file" id="featured_image" name="featured_image" accept="image/*">
                <small>Upload a new image to replace the current one.</small>
                <div id="imagePreview" class="image-preview"></div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="draft" <?php echo $content_item['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo $content_item['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Update Content</button>
                <a href="content_view.php?id=<?php echo $content_id; ?>" class="btn btn-info">👁 Preview</a>
                <a href="content.php" class="btn btn-secondary">Cancel</a>
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
        padding: 8px 16px;
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

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background: #138496;
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
    .form-group input[type="file"],
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
    .form-group input[type="file"]:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #999;
        font-size: 12px;
    }

    .current-image {
        margin-bottom: 15px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .current-image img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .current-image p {
        margin: 5px 0;
        color: #666;
        font-size: 13px;
    }

    .image-preview {
        margin-top: 15px;
        display: none;
    }

    .image-preview img {
        max-width: 300px;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #eee;
    }

    .content-form {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

<script>
    // Image preview
    document.getElementById('featured_image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = '<img src="' + event.target.result + '" alt="Preview">';
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Auto-generate slug from title
    document.getElementById('title').addEventListener('change', function() {
        const slug = document.getElementById('slug');
        if (!slug.value) {
            slug.value = this.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
</script>

<?php include('footer.php'); ?>
