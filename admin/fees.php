<?php
include('session.php');
requireAccess('fees', 'view');

$page_title = 'Fees - School Management System';

// Handle status update
if (isset($_POST['update_status'])) {
    $fee_id = intval($_POST['fee_id']);
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    
    if ($status === 'paid') {
        $paid_date = date('Y-m-d');
        updateData('fees', ['status' => 'paid', 'paid_date' => $paid_date], "id = $fee_id");
    } else {
        updateData('fees', ['status' => 'pending'], "id = $fee_id");
    }
    
    header("Location: fees.php");
    exit();
}

$filter_class = isset($_GET['filter_class']) ? intval($_GET['filter_class']) : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';

$where = "1=1";
if ($filter_class) {
    $where .= " AND f.class_id = $filter_class";
}
if ($filter_status) {
    $where .= " AND f.status = '$filter_status'";
}

$fees = getAllRows("
    SELECT f.*, s.roll_number, s.first_name, s.last_name, c.class_name 
    FROM fees f 
    JOIN students s ON f.student_id = s.id 
    JOIN classes c ON f.class_id = c.id 
    WHERE $where 
    ORDER BY f.due_date DESC
");

$classes = getAllRows("SELECT * FROM classes ORDER BY class_name");

// Calculate statistics
$total_due = getRow("SELECT SUM(fee_amount) as total FROM fees WHERE status = 'pending'")['total'] ?? 0;
$total_collected = getRow("SELECT SUM(fee_amount) as total FROM fees WHERE status = 'paid'")['total'] ?? 0;

include('header.php');
?>

<div class="page-header">
    <h1>Fees Management</h1>
</div>

<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
        <div class="stat-content">
            <h3>₹<?php echo number_format($total_due, 2); ?></h3>
            <p>Total Due</p>
        </div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
        <div class="stat-content">
            <h3>₹<?php echo number_format($total_collected, 2); ?></h3>
            <p>Total Collected</p>
        </div>
    </div>
</div>

<div class="filters">
    <form method="GET" action="fees.php" class="filter-form">
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
            <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="paid" <?php echo $filter_status === 'paid' ? 'selected' : ''; ?>>Paid</option>
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
                <th>Fee Amount</th>
                <th>Fee Type</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($fees) > 0) {
                foreach ($fees as $fee) {
                    $status_class = $fee['status'] === 'paid' ? 'status-active' : 'status-inactive';
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fee['class_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($fee['roll_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($fee['first_name'] . " " . $fee['last_name']) . "</td>";
                    echo "<td>₹" . number_format($fee['fee_amount'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($fee['fee_type'] ?? 'N/A') . "</td>";
                    echo "<td>" . ($fee['due_date'] ? date('d-M-Y', strtotime($fee['due_date'])) : 'N/A') . "</td>";
                    echo "<td><span class='badge $status_class'>" . ucfirst($fee['status']) . "</span></td>";
                    echo "<td>";
                    if ($fee['status'] === 'pending') {
                        echo "<form method='POST' style='display:inline;'>";
                        echo "<input type='hidden' name='fee_id' value='" . $fee['id'] . "'>";
                        echo "<input type='hidden' name='update_status' value='1'>";
                        echo "<input type='hidden' name='status' value='paid'>";
                        echo "<button type='submit' class='btn-edit' onclick='return confirm(\"Mark as paid?\")'>Mark Paid</button>";
                        echo "</form>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>No fees found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
