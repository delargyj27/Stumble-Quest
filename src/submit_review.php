<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SERVER["REMOTE_ADDR"] == "172.18.0.1") {
    $database = "if0_36069118_dbsquest";
    // This is the host name of the MariaDB container when running locally
    $servername = "db";
    $db_username = 'jkmapdev1';
    $db_password = 'proximity';
} else {
    // Database connection details
    $servername = "sql105.infinityfree.com";
    $db_username = "if0_36069118";
    $db_password = "44WqSXc31wzj7";
    $database = "if0_36069118_dbsquest";
}

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update review if POST data is received
    if (isset($_POST['food'], $_POST['services'], $_POST['drinks'], $_POST['vibe'], $_POST['cleanliness'], $_POST['barid'])) {
        $food = $_POST['food'];
        $services = $_POST['services'];
        $drinks = $_POST['drinks'];
        $vibe = $_POST['vibe'];
        $cleanliness = $_POST['cleanliness'];
        $barid = $_POST['barid'];

        // Calculate overall rating
        $rating = calculateOverallRating($food, $services, $drinks, $vibe, $cleanliness);

        // Prepare statement to update review, increment counter, and set rating
        $stmt = $conn->prepare("UPDATE bars SET food=?, service=?, counter=counter+1, drinks=?, vibe=?, cleanliness=?, rating=? WHERE barid=?");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("iiiiidi", $food, $services, $drinks, $vibe, $cleanliness, $rating, $barid);

        // Execute statement
        if ($stmt->execute()) {
            // Redirect back to bars.php after successful update
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
} else {
    // Fetch data for manager if GET data is received
    if (isset($_GET['managerid'])) {
        $manager_id = $_GET['managerid'];

        // Prepare statement to fetch bar data for manager
        $stmt = $conn->prepare("SELECT barname, menuurl, description, baraddress, outdoor_seating, wheelchair_accessible, counter, food, service, vibe, drinks, cleanliness, safety, barid FROM bars WHERE managerid = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("i", $manager_id);

        // Execute statement
        if ($stmt->execute()) {
            // Bind result variables
            $stmt->bind_result($name, $menu, $description, $address, $outdoor_seating, $wheelchair_accessible, $counter, $food, $service, $vibe, $drinks, $cleanliness, $safety, $barid);

            // Fetch data
            $stmt->fetch();

            // Calculate the ratings
            $food_rating = calculateRating($food, $counter);
            $service_rating = calculateRating($service, $counter);
            $vibe_rating = calculateRating($vibe, $counter);
            $drinks_rating = calculateRating($drinks, $counter);
            $cleanliness_rating = calculateRating($cleanliness, $counter);
            $safety_rating = calculateRating($safety, $counter);

            // Function to calculate the rating
            function calculateRating($value, $counter): int
            {
                if ($counter != 0) {
                    $value = (float)$value;
                    $counter = (int)$counter;
                    $rating = round($value / $counter);
                    return $rating;
                } else {
                    return 0;
                }
            }
        } else {
            echo "Error fetching bar data: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Manager ID is missing.";
    }
}

// Function to calculate overall rating
function calculateOverallRating($food, $services, $drinks, $vibe, $cleanliness): float
{
    // You can adjust the formula for calculating the overall rating as per your requirements
    $overallRating = ($food + $services + $drinks + $vibe + $cleanliness) / 5;
    return $overallRating;
}

// Close connection
$conn->close();
