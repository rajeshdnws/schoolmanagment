<?php
include('session.php');
requireAccess('teachers', 'view');

if (!isset($_GET['id'])) {
    header("Location: teachers.php");
    exit();
}

$id = intval($_GET['id']);
$teacher = getRow("SELECT * FROM teachers WHERE id = $id");

if (!$teacher) {
    header("Location: teachers.php");
    exit();
}

$page_title = 'View Teacher - School Management System';

include('header.php');
?>

<div class="page-header">
    <h1><?php echo htmlspecialchars($teacher['first_name'] . " " . $teacher['last_name']); ?></h1>
    <a href="teachers.php" class="btn-secondary">← Back to Teachers</a>
</div>

<div class="view-container">
    <div class="view-section">
        <h2>Personal Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Email:</label>
                <span><?php echo htmlspecialchars($teacher['email'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Phone:</label>
                <span><?php echo htmlspecialchars($teacher['phone'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Date of Birth:</label>
                <span><?php echo $teacher['date_of_birth'] ? date('d-M-Y', strtotime($teacher['date_of_birth'])) : 'N/A'; ?></span>
            </div>
            <div class="info-item">
                <label>Gender:</label>
                <span><?php echo htmlspecialchars($teacher['gender'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item full-width">
                <label>Address:</label>
                <span><?php echo htmlspecialchars($teacher['address'] ?? 'N/A'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="view-section">
        <h2>Professional Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Qualification:</label>
                <span><?php echo htmlspecialchars($teacher['qualification'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Specialization:</label>
                <span><?php echo htmlspecialchars($teacher['specialization'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Experience:</label>
                <span><?php echo intval($teacher['experience_years']); ?> years</span>
            </div>
            <div class="info-item">
                <label>Joining Date:</label>
                <span><?php echo $teacher['joining_date'] ? date('d-M-Y', strtotime($teacher['joining_date'])) : 'N/A'; ?></span>
            </div>
            <div class="info-item">
                <label>Monthly Salary:</label>
                <span>₹<?php echo number_format($teacher['salary'], 2); ?></span>
            </div>
            <div class="info-item">
                <label>Status:</label>
                <span class="badge <?php echo $teacher['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo ucfirst($teacher['status']); ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="action-buttons" style="margin-top: 30px;">
        <a href="teachers_edit.php?id=<?php echo $teacher['id']; ?>" class="btn-primary">Edit Teacher</a>
        <a href="teachers.php?delete=<?php echo $teacher['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete Teacher</a>
        <a href="teachers.php" class="btn-secondary">Back</a>
    </div>
</div>

<?php include('footer.php'); ?>
