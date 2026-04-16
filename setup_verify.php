<?php
/**
 * School Management System - Setup Verification
 * This file verifies that the system is properly set up
 */

// Check if db_config exists
if (!file_exists('db_config.php')) {
    die('ERROR: db_config.php not found. Please create it first.');
}

include('db_config.php');

$errors = [];
$warnings = [];
$success = [];

// Check PHP version
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    $errors[] = "PHP version 5.4.0 or higher required. Current version: " . PHP_VERSION;
} else {
    $success[] = "PHP version: " . PHP_VERSION;
}

// Check required PHP extensions
$required_extensions = ['mysqli', 'json', 'filter'];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "Required PHP extension '$ext' not installed";
    } else {
        $success[] = "PHP extension '$ext' is installed";
    }
}

// Check database connection
if (!isset($conn) || $conn->connect_error) {
    $errors[] = "Database connection failed: " . (isset($conn) ? $conn->connect_error : 'Unknown error');
} else {
    $success[] = "Database connected successfully";
    
    // Check if tables exist
    $tables_to_check = ['admin', 'students', 'teachers', 'classes', 'exams', 'fees'];
    foreach ($tables_to_check as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result && $result->num_rows > 0) {
            $success[] = "Table '$table' exists";
        } else {
            $warnings[] = "Table '$table' not found. Please import database.sql";
        }
    }
}

// Check file and directory permissions
$dirs_to_check = ['admin', 'assets', 'assets/css', 'assets/js'];
foreach ($dirs_to_check as $dir) {
    if (!is_dir($dir)) {
        $warnings[] = "Directory '$dir' not found";
    } else if (!is_readable($dir)) {
        $errors[] = "Directory '$dir' is not readable";
    } else {
        $success[] = "Directory '$dir' exists and is readable";
    }
}

// Check files
$files_to_check = ['index.php', 'db_config.php', 'database.sql', 'README.md'];
foreach ($files_to_check as $file) {
    if (!file_exists($file)) {
        $warnings[] = "File '$file' not found";
    } else {
        $success[] = "File '$file' exists";
    }
}

// Display results
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System - Setup Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 100%;
            padding: 40px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .status-section {
            margin-bottom: 30px;
        }
        
        .status-section h2 {
            color: #667eea;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .message {
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 5px;
            border-left: 4px solid;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }
        
        .warning {
            background-color: #fff3cd;
            color: #856404;
            border-left-color: #ffc107;
        }
        
        .status-icon {
            margin-right: 10px;
            font-weight: bold;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
        }
        
        a {
            flex: 1;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background-color: #ecf0f1;
            color: #333;
            border: 1px solid #bdc3c7;
        }
        
        .btn-secondary:hover {
            background-color: #bdc3c7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 School Management System - Setup Verification</h1>
        
        <?php if (!empty($errors)): ?>
        <div class="status-section">
            <h2>❌ Errors (<?php echo count($errors); ?>)</h2>
            <?php foreach ($errors as $error): ?>
                <div class="message error">
                    <span class="status-icon">✗</span><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($warnings)): ?>
        <div class="status-section">
            <h2>⚠️ Warnings (<?php echo count($warnings); ?>)</h2>
            <?php foreach ($warnings as $warning): ?>
                <div class="message warning">
                    <span class="status-icon">!</span><?php echo htmlspecialchars($warning); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
        <div class="status-section">
            <h2>✅ Success (<?php echo count($success); ?>)</h2>
            <?php foreach ($success as $msg): ?>
                <div class="message success">
                    <span class="status-icon">✓</span><?php echo htmlspecialchars($msg); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div class="action-buttons">
            <a href="index.php" class="btn-primary">Go to Login</a>
            <a href="README.md" class="btn-secondary">Read Docs</a>
        </div>
    </div>
</body>
</html>
