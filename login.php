<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Database connection details
$servername = "sql105.infinityfree.com";
$username = "if0_36069118";
$password = "44WqSXc31wzj7";
$database = "if0_36069118_dbsquest";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from the form
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password']; // Password as plain text for now, will be hashed for actual use

// Validation
if (empty($username) || empty($password)) {
    die("Please enter both username and password.");
}

// Check user credentials
$checkQuery = $conn->prepare("SELECT * FROM users WHERE username = ?");
$checkQuery->bind_param("s", $username);
$checkQuery->execute();
$checkResult = $checkQuery->get_result();

if ($checkResult->num_rows > 0) {
    $row = $checkResult->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $_SESSION["username"] = $username;
        echo "Login successful";
        // Redirect to the main menu
        header("Location: index.html");
        exit(); // Ensure that no other code is executed after the header redirect
    } else {
        echo "Invalid username or password";
    }
} else {
    echo "Invalid username or password";
}

// Close the database connection
$checkQuery->close();
$conn->close();
?>
