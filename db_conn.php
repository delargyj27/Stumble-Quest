<?php
// Database connection
$servername = "stumblequest.clu0m60664ab.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "dqPQpd4T2IOzHCjj6dUO";
$database = "SQuest";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
