<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Get user input from the form
$username = mysqli_real_escape_string($conn, $_POST['username']);
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Password hashing
$userType = $_POST['userType'];

// Check if the username is already taken
$checkQuery = $conn->prepare("SELECT * FROM users WHERE username = ?");
$checkQuery->bind_param("s", $username);
$checkQuery->execute();
$checkResult = $checkQuery->get_result();

if ($checkResult->num_rows > 0) {
    die("Username is already taken. Please choose another one.");
}

$checkQuery->close();

// Insert user into table based on user type
if ($userType === 'regular') {
    $insertQuery = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
} elseif ($userType === 'manager') {
    $insertQuery = $conn->prepare("INSERT INTO managerprofile (username, password) VALUES (?, ?)");
} else {
    die("Invalid user type");
}

$insertQuery->bind_param("ss", $username, $hashedPassword);

if ($insertQuery->execute()) {
    echo "User registered successfully";
} else {
    echo "Error: User registration failed";
    error_log("Error: " . $insertQuery->error);
}

$insertQuery->close();
$conn->close();
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
    <h1>Register for Stumble Quest</h1>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="userType">User Type:</label>
        <select id="userType" name="userType" required>
            <option value="regular">Regular User</option>
            <option value="manager">Bar Manager</option>
        </select>

        <button type="submit">Register</button>
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

