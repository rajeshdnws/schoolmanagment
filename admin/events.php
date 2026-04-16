<?php
include('session.php');
requireAccess('events', 'view');

$page_title = 'Event Management - School Management System';
include('header.php');

// Get all events
$events_list = getAllRows("SELECT * FROM events ORDER BY event_date DESC");
?>

<div class="container">
    <div class="page-header">
        <h1>📅 Event Management</h1>
        <?php if (hasAccess('events', 'add')): ?>
            <a href="events_add.php" class="btn btn-primary">+ Add Event</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($events_list)): ?>
    <div class="events-table">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events_list as $event): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($event['title']); ?></strong>
                        <?php if ($event['category']): ?>
                            <br><small style="color: #999;">Category: <?php echo htmlspecialchars($event['category']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                        <?php if ($event['event_time']): ?>
                            <br><small><?php echo date('h:i A', strtotime($event['event_time'])); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $event['status'] === 'upcoming' ? 'primary' : ($event['status'] === 'completed' ? 'success' : 'warning'); ?>">
                            <?php echo ucfirst($event['status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if (hasAccess('events', 'edit')): ?>
                            <a href="events_edit.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <?php endif; ?>
                        <?php if (hasAccess('events', 'delete')): ?>
                            <button class="btn btn-sm btn-danger" onclick="deleteEvent(<?php echo $event['id']; ?>)">Delete</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <p>No events found. <a href="events_add.php">Create your first event</a></p>
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

    .events-table {
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
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-primary {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-success {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge-warning {
        background: #fff3cd;
        color: #664d03;
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

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .table {
            font-size: 12px;
        }

        .table th, .table td {
            padding: 10px;
        }
    }
</style>

<script>
function deleteEvent(id) {
    if (confirm('Are you sure you want to delete this event?')) {
        window.location.href = 'events_delete.php?id=' + id;
    }
}
</script>

<?php include('footer.php'); ?>
