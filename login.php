<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
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
$username = mysqli_real_escape_string($conn, $_POST["username"]);
$password = $_POST["password"];

// Validation
if (empty($username) || empty($password)) {
    redirect("login_failed.html");
}

// Check user credentials in bar manager database
$checkBarManagerQuery = $conn->prepare("SELECT * FROM managerprofile WHERE username = ?");
$checkBarManagerQuery->bind_param("s", $username);
$checkBarManagerQuery->execute();
$checkBarManagerResult = $checkBarManagerQuery->get_result();

if ($checkBarManagerResult->num_rows > 0) {
    $row = $checkBarManagerResult->fetch_assoc();
    $manager_id = $row["managerid"]; // Get managerid associated with the bar manager to pass along to barProfiles.html

    // Retrieve the hashed password from the database
    $hashed_password_from_db = $row["password"];

    // Verify the password
    if (password_verify($password, $hashed_password_from_db)) {
        $_SESSION["managerid"] = $manager_id;
        // Redirect to barProfilesLoader.php with managerid included in the URL
        header("Location: barProfilesLoader.php?managerid=" . $manager_id);
        exit(); // Ensure that script execution stops after redirection
    }
}

// If user is not found in managerprofile table, check regular user credentials
$checkRegularUserQuery = $conn->prepare("SELECT * FROM users WHERE username = ?");
$checkRegularUserQuery->bind_param("s", $username);
$checkRegularUserQuery->execute();
$checkRegularUserResult = $checkRegularUserQuery->get_result();

if ($checkRegularUserResult->num_rows > 0) {
    $row = $checkRegularUserResult->fetch_assoc();

    if (password_verify($password, $row["password"])) {
        $_SESSION["username"] = $username;
        redirect("index.html");
    }
}

// If user is not found in any database, redirect to login failed page
redirect("login_failed.html");

// Close the database connections
$checkBarManagerQuery->close();
$checkRegularUserQuery->close();
$conn->close();

// Function to redirect to a specific page immediately
function redirect($url) {
    header("Location: $url");
    exit();
}
?>

