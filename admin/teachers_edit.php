<?php
include('session.php');
requireAccess('teachers', 'edit');

if (!isset($_GET['id'])) {
    header("Location: teachers.php");
    exit();
}

$id = intval($_GET['id']);
$teacher = getRow("SELECT * FROM teachers WHERE id = $id");

if (!$teacher) {
    header("Location: teachers.php");
    exit();
}

$page_title = 'Edit Teacher - School Management System';

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
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    
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
            'status' => $status
        ];
        
        updateData('teachers', $data, "id = $id");
        header("Location: teachers.php?msg=updated");
        exit();
    }
}

include('header.php');
?>

<div class="page-header">
    <h1>Edit Teacher: <?php echo htmlspecialchars($teacher['first_name'] . " " . $teacher['last_name']); ?></h1>
    <a href="teachers.php" class="btn-secondary">← Back to Teachers</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="" class="form">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="first_name">First Name * <span class="required">(Required)</span></label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    value="<?php echo htmlspecialchars($teacher['first_name']); ?>"
                    required
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="last_name">Last Name</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    value="<?php echo htmlspecialchars($teacher['last_name']); ?>"
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
                    value="<?php echo htmlspecialchars($teacher['email']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="phone">Phone</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="<?php echo htmlspecialchars($teacher['phone']); ?>"
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
                    value="<?php echo htmlspecialchars($teacher['date_of_birth']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">-- Select Gender --</option>
                    <option value="Male" <?php echo $teacher['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $teacher['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="address">Address</label>
            <textarea 
                id="address" 
                name="address" 
                rows="3"
            ><?php echo htmlspecialchars($teacher['address']); ?></textarea>
        </div>
        
        <h3>Professional Information</h3>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="qualification">Qualification</label>
                <input 
                    type="text" 
                    id="qualification" 
                    name="qualification" 
                    value="<?php echo htmlspecialchars($teacher['qualification']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="specialization">Specialization</label>
                <input 
                    type="text" 
                    id="specialization" 
                    name="specialization" 
                    value="<?php echo htmlspecialchars($teacher['specialization']); ?>"
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
                    min="0"
                    value="<?php echo htmlspecialchars($teacher['experience_years']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="joining_date">Joining Date</label>
                <input 
                    type="date" 
                    id="joining_date" 
                    name="joining_date"
                    value="<?php echo htmlspecialchars($teacher['joining_date']); ?>"
                >
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="salary">Salary</label>
                <input 
                    type="number" 
                    id="salary" 
                    name="salary" 
                    step="0.01"
                    value="<?php echo htmlspecialchars($teacher['salary']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active" <?php echo $teacher['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $teacher['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="on_leave" <?php echo $teacher['status'] === 'on_leave' ? 'selected' : ''; ?>>On Leave</option>
                </select>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Teacher</button>
            <a href="teachers.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
