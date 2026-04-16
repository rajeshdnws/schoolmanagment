<?php
include('session.php');
requireAccess('teachers', 'view');

$page_title = 'Teachers - School Management System';

// Handle delete
if (isset($_GET['delete'])) {
    requireAccess('teachers', 'delete');
    $id = intval($_GET['delete']);
    deleteData('teachers', "id = $id");
    header("Location: teachers.php?msg=deleted");
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$where = "1=1";
if ($search) {
    $where .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%')";
}

$teachers = getAllRows("SELECT * FROM teachers WHERE $where ORDER BY first_name");

include('header.php');
?>

<div class="page-header">
    <h1>Teachers Management</h1>
    <?php if (hasAccess('teachers', 'add')): ?>
        <a href="teachers_add.php" class="btn-primary">+ Add New Teacher</a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php
        if ($_GET['msg'] === 'deleted') echo "Teacher deleted successfully!";
        elseif ($_GET['msg'] === 'added') echo "Teacher added successfully!";
        elseif ($_GET['msg'] === 'updated') echo "Teacher updated successfully!";
        ?>
    </div>
<?php endif; ?>

<div class="filters">
    <form method="GET" action="teachers.php" class="filter-form">
        <input 
            type="text" 
            name="search" 
            placeholder="Search by name or email..."
            value="<?php echo htmlspecialchars($search); ?>"
        >
        <button type="submit" class="btn-secondary">Search</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Specialization</th>
                <th>Experience</th>
                <th>Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($teachers) > 0) {
                foreach ($teachers as $teacher) {
                    $status_class = $teacher['status'] === 'active' ? 'status-active' : 'status-inactive';
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($teacher['first_name'] . " " . $teacher['last_name']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($teacher['email'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($teacher['phone'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($teacher['specialization'] ?? 'N/A') . "</td>";
                    echo "<td>" . intval($teacher['experience_years']) . " years</td>";
                    echo "<td>₹" . number_format($teacher['salary'], 2) . "</td>";
                    echo "<td><span class='badge $status_class'>" . ucfirst($teacher['status']) . "</span></td>";
                    echo "<td class='action-buttons'>";
                    if (hasAccess('teachers', 'edit')) {
                        echo "<a href='teachers_edit.php?id=" . $teacher['id'] . "' class='btn-edit'>Edit</a>";
                    }
                    echo "<a href='teachers_view.php?id=" . $teacher['id'] . "' class='btn-view'>View</a>";
                    if (hasAccess('teachers', 'delete')) {
                        echo "<a href='teachers.php?delete=" . $teacher['id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>No teachers found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
