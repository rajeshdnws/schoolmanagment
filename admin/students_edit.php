<?php
include('session.php');
requireAccess('students', 'edit');

$page_title = 'Edit Student - School Management System';

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$id = intval($_GET['id']);
$student = getRow("SELECT * FROM students WHERE id = $id");

if (!$student) {
    header("Location: students.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $state = isset($_POST['state']) ? trim($_POST['state']) : '';
    $pincode = isset($_POST['pincode']) ? trim($_POST['pincode']) : '';
    $class_id = isset($_POST['class_id']) ? intval($_POST['class_id']) : '';
    $father_name = isset($_POST['father_name']) ? trim($_POST['father_name']) : '';
    $father_phone = isset($_POST['father_phone']) ? trim($_POST['father_phone']) : '';
    $mother_name = isset($_POST['mother_name']) ? trim($_POST['mother_name']) : '';
    $mother_phone = isset($_POST['mother_phone']) ? trim($_POST['mother_phone']) : '';
    $admission_date = isset($_POST['admission_date']) ? $_POST['admission_date'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    
    if (empty($first_name) || empty($class_id)) {
        $error = "First name and class are required!";
    } else {
        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'date_of_birth' => $dob,
            'gender' => $gender,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
            'class_id' => $class_id,
            'father_name' => $father_name,
            'father_phone' => $father_phone,
            'mother_name' => $mother_name,
            'mother_phone' => $mother_phone,
            'admission_date' => $admission_date,
            'status' => $status
        ];
        
        updateData('students', $data, "id = $id");
        header("Location: students.php?msg=updated");
        exit();
    }
}

$classes = getAllRows("SELECT * FROM classes ORDER BY class_name");

include('header.php');
?>

<div class="page-header">
    <h1>Edit Student: <?php echo htmlspecialchars($student['first_name'] . " " . $student['last_name']); ?></h1>
    <a href="students.php" class="btn-secondary">← Back to Students</a>
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
                    value="<?php echo htmlspecialchars($student['first_name']); ?>"
                    required
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="last_name">Last Name</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    value="<?php echo htmlspecialchars($student['last_name']); ?>"
                >
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="class_id">Class * <span class="required">(Required)</span></label>
                <select id="class_id" name="class_id" required>
                    <option value="">-- Select Class --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>" 
                            <?php echo $student['class_id'] == $class['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class['class_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group col-md-6">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active" <?php echo $student['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $student['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="suspended" <?php echo $student['status'] === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?php echo htmlspecialchars($student['email']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="phone">Phone</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="<?php echo htmlspecialchars($student['phone']); ?>"
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
                    value="<?php echo htmlspecialchars($student['date_of_birth']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">-- Select Gender --</option>
                    <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo $student['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="city">City</label>
                <input 
                    type="text" 
                    id="city" 
                    name="city" 
                    value="<?php echo htmlspecialchars($student['city']); ?>"
                >
            </div>
            
            <div class="form-group col-md-4">
                <label for="state">State</label>
                <input 
                    type="text" 
                    id="state" 
                    name="state" 
                    value="<?php echo htmlspecialchars($student['state']); ?>"
                >
            </div>
            
            <div class="form-group col-md-4">
                <label for="pincode">Pincode</label>
                <input 
                    type="text" 
                    id="pincode" 
                    name="pincode" 
                    value="<?php echo htmlspecialchars($student['pincode']); ?>"
                >
            </div>
        </div>
        
        <div class="form-group">
            <label for="address">Address</label>
            <textarea 
                id="address" 
                name="address" 
                rows="3"
            ><?php echo htmlspecialchars($student['address']); ?></textarea>
        </div>
        
        <h3>Parent Information</h3>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="father_name">Father's Name</label>
                <input 
                    type="text" 
                    id="father_name" 
                    name="father_name" 
                    value="<?php echo htmlspecialchars($student['father_name']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="father_phone">Father's Phone</label>
                <input 
                    type="tel" 
                    id="father_phone" 
                    name="father_phone" 
                    value="<?php echo htmlspecialchars($student['father_phone']); ?>"
                >
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="mother_name">Mother's Name</label>
                <input 
                    type="text" 
                    id="mother_name" 
                    name="mother_name" 
                    value="<?php echo htmlspecialchars($student['mother_name']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="mother_phone">Mother's Phone</label>
                <input 
                    type="tel" 
                    id="mother_phone" 
                    name="mother_phone" 
                    value="<?php echo htmlspecialchars($student['mother_phone']); ?>"
                >
            </div>
        </div>
        
        <div class="form-group">
            <label for="admission_date">Admission Date</label>
            <input 
                type="date" 
                id="admission_date" 
                name="admission_date"
                value="<?php echo htmlspecialchars($student['admission_date']); ?>"
            >
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Student</button>
            <a href="students.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
