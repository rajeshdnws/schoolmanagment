<?php
include('session.php');
requireAccess('attendance', 'view');

$page_title = 'Attendance - School Management System';

$filter_class = isset($_GET['filter_class']) ? intval($_GET['filter_class']) : '';
$attendance_date = isset($_GET['attendance_date']) ? $_GET['attendance_date'] : date('Y-m-d');

$where = "1=1 AND a.attendance_date = '$attendance_date'";
if ($filter_class) {
    $where .= " AND a.class_id = $filter_class";
}

$attendance_records = getAllRows("
    SELECT a.*, s.roll_number, s.first_name, s.last_name, c.class_name 
    FROM attendance a 
    JOIN students s ON a.student_id = s.id 
    JOIN classes c ON a.class_id = c.id 
    WHERE $where 
    ORDER BY c.class_name, s.roll_number
");

$classes = getAllRows("SELECT * FROM classes ORDER BY class_name");

include('header.php');
?>

<div class="page-header">
    <h1>Attendance Management</h1>
    <?php if (hasAccess('attendance', 'add')): ?>
        <a href="attendance_add.php" class="btn btn-primary">+ Add Attendance</a>
    <?php endif; ?>
</div>

<div class="filters">
    <form method="GET" action="attendance.php" class="filter-form">
        <input type="date" name="attendance_date" value="<?php echo htmlspecialchars($attendance_date); ?>">
        <select name="filter_class">
            <option value="">All Classes</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo $class['id']; ?>" <?php echo $filter_class == $class['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($class['class_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-secondary">Filter</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Class</th>
                <th>Roll No</th>
                <th>Student Name</th>
                <th>Status</th>
                <th>Date</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($attendance_records) > 0) {
                foreach ($attendance_records as $record) {
                    $status_class = $record['status'] === 'Present' ? 'status-active' : 'status-inactive';
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($record['class_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($record['roll_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($record['first_name'] . " " . $record['last_name']) . "</td>";
                    echo "<td><span class='badge $status_class'>" . htmlspecialchars($record['status']) . "</span></td>";
                    echo "<td>" . date('d-M-Y', strtotime($record['attendance_date'])) . "</td>";
                    echo "<td>" . htmlspecialchars($record['remarks'] ?? '') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No attendance records found for this date</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
