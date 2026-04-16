<?php
include('session.php');
requireAccess('students', 'view');

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$id = intval($_GET['id']);
$student = getRow("SELECT s.*, c.class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = $id");

if (!$student) {
    header("Location: students.php");
    exit();
}

$page_title = 'View Student - School Management System';

include('header.php');
?>

<div class="page-header">
    <h1><?php echo htmlspecialchars($student['first_name'] . " " . $student['last_name']); ?></h1>
    <a href="students.php" class="btn-secondary">← Back to Students</a>
</div>

<div class="view-container">
    <div class="view-section">
        <h2>Personal Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Roll Number:</label>
                <span><?php echo htmlspecialchars($student['roll_number']); ?></span>
            </div>
            <div class="info-item">
                <label>Class:</label>
                <span><?php echo htmlspecialchars($student['class_name'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Email:</label>
                <span><?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Phone:</label>
                <span><?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Date of Birth:</label>
                <span><?php echo $student['date_of_birth'] ? date('d-M-Y', strtotime($student['date_of_birth'])) : 'N/A'; ?></span>
            </div>
            <div class="info-item">
                <label>Gender:</label>
                <span><?php echo htmlspecialchars($student['gender'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Admission Date:</label>
                <span><?php echo $student['admission_date'] ? date('d-M-Y', strtotime($student['admission_date'])) : 'N/A'; ?></span>
            </div>
            <div class="info-item">
                <label>Status:</label>
                <span class="badge <?php echo $student['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo ucfirst($student['status']); ?>
                </span>
            </div>
        </div>
        
        <h3>Address Information</h3>
        <div class="info-grid">
            <div class="info-item full-width">
                <label>Address:</label>
                <span><?php echo htmlspecialchars($student['address'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>City:</label>
                <span><?php echo htmlspecialchars($student['city'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>State:</label>
                <span><?php echo htmlspecialchars($student['state'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Pincode:</label>
                <span><?php echo htmlspecialchars($student['pincode'] ?? 'N/A'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="view-section">
        <h2>Parent Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Father's Name:</label>
                <span><?php echo htmlspecialchars($student['father_name'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Father's Phone:</label>
                <span><?php echo htmlspecialchars($student['father_phone'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Mother's Name:</label>
                <span><?php echo htmlspecialchars($student['mother_name'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <label>Mother's Phone:</label>
                <span><?php echo htmlspecialchars($student['mother_phone'] ?? 'N/A'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="action-buttons" style="margin-top: 30px;">
        <a href="students_edit.php?id=<?php echo $student['id']; ?>" class="btn-primary">Edit Student</a>
        <a href="students.php?delete=<?php echo $student['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete Student</a>
        <a href="students.php" class="btn-secondary">Back</a>
    </div>
</div>

<?php include('footer.php'); ?>
