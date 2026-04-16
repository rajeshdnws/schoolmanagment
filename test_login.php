<?php
/**
 * Login System Verification Tool
 * Check if login system is properly configured to use the users table
 */

include('db_config.php');

$tests = array(
    'database_connection' => false,
    'users_table_exists' => false,
    'admin_user_exists' => false,
    'password_verification' => false,
    'error_messages' => array()
);

// 1. Test Database Connection
try {
    if ($conn->ping()) {
        $tests['database_connection'] = true;
    } else {
        $tests['error_messages'][] = 'Database connection failed';
    }
} catch (Exception $e) {
    $tests['error_messages'][] = 'Database error: ' . $e->getMessage();
}

// 2. Test Users Table Exists
$table_check = $conn->query("SHOW TABLES LIKE 'users'");
if ($table_check && $table_check->num_rows > 0) {
    $tests['users_table_exists'] = true;
} else {
    $tests['error_messages'][] = 'Users table not found. Run database.sql to create it.';
}

// 3. Test Admin User Exists
$admin_check = $conn->query("SELECT id, username, email, first_name, last_name, password FROM users WHERE username = 'admin_user'");
if ($admin_check && $admin_check->num_rows > 0) {
    $tests['admin_user_exists'] = true;
    $admin_user = $admin_check->fetch_assoc();
} else {
    $tests['error_messages'][] = 'Default admin_user not found. Database.sql may not have been executed.';
}

// 4. Test Password Verification
// The hash in database.sql is from password_hash('admin123', PASSWORD_DEFAULT)
if (isset($admin_user)) {
    // Try to verify with a known password
    $test_password = 'admin123';
    if (password_verify($test_password, $admin_user['password'])) {
        $tests['password_verification'] = true;
    } else {
        // Try to create a test hash to verify password_hash function works
        $test_hash = password_hash($test_password, PASSWORD_DEFAULT);
        if (password_verify($test_password, $test_hash)) {
            $tests['error_messages'][] = 'Password hashing works, but the stored password is incorrect.';
        } else {
            $tests['error_messages'][] = 'Password verification function is not working properly.';
        }
    }
}

// 5. Display all users in database
$all_users = $conn->query("SELECT id, username, email, first_name, last_name, role, status FROM users LIMIT 10");
$users_list = array();
if ($all_users && $all_users->num_rows > 0) {
    while ($row = $all_users->fetch_assoc()) {
        $users_list[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .test-section {
            margin-bottom: 30px;
        }

        .test-section h2 {
            color: #667eea;
            font-size: 18px;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .test-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .test-status {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            margin-right: 15px;
            font-size: 20px;
        }

        .status-pass {
            background-color: #27ae60;
        }

        .status-fail {
            background-color: #e74c3c;
        }

        .test-content {
            flex: 1;
        }

        .test-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .test-detail {
            font-size: 13px;
            color: #666;
        }

        .error-messages {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .error-messages h3 {
            margin-bottom: 10px;
        }

        .error-messages li {
            margin-left: 20px;
            margin-bottom: 5px;
        }

        .success-banner {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .warning-banner {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #ffeaa7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: #333;
        }

        table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            justify-content: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background-color: #5568d3;
        }

        .btn-secondary {
            background-color: #e74c3c;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #c0392b;
        }

        .btn-info {
            background-color: #3498db;
            color: white;
        }

        .btn-info:hover {
            background-color: #2980b9;
        }

        code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }

        .summary {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 4px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Login System Verification</h1>
        <p class="subtitle">Checking if login system is properly configured to use the users table</p>

        <?php
        // Display error messages if any
        if (!empty($tests['error_messages'])) {
            echo '<div class="error-messages">';
            echo '<h3>⚠️ Issues Found:</h3>';
            echo '<ul>';
            foreach ($tests['error_messages'] as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }

        // Display success if all tests pass
        if ($tests['database_connection'] && $tests['users_table_exists'] && $tests['admin_user_exists']) {
            echo '<div class="success-banner">✅ Database setup looks good! Your system is ready.</div>';
        }
        ?>

        <!-- Database Tests -->
        <div class="test-section">
            <h2>📊 Database Setup Tests</h2>

            <div class="test-item">
                <div class="test-status <?php echo $tests['database_connection'] ? 'status-pass' : 'status-fail'; ?>">
                    <?php echo $tests['database_connection'] ? '✓' : '✕'; ?>
                </div>
                <div class="test-content">
                    <div class="test-label">Database Connection</div>
                    <div class="test-detail">
                        <?php 
                        if ($tests['database_connection']) {
                            echo 'Connected to: <code>' . DB_NAME . '</code>';
                        } else {
                            echo 'Failed to connect. Check db_config.php settings.';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="test-item">
                <div class="test-status <?php echo $tests['users_table_exists'] ? 'status-pass' : 'status-fail'; ?>">
                    <?php echo $tests['users_table_exists'] ? '✓' : '✕'; ?>
                </div>
                <div class="test-content">
                    <div class="test-label">Users Table Exists</div>
                    <div class="test-detail">
                        <?php 
                        if ($tests['users_table_exists']) {
                            echo 'Users table is ready in the database.';
                        } else {
                            echo 'Users table not found. Run database.sql to create it.';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="test-item">
                <div class="test-status <?php echo $tests['admin_user_exists'] ? 'status-pass' : 'status-fail'; ?>">
                    <?php echo $tests['admin_user_exists'] ? '✓' : '✕'; ?>
                </div>
                <div class="test-content">
                    <div class="test-label">Admin User Exists</div>
                    <div class="test-detail">
                        <?php 
                        if ($tests['admin_user_exists']) {
                            echo 'Username: <code>admin_user</code> | Email: <code>' . htmlspecialchars($admin_user['email']) . '</code>';
                        } else {
                            echo 'Admin user not found. Check database.sql was executed correctly.';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="test-item">
                <div class="test-status <?php echo $tests['password_verification'] ? 'status-pass' : 'status-fail'; ?>">
                    <?php echo $tests['password_verification'] ? '✓' : '✕'; ?>
                </div>
                <div class="test-content">
                    <div class="test-label">Password Verification</div>
                    <div class="test-detail">
                        <?php 
                        if ($tests['password_verification']) {
                            echo 'Password verification works correctly. Default password should work.';
                        } else {
                            echo 'Password may need to be reset. Use the admin panel to create a new password.';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin User Details -->
        <?php if ($tests['admin_user_exists']): ?>
        <div class="test-section">
            <h2>👤 Admin User Details</h2>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>ID</td>
                    <td><?php echo htmlspecialchars($admin_user['id']); ?></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><code><?php echo htmlspecialchars($admin_user['username']); ?></code></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo htmlspecialchars($admin_user['email']); ?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><?php echo htmlspecialchars($admin_user['first_name'] . ' ' . $admin_user['last_name']); ?></td>
                </tr>
                <tr>
                    <td>Role</td>
                    <td><span style="background: #667eea; color: white; padding: 4px 8px; border-radius: 3px;"><?php echo htmlspecialchars($admin_user['role']); ?></span></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- All Users List -->
        <?php if (!empty($users_list)): ?>
        <div class="test-section">
            <h2>📋 All Users in System</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users_list as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><code><?php echo htmlspecialchars($user['username']); ?></code></td>
                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo htmlspecialchars($user['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Next Steps -->
        <div class="test-section">
            <h2>🚀 Next Steps</h2>

            <?php if ($tests['database_connection'] && $tests['users_table_exists'] && $tests['admin_user_exists']): ?>
                <div class="success-banner">
                    <strong>✅ Everything is set up!</strong> You can now:
                    <ul style="margin-left: 20px; margin-top: 10px;">
                        <li>Test login at <a href="index.php" style="color: inherit; text-decoration: underline;"><code>http://dps.local/index.php</code></a></li>
                        <li>Use username: <code>admin_user</code></li>
                        <li>Access the admin panel after login</li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="warning-banner">
                    <strong>⚠️ Setup incomplete.</strong> Please:
                    <ul style="margin-left: 20px; margin-top: 10px;">
                        <li>Run <code>database.sql</code> in phpMyAdmin</li>
                        <li>Verify the database name is <code>school_managements</code></li>
                        <li>Refresh this page after running the SQL</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="actions">
            <?php if ($tests['admin_user_exists']): ?>
                <a href="index.php" class="btn btn-primary">Go to Login →</a>
            <?php endif; ?>
            <a href="admin/verify_users.php" class="btn btn-info">Check User Management System</a>
            <a href="javascript:location.reload()" class="btn btn-secondary">Refresh This Page</a>
        </div>
    </div>
</body>
</html>
