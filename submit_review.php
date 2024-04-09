<?php
session_start(); // Start the session to access session variables

// Check if the user is already logged in
if (!isset($_SESSION['userid'])) {
    // Redirect the user to the login page if they are not logged in
    header("Location: login.php");
    exit(); // Stop script execution after redirection
}

// Check if the logged-in user is user with ID 6
if ($_SESSION['userid'] != 6) {
    echo "Only user with ID 6 can submit reviews.";
    exit(); // Stop script execution
}

// Connect to the database
$servername = "sql105.infinityfree.com";
$username = "if0_36069118";
$password = "44WqSXc31wzj7";
$dbname = "if0_36069118_dbsquest";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the review text, rating, and bar ID are set
if (isset($_POST['reviewtext'], $_POST['food'], $_POST['services'], $_POST['drinks'], $_POST['vibe'], $_POST['cleanliness'], $_POST['barid'])) {
    // Sanitize inputs to prevent SQL injection
    $reviewText = mysqli_real_escape_string($conn, $_POST['reviewtext']);
    $food = mysqli_real_escape_string($conn, $_POST['food']);
    $services = mysqli_real_escape_string($conn, $_POST['services']);
    $drinks = mysqli_real_escape_string($conn, $_POST['drinks']);
    $vibe = mysqli_real_escape_string($conn, $_POST['vibe']);
    $cleanliness = mysqli_real_escape_string($conn, $_POST['cleanliness']);
    $barId = mysqli_real_escape_string($conn, $_POST['barid']);
    $userId = $_SESSION['userid']; // Retrieve user ID from session variable

    // Insert the review into the database
    $sql = "INSERT INTO userreviews (userid, barid, food, service, drinks, vibe, cleanliness, reviewtext) VALUES ($userId, $barId, $food, $services, $drinks, $vibe, $cleanliness, '$reviewText')";
    if ($conn->query($sql) === TRUE) {
        echo "Review submitted successfully!";
    } else {
        echo "Error inserting review: " . $conn->error;
    }

} else {
    echo "Review text, rating, or bar ID not set.";
}

// Close the database connection
$conn->close();
?>
