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
$sql = "SELECT barname, barid
FROM bars 
WHERE managerid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$stmt->bind_result($name, $barid);
$stmt->fetch();

// Close statement
$stmt->close();

// Query to fetch the top 8 picture URLs
$sql = "SELECT photourl 
FROM barphotos 
where barid = ?
ORDER BY barid";
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



$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Stumble Quest</title>
    <link rel="stylesheet" type="text/css" href="gallery.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
</head>
<body>
<div class="top-bar">
    <a href="tel:(631)-000-0000"><ion-icon name="call-outline"></ion-icon> <span>Click To Call Our Team Now!</span></a>

    <ul>
        <li><a href=""><ion-icon name="logo-instagram"></ion-icon></a></li>
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
        <li><a href="register.html">Register</a></li>
        <li><a href="login.html">Login</a></li>
        <li><a href="map.html">Map</a></li>
        <li><a href="bars.html">Bars</a></li>
        <li><a href="crawl.html">Crawl</a></li>
        <li><a href="events.html">Calendar</a></li>
        <li><a href="aboutUs.html">FAQ</a></li>
    </ul>
</nav>

<!-- Main content container -->
<div class="main-content">
    <!-- Bar name -->
    <h1 class="bar_name"><?php echo $name; ?></h1>

    <div class="container">


    <!-- Pictures -->
<div class="pictures-box">
    <div class="thumbnails">
        <!-- Loop through the picture URLs and create thumbnail elements -->
        <?php 
        if (!is_null($picture_urls)) {
            foreach ($picture_urls as $url): ?>
                <div class="thumbnail">
                    <img src="<?php echo $url; ?>" alt="Bar Picture">
                </div>
            <?php endforeach;
        } else {
            echo 'No images found.';
        }
        ?>
    </div>
</div>
</div>
</div>
</body>
</html>

