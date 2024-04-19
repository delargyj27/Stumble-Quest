<?php
// Start session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to MySQL database
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

// Retrieve managerid from the session
$manager_id = isset($_SESSION["managerid"]) ? $_SESSION["managerid"] : null;
$sql = "SELECT barname, menuurl, description, baraddress, outdoor_seating, wheelchair_accessible, counter, live_music, food, service, vibe, drinks, cleanliness, safety, barid
FROM bars 
WHERE managerid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$stmt->bind_result($name, $menu, $description, $address, $outdoor_seating, $wheelchair_accessible, $counter, $live_music, $food, $service, $vibe, $drinks, $cleanliness, $safety, $barid);
$stmt->fetch();

// Calculate the ratings
$live_music_rating = calculateRating($live_music, $counter);
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

// Close statement
$stmt->close();

// Query to fetch the top 4 picture URLs
$sql = "SELECT photourl 
FROM barphotos 
where barid = ? 
ORDER BY barid LIMIT 4";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $barid);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any results
if ($result->num_rows > 0) {
    // Initialize an empty array to store picture URLs
    $picture_urls = array();

    // Fetch picture URLs and store them in the array
    while ($row = $result->fetch_assoc()) {
        $picture_urls[] = $row['photourl'];
    }
}

// Query to fetch the events

$sql = "SELECT eventname, eventdate 
        FROM events  
        WHERE barid = ?
        ORDER BY eventdate";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $barid);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any results
if ($result->num_rows > 0) {
    // Initialize an empty array to store events
    $events = array();

    // Fetch events and store them in the array
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
// Close statement
$result->close();


$manager_id = isset($_SESSION["managerid"]) ? $_SESSION["managerid"] : null;
$sql = "SELECT barid
FROM bars 
WHERE managerid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$stmt->bind_result($barid);
$stmt->fetch();
$stmt->close(); // Close the statement here

// Check if the "Update" button is clicked
if (isset($_POST['update-button'])) {

    // Retrieve form data
    $barname = isset($_POST["barname"]) ? $_POST["barname"] : null;
    $description = isset($_POST["description"]) ? $_POST["description"] : null;
    $baraddress = isset($_POST["location"]) ? $_POST["location"] : null;
    $menuurl = isset($_POST["menuurl"]) ? $_POST["menuurl"] : null;
    $outdoor_seating = isset($_POST["outdoor_seating"]) ? $_POST["outdoor_seating"] : null;
    $wheelchair_accessible = isset($_POST["wheelchair_accessible"]) ? $_POST["wheelchair_accessible"] : null;
    $live_music = isset($_POST["live_music"]) ? $_POST["live_music"] : null;

    // Prepare and execute the update query
    $sql = "UPDATE bars 
            SET barname=?, description=?, baraddress=?, menuurl=?, outdoor_seating=?, wheelchair_accessible=?, live_music=?
            WHERE barid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $barname, $description, $baraddress, $menuurl, $outdoor_seating, $wheelchair_accessible, $live_music, $barid);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: {$_SERVER['PHP_SELF']}?managerid={$manager_id}");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?managerid={$manager_id}&error=update_failed");
        exit();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Stumble Quest</title>
    <link rel="stylesheet" type="text/css" href="barProfiles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
</head>
<body>
<div class="top-bar">
    <a href="tel:(631)-000-0000"><ion-icon name="call-outline"></ion-icon> <span>Click To Call Our Team Now!</span></a>
    <ul>
        <li><a href="login.html">Login</a></li>
        <li><a href="register.html">Register</a></li>
    </ul>
</div>

<nav>
    <div class="logo">
        <a href="#"><img src="images/pint.png" alt="logo">Stumble Quest</a>
    </div>
    <div class="toggle">
        <a href="#"><ion-icon name="menu-outline"></ion-icon></a>
    </div>
    <ul class="menu">
        <li><a href="index.html">Home</a></li>
        <li><a href="map.html">Map</a></li>
        <li><a href="bar.html">Bars</a></li>
        <li><a href="crawl.html">Crawl</a></li>
        <li><a href="events.html">Calander</a></li>
        <li><a href="aboutUs.html">FAQ</a></li>
    </ul>
</nav>

<!-- Main content container -->
<div class="main-content">
    <!-- Bar name -->
    <h1 class="bar_name"><?php echo $name; ?></h1>

    <div class="container">


        <!-- Bar Information -->
        <div class="bar-info">
            <h2>Information</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="info-item">
                    <label for="barname">Bar Name:</label>
                    <input type="text" id="barname" name="barname" value="<?php echo $name; ?>">
                </div>
                <div class="info-item">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="5"><?php echo $description; ?></textarea>
                </div>
                <div class="info-item">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo $address; ?>">
                </div>
                <div class="info-item">
                    <label for="menuurl">Menu URL:</label>
                    <input type="text" id="menuurl" name="menuurl" value="<?php echo $menu; ?>">
                </div>
                <div class="info-item">
                    <label>Outdoor Seating:</label>
                    <div class="radio-buttons">
                        <input type="radio" id="outdoor_seating_yes" name="outdoor_seating" value="Yes" <?php if ($outdoor_seating === 'Yes') echo 'checked'; ?>>
                        <label for="outdoor_seating_yes">Yes</label>
                        <input type="radio" id="outdoor_seating_no" name="outdoor_seating" value="No" <?php if ($outdoor_seating === 'No') echo 'checked'; ?>>
                        <label for="outdoor_seating_no">No</label>
                    </div>
                </div>

                <div class="info-item">
                    <label>Wheelchair Accessible:</label>
                    <div class="radio-buttons">
                        <input type="radio" id="wheelchair_accessible_yes" name="wheelchair_accessible" value="Yes" <?php if ($wheelchair_accessible === 'Yes') echo 'checked'; ?>>
                        <label for="wheelchair_accessible_yes">Yes</label>
                        <input type="radio" id="wheelchair_accessible_no" name="wheelchair_accessible" value="No" <?php if ($wheelchair_accessible === 'No') echo 'checked'; ?>>
                        <label for="wheelchair_accessible_no">No</label>
                    </div>
                </div>

                <div class="info-item">
                    <label>Live Music:</label>
                    <div class="radio-buttons">
                        <input type="radio" id="live_music_yes" name="live_music" value="Yes" <?php if ($live_music === 'Yes') echo 'checked'; ?>>
                        <label for="live_music_yes">Yes</label>
                        <input type="radio" id="live_music_no" name="live_music" value="No" <?php if ($live_music === 'No') echo 'checked'; ?>>
                        <label for="live_music_no">No</label>
                    </div>
                </div>
                <button type="submit" name="update-button" class="update-button">Update</button>
            </form>

        </div>

        <!-- Ratings -->
        <div class="ratings-box">
            <h2>Ratings</h2>
            <div class="rating-item">
                <label for="food">Food:</label>
                <div class="rating-value" id="food"><?php echo $food_rating; ?></div>
            </div>
            <div class="rating-item">
                <label for="service">Service:</label>
                <div class="rating-value" id="service"><?php echo $service_rating; ?></div>
            </div>
            <div class="rating-item">
                <label for="vibe">Vibe:</label>
                <div class="rating-value" id="vibe"><?php echo $vibe_rating; ?></div>
            </div>
            <div class="rating-item">
                <label for="drinks">Drinks:</label>
                <div class="rating-value" id="drinks"><?php echo $drinks_rating; ?></div>
            </div>
            <div class="rating-item">
                <label for="cleanliness">Cleanliness:</label>
                <div class="rating-value" id="cleanliness"><?php echo $cleanliness_rating; ?></div>
            </div>
            <div class="rating-item">
                <label for="safety">Safety:</label>
                <div class="rating-value" id="safety"><?php echo $service_rating; ?></div>
            </div>
        </div>

        <!-- Events -->
        <div class="events-container">
            <div class="events-header">
                <h2>Events</h2>
                <a href="barEventsPage.php" class="edit-button">Event Details</a>
            </div>
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-item">
                        <div class="event-name"><?php echo $event['eventname']; ?></div>
                        <div class="event-date"><?php echo $event['eventdate']; ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No events</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Pictures -->
    <div class="pictures-box">
        <button onclick="window.location.href='gallery.php'"  class="pictures-button">Bar Pictures</button>
        <div class="thumbnails">
            <!-- Loop through the picture URLs and create thumbnail elements -->
            <?php foreach ($picture_urls as $url): ?>
                <div class="thumbnail">
                    <img src="<?php echo $url; ?>" alt="Bar Picture">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>



</body>
</html>