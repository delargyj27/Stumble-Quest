<?php
// Start the session to access session variables
session_start();

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

// Apply Filters if Set
$filterSql = "";

// Array to store individual filter conditions
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

// Error handling
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Generate HTML for each bar profile box
$html = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $barId = $row['barid']; // Updated to use 'barid'
        $barName = $row["barname"];
        $description = $row["description"];
        $menuUrl = $row["menuurl"];
        $address = $row["baraddress"]; // Corrected column name
        $rating = $row["rating"]; // Assuming rating is fetched from database
        
        // Generate HTML for rating stars
        $starsHtml = "";
        if ($rating !== null) {
            // Calculate the number of full and empty mugs based on the rating
            $fullMugs = floor($rating);
            $emptyMugs = 5 - $fullMugs;
            
            // Display full mugs
            $starsHtml .= str_repeat("<img src='images/pint.png' alt='Full Mug'>", $fullMugs);
            
            // Display empty mugs
            $starsHtml .= str_repeat("<img src='images/selected_beer.png' alt='Empty Mug'>", $emptyMugs);
        } else {
            // If rating is null
            $starsHtml = "No ratings yet";
        }
        
        // Generate the HTML for each bar profile box with the dynamic link
        $html .= "<div class='bar-profile'>
            <div class='bar-image'>
                <h2>$barName</h2>
            </div>
            <div class='bar-info'>";
        
        // Add bar name
        $html .= "<p>Bar Name: $barName</p>";

        // Add description
        $html .= "<p>Description: $description</p>";

        // Check if menu URL exists
        if (!empty($menuUrl)) {
            $html .= "<p><a href='$menuUrl'>Menu</a></p>";
        }

        // Add address
        $html .= "<p>Address: $address</p>";

        // Add rating form
        $html .= "<form class='rating-form' action='submit_review.php' method='post'>"; // Updated action attribute
        $html .= "<label for='rating_$barId'>Rate this bar:</label>";
        $html .= "<input type='number' id='rating_$barId' class='bar-rating' name='rating' min='0' max='5' step='0.1'>";
        $html .= "<input type='hidden' name='barid' value='$barId'>"; // Updated name attribute
        
        // Add textarea for review text
        $html .= "<label for='reviewtext_$barId'>Write your review:</label>";
        $html .= "<textarea id='reviewtext_$barId' class='bar-review-text' name='reviewtext'></textarea>";

        $html .= "<button type='submit'>Submit</button>";
        $html .= "</form>";

        // Add rating stars
        $html .= "<div class='rating'>$starsHtml</div>";

        // Add "View More Info" link
        $html .= "<p><a href='bar_details.php?bar_id={$row['barid']}'>View More Info</a></p>"; // Updated link
        
        $html .= "</div>
        </div>";
    }
} else {
    $html = "No bars found.";
}

// Close MySQL connection
$conn->close();

// Return the generated HTML
echo $html;
?>