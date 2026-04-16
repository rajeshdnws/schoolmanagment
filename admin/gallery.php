<?php
include('session.php');
requireAccess('gallery', 'view');

$page_title = 'Gallery Management - School Management System';
include('header.php');

// Get all gallery items
$gallery_list = getAllRows("SELECT * FROM gallery ORDER BY display_order ASC, created_at DESC");
?>

<div class="container">
    <div class="page-header">
        <h1>🖼️ Gallery Management</h1>
        <?php if (hasAccess('gallery', 'add')): ?>
            <a href="gallery_add.php" class="btn btn-primary">+ Add Images</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($gallery_list)): ?>
    <div class="gallery-grid">
        <?php foreach ($gallery_list as $item): ?>
        <div class="gallery-card">
            <div class="gallery-image">
                <img src="../<?php echo $item['image_path']; ?>" alt="<?php echo htmlspecialchars($item['alt_text']); ?>">
                <div class="gallery-overlay">
                    <?php if (hasAccess('gallery', 'edit')): ?>
                        <a href="gallery_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <?php endif; ?>
                    <?php if (hasAccess('gallery', 'delete')): ?>
                        <button class="btn btn-sm btn-danger" onclick="deleteGallery(<?php echo $item['id']; ?>)">Delete</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="gallery-info">
                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                <?php if ($item['category']): ?>
                    <span class="badge badge-info"><?php echo htmlspecialchars($item['category']); ?></span>
                <?php endif; ?>
                <p><?php echo substr($item['description'], 0, 50) . (strlen($item['description']) > 50 ? '...' : ''); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <p>No gallery items found. <a href="gallery_add.php">Add your first gallery image</a></p>
    </div>
    <?php endif; ?>
</div>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .gallery-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .gallery-image {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
    }

    .gallery-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-card:hover .gallery-image img {
        transform: scale(1.05);
    }

    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-card:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-info {
        padding: 15px;
    }

    .gallery-info h3 {
        margin: 0 0 8px 0;
        font-size: 16px;
    }

    .gallery-info p {
        margin: 8px 0 0 0;
        color: #666;
        font-size: 13px;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-right: 5px;
    }

    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 11px;
    }

    .btn-warning {
        background: #ffc107;
        color: black;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }

        .page-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
</style>

<script>
function deleteGallery(id) {
    if (confirm('Are you sure you want to delete this image?')) {
        window.location.href = 'gallery_delete.php?id=' + id;
    }
}
</script>

<?php include('footer.php'); ?>
