<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Connect to MySQL database
$servername = "sql105.infinityfree.com";
$username = "if0_36069118";
$password = "44WqSXc31wzj7";
$dbname = "if0_36069118_dbsquest";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

// Function to retrieve bar information
function getBarInfo($manager_id, $conn) {
    // fetch the bar info based on manager_id
    $stmt = $conn->prepare(
        "SELECT barname, baraddress, description, menuurl, outdoor_seating, wheelchair_accessible, live_music, food, service, vibe, drinks, cleanliness, safety 
        FROM bars 
        WHERE managerid = ?");
   
    $stmt->bind_param("i", $manager_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query was successful
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row; // Return the bar information as an associative array
    } else {
        return null;
    }
}
// delete when done just for debugging
$bar_info = getBarInfo($manager_id, $conn);
var_dump($bar_info);
manager_id = null;

// Check if the managerid is provided in the URL parameters
if (isset($_GET["managerid"]) && !empty($_GET["managerid"])) {
    $manager_id = $_GET["managerid"];

    // Print out the managerid for debugging
    echo "Manager ID: " . $manager_id;

    // Verify that $manager_id is a valid integer
    if (!is_numeric($manager_id)) {
        // If $manager_id is not a valid integer, log an error and return an error message
        error_log("Invalid manager ID provided: " . $manager_id);
        header('Content-Type: application/json');
        echo json_encode(["error" => "Invalid manager ID provided"]);
        exit; // Stop script execution
    }

    // Print out the managerid for debugging
    echo "Manager ID: " . $manager_id;

    // Retrieve bar information
    $bar_info = getBarInfo($manager_id, $conn);

    // Log or print the JSON data for debugging
    if ($bar_info) {
        // Log the JSON data to the PHP error log
        error_log(json_encode($bar_info));
        
        // Return the bar information as JSON
        header('Content-Type: application/json');
        echo json_encode($bar_info);
    } else {
        // If no bar information was found, log and return an empty JSON object or an error message
        error_log("Bar not found for manager ID: " . $manager_id);
        header('Content-Type: application/json');
        echo json_encode(["error" => "Bar not found"]);
    }
} else {
    // If managerid is not provided in the URL parameters, log and return an error message
    error_log("Manager ID not provided in URL parameters");
    header('Content-Type: application/json');
    echo json_encode(["error" => "Manager ID not provided"]);
}

// Close connection
$conn->close();
?>
