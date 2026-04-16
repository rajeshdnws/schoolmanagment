<?php
include('session.php');
requireAccess('settings', 'edit');

$page_title = 'Settings - School Management System';

$admin = getRow("SELECT * FROM users WHERE id = " . $_SESSION['admin_id']);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    if (empty($first_name) || empty($email)) {
        $error = "First name and email are required!";
    } elseif (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match!";
        } elseif (strlen($new_password) < 6) {
            $error = "Password must be at least 6 characters long!";
        } else {
            $data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => password_hash($new_password, PASSWORD_DEFAULT)
            ];
            updateData('users', $data, "id = " . $_SESSION['admin_id']);
            $_SESSION['admin_name'] = $first_name . ' ' . $last_name;
            $success = "Profile updated successfully!";
        }
    } else {
        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email
        ];
        updateData('users', $data, "id = " . $_SESSION['admin_id']);
        $_SESSION['admin_name'] = $first_name . ' ' . $last_name;
        $success = "Profile updated successfully!";
    }
}

include('header.php');
?>

<div class="page-header">
    <h1>Settings</h1>
    <a href="profile.php" class="btn-secondary">← Back to Profile</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="settings.php" class="form">
        <h2>Profile Settings</h2>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="username">Username (Read-Only)</label>
                <input 
                    type="text" 
                    id="username" 
                    value="<?php echo htmlspecialchars($admin['username']); ?>"
                    disabled
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="first_name">First Name</label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    value="<?php echo htmlspecialchars($admin['first_name']); ?>"
                    required
                >
            </div>
        </div>
        
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input 
                type="text" 
                id="last_name" 
                name="last_name" 
                value="<?php echo htmlspecialchars($admin['last_name']); ?>"
            >
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?php echo htmlspecialchars($admin['email']); ?>"
                required
            >
        </div>
        
        <h3>Change Password (Optional)</h3>
        <p style="color: #999; font-size: 13px; margin-bottom: 20px;">Leave blank if you don't want to change password</p>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="new_password">New Password</label>
                <input 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    placeholder="Enter new password (min 6 characters)"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="confirm_password">Confirm Password</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Confirm new password"
                >
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Settings</button>
            <a href="profile.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
