<?php
include('session.php');
requireAccess('profile', 'view');

$page_title = 'Profile - School Management System';

$admin = getRow("SELECT * FROM users WHERE id = " . $_SESSION['admin_id']);

include('header.php');
?>

<div class="page-header">
    <h1>My Profile</h1>
</div>

<div class="view-container">
    <div class="view-section">
        <h2>Admin Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Username:</label>
                <span><?php echo htmlspecialchars($admin['username']); ?></span>
            </div>
            <div class="info-item">
                <label>Full Name:</label>
                <span><?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?></span>
            </div>
            <div class="info-item">
                <label>Email:</label>
                <span><?php echo htmlspecialchars($admin['email']); ?></span>
            </div>
            <div class="info-item">
                <label>Member Since:</label>
                <span><?php echo date('d-M-Y', strtotime($admin['created_at'])); ?></span>
            </div>
            <div class="info-item">
                <label>Last Updated:</label>
                <span><?php echo date('d-M-Y H:i', strtotime($admin['updated_at'])); ?></span>
            </div>
        </div>
    </div>
    
    <div class="action-buttons" style="margin-top: 30px;">
        <a href="settings.php" class="btn-primary">Edit Profile</a>
        <a href="dashboard.php" class="btn-secondary">Back</a>
    </div>
</div>

<?php include('footer.php'); ?>
