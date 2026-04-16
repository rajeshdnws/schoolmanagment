<?php
include('session.php');

// Check access
requireAccess('students', 'view');

$page_title = 'Students - School Management System';

// Handle delete
if (isset($_GET['delete'])) {
    requireAccess('students', 'delete');
    $id = intval($_GET['delete']);
    deleteData('students', "id = $id");
    header("Location: students.php?msg=deleted");
    exit();
}

// Get filter and search
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$where = "1=1";
if ($filter) {
    $where .= " AND class_id = $filter";
}
if ($search) {
    $where .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR roll_number LIKE '%$search%' OR email LIKE '%$search%')";
}

$students = getAllRows("SELECT s.*, c.class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE $where ORDER BY s.roll_number");
$classes = getAllRows("SELECT * FROM classes ORDER BY class_name");

include('header.php');
?>

<div class="page-header">
    <h1>Students Management</h1>
    <?php if (hasAccess('students', 'add')): ?>
        <a href="students_add.php" class="btn-primary">+ Add New Student</a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php
        if ($_GET['msg'] === 'deleted') echo "Student deleted successfully!";
        elseif ($_GET['msg'] === 'added') echo "Student added successfully!";
        elseif ($_GET['msg'] === 'updated') echo "Student updated successfully!";
        ?>
    </div>
<?php endif; ?>

<div class="filters">
    <form method="GET" action="students.php" class="filter-form">
        <input 
            type="text" 
            name="search" 
            placeholder="Search by name, roll number, or email..."
            value="<?php echo htmlspecialchars($search); ?>"
        >
        <select name="filter">
            <option value="">All Classes</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo $class['id']; ?>" <?php echo $filter == $class['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($class['class_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-secondary">Search</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Roll No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Class</th>
                <th>DOB</th>
                <th>Admission Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($students) > 0) {
                foreach ($students as $student) {
                    $status_class = $student['status'] === 'active' ? 'status-active' : 'status-inactive';
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($student['roll_number']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($student['first_name'] . " " . $student['last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($student['email'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($student['phone'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($student['class_name'] ?? 'N/A') . "</td>";
                    echo "<td>" . ($student['date_of_birth'] ? date('d-M-Y', strtotime($student['date_of_birth'])) : 'N/A') . "</td>";
                    echo "<td>" . ($student['admission_date'] ? date('d-M-Y', strtotime($student['admission_date'])) : 'N/A') . "</td>";
                    echo "<td><span class='badge $status_class'>" . ucfirst($student['status']) . "</span></td>";
                    echo "<td class='action-buttons'>";
                    if (hasAccess('students', 'edit')) {
                        echo "<a href='students_edit.php?id=" . $student['id'] . "' class='btn-edit'>Edit</a>";
                    }
                    echo "<a href='students_view.php?id=" . $student['id'] . "' class='btn-view'>View</a>";
                    if (hasAccess('students', 'delete')) {
                        echo "<a href='students.php?delete=" . $student['id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>No students found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
