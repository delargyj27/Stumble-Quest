<?php
// Start session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the username session variable is set
if (isset($_SESSION["username"])) {

// Username is set, you can use it for SQL queries or any other purposes
    $username = $_SESSION["username"];

// Connect to MySQL database
    $servername = "sql105.infinityfree.com";
    $db_username = "if0_36069118";
    $db_password = "44WqSXc31wzj7";
    $db_name = "if0_36069118_dbsquest";

// Create connection
    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {

        // SQL query using username to fetch the user ID
        $sql = "SELECT userid, IFNULL(nickname, '') as nickname
        FROM users
        WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // If user not found in users table, redirect
        if ($result->num_rows == 0) {
            header("Location: login.html");
            exit();
        }

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Store userid and nickname in variables
            $userid = $row['userid'];
            $nickname = $row['nickname'];
        }
        // Close statement
        $stmt->close();
    }
} else {
    // Handle the case where $_SESSION["username"] is not set
    header("Location: login.html");
    exit();
}

// Check if the nickname is set and set display name
$display_name = !empty($nickname) ? $nickname : $username;

// Array of quotes
$quotes = array(
    "My only source of exercise is a bar crawl.",
    "It is a 5 minute walk from my apartment to the bar
    a 25 minute walk from the bar to my apartment
    The difference is Staggering.",
    "I could barely walk last night I
    I was crawling all over the place.",
    "When you're drunk and barely able to stand
    Remember that it's a Pub Crawl not a a Pub Walk.",
    "Raise your hand if you're still a little drunk from last night.",
    "Are you drunk?    Yes[]    No[]
    
                                                                        X",
    "If you lose your friends
    convince a group of drunken strangers
    to continue the bar crawl.",
    "Life is too short for bad beer, so let's make every crawl count.",
    "I don't always drink on weekdays, but when I do, it's because there's a crawl involved.",
    "Bar crawls: the ultimate test of friendship and liver endurance.",
    "I'm not drunk, I'm just embracing the spirit of the crawl.",
    "My therapist told me to take things one bar at a time.",
    "Some people run marathons, I prefer to crawl from pub to pub.",
    "Why limit happy to just one hour when you can have a whole crawl?",
    "I don't need an excuse to crawl, but I'll take any reason to celebrate.",
    "Life's too short to drink cheap beer, so let's crawl and savor the good stuff.",
    "Friends don't let friends crawl alone.",
    "The best stories begin and end on a bar crawl.",
    "I'm not stumbling, I'm just practicing my bar crawl dance moves.",
    "They say laughter is the best medicine, but I'll take a crawl any day.",
    "I'm not lost, I'm just exploring the next bar on the crawl.",
    "Who needs a gym when you can get a workout from a good old-fashioned bar crawl?",
    "Pub crawls: where strangers become friends and friends become legends.",
    "A crawl a day keeps the doctor away... or at least makes you forget about the appointment.",
    "Drinking responsibly means knowing when to stop... and when to start the next crawl.",
    "I don't have a drinking problem, I have a bar crawl solution.",
    "The only bad crawl is the one you didn't join."
);

// Generate a random index
$randomIndex = array_rand($quotes);
// Select random quote
$randomQuote = $quotes[$randomIndex];



// Query to fetch the crawls for My Crawls
$existingCrawls = array();

$sql = "SELECT c.crawl_id, c.name, c.date, c.start_time, c.description, c.visibility, c.status
        FROM crawls c
        WHERE c.creator_id = ?
        ORDER BY date";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any results
if ($result->num_rows > 0) {
    // Fetch crawls and store them in the array
    while ($row = $result->fetch_assoc()) {

        // Add the row to the existingCrawls array
        $existingCrawls[] = $row;

        // Store the crawl_id in the crawl_ids array
        $crawl_ids[] = $row['crawl_id'];
    }
}
// Close statement
$result->close();

// Query for join a crawl list
$publicCrawls = array();

$sql = "SELECT c.crawl_id, c.name
        FROM crawls c
        LEFT JOIN crawl_members cm ON c.crawl_id = cm.crawl_id AND cm.userid = ?
        WHERE c.visibility = 'public' AND cm.userid IS NULL
        AND c.creator_id <> ?
        ORDER BY date";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userid, $userid);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any results
if ($result->num_rows > 0) {
    // Fetch crawls and store them in the array
    while ($row = $result->fetch_assoc()) {
        // Add the row to the availableCrawls array
        $publicCrawls[] = $row;
    }
}
// Close statement
$result->close();

// Check if the "Add Crawl" button is clicked
if (isset($_POST['add-crawl'])) {
    // Retrieve form data
    $crawlname = isset($_POST["crawlname"]) ? $_POST["crawlname"] : null;
    $description = isset($_POST["description"]) ? $_POST["description"] : null;
    $crawldate = isset($_POST["crawldate"]) ? $_POST["crawldate"] : null;
    $start_time = isset($_POST["start_time"]) ? $_POST["start_time"] : null;
    $visibility = isset($_POST["visibility"]) ? $_POST["visibility"] : 'public'; // Default visibility


    // Prepare and execute the insert query
    $sql = "INSERT INTO crawls (creator_id, name, date, start_time, description, visibility) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $userid, $crawlname, $crawldate, $start_time, $description, $visibility);
    $stmt->execute();

    // Check if the crawl was added successfully
    if ($stmt->affected_rows > 0) {
        // Get the crawl_id of the newly added crawl
        $crawl_id = $stmt->insert_id;

        // Redirect to crawl.html with crawl_id as a parameter
        header("Location: crawl.html?crawl_id={$crawl_id}");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userid}&error=crawl_add_failed");
        exit();
    }
}

// Check if the "Join Crawl" button is clicked
if (isset($_POST['join-crawl'])) {
    // Retrieve crawl_id and userid from the form
    $crawl_id = $_POST['crawl_id'];
    $userid = $_POST['userid'];

    // Insert into crawl_members table
    $sql = "INSERT INTO crawl_members (crawl_id, userid, status) VALUES (?, ?, 'accepted')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $crawl_id, $userid);

    // Execute the insert query
    if ($stmt->execute()) {
        // Redirect to the same page to avoid form resubmission
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userid}&success=crawl_updated");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userid}&success=crawl_update_failed");
        exit();
    }
}

// SQL query to populate list for invite list
$sql = "SELECT u.userid, COALESCE(u.nickname, u.username) AS display_name
        FROM users u
        LEFT JOIN crawl_members cm ON cm.userid = u.userid AND cm.crawl_id = ? AND cm.status = 'accepted'
        WHERE cm.userid IS NULL AND u.userid != ?
        ORDER BY display_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $crawl_id, $userid);
$stmt->execute();
$result = $stmt->get_result();

$non_accepted_users = array();

// Fetch results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $non_accepted_users[] = $row;
    }
}

//SQL query to populate crawl list for invites
$sql = "select crawl_id, name as crawl_name, date
        from crawls
        where creator_id = ?
        order by date";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$invite_user_crawls = array();
// Fetch results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $invite_user_crawls[] = $row;
    }
}


// Check if the "Invite" button is clicked
if (isset($_POST['invite-crawl'])) {
    $invite_user_id = $_POST['invite_user_id'];
    $crawl_id = $_POST['invite_crawl_id'];

    // Prepare and execute the insert query
    $sql = "INSERT INTO crawl_members (userid, crawl_id, status) VALUES (?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("ii", $invite_user_id, $crawl_id);
    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }

    if ($stmt->affected_rows > 0) {
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userid}&success=user_added");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userid}&success=failed");
        exit();
    }
}

// Check if the "Save Changes" button is clicked for editing a crawl
if (isset($_POST['edit-crawl'])) {
    // Retrieve form data
    $edit_crawl_id = isset($_POST["edit_crawl_id"]) ? $_POST["edit_crawl_id"] : null;
    $edit_crawlname = isset($_POST["edit_crawlname{$edit_crawl_id}"]) ? $_POST["edit_crawlname{$edit_crawl_id}"] : null;
    $edit_description = isset($_POST["edit_description{$edit_crawl_id}"]) ? $_POST["edit_description{$edit_crawl_id}"] : null;
    $edit_crawldate = isset($_POST["edit_crawldate{$edit_crawl_id}"]) ? $_POST["edit_crawldate{$edit_crawl_id}"] : null;
    $edit_start_time = isset($_POST["edit_start_time{$edit_crawl_id}"]) ? $_POST["edit_start_time{$edit_crawl_id}"] : null;
    $edit_visibility = isset($_POST["edit_visibility{$edit_crawl_id}"]) ? $_POST["edit_visibility{$edit_crawl_id}"] : null;
    $edit_status = isset($_POST["status{$edit_crawl_id}"]) ? $_POST["status{$edit_crawl_id}"] : null;

    // Prepare and execute the update query
    $sql = "UPDATE crawls
        SET name=?, date=?, start_time=?, description=?, visibility=?, status=?
        WHERE crawl_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $edit_crawlname, $edit_crawldate, $edit_start_time, $edit_description, $edit_visibility, $edit_status, $edit_crawl_id);
    $stmt->execute();
    // Check if the crawl was updated successfully
    if ($stmt->affected_rows > 0) {
        // Redirect to the same page to avoid form resubmission
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userid}&success=crawl_updated");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userid}&success=crawl_update_failed");
        exit();
    }
}


// Check if the "Delete" button is clicked for deleting an crawl
if (isset($_POST['delete-crawl'])) {
    // Retrieve the crawl ID to be deleted
    $delete_crawl_id = isset($_POST["edit_crawl_id"]) ? $_POST["edit_crawl_id"] : null;

    // Prepare and execute the delete query
    $sql_delete_crawl = "DELETE FROM crawls WHERE crawl_id = ?";
    $sql_delete_crawlbars = "DELETE FROM crawlbars WHERE crawl_id = ?";
    $stmt_delete_crawl = $conn->prepare($sql_delete_crawl);
    $stmt_delete_crawl->bind_param("i", $delete_crawl_id);
    $stmt_delete_crawl->execute();

    $stmt_delete_crawlbars = $conn->prepare($sql_delete_crawlbars);
    $stmt_delete_crawlbars->bind_param("i", $delete_crawl_id);
    $stmt_delete_crawlbars->execute();

    // Check if the crawl was deleted successfully
    if ($stmt_delete_crawl->affected_rows > 0) {
        // Redirect to the same page to avoid form resubmission
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userid}&success=crawl_updated");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?userid={$userid}&success=crawl_update_failed");
        exit();
    }
}

// Fetch crawls information from tables crawlbars and crawls join on the barid
$crawls = array();
$sql = "SELECT DISTINCT c.*
        FROM crawls c
        LEFT JOIN crawlbars cb ON cb.crawl_id = c.crawl_id
        WHERE c.visibility = 'public' AND c.date >= CURDATE()
        ORDER BY c.date;";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any results
if ($result->num_rows > 0) {
    // Initialize an empty array to store crawls
    $crawls = array();

    // Fetch crawls and store them in the array
    while ($row = $result->fetch_assoc()) {
        $crawls[] = $row;
    }
}




// Close statement
$result->close();
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Stumble Quest</title>
    <link rel="stylesheet" type="text/css" href="crawls.css">
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
        <li><a href="crawls.php">Crawl</a></li>
        <li><a href="crawls.html">Calander</a></li>
        <li><a href="aboutUs.html">FAQ</a></li>
    </ul>
</nav>

<!-- Main content container -->
<div class="main-content">
    <!-- Person name -->
    <h1 class="person_name"><?php echo $display_name; ?></h1>

    <!-- Crawls Section -->
    <div class="crawls-section">

        <!-- Form for adding a new crawl -->
        <div class="add-crawl-form" style="float: left; width: 80%;">
            <h2>Add New Crawl</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="crawlname">Crawl Name:</label>
                <input type="text" id="crawlname" name="crawlname" required>
                <label for="crawldate">Crawl Date:</label>
                <input type="date" id="crawldate" name="crawldate" required>
                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required>
                <label for="visibility">Visibility:</label>
                <select id="visibility" name="visibility">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select><br>
                <label for="description">Description:</label><br>

                <textarea id="description" name="description" rows="4" required></textarea>
                <button type="submit" name="add-crawl" style="margin-left: 30px;">Add Crawl</button>

                <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">

            </form>
        </div>

        <!-- Join A Crawl Section -->
        <div class="join-crawl-section" style="float: left; width: 15%;">
            <h2>Join A Crawl</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                <select name="crawl_id" required>
                    <option value="" selected disabled>Select a crawl to join</option>
                    <?php foreach ($publicCrawls as $crawl): ?>
                        <option value="<?php echo $crawl['crawl_id']; ?>">
                            <?php echo $crawl['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>
                <button type="submit" name="join-crawl" style="margin-top: 20px;">Join Crawl</button>
            </form>
        </div>

        <div style="clear: both;"></div> <!-- This clears the floats -->


        <!-- Existing crawls Container -->
        <h2 style="text-align: center; font-family: 'Gloria Hallelujah', cursive; font-size: 30px; background-color: #ffc107; padding: 10px;border: 2px solid black;"><?php echo nl2br($randomQuote); ?></h2>


        <div class="existing-crawls-container">

            <div class="my-crawls-column">
                <div class="crawls-header">
                    <h2>My Crawls:</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <button type="submit" id="inviteButton" name="invite-crawl" class="invite-button">Invite User</button>

                        <select id="userCrawlsDropdown" name="invite_crawl_id" required style="margin-left: 10px;">
                            <option value="" selected disabled>Select a crawl</option>
                            <?php foreach ($invite_user_crawls as $list_crawl): ?>
                                <option value="<?php echo htmlspecialchars($list_crawl['crawl_id']); ?>">
                                    <?php echo htmlspecialchars($list_crawl['crawl_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="inviteUserDropdown" name="invite_user_id" required style="margin-left: 10px;">
                            <option value="" selected disabled>Select a user</option>
                            <?php foreach ($non_accepted_users as $user): ?>
                                <option value="<?php echo htmlspecialchars($user['userid']); ?>">
                                    <?php echo htmlspecialchars($user['display_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <!-- Loop through existing crawls and display them in the form -->
                <?php foreach ($existingCrawls as $crawl): ?>
                    <div class="one-crawl-container">
                        <div class="crawl-container">
                            <div class="crawl-info">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="info-item">
                                        <label for="edit_crawlname<?php echo $crawl['crawl_id']; ?>">Crawl Name:</label>
                                        <input type="text" id="edit_crawlname<?php echo $crawl['crawl_id']; ?>" name="edit_crawlname<?php echo $crawl['crawl_id']; ?>" value="<?php echo $crawl['name']; ?>" required>
                                    </div>
                                    <div class="info-item">
                                        <label for="edit_description<?php echo $crawl['crawl_id']; ?>">Description:</label>
                                        <textarea id="edit_description<?php echo $crawl['crawl_id']; ?>" name="edit_description<?php echo $crawl['crawl_id']; ?>" rows="4" required><?php echo $crawl['description']; ?></textarea>
                                    </div>
                                    <div class="crawl-datetime">
                                        <div class="info-item">
                                            <label for="edit_crawldate<?php echo $crawl['crawl_id']; ?>">Crawl Date:</label>
                                            <input type="date" id="edit_crawldate<?php echo $crawl['crawl_id']; ?>" name="edit_crawldate<?php echo $crawl['crawl_id']; ?>" value="<?php echo $crawl['date']; ?>" required>
                                        </div>
                                        <div class="info-item">
                                            <label for="edit_start_time<?php echo $crawl['crawl_id']; ?>">Start Time:</label>
                                            <input type="time" id="edit_start_time<?php echo $crawl['crawl_id']; ?>" name="edit_start_time<?php echo $crawl['crawl_id']; ?>" value="<?php echo $crawl['start_time']; ?>" required>
                                        </div>
                                        <div class="info-item">
                                            <select id="edit_status<?php echo $crawl['crawl_id']; ?>" name="edit_status<?php echo $crawl['crawl_id']; ?>">
                                                <option value="active" <?php echo ($crawl['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="completed" <?php echo ($crawl['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                                <option value="cancelled" <?php echo ($crawl['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <select id="edit_visibility<?php echo $crawl['crawl_id']; ?>" name="edit_visibility<?php echo $crawl['crawl_id']; ?>" style="margin-left: 30px;">
                                                <option value="public" <?php echo ($crawl['visibility'] === 'public') ? 'selected' : ''; ?>>Public</option>
                                                <option value="private" <?php echo ($crawl['visibility'] === 'private') ? 'selected' : ''; ?>>Private</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Hidden input field to store the crawl ID -->
                                    <input type="hidden" name="edit_crawl_id" value="<?php echo $crawl['crawl_id']; ?>">
                                    <button type="submit" name="edit-crawl">Save Changes</button>
                                    <!-- Delete button -->
                                    <button type="submit" name="delete-crawl" style="margin-left: 30px;" onclick="return confirm('Are you sure you want to delete this crawl?')">Delete</button>

                                </form>
                            </div>

                            <!-- Display the list of unique bar names for the current crawl -->
                            <div class="crawl-bar-route">
                                <strong>Crawl Route:</strong><br>
                                <ul>
                                    <?php
                                    // Fetch bar names associated with the current crawl
                                    $sql = "SELECT b.barname
                            FROM bars b
                            JOIN crawlbars cb ON b.barid = cb.barid
                            WHERE cb.crawl_id = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $crawl['crawl_id']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    // Check if there are any results
                                    if ($result->num_rows > 0) {
                                        // Loop through the results and display bar names
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<li>" . $row['barname'] . "</li>";
                                        }
                                    } else {
                                        echo "<li>No bars found for this crawl.</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>


            </div>

            <div class="crawls-column">
                <h2>Crawls:</h2>
                <div class="all-crawls-container">

                    <div class="crawls-list">
                        <h2>Crawls:</h2>
                        <!-- Crawls information content goes here -->
                        <ul class="tabbed-list">
                            <?php foreach ($crawls as $crawl): ?>
                                <li>
                                    <strong>Crawl Creator ID:</strong> <?php echo $crawl['creator_id']; ?><br>
                                    <strong>Name:</strong> <?php echo $crawl['name']; ?><br>
                                    <strong>Date:</strong> <?php echo date('m/d/y', strtotime($crawl['date'])); ?><br>
                                    <strong>Start Time:</strong> <?php echo date('h:i A', strtotime($crawl['start_time'])); ?><br>
                                    <strong>Description:</strong> <?php echo $crawl['description']; ?>
                                </li>
                                <br>
                            <?php endforeach; ?>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

