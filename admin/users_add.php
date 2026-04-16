<?php
include('session.php');
include('users_functions.php');
requireAccess('users', 'add');

$page_title = 'Add New User - School Management System';

$error = '';
$success = '';
$generated_password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $state = isset($_POST['state']) ? trim($_POST['state']) : '';
    $pincode = isset($_POST['pincode']) ? trim($_POST['pincode']) : '';
    $role = isset($_POST['role']) ? $_POST['role'] : 'student';
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';
    $qualification = isset($_POST['qualification']) ? trim($_POST['qualification']) : '';
    $specialization = isset($_POST['specialization']) ? trim($_POST['specialization']) : '';
    $experience_years = isset($_POST['experience_years']) ? intval($_POST['experience_years']) : 0;
    $joining_date = isset($_POST['joining_date']) ? $_POST['joining_date'] : date('Y-m-d');
    $generate_pass = isset($_POST['generate_password']) ? true : false;
    
    // Generate password if checkbox is selected
    if ($generate_pass) {
        $password = generatePassword();
        $generated_password = $password;
    }
    
    // Validate input
    $validation_errors = validateUserInput($username, $email, $first_name, $password, false);
    
    if (count($validation_errors) > 0) {
        $error = implode('<br>', $validation_errors);
    } elseif (empty($password)) {
        $error = 'Password is required. Either enter a password or check "Generate Password".';
    } else {
        // Add user
        $result = addUser($username, $password, $email, $first_name, $last_name, $phone, $dob, $gender, $address, $city, $state, $pincode, $role, $department, $qualification, $specialization, $experience_years, $joining_date);
        
        if ($result['success']) {
            header("Location: users.php?msg=added");
            exit();
        } else {
            $error = $result['message'];
        }
    }
}

include('header.php');
?>

<div class="page-header">
    <h1>➕ Add New User</h1>
    <a href="users.php" class="btn-secondary">← Back to Users</a>
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

<?php if ($generated_password): ?>
    <div class="alert alert-info">
        <strong>Generated Password:</strong> <?php echo htmlspecialchars($generated_password); ?>
        <br><small>Please save this password and share it with the user securely.</small>
    </div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="users_add.php" class="add-user-form">
        
        <!-- Role Selection -->
        <div class="form-group-row">
            <h3>📋 Select User Role</h3>
        </div>
        
        <div class="form-group-row">
            <div class="form-group">
                <label for="role">User Role *</label>
                <select name="role" id="role" class="form-control" required onchange="updateRoleFields()">
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="nts">Non-Teaching Staff</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="form-group-row">
            <h3>👤 Basic Information</h3>
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="username">Username *</label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    class="form-control" 
                    placeholder="Enter username" 
                    required
                    value="<?php echo htmlspecialchars($username ?? ''); ?>"
                >
                <small>3-50 characters, letters, numbers, underscore, hyphen only</small>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control" 
                    placeholder="Enter email" 
                    required
                    value="<?php echo htmlspecialchars($email ?? ''); ?>"
                >
            </div>
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input 
                    type="text" 
                    name="first_name" 
                    id="first_name" 
                    class="form-control" 
                    placeholder="Enter first name" 
                    required
                    value="<?php echo htmlspecialchars($first_name ?? ''); ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input 
                    type="text" 
                    name="last_name" 
                    id="last_name" 
                    class="form-control" 
                    placeholder="Enter last name"
                    value="<?php echo htmlspecialchars($last_name ?? ''); ?>"
                >
            </div>
        </div>

        <!-- Password Section -->
        <div class="form-group-row">
            <h3>🔐 Password</h3>
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="password">Password *</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control" 
                    placeholder="Enter password"
                    value="<?php echo htmlspecialchars($password ?? ''); ?>"
                >
                <small>Minimum 6 characters</small>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="generate_password" id="generate_password" onchange="togglePasswordField()">
                    Generate Random Password
                </label>
                <small>If checked, a random password will be generated</small>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="form-group-row">
            <h3>📞 Contact Information</h3>
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="phone">Phone</label>
                <input 
                    type="tel" 
                    name="phone" 
                    id="phone" 
                    class="form-control" 
                    placeholder="Enter phone number"
                    value="<?php echo htmlspecialchars($phone ?? ''); ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo isset($gender) && $gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo isset($gender) && $gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo isset($gender) && $gender === 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input 
                    type="date" 
                    name="dob" 
                    id="dob" 
                    class="form-control"
                    value="<?php echo htmlspecialchars($dob ?? ''); ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="joining_date">Joining Date</label>
                <input 
                    type="date" 
                    name="joining_date" 
                    id="joining_date" 
                    class="form-control"
                    value="<?php echo htmlspecialchars($joining_date ?? date('Y-m-d')); ?>"
                >
            </div>
        </div>

        <!-- Address -->
        <div class="form-group-row">
            <h3>🏠 Address</h3>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea 
                name="address" 
                id="address" 
                class="form-control" 
                rows="3" 
                placeholder="Enter complete address"
            ><?php echo htmlspecialchars($address ?? ''); ?></textarea>
        </div>

        <div class="form-group-row">
            <div class="form-group">
                <label for="city">City</label>
                <input 
                    type="text" 
                    name="city" 
                    id="city" 
                    class="form-control" 
                    placeholder="Enter city"
                    value="<?php echo htmlspecialchars($city ?? ''); ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="state">State</label>
                <input 
                    type="text" 
                    name="state" 
                    id="state" 
                    class="form-control" 
                    placeholder="Enter state"
                    value="<?php echo htmlspecialchars($state ?? ''); ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="pincode">Pincode</label>
                <input 
                    type="text" 
                    name="pincode" 
                    id="pincode" 
                    class="form-control" 
                    placeholder="Enter pincode"
                    value="<?php echo htmlspecialchars($pincode ?? ''); ?>"
                >
            </div>
        </div>

        <!-- Professional Information (for Teachers and Staff) -->
        <div id="professional-section" style="display: none;">
            <div class="form-group-row">
                <h3>💼 Professional Information</h3>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="department">Department</label>
                    <input 
                        type="text" 
                        name="department" 
                        id="department" 
                        class="form-control" 
                        placeholder="Enter department"
                        value="<?php echo htmlspecialchars($department ?? ''); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="qualification">Qualification</label>
                    <input 
                        type="text" 
                        name="qualification" 
                        id="qualification" 
                        class="form-control" 
                        placeholder="e.g., B.A., B.Tech, M.Sc"
                        value="<?php echo htmlspecialchars($qualification ?? ''); ?>"
                    >
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <input 
                        type="text" 
                        name="specialization" 
                        id="specialization" 
                        class="form-control" 
                        placeholder="Enter specialization"
                        value="<?php echo htmlspecialchars($specialization ?? ''); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="experience_years">Years of Experience</label>
                    <input 
                        type="number" 
                        name="experience_years" 
                        id="experience_years" 
                        class="form-control" 
                        placeholder="0"
                        min="0"
                        value="<?php echo htmlspecialchars($experience_years ?? 0); ?>"
                    >
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn-primary">✅ Add User</button>
            <a href="users.php" class="btn-secondary">❌ Cancel</a>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>

<style>
.form-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.add-user-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.form-group-row h3 {
    grid-column: 1 / -1;
    margin-top: 20px;
    margin-bottom: 10px;
    color: var(--primary-color);
    font-size: 16px;
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 10px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-color);
}

.form-control,
textarea {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus,
textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 5px rgba(102, 126, 234, 0.1);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 400;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

small {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-primary,
.btn-secondary {
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background-color: #5568d3;
}

.btn-secondary {
    background-color: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background-color: #7f8c8d;
}

@media (max-width: 768px) {
    .form-group-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function updateRoleFields() {
    const role = document.getElementById('role').value;
    const professionalSection = document.getElementById('professional-section');
    
    if (role === 'teacher' || role === 'nts') {
        professionalSection.style.display = 'block';
    } else {
        professionalSection.style.display = 'none';
    }
}

function togglePasswordField() {
    const generateCheckbox = document.getElementById('generate_password');
    const passwordField = document.getElementById('password');
    
    if (generateCheckbox.checked) {
        passwordField.disabled = true;
        passwordField.value = '';
        passwordField.placeholder = 'Password will be generated automatically';
    } else {
        passwordField.disabled = false;
        passwordField.placeholder = 'Enter password';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRoleFields();
});
</script>
