<?php
// Database connection
$servername = "sql105.infinityfree.com";
$username = "if0_36069118";
$password = "44WqSXc31wzj7";
$database = "if0_36069118_dbsquest";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch bar information
$sql = "SELECT barname FROM bars WHERE barid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_GET['bar_id']); // Assuming the bar_id is passed in the URL
$stmt->execute();
$stmt->bind_result($barName);
$stmt->fetch();
$stmt->close();

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Stumble Quest</title>
    <link rel="stylesheet" type="text/css" href="barProfiles.css">
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
    <h1 class="bar-name"><?php echo $barName; ?></h1>
</div>
</body>
</html>
