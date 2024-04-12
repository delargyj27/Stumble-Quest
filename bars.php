<?php
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

// Check if filter parameters are set
$filterSql = "";
$conditions = [];
if (isset($_GET['wheelchair'])) {
    $wheelchairFilter = $_GET['wheelchair'] === 'Yes' ? 'Yes' : null;
    $conditions[] = "(wheelchair_accessible = '$wheelchairFilter' OR wheelchair_accessible IS NULL)";
}
if (isset($_GET['outdoorSeating'])) {
    $outdoorSeatingFilter = $_GET['outdoorSeating'] === 'Yes' ? 'Yes' : null;
    $conditions[] = "(outdoor_seating = '$outdoorSeatingFilter' OR outdoor_seating IS NULL)";
}
if (isset($_GET['liveMusic'])) {
    $liveMusicFilter = $_GET['liveMusic'] === 'Yes' ? 'Yes' : null;
    $conditions[] = "(live_music = '$liveMusicFilter' OR live_music IS NULL)";
}

// Combine all filter conditions using AND operator
if (!empty($conditions)) {
    $filterSql = " AND " . implode(" AND ", $conditions);
}

// Fetch data from the "bars" table with applied filters
$sql = "SELECT * FROM bars WHERE 1" . $filterSql;
$result = $conn->query($sql);

// Check if result is valid
if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Generate HTML for bar profiles
$html = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $barId = $row['barid'];
        $barName = $row["barname"];
        $description = $row["description"];
        $menuUrl = $row["menuurl"];
        $address = $row["baraddress"];

        // Generate HTML for each bar profile box
        $html .= "<div class='bar-profile'>
            <div class='bar-image'>
                <h2>$barName</h2>
            </div>
            <div class='bar-info'>
                <p>Bar Name: $barName</p>
                <p>Description: $description</p>";

        // Add menu URL if available
        if (!empty($menuUrl)) {
            $html .= "<p><a href='$menuUrl'>Menu</a></p>";
        }

        $html .= "<p>Address: $address</p>
                <form class='rating-form' action='submit_review.php' method='post'>
                    <label for='food'>Food:</label>
                    <input type='number' id='food' class='rating' name='food' min='0' max='5' step='0.1'>
                    <label for='services'>Service:</label>
                    <input type='number' id='services' class='rating' name='services' min='0' max='5' step='0.1'>
                    <label for='drinks'>Drinks:</label>
                    <input type='number' id='drinks' class='rating' name='drinks' min='0' max='5' step='0.1'>
                    <label for='vibe'>Vibe:</label>
                    <input type='number' id='vibe' class='rating' name='vibe' min='0' max='5' step='0.1'>
                    <label for='cleanliness'>Cleanliness:</label>
                    <input type='number' id='cleanliness' class='rating' name='cleanliness' min='0' max='5' step='0.1'>
                    <input type='hidden' name='barid' value='$barId'>
                    <button type='submit'>Submit</button>
                </form>
            </div>
        </div>";
    }
} else {
    $html = "No bars found.";
}

// Close connection
$conn->close();

// Output HTML
echo $html;
?>
