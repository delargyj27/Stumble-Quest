<?php
// bars.php - PHP code for retrieving data from the database

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
$sql = "SELECT barname, baraddress, description, menuurl FROM bars;";

// Execute the query
$result = $conn->query($sql);

// Check if there are rows in the result set
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Bar Name</th><th>Bar Address</th><th>Description</th><th>Menu URL</th></tr>";

// Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['barname'] . "</td><td>" . $row['baraddress'] . "</td><td>" . $row['description'] . "</td><td>" . $row['menuurl'] . "</td></tr>";
    }

    echo "</table>";
} else {
    echo "0 results";
}

// Close the connection
$conn->close();
?>
