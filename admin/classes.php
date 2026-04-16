<?php
include('session.php');
requireAccess('classes', 'view');

$page_title = 'Classes - School Management System';

// Handle delete
if (isset($_GET['delete'])) {
    requireAccess('classes', 'delete');
    $id = intval($_GET['delete']);
    deleteData('classes', "id = $id");
    header("Location: classes.php?msg=deleted");
    exit();
}

$classes = getAllRows("SELECT c.*, t.first_name, t.last_name FROM classes c LEFT JOIN teachers t ON c.class_teacher_id = t.id ORDER BY c.class_name");

include('header.php');
?>

<div class="page-header">
    <h1>Classes Management</h1>
    <?php if (hasAccess('classes', 'add')): ?>
        <a href="classes_add.php" class="btn-primary">+ Add New Class</a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php
        if ($_GET['msg'] === 'deleted') echo "Class deleted successfully!";
        elseif ($_GET['msg'] === 'added') echo "Class added successfully!";
        elseif ($_GET['msg'] === 'updated') echo "Class updated successfully!";
        ?>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Class Name</th>
                <th>Section</th>
                <th>Capacity</th>
                <th>Class Teacher</th>
                <th>Students Enrolled</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($classes) > 0) {
                foreach ($classes as $class) {
                    $student_count = getRow("SELECT COUNT(*) as count FROM students WHERE class_id = " . $class['id'])['count'];
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($class['class_name']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($class['section'] ?? 'N/A') . "</td>";
                    echo "<td>" . intval($class['capacity']) . "</td>";
                    echo "<td>" . htmlspecialchars(($class['first_name'] ?? '') . " " . ($class['last_name'] ?? '')) . "</td>";
                    echo "<td>" . $student_count . "</td>";
                    echo "<td>" . htmlspecialchars(substr($class['description'] ?? '', 0, 50)) . "</td>";
                    echo "<td class='action-buttons'>";
                    if (hasAccess('classes', 'edit')) {
                        echo "<a href='classes_edit.php?id=" . $class['id'] . "' class='btn-edit'>Edit</a>";
                    }
                    if (hasAccess('classes', 'delete')) {
                        echo "<a href='classes.php?delete=" . $class['id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No classes found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
