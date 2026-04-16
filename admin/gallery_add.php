<?php
include('session.php');
requireAccess('gallery', 'add');

$page_title = 'Add Gallery Images - School Management System';
include('header.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $alt_text = sanitize($_POST['alt_text'] ?? $title);
    $category = sanitize($_POST['category'] ?? '');
    
    if (empty($title)) {
        $error = 'Image title is required';
    } elseif (!isset($_FILES['image']) || $_FILES['image']['size'] === 0) {
        $error = 'Please select an image';
    } else {
        // Handle image upload
        $uploadDir = '../uploads/gallery/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['image']['name']));
        $uploadPath = $uploadDir . $filename;
        $imagePath = 'uploads/gallery/' . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $query = "INSERT INTO gallery (title, description, image_path, alt_text, category, created_by) 
                      VALUES ('$title', '$description', '$imagePath', '$alt_text', '$category', {$_SESSION['admin_id']})";
            
            if (executeQuery($query)) {
                $success = 'Image added successfully!';
                $_POST = [];
            } else {
                $error = 'Failed to save image info. Please try again.';
            }
        } else {
            $error = 'Failed to upload image. Please try again.';
        }
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Get categories
$categories = getAllRows("SELECT DISTINCT category FROM gallery WHERE category != '' ORDER BY category");
?>

<div class="container">
    <div class="form-container">
        <h1>🖼️ Add Gallery Image</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <div style="margin-top: 20px;">
                <a href="gallery_add.php" class="btn btn-primary">Add Another</a>
                <a href="gallery.php" class="btn btn-secondary">Back to Gallery</a>
            </div>
        <?php else: ?>
        
        <form method="POST" enctype="multipart/form-data" class="form-layout">
            <div class="form-group">
                <label>Image Title *</label>
                <input type="text" name="title" class="form-input" placeholder="Image title" 
                       value="<?php echo $_POST['title'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Select Image *</label>
                <div class="image-upload-area">
                    <input type="file" name="image" class="form-input" accept="image/*" required id="imageInput" onchange="previewImage(event)">
                    <div id="imagePreview" class="image-preview"></div>
                </div>
                <small>Recommended size: 800x600px or larger. Max 5MB</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-input">
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                    <?php echo ($_POST['category'] ?? '') === $cat['category'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="other" <?php echo ($_POST['category'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Alt Text (for accessibility)</label>
                    <input type="text" name="alt_text" class="form-input" placeholder="Image description" 
                           value="<?php echo $_POST['alt_text'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-input" rows="4" 
                          placeholder="Image description"><?php echo $_POST['description'] ?? ''; ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Upload Image</button>
                <a href="gallery.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
        
        <?php endif; ?>
    </div>
</div>

<style>
    .container {
        max-width: 800px;
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

    .image-upload-area {
        position: relative;
        border: 2px dashed #667eea;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f5f6fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-upload-area:hover {
        background: #e8f0ff;
        border-color: #764ba2;
    }

    .image-upload-area input[type="file"] {
        display: none;
    }

    .image-preview {
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 15px;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
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

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(file);
    }
}

// Make upload area clickable
document.querySelector('.image-upload-area').addEventListener('click', function() {
    document.getElementById('imageInput').click();
});
</script>

<?php include('footer.php'); ?>
