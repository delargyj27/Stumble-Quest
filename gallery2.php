<?php
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

// Retrieve bar ID from URL
$bar_id = isset($_GET["barid"]) ? $_GET["barid"] : null;

// Fetch photos for the corresponding bar
$sql = "SELECT photourl 
FROM barphotos 
WHERE barid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bar_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch image URLs
$image_urls = array();
while ($row = $result->fetch_assoc()) {
    $image_urls[] = $row['photourl'];
}

// Close statement
$stmt->close();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <!-- Add your CSS styles here -->
    <link rel="stylesheet" type="text/css" href="gallery.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Gallery</h1>

    <div class="gallery">
        <!-- Display images -->
        <?php foreach ($picture_urls as $url): ?>
                <div class="thumbnail">
                    <img src="<?php echo $url; ?>" alt="Bar Picture">
                </div>
            <?php endforeach; ?>
    </div>
</body>
</html>
