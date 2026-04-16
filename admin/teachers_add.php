<?php
include('session.php');
requireAccess('teachers', 'add');

$page_title = 'Add Teacher - School Management System';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $qualification = isset($_POST['qualification']) ? trim($_POST['qualification']) : '';
    $specialization = isset($_POST['specialization']) ? trim($_POST['specialization']) : '';
    $experience = isset($_POST['experience']) ? intval($_POST['experience']) : 0;
    $joining_date = isset($_POST['joining_date']) ? $_POST['joining_date'] : '';
    $salary = isset($_POST['salary']) ? floatval($_POST['salary']) : 0;
    
    if (empty($first_name)) {
        $error = "First name is required!";
    } else {
        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'date_of_birth' => $dob,
            'gender' => $gender,
            'address' => $address,
            'qualification' => $qualification,
            'specialization' => $specialization,
            'experience_years' => $experience,
            'joining_date' => $joining_date,
            'salary' => $salary,
            'status' => 'active'
        ];
        
        insertData('teachers', $data);
        header("Location: teachers.php?msg=added");
        exit();
    }
}

include('header.php');
?>

<div class="page-header">
    <h1>Add New Teacher</h1>
    <a href="teachers.php" class="btn-secondary">← Back to Teachers</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="teachers_add.php" class="form">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="first_name">First Name * <span class="required">(Required)</span></label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    placeholder="Enter first name"
                    value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                    required
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="last_name">Last Name</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    placeholder="Enter last name"
                    value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>"
                >
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="teacher@example.com"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="phone">Phone</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    placeholder="Enter phone number"
                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                >
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="dob">Date of Birth</label>
                <input 
                    type="date" 
                    id="dob" 
                    name="dob"
                    value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">-- Select Gender --</option>
                    <option value="Male" <?php echo isset($_POST['gender']) && $_POST['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo isset($_POST['gender']) && $_POST['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="address">Address</label>
            <textarea 
                id="address" 
                name="address" 
                placeholder="Enter full address"
                rows="3"
            ><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
        </div>
        
        <h3>Professional Information</h3>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="qualification">Qualification</label>
                <input 
                    type="text" 
                    id="qualification" 
                    name="qualification" 
                    placeholder="e.g., B.Tech, M.Sc"
                    value="<?php echo isset($_POST['qualification']) ? htmlspecialchars($_POST['qualification']) : ''; ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="specialization">Specialization</label>
                <input 
                    type="text" 
                    id="specialization" 
                    name="specialization" 
                    placeholder="e.g., Mathematics, Science"
                    value="<?php echo isset($_POST['specialization']) ? htmlspecialchars($_POST['specialization']) : ''; ?>"
                >
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="experience">Experience (Years)</label>
                <input 
                    type="number" 
                    id="experience" 
                    name="experience" 
                    placeholder="0"
                    min="0"
                    value="<?php echo isset($_POST['experience']) ? htmlspecialchars($_POST['experience']) : ''; ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="joining_date">Joining Date</label>
                <input 
                    type="date" 
                    id="joining_date" 
                    name="joining_date"
                    value="<?php echo isset($_POST['joining_date']) ? htmlspecialchars($_POST['joining_date']) : date('Y-m-d'); ?>"
                >
            </div>
        </div>
        
        <div class="form-group">
            <label for="salary">Salary</label>
            <input 
                type="number" 
                id="salary" 
                name="salary" 
                placeholder="0.00"
                step="0.01"
                value="<?php echo isset($_POST['salary']) ? htmlspecialchars($_POST['salary']) : ''; ?>"
            >
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Add Teacher</button>
            <a href="teachers.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
