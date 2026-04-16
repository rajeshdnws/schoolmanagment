<?php
include('session.php');
requireAccess('exams', 'edit');

if (!isset($_GET['id'])) {
    header("Location: exams.php");
    exit();
}

$id = intval($_GET['id']);
$exam = getRow("SELECT * FROM exams WHERE id = $id");

if (!$exam) {
    header("Location: exams.php");
    exit();
}

$page_title = 'Edit Exam - School Management System';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exam_name = isset($_POST['exam_name']) ? trim($_POST['exam_name']) : '';
    $class_id = isset($_POST['class_id']) ? intval($_POST['class_id']) : '';
    $subject_id = isset($_POST['subject_id']) ? intval($_POST['subject_id']) : '';
    $exam_date = isset($_POST['exam_date']) ? $_POST['exam_date'] : '';
    $exam_time = isset($_POST['exam_time']) ? $_POST['exam_time'] : '';
    $total_marks = isset($_POST['total_marks']) ? intval($_POST['total_marks']) : 0;
    $duration = isset($_POST['duration']) ? intval($_POST['duration']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    
    if (empty($exam_name) || empty($class_id)) {
        $error = "Exam name and class are required!";
    } else {
        $data = [
            'exam_name' => $exam_name,
            'class_id' => $class_id,
            'subject_id' => $subject_id ?: 0,
            'exam_date' => $exam_date,
            'exam_time' => $exam_time,
            'total_marks' => $total_marks,
            'duration_minutes' => $duration,
            'status' => $status
        ];
        
        updateData('exams', $data, "id = $id");
        header("Location: exams.php?msg=updated");
        exit();
    }
}

$classes = getAllRows("SELECT * FROM classes ORDER BY class_name");
$subjects = getAllRows("SELECT * FROM subjects ORDER BY subject_name");

include('header.php');
?>

<div class="page-header">
    <h1>Edit Exam: <?php echo htmlspecialchars($exam['exam_name']); ?></h1>
    <a href="exams.php" class="btn-secondary">← Back to Exams</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="" class="form">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exam_name">Exam Name * <span class="required">(Required)</span></label>
                <input 
                    type="text" 
                    id="exam_name" 
                    name="exam_name" 
                    value="<?php echo htmlspecialchars($exam['exam_name']); ?>"
                    required
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="class_id">Class * <span class="required">(Required)</span></label>
                <select id="class_id" name="class_id" required>
                    <option value="">-- Select Class --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>" 
                            <?php echo $exam['class_id'] == $class['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class['class_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="subject_id">Subject</label>
                <select id="subject_id" name="subject_id">
                    <option value="">-- Select Subject --</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo $subject['id']; ?>" 
                            <?php echo $exam['subject_id'] == $subject['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($subject['subject_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group col-md-6">
                <label for="exam_date">Exam Date</label>
                <input 
                    type="date" 
                    id="exam_date" 
                    name="exam_date"
                    value="<?php echo htmlspecialchars($exam['exam_date']); ?>"
                >
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exam_time">Exam Time</label>
                <input 
                    type="time" 
                    id="exam_time" 
                    name="exam_time"
                    value="<?php echo htmlspecialchars($exam['exam_time']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="total_marks">Total Marks</label>
                <input 
                    type="number" 
                    id="total_marks" 
                    name="total_marks" 
                    min="0"
                    value="<?php echo htmlspecialchars($exam['total_marks']); ?>"
                >
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="duration">Duration (Minutes)</label>
                <input 
                    type="number" 
                    id="duration" 
                    name="duration" 
                    min="0"
                    value="<?php echo htmlspecialchars($exam['duration_minutes']); ?>"
                >
            </div>
            
            <div class="form-group col-md-6">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="scheduled" <?php echo $exam['status'] === 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                    <option value="ongoing" <?php echo $exam['status'] === 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                    <option value="completed" <?php echo $exam['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Exam</button>
            <a href="exams.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
