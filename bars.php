<?php
// bars.php - PHP code for retrieving data from the database

// Include necessary files (e.g., database connection)
require_once 'db_connection.php';

// Fetch data from the database
$result = $conn->query("SELECT barname, baraddress, description, menuurl FROM bars;");

// Check for query execution success
if ($result) {
    $barsData = $result->fetch_all(MYSQLI_ASSOC);
    $result->free_result();
} else {
    $barsData = []; // Empty array if there's an error
}

// Include the HTML template
include 'bars.html';
?>
