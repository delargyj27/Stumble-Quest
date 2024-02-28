<?php
// Database connection
$servername = "stumblequest.clu0m60664ab.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "dqPQpd4T2IOzHCjj6dUO";
$database = "SQuest";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the database!";
}
?>
