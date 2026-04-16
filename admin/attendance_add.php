<?php
include('session.php');
requireAccess('attendance', 'add');

$page_title = 'Add Attendance - School Management System';
include('header.php');

$error = '';
$success = '';

// Get all classes
$classes = getAllRows("SELECT * FROM classes ORDER BY class_name");

// Get students for selected class
$selected_class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : (isset($_POST['class_id']) ? (int)$_POST['class_id'] : '');
$students = [];
$attendance_date = isset($_GET['attendance_date']) ? $_GET['attendance_date'] : (isset($_POST['attendance_date']) ? $_POST['attendance_date'] : date('Y-m-d'));

// Load students if class is selected
if ($selected_class_id) {
    $class_students = getAllRows("SELECT * FROM students WHERE class_id = $selected_class_id AND status = 'active' ORDER BY roll_number");
    $students = $class_students ? $class_students : [];
}

// Get class name for display
$class_name = '';
if ($selected_class_id) {
    $class_result = getRow("SELECT class_name FROM classes WHERE id = $selected_class_id");
    $class_name = $class_result ? $class_result['class_name'] : 'Unknown';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_attendance'])) {
    $class_id = (int)$_POST['class_id'];
    $attendance_date = $_POST['attendance_date'];
    $attendance_data = isset($_POST['attendance']) ? $_POST['attendance'] : [];
    
    if (empty($class_id)) {
        $error = 'Please select a class';
    } elseif (empty($attendance_date)) {
        $error = 'Please select an attendance date';
    } elseif (empty($attendance_data)) {
        $error = 'No attendance data to save';
    } else {
        // Insert/Update attendance records
        $inserted = 0;
        $updated = 0;
        $failed = 0;
        
        foreach ($attendance_data as $student_id => $attendance_info) {
            $student_id = (int)$student_id;
            $status = isset($attendance_info['status']) ? sanitize($attendance_info['status']) : 'Absent';
            $remarks = isset($attendance_info['remarks']) ? sanitize($attendance_info['remarks']) : '';
            
            // Check if record exists
            $existing = getRow("SELECT id FROM attendance WHERE student_id = $student_id AND attendance_date = '$attendance_date' AND class_id = $class_id");
            
            if ($existing) {
                // Update existing record
                $query = "UPDATE attendance SET status = '$status', remarks = '$remarks', updated_at = NOW() WHERE id = {$existing['id']}";
                if (executeQuery($query)) {
                    $updated++;
                } else {
                    $failed++;
                }
            } else {
                // Insert new record
                $query = "INSERT INTO attendance (student_id, class_id, attendance_date, status, remarks, created_at) 
                         VALUES ($student_id, $class_id, '$attendance_date', '$status', '$remarks', NOW())";
                if (executeQuery($query)) {
                    $inserted++;
                } else {
                    $failed++;
                }
            }
        }
        
        if ($failed == 0) {
            $success = "✓ Attendance saved successfully! (Inserted: $inserted, Updated: $updated)";
        } else {
            $error = "Attendance saved with errors! (Inserted: $inserted, Updated: $updated, Failed: $failed)";
        }
    }
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}
?>

<div class="container">
    <div class="page-header">
        <h1>📋 Add Attendance</h1>
        <a href="attendance.php" class="btn btn-secondary">Back</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="attendance-form">
        <div class="form-row">
            <div class="form-group">
                <label for="class_id">Select Class *</label>
                <select id="class_id" name="class_id" required>
                    <option value="">-- Choose a Class --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>" <?php echo $selected_class_id == $class['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class['class_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="attendance_date">Attendance Date *</label>
                <input type="date" id="attendance_date" name="attendance_date" value="<?php echo htmlspecialchars($attendance_date); ?>" required>
            </div>

            <?php if ($selected_class_id && !empty($students)): ?>
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-info" onclick="loadStudents()">Load Students</button>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($selected_class_id && !empty($students)): ?>
            <div class="attendance-section">
                <h3>Mark Attendance for <?php echo htmlspecialchars($class_name); ?></h3>
                <p class="info-text">Date: <?php echo date('l, d-M-Y', strtotime($attendance_date)); ?> | Total Students: <?php echo count($students); ?></p>

                <div class="students-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($students as $student):
                                // Fetch existing attendance if any
                                $existing_attendance = getRow("SELECT status, remarks FROM attendance WHERE student_id = {$student['id']} AND attendance_date = '$attendance_date' AND class_id = $selected_class_id");
                                $existing_status = $existing_attendance ? $existing_attendance['status'] : 'Present';
                                $existing_remarks = $existing_attendance ? $existing_attendance['remarks'] : '';
                            ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['roll_number']); ?></td>
                                        <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                        <td>
                                            <div class="status-buttons">
                                                <label class="radio-label">
                                                    <input type="radio" name="attendance[<?php echo $student['id']; ?>][status]" value="Present" <?php echo $existing_status === 'Present' ? 'checked' : ''; ?>>
                                                    <span class="radio-btn present">✓ Present</span>
                                                </label>
                                                <label class="radio-label">
                                                    <input type="radio" name="attendance[<?php echo $student['id']; ?>][status]" value="Absent" <?php echo $existing_status === 'Absent' ? 'checked' : ''; ?>>
                                                    <span class="radio-btn absent">✗ Absent</span>
                                                </label>
                                                <label class="radio-label">
                                                    <input type="radio" name="attendance[<?php echo $student['id']; ?>][status]" value="Leave" <?php echo $existing_status === 'Leave' ? 'checked' : ''; ?>>
                                                    <span class="radio-btn leave">◯ Leave</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="attendance[<?php echo $student['id']; ?>][remarks]" placeholder="Optional remarks" value="<?php echo htmlspecialchars($existing_remarks); ?>" maxlength="100">
                                        </td>
                                    </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit_attendance" class="btn btn-primary">💾 Save Attendance</button>
                    <a href="attendance.php" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        <?php elseif ($selected_class_id): ?>
            <div class="alert alert-warning">
                ⚠ No active students found in this class.
            </div>
        <?php endif; ?>
    </form>
</div>

<style>
    .container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }

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

    .btn {
        padding: 10px 20px;
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

    .btn-secondary {
        background: #666;
        color: white;
    }

    .btn-secondary:hover {
        background: #555;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background: #138496;
    }

    .attendance-form {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group select,
    .form-group input[type="date"] {
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group select:focus,
    .form-group input[type="date"]:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .attendance-section {
        margin-top: 30px;
    }

    .attendance-section h3 {
        color: #667eea;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .info-text {
        color: #999;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .students-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
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

    .status-buttons {
        display: flex;
        gap: 8px;
    }

    .radio-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        margin: 0;
    }

    .radio-label input[type="radio"] {
        margin-right: 6px;
        cursor: pointer;
    }

    .radio-btn {
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .radio-btn.present {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .radio-btn.absent {
        background: #fff3e0;
        color: #e65100;
    }

    .radio-btn.leave {
        background: #e3f2fd;
        color: #1565c0;
    }

    .radio-label input[type="radio"]:checked + .radio-btn {
        font-weight: 700;
        border-color: currentColor;
        transform: scale(1.05);
    }

    .radio-label input[type="radio"]:checked + .radio-btn.present {
        background: #4caf50;
        color: white;
    }

    .radio-label input[type="radio"]:checked + .radio-btn.absent {
        background: #ff9800;
        color: white;
    }

    .radio-label input[type="radio"]:checked + .radio-btn.leave {
        background: #2196f3;
        color: white;
    }

    .table input[type="text"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
    }

    .table input[type="text"]:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #eee;
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

    .alert-warning {
        background: #ffe;
        border-left: 4px solid #ff0;
        color: #663;
    }

    .text-center {
        text-align: center;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .status-buttons {
            flex-wrap: wrap;
        }

        .radio-btn {
            padding: 4px 8px;
            font-size: 11px;
        }

        .table {
            font-size: 13px;
        }

        .table th,
        .table td {
            padding: 10px;
        }
    }
</style>

<script>
function loadStudents() {
    const classId = document.getElementById('class_id').value;
    const attendanceDate = document.getElementById('attendance_date').value;
    
    if (!classId) {
        alert('Please select a class first');
        return;
    }
    
    if (!attendanceDate) {
        alert('Please select an attendance date');
        return;
    }
    
    // Reload page to load students
    window.location.href = '?class_id=' + classId + '&attendance_date=' + attendanceDate;
}

// Auto-load students when class is selected
document.getElementById('class_id').addEventListener('change', function() {
    if (this.value) {
        loadStudents();
    }
});
</script>

<?php include('footer.php'); ?>
