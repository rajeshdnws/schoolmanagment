<?php
include('session.php');
requireAccess('content', 'view');

$page_title = 'View Content - School Management System';
include('header.php');

$error = '';
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
?>

<div class="container">
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <strong>Error:</strong> <?php echo $error; ?>
            <br><a href="content.php" class="btn btn-sm btn-primary" style="margin-top: 10px;">Back to Content</a>
        </div>
    <?php else: ?>
        <div class="content-view-header">
            <div>
                <h1><?php echo htmlspecialchars($content_item['title']); ?></h1>
                <p class="meta-info">
                    <span class="badge badge-<?php echo $content_item['status'] === 'published' ? 'success' : 'warning'; ?>">
                        <?php echo ucfirst($content_item['status']); ?>
                    </span>
                    <span>Created: <?php echo date('M d, Y \a\t h:i A', strtotime($content_item['created_at'])); ?></span>
                </p>
            </div>
            <div class="view-actions">
                <?php if (hasAccess('content', 'edit')): ?>
                    <a href="content_edit.php?id=<?php echo $content_item['id']; ?>" class="btn btn-warning">✎ Edit</a>
                <?php endif; ?>
                <a href="content.php" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <?php if (!empty($content_item['featured_image'])): ?>
        <div class="content-featured-image">
            <img src="<?php echo htmlspecialchars($content_item['featured_image']); ?>" alt="<?php echo htmlspecialchars($content_item['title']); ?>">
        </div>
        <?php endif; ?>

        <div class="content-view-body">
            <div class="content-section">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($content_item['description'])); ?></p>
            </div>

            <div class="content-section">
                <h3>Content</h3>
                <div class="content-html">
                    <?php echo $content_item['content']; ?>
                </div>
            </div>

            <div class="content-metadata">
                <div class="meta-item">
                    <strong>Slug:</strong> <code><?php echo htmlspecialchars($content_item['slug']); ?></code>
                </div>
                <div class="meta-item">
                    <strong>Status:</strong> <span class="badge badge-<?php echo $content_item['status'] === 'published' ? 'success' : 'warning'; ?>">
                        <?php echo ucfirst($content_item['status']); ?>
                    </span>
                </div>
                <div class="meta-item">
                    <strong>Created At:</strong> <?php echo date('M d, Y \a\t h:i A', strtotime($content_item['created_at'])); ?>
                </div>
                <div class="meta-item">
                    <strong>Last Updated:</strong> <?php echo date('M d, Y \a\t h:i A', strtotime($content_item['updated_at'])); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .content-view-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }

    .content-view-header h1 {
        margin: 0 0 10px 0;
        color: #333;
    }

    .meta-info {
        display: flex;
        gap: 20px;
        align-items: center;
        color: #666;
        font-size: 14px;
    }

    .view-actions {
        display: flex;
        gap: 10px;
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

    .btn-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
    }

    .btn-secondary {
        background: #666;
        color: white;
    }

    .btn-secondary:hover {
        background: #555;
    }

    .content-featured-image {
        margin: 30px 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .content-featured-image img {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
    }

    .content-view-body {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .content-section {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #eee;
    }

    .content-section:last-child {
        border-bottom: none;
    }

    .content-section h3 {
        color: #667eea;
        margin-top: 0;
        font-size: 18px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .content-section p {
        color: #555;
        line-height: 1.6;
    }

    .content-html {
        color: #555;
        line-height: 1.8;
        font-size: 15px;
    }

    .content-html img {
        max-width: 100%;
        height: auto;
        margin: 15px 0;
        border-radius: 5px;
    }

    .content-metadata {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin-top: 30px;
    }

    .meta-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .meta-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .meta-item strong {
        color: #667eea;
        display: block;
        margin-bottom: 5px;
    }

    .meta-item code {
        background: #f0f0f0;
        padding: 4px 8px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
        color: #d63384;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #4CAF50;
        color: white;
    }

    .badge-warning {
        background: #ff9800;
        color: white;
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

    .container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
    }
</style>

<?php include('footer.php'); ?>
