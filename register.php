<?php
header("Content-Type: text/html");
// Database connection
$servername = "stumblequest.clu0m60664ab.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "dqPQpd4T2IOzHCjj6dUO";
$database = "SQuest";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind the statement to prevent SQL injection
    if ($user_type == "Regular User") {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password);
    }
    else{
        $stmt = $conn->prepare("INSERT INTO managerprofile (username, password) VALUES (?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password);
    }

    if ($stmt->execute()) {
        echo "Registration successful";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

