<?php
/**
 * Role-Based Access Control (RBAC) System
 * Implements proper authorization for different user roles
 */

// Define Role Permissions
$ROLE_PERMISSIONS = array(
    'admin' => array(
        'dashboard' => true,
        'students' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'teachers' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'classes' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'attendance' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'exams' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'marks' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'fees' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'users' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'content' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'gallery' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'events' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true),
        'profile' => true,
        'settings' => true,
    ),
    
    'teacher' => array(
        'dashboard' => true,
        'students' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'teachers' => array('view' => false, 'add' => false, 'edit' => false, 'delete' => false),
        'classes' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'attendance' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'exams' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'marks' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'fees' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'users' => array('view' => false, 'add' => false, 'edit' => false, 'delete' => false),
        'content' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'gallery' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'events' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'profile' => true,
        'settings' => true,
    ),
    
    'student' => array(
        'dashboard' => true,
        'students' => array('view' => false, 'add' => false, 'edit' => false, 'delete' => false),
        'teachers' => array('view' => false, 'add' => false, 'edit' => false, 'delete' => false),
        'classes' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'attendance' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'exams' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'marks' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'fees' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'users' => array('view' => false, 'add' => false, 'edit' => false, 'delete' => false),
        'content' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'gallery' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'events' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'profile' => true,
        'settings' => true,
    ),
    
    'nts' => array(
        'dashboard' => true,
        'students' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'teachers' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'classes' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'attendance' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'exams' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
        'content' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'gallery' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'events' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'marks' => array('view' => false, 'add' => false, 'edit' => false, 'delete' => false),
        'fees' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
        'users' => array('view' => false, 'add' => false, 'edit' => false, 'delete' => false),
        'profile' => true,
        'settings' => true,
    ),
);

// Get current user role
function getCurrentUserRole() {
    return isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'student';
}

// Check if user has access to a page
function hasAccess($module, $action = 'view') {
    global $ROLE_PERMISSIONS;
    
    $role = getCurrentUserRole();
    
    if (!isset($ROLE_PERMISSIONS[$role])) {
        return false;
    }
    
    $permissions = $ROLE_PERMISSIONS[$role];
    
    // Check module exists
    if (!isset($permissions[$module])) {
        return false;
    }
    
    // If permission is boolean
    if (is_bool($permissions[$module])) {
        return $permissions[$module];
    }
    
    // If permission is array (has actions)
    if (is_array($permissions[$module])) {
        return isset($permissions[$module][$action]) ? $permissions[$module][$action] : false;
    }
    
    return false;
}

// Require access to module/action
function requireAccess($module, $action = 'view', $redirect = true) {
    if (!hasAccess($module, $action)) {
        if ($redirect) {
            header("Location: dashboard.php?access=denied");
            exit();
        }
        return false;
    }
    return true;
}

// Check if user is admin
function isAdmin() {
    return getCurrentUserRole() === 'admin';
}

// Check if user is teacher
function isTeacher() {
    return getCurrentUserRole() === 'teacher';
}

// Check if user is student
function isStudent() {
    return getCurrentUserRole() === 'student';
}

// Check if user is staff
function isStaff() {
    return getCurrentUserRole() === 'nts';
}

// Get role display name
function getRoleDisplayName($role = null) {
    if ($role === null) {
        $role = getCurrentUserRole();
    }
    
    $roles = array(
        'admin' => 'Administrator',
        'teacher' => 'Teacher',
        'student' => 'Student',
        'nts' => 'Non-Teaching Staff'
    );
    
    return isset($roles[$role]) ? $roles[$role] : 'User';
}

// Get role badge color
function getRoleColor($role = null) {
    if ($role === null) {
        $role = getCurrentUserRole();
    }
    
    $colors = array(
        'admin' => '#e74c3c',
        'teacher' => '#3498db',
        'student' => '#27ae60',
        'nts' => '#f39c12'
    );
    
    return isset($colors[$role]) ? $colors[$role] : '#95a5a6';
}

// Get available modules for current user
function getAvailableModules() {
    global $ROLE_PERMISSIONS;
    $role = getCurrentUserRole();
    
    if (!isset($ROLE_PERMISSIONS[$role])) {
        return array();
    }
    
    $modules = array();
    $permissions = $ROLE_PERMISSIONS[$role];
    
    foreach ($permissions as $module => $access) {
        if ($access === true || (is_array($access) && isset($access['view']) && $access['view'])) {
            $modules[] = $module;
        }
    }
    
    return $modules;
}

// Log access attempt
function logAccessAttempt($module, $action, $allowed = true) {
    global $conn;
    
    $user_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
    $role = getCurrentUserRole();
    $timestamp = date('Y-m-d H:i:s');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    if ($user_id) {
        // Optional: Store access logs in database (requires access_logs table)
        // $query = "INSERT INTO access_logs (user_id, role, module, action, allowed, ip_address, timestamp) 
        //          VALUES ($user_id, '$role', '$module', '$action', " . ($allowed ? 1 : 0) . ", '$ip_address', '$timestamp')";
        // $conn->query($query);
    }
}

// Show unauthorized message
function showUnauthorizedMessage() {
    echo '
    <div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 4px; margin: 20px; text-align: center;">
        <h3>⚠️ Access Denied</h3>
        <p>You do not have permission to access this resource.</p>
        <p style="margin-top: 10px;">
            <a href="dashboard.php" style="color: #721c24; text-decoration: underline;">Return to Dashboard</a>
        </p>
    </div>
    ';
    exit();
}

?>
