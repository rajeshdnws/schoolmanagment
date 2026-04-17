<?php
include('session.php');
requireAccess('chatbot', 'view');

$page_title = 'Chatbot Management - School Management System';
include('header.php');

// Get all FAQs
$faq_list = getAllRows("SELECT * FROM chatbot_faqs ORDER BY priority DESC, created_at DESC");

$categories = [];
foreach ($faq_list as $faq) {
    if ($faq['category'] && !in_array($faq['category'], $categories)) {
        $categories[] = $faq['category'];
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1>🤖 Chatbot Management</h1>
        <?php if (hasAccess('chatbot', 'add')): ?>
            <a href="chatbot_add.php" class="btn btn-primary">+ Add FAQ</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($faq_list)): ?>
    <div class="chatbot-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faq_list as $faq): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars(substr($faq['question'], 0, 50)); ?></strong>
                        <?php if (strlen($faq['question']) > 50): ?>
                            <span>...</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge badge-info">
                            <?php echo htmlspecialchars($faq['category'] ?? 'Uncategorized'); ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo $faq['status'] === 'active' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($faq['status']); ?>
                        </span>
                    </td>
                    <td><?php echo $faq['priority']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($faq['created_at'])); ?></td>
                    <td>
                        <?php if (hasAccess('chatbot', 'edit')): ?>
                            <a href="chatbot_edit.php?id=<?php echo $faq['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <?php endif; ?>
                        <?php if (hasAccess('chatbot', 'delete')): ?>
                            <button class="btn btn-sm btn-danger" onclick="deleteFaq(<?php echo $faq['id']; ?>)">Delete</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <p>No FAQs found. <a href="chatbot_add.php">Add your first FAQ</a></p>
    </div>
    <?php endif; ?>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }

    .page-header h1 {
        margin: 0;
        color: #333;
    }

    .chatbot-table {
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
        background: #f9f9f9;
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

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }

    .btn-warning {
        background: #ff9800;
        color: white;
    }

    .btn-warning:hover {
        background: #e68900;
    }

    .btn-danger {
        background: #f44336;
        color: white;
    }

    .btn-danger:hover {
        background: #da190b;
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

    .badge-info {
        background: #2196F3;
        color: white;
    }

    .empty-state {
        background: white;
        padding: 50px;
        border-radius: 10px;
        text-align: center;
        color: #999;
    }

    .container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 20px;
    }
</style>

<script>
function deleteFaq(id) {
    if (confirm('Are you sure you want to delete this FAQ?')) {
        window.location.href = 'chatbot_delete.php?id=' + id;
    }
}
</script>

<?php include('footer.php'); ?>
