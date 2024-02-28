<?php

$username = $_POST["username"] ?? '';
$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';
$user_type = $_POST["user_type"] ?? '';

// Validation
if (empty($username) || empty($email) || empty($password)) {
    die("Please fill in all required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

// Database connection
require_once "database.php";

// Check if username already exists
$stmt = $mysqli->prepare("SELECT username FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("Username already exists. Please choose a different one.");
}

$stmt->close();

// Check if email already exists
$stmt = $mysqli->prepare("SELECT email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("Email already exists. Please use a different one.");
}

$stmt->close();

// Prepare SQL statement
$stmt = $mysqli->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)");

// Bind parameters
$stmt->bind_param("ssss", $username, $email, $password_hash, $user_type);

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Execute query
if (!$stmt->execute()) {
    die("Error: " . $stmt->error);
}

echo "Registration successful.";

// Close statement and database connection
$stmt->close();
$mysqli->close();
?>


