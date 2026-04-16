<?php
include('session.php');
requireAccess('content', 'view');

$page_title = 'Content Management - School Management System';
include('header.php');

// Get all content
$content_list = getAllRows("SELECT * FROM content ORDER BY created_at DESC");
?>

<div class="container">
    <div class="page-header">
        <h1>📝 Content Management</h1>
        <?php if (hasAccess('content', 'add')): ?>
            <a href="content_add.php" class="btn btn-primary">+ Add New Content</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($content_list)): ?>
    <div class="content-table">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($content_list as $item): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                        <br>
                        <small style="color: #999;">Slug: <?php echo htmlspecialchars($item['slug']); ?></small>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo $item['status'] === 'published' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($item['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                    <td>
                        <a href="content_view.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info">View</a>
                        <?php if (hasAccess('content', 'edit')): ?>
                            <a href="content_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <?php endif; ?>
                        <?php if (hasAccess('content', 'delete')): ?>
                            <button class="btn btn-sm btn-danger" onclick="deleteContent(<?php echo $item['id']; ?>)">Delete</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <p>No content found. <a href="content_add.php">Create your first content</a></p>
    </div>
    <?php endif; ?>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .content-table {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
    }

    .table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .table tbody tr:hover {
        background: #f5f6fa;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .btn {
        display: inline-block;
        padding: 8px 16px;
        margin: 2px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
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
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 10px;
    }
</style>

<script>
function deleteContent(id) {
    if (confirm('Are you sure you want to delete this content?')) {
        window.location.href = 'content_delete.php?id=' + id;
    }
}
</script>

<?php include('footer.php'); ?>
