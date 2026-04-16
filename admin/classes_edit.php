<?php
include('session.php');
requireAccess('classes', 'edit');

if (!isset($_GET['id'])) {
    header("Location: classes.php");
    exit();
}

$id = intval($_GET['id']);
$class = getRow("SELECT * FROM classes WHERE id = $id");

if (!$class) {
    header("Location: classes.php");
    exit();
}

$page_title = 'Edit Class - School Management System';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = isset($_POST['class_name']) ? trim($_POST['class_name']) : '';
    $section = isset($_POST['section']) ? trim($_POST['section']) : '';
    $capacity = isset($_POST['capacity']) ? intval($_POST['capacity']) : 0;
    $class_teacher_id = isset($_POST['class_teacher_id']) ? intval($_POST['class_teacher_id']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    
    if (empty($class_name)) {
        $error = "Class name is required!";
    } else {
        $data = [
            'class_name' => $class_name,
            'section' => $section,
            'capacity' => $capacity,
            'class_teacher_id' => $class_teacher_id ?: 0,
            'description' => $description
        ];
        
        updateData('classes', $data, "id = $id");
        header("Location: classes.php?msg=updated");
        exit();
    }
}

$teachers = getAllRows("SELECT id, first_name, last_name FROM teachers WHERE status = 'active' ORDER BY first_name");

include('header.php');
?>

<div class="page-header">
    <h1>Edit Class: <?php echo htmlspecialchars($class['class_name']); ?></h1>
    <a href="classes.php" class="btn-secondary">← Back to Classes</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="" class="form">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="class_name">Class Name * <span class="required">(Required)</span></label>
                <input 
                    type="text" 
                    id="class_name" 
                    name="class_name" 
                    value="<?php echo htmlspecialchars($class['class_name']); ?>"
                    required
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="section">Section</label>
                <input 
                    type="text" 
                    id="section" 
                    name="section" 
                    value="<?php echo htmlspecialchars($class['section']); ?>"
                >
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="capacity">Capacity</label>
                <input 
                    type="number" 
                    id="capacity" 
                    name="capacity" 
                    min="1"
                    value="<?php echo htmlspecialchars($class['capacity']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="class_teacher_id">Class Teacher</label>
                <select id="class_teacher_id" name="class_teacher_id">
                    <option value="">-- Select Teacher --</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?php echo $teacher['id']; ?>" 
                            <?php echo $class['class_teacher_id'] == $teacher['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($teacher['first_name'] . " " . $teacher['last_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea 
                id="description" 
                name="description" 
                rows="4"
            ><?php echo htmlspecialchars($class['description']); ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Class</button>
            <a href="classes.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
