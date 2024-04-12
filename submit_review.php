<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "sql105.infinityfree.com";
$username = "if0_36069118";
$password = "44WqSXc31wzj7";
$dbname = "if0_36069118_dbsquest";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['barid'], $_POST['food'], $_POST['services'], $_POST['drinks'], $_POST['vibe'], $_POST['cleanliness'])) {
    $barId = $_POST['barid'];
    $food = $_POST['food'];
    $services = $_POST['services'];
    $drinks = $_POST['drinks'];
    $vibe = $_POST['vibe'];
    $cleanliness = $_POST['cleanliness'];

    // Prepare statement
    $stmt = $conn->prepare("UPDATE bars SET food=?, service=?, drinks=?, vibe=?, cleanliness=? WHERE barid=?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("iiiiii", $food, $services, $drinks, $vibe, $cleanliness, $barId);

    // Execute statement
    if ($stmt->execute()) {
        // Redirect back to bars.html
        header("Location: bars.html");
        exit; // Make sure to exit after redirecting
    } else {
        echo "Error updating review: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    echo "Review details are incomplete.";
}

// Close connection
$conn->close();
?>