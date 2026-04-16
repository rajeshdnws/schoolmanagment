<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'school_managements');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    // Database created or already exists
}

// Select database
$conn->select_db(DB_NAME);

// Set charset to utf8
$conn->set_charset("utf8");

// Function to execute queries safely
function executeQuery($query) {
    global $conn;
    $result = $conn->query($query);
    if ($conn->connect_errno) {
        die("Query Error: " . $conn->connect_error);
    }
    return $result;
}

// Function to get single row
function getRow($query) {
    $result = executeQuery($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

// Function to get all rows
function getAllRows($query) {
    $result = executeQuery($query);
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Function to insert data
function insertData($table, $data) {
    global $conn;
    $columns = implode(", ", array_keys($data));
    $values = implode("', '", array_values($data));
    $query = "INSERT INTO $table ($columns) VALUES ('$values')";
    return executeQuery($query);
}

// Function to update data
function updateData($table, $data, $where) {
    global $conn;
    $updateFields = [];
    foreach ($data as $key => $value) {
        $updateFields[] = "$key = '$value'";
    }
    $updateStr = implode(", ", $updateFields);
    $query = "UPDATE $table SET $updateStr WHERE $where";
    return executeQuery($query);
}

// Function to delete data
function deleteData($table, $where) {
    global $conn;
    $query = "DELETE FROM $table WHERE $where";
    return executeQuery($query);
}
?>
