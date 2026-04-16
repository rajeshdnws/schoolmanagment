<?php
include('session.php');
requireAccess('exams', 'view');

$page_title = 'Exams - School Management System';

// Handle delete
if (isset($_GET['delete'])) {
    requireAccess('exams', 'delete');
    $id = intval($_GET['delete']);
    deleteData('exams', "id = $id");
    header("Location: exams.php?msg=deleted");
    exit();
}

$filter_class = isset($_GET['filter_class']) ? intval($_GET['filter_class']) : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';

$where = "1=1";
if ($filter_class) {
    $where .= " AND e.class_id = $filter_class";
}
if ($filter_status) {
    $where .= " AND e.status = '$filter_status'";
}

$exams = getAllRows("
    SELECT e.*, c.class_name, s.subject_name 
    FROM exams e 
    LEFT JOIN classes c ON e.class_id = c.id 
    LEFT JOIN subjects s ON e.subject_id = s.id 
    WHERE $where 
    ORDER BY e.exam_date DESC
");

$classes = getAllRows("SELECT * FROM classes ORDER BY class_name");

include('header.php');
?>

<div class="page-header">
    <h1>Exams Management</h1>
    <a href="exams_add.php" class="btn-primary">+ Add New Exam</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php
        if ($_GET['msg'] === 'deleted') echo "Exam deleted successfully!";
        elseif ($_GET['msg'] === 'added') echo "Exam added successfully!";
        elseif ($_GET['msg'] === 'updated') echo "Exam updated successfully!";
        ?>
    </div>
<?php endif; ?>

<div class="filters">
    <form method="GET" action="exams.php" class="filter-form">
        <select name="filter_class">
            <option value="">All Classes</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo $class['id']; ?>" <?php echo $filter_class == $class['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($class['class_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="filter_status">
            <option value="">All Status</option>
            <option value="scheduled" <?php echo $filter_status === 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
            <option value="ongoing" <?php echo $filter_status === 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
            <option value="completed" <?php echo $filter_status === 'completed' ? 'selected' : ''; ?>>Completed</option>
        </select>
        <button type="submit" class="btn-secondary">Filter</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Exam Name</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Time</th>
                <th>Total Marks</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($exams) > 0) {
                foreach ($exams as $exam) {
                    $status_class = $exam['status'] === 'completed' ? 'status-active' : 'status-inactive';
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($exam['exam_name']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($exam['class_name'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($exam['subject_name'] ?? 'N/A') . "</td>";
                    echo "<td>" . ($exam['exam_date'] ? date('d-M-Y', strtotime($exam['exam_date'])) : 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($exam['exam_time']) . "</td>";
                    echo "<td>" . intval($exam['total_marks']) . "</td>";
                    echo "<td><span class='badge $status_class'>" . ucfirst(str_replace('_', ' ', $exam['status'])) . "</span></td>";
                    echo "<td class='action-buttons'>";
                    echo "<a href='exams_edit.php?id=" . $exam['id'] . "' class='btn-edit'>Edit</a>";
                    echo "<a href='exams.php?delete=" . $exam['id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>No exams found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
