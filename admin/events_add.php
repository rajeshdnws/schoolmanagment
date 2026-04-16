<?php
include('session.php');
requireAccess('events', 'add');

$page_title = 'Add Event - School Management System';
include('header.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $event_date = sanitize($_POST['event_date'] ?? '');
    $event_time = sanitize($_POST['event_time'] ?? '');
    $end_date = sanitize($_POST['end_date'] ?? '');
    $location = sanitize($_POST['location'] ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $status = sanitize($_POST['status'] ?? 'upcoming');
    
    if (empty($title)) {
        $error = 'Event title is required';
    } elseif (empty($event_date)) {
        $error = 'Event date is required';
    } else {
        // Handle featured image
        $featured_image = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['size'] > 0) {
            $uploadDir = '../uploads/events/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['featured_image']['name']));
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadPath)) {
                $featured_image = 'uploads/events/' . $filename;
            }
        }
        
        $query = "INSERT INTO events (title, description, event_date, event_time, end_date, location, category, featured_image, status, created_by) 
                  VALUES ('$title', '$description', '$event_date', '$event_time', '$end_date', '$location', '$category', '$featured_image', '$status', {$_SESSION['admin_id']})";
        
        if (executeQuery($query)) {
            $success = 'Event added successfully!';
            $_POST = [];
        } else {
            $error = 'Failed to add event. Please try again.';
        }
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Get categories
$categories = getAllRows("SELECT DISTINCT category FROM events WHERE category != '' ORDER BY category");
?>

<div class="container">
    <div class="form-container">
        <h1>📅 Add New Event</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <div style="margin-top: 20px;">
                <a href="events_add.php" class="btn btn-primary">Add Another</a>
                <a href="events.php" class="btn btn-secondary">Back to Events</a>
            </div>
        <?php else: ?>
        
        <form method="POST" enctype="multipart/form-data" class="form-layout">
            <div class="form-group">
                <label>Event Title *</label>
                <input type="text" name="title" class="form-input" placeholder="Event title" 
                       value="<?php echo $_POST['title'] ?? ''; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Start Date *</label>
                    <input type="date" name="event_date" class="form-input" 
                           value="<?php echo $_POST['event_date'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Start Time</label>
                    <input type="time" name="event_time" class="form-input" 
                           value="<?php echo $_POST['event_time'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-input" 
                           value="<?php echo $_POST['end_date'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-input" placeholder="Event location" 
                           value="<?php echo $_POST['location'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-input">
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                    <?php echo ($_POST['category'] ?? '') === $cat['category'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="Sports" <?php echo ($_POST['category'] ?? '') === 'Sports' ? 'selected' : ''; ?>>Sports</option>
                        <option value="Academic" <?php echo ($_POST['category'] ?? '') === 'Academic' ? 'selected' : ''; ?>>Academic</option>
                        <option value="Cultural" <?php echo ($_POST['category'] ?? '') === 'Cultural' ? 'selected' : ''; ?>>Cultural</option>
                        <option value="Celebration" <?php echo ($_POST['category'] ?? '') === 'Celebration' ? 'selected' : ''; ?>>Celebration</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-input">
                        <option value="upcoming" <?php echo ($_POST['status'] ?? '') === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                        <option value="ongoing" <?php echo ($_POST['status'] ?? '') === 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                        <option value="completed" <?php echo ($_POST['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo ($_POST['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-input" rows="6" 
                          placeholder="Event description"><?php echo $_POST['description'] ?? ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Featured Image</label>
                <input type="file" name="featured_image" class="form-input" accept="image/*">
                <small>Recommended size: 1200x600px</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Event</button>
                <a href="events.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
        
        <?php endif; ?>
    </div>
</div>

<style>
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .form-container {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-layout {
        margin-top: 30px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.1);
    }

    .form-input small {
        display: block;
        margin-top: 5px;
        color: #999;
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        display: inline-block;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-secondary {
        background: #ddd;
        color: #333;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<?php include('footer.php'); ?>
