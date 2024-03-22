<?php
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

// Function to fetch bar photo from the database based on bar ID
function getBarPhoto($barID, $conn) {
    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT photo FROM barphotos WHERE barid = ?");
    $stmt->bind_param("i", $barID);

    // Execute the query
    $stmt->execute();

    // Bind the result variable
    $stmt->bind_result($photo);

    // Fetch the result
    $stmt->fetch();

    // Close statement
    $stmt->close();

    // Return the photo
    return $photo;
}

// Get the bar ID from the URL parameter
$barID = $_GET['barID']; // Assuming it's passed via URL

// Get the bar photo using the function
$barPhoto = getBarPhoto($barID, $conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Profile</title>
    <!-- Add your CSS styles -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="bar-profile">
    <!-- Bar Picture -->
    <img src="<?php echo $barPhoto; ?>" alt="Bar Image">

    <!-- Container for Bar Information -->
    <div class="bar-info">
        <h2>Bar Information</h2>
        <!-- Your other HTML content for bar information goes here -->
    </div>
</div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
