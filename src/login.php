<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Start session
session_start();

// If this is running in the Docker container...
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

// Get user input from the form
$username = mysqli_real_escape_string($conn, $_POST["username"]);
$password = $_POST["password"];

// Validation
if (empty($username) || empty($password)) {
    redirect("login_failed.html");
}

// Check user credentials in bar manager database
$checkBarManagerQuery = $conn->prepare("SELECT * FROM managerprofile WHERE username = ?");
$checkBarManagerQuery->bind_param("s", $username);
$checkBarManagerQuery->execute();
$checkBarManagerResult = $checkBarManagerQuery->get_result();

if ($checkBarManagerResult->num_rows > 0) {
    $row = $checkBarManagerResult->fetch_assoc();
    $manager_id = $row["managerid"]; // Get managerid associated with the bar manager to pass along to barProfiles.html

    // Retrieve the hashed password from the database
    $hashed_password_from_db = $row["password"];

    // Verify the password
    if (password_verify($password, $hashed_password_from_db)) {
        $_SESSION["managerid"] = $manager_id;
        // Redirect to barProfilesLoader.php with managerid included in the URL
        header("Location: barProfilesLoader.php?managerid=" . $manager_id);
        exit(); // Ensure that script execution stops after redirection
    }
}

// If user is not found in managerprofile table, check regular user credentials
$checkRegularUserQuery = $conn->prepare("SELECT * FROM users WHERE username = ?");
$checkRegularUserQuery->bind_param("s", $username);
$checkRegularUserQuery->execute();
$checkRegularUserResult = $checkRegularUserQuery->get_result();

if ($checkRegularUserResult->num_rows > 0) {
    $row = $checkRegularUserResult->fetch_assoc();

    if (password_verify($password, $row["password"])) {
        $_SESSION["username"] = $username;
        redirect("userProfilesLoader.php");
    }
}

// If user is not found in any database, redirect to login failed page
redirect("login_failed.html");

// Close the database connections
$checkBarManagerQuery->close();
$checkRegularUserQuery->close();
$conn->close();

// Function to redirect to a specific page immediately
function redirect($url)
{
    header("Location: $url");
    exit();
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

    <div class="register">
        <h1>Login</h1>
        <form action="/login.php" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username" id="username" required>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" id="password" required>
            <input type="submit" value="Login">
        </form>
    </div>

    <script>
        const toggleButton = document.querySelector('.toggle a');
        const menu = document.querySelector('.menu');

        toggleButton.addEventListener('click', () => {
            menu.classList.toggle('active');
        });
    </script>

</body>

</html>