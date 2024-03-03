<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "sql105.infinityfree.com";
$username = "if0_36069118";
$password = "44WqSXc31wzj7";
$database = "if0_36069118_dbsquest";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from the form
$username = mysqli_real_escape_string($conn, $_POST['username']);
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Password hashing
$userType = $_POST['userType'];

// Check if the username is already taken
$checkQuery = $conn->prepare("SELECT * FROM users WHERE username = ?");
$checkQuery->bind_param("s", $username);
$checkQuery->execute();
$checkResult = $checkQuery->get_result();

if ($checkResult->num_rows > 0) {
    die("Username is already taken. Please choose another one.");
}

$checkQuery->close();

// Insert user into table based on user type
if ($userType === 'regular') {
    $insertQuery = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
} elseif ($userType === 'manager') {
    $insertQuery = $conn->prepare("INSERT INTO managerprofile (username, password) VALUES (?, ?)");
} else {
    die("Invalid user type");
}

$insertQuery->bind_param("ss", $username, $hashedPassword);

if ($insertQuery->execute()) {
    echo "User registered successfully";
} else {
    echo "Error: User registration failed";
    error_log("Error: " . $insertQuery->error);
}

$insertQuery->close();
$conn->close();
?>

</body>
</html>
