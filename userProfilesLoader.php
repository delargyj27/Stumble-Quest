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

// Retrieve userid from the session
$user_id = isset($_SESSION["userid"]) ? $_SESSION["userid"] : null;
$sql = "SELECT username FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();

// Set session variable with current username
$_SESSION["username"] = $user_name;

$stmt->close();

// Update username if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $new_username = $_POST["username"];
    $sql_update = "UPDATE users SET username = ? WHERE userid = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $new_username, $user_id);
    $stmt_update->execute();
    $stmt_update->close();

    // Update session variable with new username
    $_SESSION["username"] = $new_username;

    // Redirect to current page to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stumble Quest</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
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

    <div class="main-content">
        <!-- Bar name -->
        <h1 class="user_name"><?php echo $user_name !== null ? htmlspecialchars($user_name) : ''; ?></h1>

        <div class="container">
            <!-- Bar Information -->
            <div class="User-info">
                <h2>Information</h2>
                <form method="post" action="">
                    <div class="info-item">
                        <label for="username">User Name:</label>
                        <input type="text" id="username" name="username" value="<?php echo $user_name !== null ? htmlspecialchars($user_name) : ''; ?>">
                    </div>
                    <div class="info-item">
                        <input type="submit" name="submit" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

