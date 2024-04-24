<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "sql105.infinityfree.com";
$username = "if0_36069118";
$password = "44WqSXc31wzj7";
$dbname = "if0_36069118_dbsquest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve userid from the session
$userId = isset($_SESSION["userid"]) ? $_SESSION["userid"] : null;

// Check if the "Update" button is clicked
if (isset($_POST['update-button'])) {

    // Retrieve form data
    $userName = isset($_POST["username"]) ? $_POST["username"] : null;
    $nickname = isset($_POST["nickname"]) ? $_POST["nickname"] : null;

    // Prepare and execute the update query
    $sql = "UPDATE users 
            SET username=?, nickname=?
            WHERE userid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $userName, $nickname, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userId}");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userId}&error=update_failed");
        exit();
    }
}

// Query crawl invites for the current user
$sql = "SELECT invite_id, status, sent_at FROM crawl_members WHERE userid = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($inviteId, $status, $sentAt);

// Store results in an array
$invites = [];
while ($stmt->fetch()) {
    $invite = [
        "inviteId" => $inviteId,
        "status" => $status,
        "sentAt" => $sentAt
    ];
    $invites[] = $invite;
}

$stmt->close();

// Handle accept/reject action
if (isset($_POST['action'])) {
    $inviteId = $_POST['invite_id'];
    $action = $_POST['action'];

    // Update status in the database
    $updateSql = "UPDATE crawl_members SET status = ? WHERE userid = ? AND invite_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sii", $action, $userId, $inviteId);
    $updateStmt->execute();
    $updateStmt->close();

    // Redirect back to the page after updating status
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

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
    <style>
        .main-content {
            display: flex;
            justify-content: space-between; /* To evenly distribute the containers */
            margin: 0 auto; /* Center the content horizontally */
            max-width: 1200px; /* Limit maximum width */
            margin-top: 40px;
        }

        .container {
            width: calc(33.33% - 10px); /* Adjust width to fit your layout */
            background-color: #fff;
            box-shadow: 0 0 9px 0 rgba(0, 0, 0, 0.3);
            padding: 10px 20px; /* Added padding */
        }

        .container h1 {
            text-align: center;
            color: #222;
            font-size: 24px;
            font-family: "Poppins", sans-serif;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }

        .container form {
            display: flex;
            flex-direction: column;
        }

        .container form label {
            margin-bottom: 5px;
        }

        .container form input[type="text"] {
            width: calc(100% - 30px);
            height: 50px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 0 15px;
        }

        .container form input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: aqua;
            border: 0;
            cursor: pointer;
            font-weight: bold;
            color: #fff;
            transition: background-color 0.2s;
        }

        .container form input[type="submit"]:hover {
            background-color: aquamarine;
            transition: background-color 0.2s;
        }

        .container button {
            width: 100%;
            padding: 15px;
            background-color: aqua;
            border: 0;
            cursor: pointer;
            font-weight: bold;
            color: #fff;
            margin-top: 10px;
            transition: background-color 0.2s;
        }

        .container button:hover {
            background-color: aquamarine;
            transition: background-color 0.2s;
        }
        .container .no-invites {
            margin-top: 20px; /* Adjust spacing */
            text-align: center; /* Center the message */
        }

    </style>
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
            <li><a href="index.php">Home</a></li>
            <li><a href="map.php">Map</a></li>
            <li><a href="bars.html">Bars</a></li>
            <li><a href="crawl.php">Crawl</a></li>
            <li><a href="events.php">Calendar</a></li>
            <li><a href="aboutUs.php">FAQ</a></li>
        </ul>
    </nav>

    <div class="main-content">
        <div class="container" id="userContainer">
            <h1>User Information</h1>

            <form method="post" action="">
                <div class="info-item">
                    <label for="username">User Name:</label>
                    <input type="text" id="username" name="username" value="">
                </div>
                <div class="info-item">
                    <label for="nickname">Nickname:</label>
                    <input type="text" id="nickname" name="nickname" value="">
                </div>
                <div class="info-item">
                    <input type="submit" name="update-button" value="Update">
                </div>
            </form>
            
            <!-- Radio buttons to change container background color -->
            <div>
                <p>Choose Background Color:</p>
                <input type="radio" id="lightRed" name="backgroundColor" value="#ffcccc">
                <label for="lightRed">Light Red</label><br>
                <input type="radio" id="lightBlue" name="backgroundColor" value="#add8e6">
                <label for="darkBlue">Light Blue</label><br>
                <input type="radio" id="armyGreen" name="backgroundColor" value="#4b5320">
                <label for="armyGreen">Army/Forest Green</label><br>
                <input type="radio" id="gold" name="backgroundColor" value="#ffd700">
                <label for="gold">Gold</label><br>
                <input type="radio" id="white" name="backgroundColor" value="#ffffff" checked>
                <label for="white">White</label><br>
            </div>
        </div>

        <!-- Duplicate the container for left and right side -->
        <!-- Left Container: Crawl Invites -->
        <div class="container" id="crawlInvitesContainer">
            <h1>Crawl Invites</h1>
            <?php if (!empty($invites)): ?>
                <ul>
                    <?php foreach ($invites as $invite): ?>
                        <li>
                            <span>Invited by User ID: <?php echo $invite['inviteId']; ?></span><br>
                            <span>Sent at: <?php echo $invite['sentAt']; ?></span><br>
                            <?php if ($invite['status'] === 'pending'): ?>
                                <form method="post" action="">
                                    <input type="hidden" name="invite_id" value="<?php echo $invite['inviteId']; ?>">
                                    <button type="submit" name="action" value="accepted">Accept</button>
                                    <button type="submit" name="action" value="rejected">Reject</button>
                                </form>
                            <?php else: ?>
                                <span>Status: <?php echo $invite['status']; ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="no-invites">
                    <p>No pending crawl invites</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="container" id="rightContainer">
            <h1>User Photos</h1>
            <!-- Add content for User Photos here -->
        </div>
    </div>

    <script>
        // JavaScript to change container background color based on radio button selection
        const radios = document.querySelectorAll('input[type="radio"]');
        const containers = document.querySelectorAll('.container');

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                containers.forEach(container => {
                    container.style.backgroundColor = this.value;
                });
            });
        });
    </script>
</body>
</html>
