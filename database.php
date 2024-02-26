<?php
// Database configuration
$host = "stumblequest.clu0m60664ab.us-east-1.rds.amazonaws.com:3306";
$dbname = "SQuest"; 
$username = "admin"; 
$password = "dqPQpd4TIOzHCjj6dUO";

// Create a connection to the database
$mysqli = new mysqli($host, $username, $password, $dbname, 3306);

// Check if the connection was successful
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

// Return the database connection object
return $mysqli;
?>
