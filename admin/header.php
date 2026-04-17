<?php
// Header Template - Include navbar and styling
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'School Management System'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <h2>📚 School Management System</h2>
            </div>
            
            <ul class="navbar-menu">
                <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                
                <!-- Students Menu (Admin, Teachers, NTS, Students can view) -->
                <?php if (hasAccess('students')): ?>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle">Students ▼</a>
                    <div class="dropdown-menu">
                        <a href="students.php">All Students</a>
                        <?php if (hasAccess('students', 'add')): ?>
                            <a href="students_add.php">Add Student</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>
                
                <!-- Teachers Menu (Admin, NTS can view) -->
                <?php if (hasAccess('teachers')): ?>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle">Teachers ▼</a>
                    <div class="dropdown-menu">
                        <a href="teachers.php">All Teachers</a>
                        <?php if (hasAccess('teachers', 'add')): ?>
                            <a href="teachers_add.php">Add Teacher</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>
                
                <!-- Classes Menu (Admin, Teachers, NTS, Students can view) -->
                <?php if (hasAccess('classes')): ?>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle">Classes ▼</a>
                    <div class="dropdown-menu">
                        <a href="classes.php">All Classes</a>
                        <?php if (hasAccess('classes', 'add')): ?>
                            <a href="classes_add.php">Add Class</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>
                
                <!-- Users Menu (Admin only) -->
                <?php if (hasAccess('users')): ?>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle">👥 Users ▼</a>
                    <div class="dropdown-menu">
                        <a href="users.php">All Users</a>
                        <?php if (hasAccess('users', 'add')): ?>
                            <a href="users_add.php">Add User</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>
                
                <!-- Attendance Menu (Admin, Teachers, NTS, Students can view) -->
                <?php if (hasAccess('attendance')): ?>
                <li><a href="attendance.php" class="nav-link">Attendance</a></li>
                <?php endif; ?>
                
                <!-- Exams Menu (Admin, Teachers, Students can view) -->
                <?php if (hasAccess('exams')): ?>
                <li><a href="exams.php" class="nav-link">Exams</a></li>
                <?php endif; ?>
                
                <!-- Fees Menu (Admin, NTS, Students can view) -->
                <?php if (hasAccess('fees')): ?>
                <li><a href="fees.php" class="nav-link">Fees</a></li>
                <?php endif; ?>
                
                <!-- Content Management Menu (Admin only) -->
                <?php if (hasAccess('content')): ?>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle">📝 Content ▼</a>
                    <div class="dropdown-menu">
                        <a href="content.php">All Content</a>
                        <?php if (hasAccess('content', 'add')): ?>
                            <a href="content_add.php">Add Content</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>
                
                <!-- Gallery Menu (Admin only) -->
                <?php if (hasAccess('gallery')): ?>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle">🖼️ Gallery ▼</a>
                    <div class="dropdown-menu">
                        <a href="gallery.php">View Gallery</a>
                        <?php if (hasAccess('gallery', 'add')): ?>
                            <a href="gallery_add.php">Add Images</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>
                
                <!-- Events Menu (Admin only) -->
                <?php if (hasAccess('events')): ?>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle">📅 Events ▼</a>
                    <div class="dropdown-menu">
                        <a href="events.php">All Events</a>
                        <?php if (hasAccess('events', 'add')): ?>
                            <a href="events_add.php">Add Event</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>

                <!-- Chatbot Menu (Admin, NTS can manage) -->
                <?php if (hasAccess('chatbot')): ?>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle">🤖 Chatbot ▼</a>
                    <div class="dropdown-menu">
                        <a href="chatbot.php">FAQs</a>
                        <?php if (hasAccess('chatbot', 'add')): ?>
                            <a href="chatbot_add.php">Add FAQ</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>
                
                <!-- User Profile Dropdown -->
                <li class="float-right dropdown">
                    <a href="#" class="nav-link dropdown-toggle">👤 <?php echo htmlspecialchars($_SESSION['admin_name']); ?> (<?php echo getRoleDisplayName(); ?>) ▼</a>
                    <div class="dropdown-menu">
                        <a href="profile.php">My Profile</a>
                        <a href="settings.php">Settings</a>
                        <a href="logout.php" style="color: #e74c3c;">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
