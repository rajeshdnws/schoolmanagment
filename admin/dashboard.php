<?php
include('session.php');
requireAccess('dashboard', 'view');

$page_title = 'Dashboard - School Management System';

// Get statistics
$total_students = getRow("SELECT COUNT(*) as count FROM students")['count'];
$total_teachers = getRow("SELECT COUNT(*) as count FROM teachers")['count'];
$total_classes = getRow("SELECT COUNT(*) as count FROM classes")['count'];
$pending_fees = getRow("SELECT COUNT(*) as count FROM fees WHERE status = 'pending'")['count'];

include('header.php');
?>

<div class="dashboard-header">
    <h1>Dashboard</h1>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</p>
</div>

<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="stat-icon">👨‍🎓</div>
        <div class="stat-content">
            <h3><?php echo $total_students; ?></h3>
            <p>Total Students</p>
        </div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="stat-icon">👨‍🏫</div>
        <div class="stat-content">
            <h3><?php echo $total_teachers; ?></h3>
            <p>Total Teachers</p>
        </div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="stat-icon">🏫</div>
        <div class="stat-content">
            <h3><?php echo $total_classes; ?></h3>
            <p>Total Classes</p>
        </div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
        <div class="stat-icon">💰</div>
        <div class="stat-content">
            <h3><?php echo $pending_fees; ?></h3>
            <p>Pending Fees</p>
        </div>
    </div>
</div>

<div class="dashboard-content">
    <div class="dashboard-section">
        <h2>Recent Students</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Class</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $students = getAllRows("SELECT s.*, c.class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id ORDER BY s.created_at DESC LIMIT 5");
                if (count($students) > 0) {
                    foreach ($students as $student) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($student['roll_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($student['first_name'] . " " . $student['last_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($student['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($student['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($student['class_name'] ?? 'N/A') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No students found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <p class="mt-20"><a href="students.php" class="btn-secondary">View All Students →</a></p>
    </div>
    
    <div class="dashboard-section">
        <h2>Recent Teachers</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Specialization</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $teachers = getAllRows("SELECT * FROM teachers ORDER BY created_at DESC LIMIT 5");
                if (count($teachers) > 0) {
                    foreach ($teachers as $teacher) {
                        $status_class = $teacher['status'] === 'active' ? 'status-active' : 'status-inactive';
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($teacher['first_name'] . " " . $teacher['last_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($teacher['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($teacher['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($teacher['specialization']) . "</td>";
                        echo "<td><span class='badge $status_class'>" . ucfirst($teacher['status']) . "</span></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No teachers found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <p class="mt-20"><a href="teachers.php" class="btn-secondary">View All Teachers →</a></p>
    </div>
</div>

<?php include('footer.php'); ?>
