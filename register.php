<?php

$username = $_POST["username"] ?? '';
$email = $_POST["email"] ?? '';
$password = $_POST["password"] ?? '';
$user_type = $_POST["user_type"] ?? '';


if (empty($username) || empty($email) || empty($password)) {
    die("Please fill in all required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}


$password_hash = password_hash($password, PASSWORD_DEFAULT);


require_once "database.php";


$stmt = $mysqli->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $password_hash, $user_type);

if (!$stmt->execute()) {
    die("Error: " . $stmt->error);
}

echo "Registration successful.";

$stmt->close();
$mysqli->close();
?>
