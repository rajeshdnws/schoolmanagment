<?php
include('session.php');
include('users_functions.php');
requireAccess('users', 'view');

$page_title = 'Users Management - School Management System';

// Handle delete
if (isset($_GET['delete'])) {
    requireAccess('users', 'delete');
    $id = intval($_GET['delete']);
    $result = deleteUser($id);
    if ($result['success']) {
        header("Location: users.php?msg=deleted");
    } else {
        header("Location: users.php?msg=error&err=" . urlencode($result['message']));
    }
    exit();
}

// Get filters
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$where = "1=1";
if ($role_filter) {
    $where .= " AND role = '$role_filter'";
}
if ($status_filter) {
    $where .= " AND status = '$status_filter'";
}
if ($search) {
    $where .= " AND (username LIKE '%$search%' OR email LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%')";
}

$result = $conn->query("SELECT * FROM users WHERE $where ORDER BY created_at DESC");
$users = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Get statistics
$stats = getUserStatistics();

include('header.php');
?>

<div class="page-header">
    <h1>👥 Users Management</h1>
    <?php if (hasAccess('users', 'add')): ?>
        <a href="users_add.php" class="btn-primary">+ Add New User</a>
    <?php endif; ?>
</div>

<!-- Statistics Cards -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-number"><?php echo $stats['total_users']; ?></div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $stats['total_admins']; ?></div>
        <div class="stat-label">Administrators</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $stats['total_teachers']; ?></div>
        <div class="stat-label">Teachers</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $stats['total_students']; ?></div>
        <div class="stat-label">Students</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $stats['total_staff']; ?></div>
        <div class="stat-label">Non-Teaching Staff</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $stats['active_users']; ?></div>
        <div class="stat-label">Active Users</div>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php
        if ($_GET['msg'] === 'deleted') echo "User deleted successfully!";
        elseif ($_GET['msg'] === 'added') echo "User added successfully!";
        elseif ($_GET['msg'] === 'updated') echo "User updated successfully!";
        elseif ($_GET['msg'] === 'error') echo isset($_GET['err']) ? htmlspecialchars($_GET['err']) : "An error occurred!";
        ?>
    </div>
<?php endif; ?>

<!-- Filters and Search -->
<div class="filter-section">
    <form method="GET" action="users.php" class="filter-form">
        <input 
            type="text" 
            name="search" 
            placeholder="Search by username, email, or name..." 
            value="<?php echo htmlspecialchars($search); ?>"
            class="filter-input"
        >
        
        <select name="role" class="filter-select">
            <option value="">All Roles</option>
            <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Administrator</option>
            <option value="teacher" <?php echo $role_filter === 'teacher' ? 'selected' : ''; ?>>Teacher</option>
            <option value="student" <?php echo $role_filter === 'student' ? 'selected' : ''; ?>>Student</option>
            <option value="nts" <?php echo $role_filter === 'nts' ? 'selected' : ''; ?>>Non-Teaching Staff</option>
        </select>
        
        <select name="status" class="filter-select">
            <option value="">All Status</option>
            <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
        
        <button type="submit" class="btn-filter">🔍 Filter</button>
        <a href="users.php" class="btn-reset">Reset</a>
    </form>
</div>

<!-- Users Table -->
<div class="table-responsive">
    <table class="users-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                        <td>
                            <span class="badge" style="background-color: <?php echo getRoleColor($user['role']); ?>;">
                                <?php echo getRoleDisplayName($user['role']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                        <td>
                            <span class="status-badge <?php echo $user['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                        <td class="action-buttons">
                            <?php if (hasAccess('users', 'edit')): ?>
                                <a href="users_edit.php?id=<?php echo $user['id']; ?>" class="btn-edit" title="Edit">✏️ Edit</a>
                            <?php endif; ?>
                            <?php if (hasAccess('users', 'delete')): ?>
                                <a href="users.php?delete=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?');" title="Delete">🗑️ Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No users found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>

<style>
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    border-left: 5px solid var(--primary-color);
}

.stat-number {
    font-size: 28px;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: #666;
}

.filter-section {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filter-form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.filter-input,
.filter-select {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.filter-input {
    flex: 1;
    min-width: 200px;
}

.filter-select {
    min-width: 150px;
}

.btn-filter,
.btn-reset {
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
}

.btn-filter {
    background-color: var(--primary-color);
    color: white;
}

.btn-reset {
    background-color: #95a5a6;
    color: white;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.users-table thead {
    background-color: var(--primary-color);
    color: white;
}

.users-table th,
.users-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.users-table tbody tr:hover {
    background-color: #f9f9f9;
}

.users-table tbody tr:last-child td {
    border-bottom: none;
}

.badge {
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-active {
    background-color: #d4edda;
    color: #155724;
}

.status-inactive {
    background-color: #f8d7da;
    color: #721c24;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-edit,
.btn-delete {
    padding: 6px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    text-decoration: none;
    display: inline-block;
}

.btn-edit {
    background-color: var(--info-color);
    color: white;
}

.btn-delete {
    background-color: var(--danger-color);
    color: white;
}

.text-center {
    text-align: center;
    color: #999;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
</style>
