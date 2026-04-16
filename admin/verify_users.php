<?php
/**
 * User Management System - Setup Verification
 * Check if all components are properly installed and configured
 */

include('db_config.php');

$verification_results = array(
    'database' => false,
    'users_table' => false,
    'files' => array(),
    'permissions' => array(),
    'sample_users' => 0
);

// 1. Check Database Connection
$db_check = $conn->ping();
$verification_results['database'] = $db_check;

// 2. Check Users Table
$table_check = $conn->query("SHOW TABLES LIKE 'users'");
$verification_results['users_table'] = $table_check->num_rows > 0;

// 3. Check Required Files
$required_files = array(
    'admin/users.php',
    'admin/users_add.php',
    'admin/users_edit.php',
    'admin/users_functions.php',
    'admin/header.php',
    'admin/footer.php',
    'admin/session.php',
    'admin/dashboard.php',
    'db_config.php',
    'database.sql',
    'assets/css/style.css',
    'assets/css/responsive.css'
);

foreach ($required_files as $file) {
    $verification_results['files'][$file] = file_exists($file);
}

// 4. Check File Permissions (writable directories)
$dirs_to_check = array(
    'admin/',
    'assets/',
    'assets/css/',
    'assets/js/'
);

foreach ($dirs_to_check as $dir) {
    $verification_results['permissions'][$dir] = is_writable($dir);
}

// 5. Count Sample Users
if ($verification_results['users_table']) {
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $row = $result->fetch_assoc();
    $verification_results['sample_users'] = $row['count'];
}

// Display Results
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System - Setup Verification</title>
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
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 900px;
            width: 100%;
        }

        h1 {
            color: #667eea;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .check-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 5px;
            background: #f5f5f5;
        }

        .check-status {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 18px;
        }

        .status-success {
            background: #27ae60;
        }

        .status-error {
            background: #e74c3c;
        }

        .check-label {
            flex: 1;
            color: #333;
        }

        .status-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .summary {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #bdc3c7;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #555;
            font-weight: 600;
        }

        .summary-value {
            color: #667eea;
            font-weight: 600;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background: #f5f5f5;
            font-weight: 600;
            color: #333;
        }

        .table tbody tr:hover {
            background: #f9f9f9;
        }

        .overall-status {
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .overall-status.ready {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .overall-status.not-ready {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }

            .check-item {
                flex-wrap: wrap;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 User Management System - Setup Verification</h1>
        <p class="subtitle">Checking system configuration and components</p>

        <?php
        $all_checks_passed = true;

        if ($verification_results['database']) {
            echo '<div class="alert alert-success">✅ Database connection successful</div>';
        } else {
            echo '<div class="alert alert-error">❌ Database connection failed</div>';
            $all_checks_passed = false;
        }
        ?>

        <!-- Database Section -->
        <div class="section">
            <h2>📊 Database</h2>
            
            <div class="check-item">
                <div class="check-status <?php echo $verification_results['database'] ? 'status-success' : 'status-error'; ?>">
                    <?php echo $verification_results['database'] ? '✓' : '✕'; ?>
                </div>
                <div>
                    <div class="check-label">Database Connection</div>
                    <div class="status-text">
                        <?php 
                        if ($verification_results['database']) {
                            echo '✅ Connected to: ' . DB_NAME;
                        } else {
                            echo '❌ Connection failed. Check db_config.php settings.';
                            $all_checks_passed = false;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="check-item">
                <div class="check-status <?php echo $verification_results['users_table'] ? 'status-success' : 'status-error'; ?>">
                    <?php echo $verification_results['users_table'] ? '✓' : '✕'; ?>
                </div>
                <div>
                    <div class="check-label">Users Table</div>
                    <div class="status-text">
                        <?php 
                        if ($verification_results['users_table']) {
                            echo '✅ Users table exists with ' . $verification_results['sample_users'] . ' user(s)';
                        } else {
                            echo '❌ Users table not found. Run database.sql to create it.';
                            $all_checks_passed = false;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Files Section -->
        <div class="section">
            <h2>📁 Required Files</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($verification_results['files'] as $file => $exists) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($file) . '</td>';
                        if ($exists) {
                            echo '<td><span style="color: #27ae60; font-weight: bold;">✓ Exists</span></td>';
                        } else {
                            echo '<td><span style="color: #e74c3c; font-weight: bold;">✕ Missing</span></td>';
                            $all_checks_passed = false;
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Permissions Section -->
        <div class="section">
            <h2>🔐 Directory Permissions</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th>Directory</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($verification_results['permissions'] as $dir => $writable) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($dir) . '</td>';
                        if ($writable) {
                            echo '<td><span style="color: #27ae60; font-weight: bold;">✓ Writable</span></td>';
                        } else {
                            echo '<td><span style="color: #e74c3c; font-weight: bold;">✕ Not Writable</span></td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="section">
            <h2>📋 Summary</h2>
            
            <div class="summary">
                <div class="summary-item">
                    <span class="summary-label">Database Status:</span>
                    <span class="summary-value"><?php echo $verification_results['database'] ? '✓ Ready' : '✕ Error'; ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Users Table:</span>
                    <span class="summary-value"><?php echo $verification_results['users_table'] ? '✓ Ready' : '✕ Missing'; ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Files Present:</span>
                    <span class="summary-value">
                        <?php 
                        $files_count = count(array_filter($verification_results['files']));
                        echo $files_count . '/' . count($verification_results['files']);
                        ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Users in System:</span>
                    <span class="summary-value"><?php echo $verification_results['sample_users']; ?></span>
                </div>
            </div>
        </div>

        <!-- Overall Status -->
        <?php
        if ($all_checks_passed) {
            echo '<div class="overall-status ready">✓ System is READY! You can start using User Management.</div>';
        } else {
            echo '<div class="overall-status not-ready">✕ System requires attention. Please fix the issues above.</div>';
        }
        ?>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="../admin/users.php" class="btn btn-primary">Go to Users Management →</a>
            <a href="../admin/dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
