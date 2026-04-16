<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

include('../db_config.php');
include('rbac.php');

// Update last login time
$user_id = $_SESSION['admin_id'];
$conn->query("UPDATE users SET last_login = NOW() WHERE id = $user_id");
?>
