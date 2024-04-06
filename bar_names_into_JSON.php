<?php
// bar_names_into_JSON.php - PHP code for retrieving only the names of bars and return a JSON

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

// SQL query data from the database
$sql = "SELECT barname FROM bars;";

// Execute the query
$result = $conn->query($sql);

// Check if there are rows in the result set
if ($result->num_rows > 0) {
    // Create an array to store the names
    $barNames = array();

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Add each bar name to the array
        $barNames[] = $row['barname'];
    }

    // Convert the names array to JSON
    echo json_encode($barNames);
} else {
    echo json_encode(array("message" => "No results"));
}

// Close the connection
$conn->close();
?>
