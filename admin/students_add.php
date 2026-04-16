<?php
include('session.php');
requireAccess('students', 'add');

$page_title = 'Add Student - School Management System';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roll_number = isset($_POST['roll_number']) ? trim($_POST['roll_number']) : '';
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
    
    // Validation
    if (empty($roll_number) || empty($first_name) || empty($class_id)) {
        $error = "Roll number, first name, and class are required fields!";
    } else {
        // Check if roll number already exists
        $check = getRow("SELECT id FROM students WHERE roll_number = '$roll_number'");
        if ($check) {
            $error = "Roll number already exists!";
        } else {
            $data = [
                'roll_number' => $roll_number,
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
                'status' => 'active'
            ];
            
            insertData('students', $data);
            header("Location: students.php?msg=added");
            exit();
        }
    }
}

$classes = getAllRows("SELECT * FROM classes ORDER BY class_name");

include('header.php');
?>

<div class="page-header">
    <h1>Add New Student</h1>
    <a href="students.php" class="btn-secondary">← Back to Students</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="students_add.php" class="form">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="roll_number">Roll Number * <span class="required">(Required)</span></label>
                <input 
                    type="text" 
                    id="roll_number" 
                    name="roll_number" 
                    placeholder="e.g., STU001"
                    value="<?php echo isset($_POST['roll_number']) ? htmlspecialchars($_POST['roll_number']) : ''; ?>"
                    required
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="class_id">Class * <span class="required">(Required)</span></label>
                <select id="class_id" name="class_id" required>
                    <option value="">-- Select Class --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>" 
                            <?php echo isset($_POST['class_id']) && $_POST['class_id'] == $class['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class['class_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
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
                    placeholder="student@example.com"
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
                    <option value="Other" <?php echo isset($_POST['gender']) && $_POST['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
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
                    placeholder="Enter city"
                    value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>"
                >
            </div>
            
            <div class="form-group col-md-4">
                <label for="state">State</label>
                <input 
                    type="text" 
                    id="state" 
                    name="state" 
                    placeholder="Enter state"
                    value="<?php echo isset($_POST['state']) ? htmlspecialchars($_POST['state']) : ''; ?>"
                >
            </div>
            
            <div class="form-group col-md-4">
                <label for="pincode">Pincode</label>
                <input 
                    type="text" 
                    id="pincode" 
                    name="pincode" 
                    placeholder="Enter pincode"
                    value="<?php echo isset($_POST['pincode']) ? htmlspecialchars($_POST['pincode']) : ''; ?>"
                >
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
        
        <h3>Parent Information</h3>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="father_name">Father's Name</label>
                <input 
                    type="text" 
                    id="father_name" 
                    name="father_name" 
                    placeholder="Enter father's name"
                    value="<?php echo isset($_POST['father_name']) ? htmlspecialchars($_POST['father_name']) : ''; ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="father_phone">Father's Phone</label>
                <input 
                    type="tel" 
                    id="father_phone" 
                    name="father_phone" 
                    placeholder="Enter father's phone"
                    value="<?php echo isset($_POST['father_phone']) ? htmlspecialchars($_POST['father_phone']) : ''; ?>"
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
                    placeholder="Enter mother's name"
                    value="<?php echo isset($_POST['mother_name']) ? htmlspecialchars($_POST['mother_name']) : ''; ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="mother_phone">Mother's Phone</label>
                <input 
                    type="tel" 
                    id="mother_phone" 
                    name="mother_phone" 
                    placeholder="Enter mother's phone"
                    value="<?php echo isset($_POST['mother_phone']) ? htmlspecialchars($_POST['mother_phone']) : ''; ?>"
                >
            </div>
        </div>
        
        <div class="form-group">
            <label for="admission_date">Admission Date</label>
            <input 
                type="date" 
                id="admission_date" 
                name="admission_date"
                value="<?php echo isset($_POST['admission_date']) ? htmlspecialchars($_POST['admission_date']) : date('Y-m-d'); ?>"
            >
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Add Student</button>
            <a href="students.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
