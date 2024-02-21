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

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, user_type) VALUES ('$username', '$hashed_password', '$user_type')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
