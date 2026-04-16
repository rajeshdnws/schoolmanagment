<?php

// Add User Function
function addUser($username, $password, $email, $first_name, $last_name, $phone, $dob, $gender, $address, $city, $state, $pincode, $role, $department, $qualification, $specialization, $experience_years, $joining_date) {
    global $conn;
    
    // Check if username already exists
    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        return array('success' => false, 'message' => 'Username already exists!');
    }
    
    // Check if email already exists
    $check_email = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check_email->num_rows > 0) {
        return array('success' => false, 'message' => 'Email already exists!');
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users 
            (username, password, email, first_name, last_name, phone, date_of_birth, gender, address, city, state, pincode, role, department, qualification, specialization, experience_years, joining_date, status) 
            VALUES 
            ('$username', '$hashed_password', '$email', '$first_name', '$last_name', '$phone', '$dob', '$gender', '$address', '$city', '$state', '$pincode', '$role', '$department', '$qualification', '$specialization', '$experience_years', '$joining_date', 'active')";
    
    if ($conn->query($sql)) {
        return array('success' => true, 'message' => 'User added successfully!', 'user_id' => $conn->insert_id);
    } else {
        return array('success' => false, 'message' => 'Error adding user: ' . $conn->error);
    }
}

// Get User by ID
function getUserById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

// Get All Users with filters
function getAllUsers($role = '', $status = '') {
    global $conn;
    
    $where = "1=1";
    if ($role) {
        $where .= " AND role = '$role'";
    }
    if ($status) {
        $where .= " AND status = '$status'";
    }
    
    $result = $conn->query("SELECT * FROM users WHERE $where ORDER BY created_at DESC");
    return $result;
}

// Update User
function updateUser($id, $username, $email, $first_name, $last_name, $phone, $dob, $gender, $address, $city, $state, $pincode, $role, $department, $qualification, $specialization, $experience_years, $joining_date, $status) {
    global $conn;
    
    // Check if username is being changed to an existing username
    $check = $conn->query("SELECT id FROM users WHERE username = '$username' AND id != $id");
    if ($check->num_rows > 0) {
        return array('success' => false, 'message' => 'Username already exists!');
    }
    
    // Check if email is being changed to an existing email
    $check_email = $conn->query("SELECT id FROM users WHERE email = '$email' AND id != $id");
    if ($check_email->num_rows > 0) {
        return array('success' => false, 'message' => 'Email already exists!');
    }
    
    $sql = "UPDATE users 
            SET username = '$username', 
                email = '$email', 
                first_name = '$first_name', 
                last_name = '$last_name', 
                phone = '$phone', 
                date_of_birth = '$dob', 
                gender = '$gender', 
                address = '$address', 
                city = '$city', 
                state = '$state', 
                pincode = '$pincode', 
                role = '$role', 
                department = '$department', 
                qualification = '$qualification', 
                specialization = '$specialization', 
                experience_years = '$experience_years', 
                joining_date = '$joining_date', 
                status = '$status',
                updated_at = NOW()
            WHERE id = $id";
    
    if ($conn->query($sql)) {
        return array('success' => true, 'message' => 'User updated successfully!');
    } else {
        return array('success' => false, 'message' => 'Error updating user: ' . $conn->error);
    }
}

// Update Password
function updateUserPassword($id, $new_password) {
    global $conn;
    
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password = '$hashed_password', updated_at = NOW() WHERE id = $id";
    
    if ($conn->query($sql)) {
        return array('success' => true, 'message' => 'Password updated successfully!');
    } else {
        return array('success' => false, 'message' => 'Error updating password: ' . $conn->error);
    }
}

// Delete User
function deleteUser($id) {
    global $conn;
    
    $sql = "DELETE FROM users WHERE id = $id";
    
    if ($conn->query($sql)) {
        return array('success' => true, 'message' => 'User deleted successfully!');
    } else {
        return array('success' => false, 'message' => 'Error deleting user: ' . $conn->error);
    }
}

// Get User Count by Role
function getUserCountByRole($role) {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = '$role'");
    $data = $result->fetch_assoc();
    return $data['count'];
}

// Get User Statistics
function getUserStatistics() {
    global $conn;
    
    $stats = array(
        'total_users' => getUserCountByRole('student') + getUserCountByRole('teacher') + getUserCountByRole('nts') + getUserCountByRole('admin'),
        'total_students' => getUserCountByRole('student'),
        'total_teachers' => getUserCountByRole('teacher'),
        'total_staff' => getUserCountByRole('nts'),
        'total_admins' => getUserCountByRole('admin'),
        'active_users' => $conn->query("SELECT COUNT(*) as count FROM users WHERE status = 'active'")->fetch_assoc()['count'],
        'inactive_users' => $conn->query("SELECT COUNT(*) as count FROM users WHERE status = 'inactive'")->fetch_assoc()['count']
    );
    
    return $stats;
}

// Generate Random Password
function generatePassword($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

// Validate User Input
function validateUserInput($username, $email, $first_name, $password = null, $is_update = false) {
    $errors = array();
    
    // Validate username
    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters';
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        $errors[] = 'Username can only contain letters, numbers, underscore and hyphen';
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    // Validate first name
    if (empty($first_name)) {
        $errors[] = 'First name is required';
    } elseif (strlen($first_name) < 2) {
        $errors[] = 'First name must be at least 2 characters';
    }
    
    // Validate password (only for new users)
    if (!$is_update && $password) {
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }
    } elseif ($is_update && $password) {
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }
    }
    
    return $errors;
}

?>
