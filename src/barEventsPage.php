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


// Query to fetch the events
$existingEvents = array();
// Get today's date
$today = date('Y-m-d');
$sql = "SELECT eventid, eventname, eventdate, start_time, end_time, description
        FROM events  
        WHERE barid = ? AND eventdate >= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $barid, $today);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any results
if ($result->num_rows > 0) {
    // Initialize an empty array to store events
    $events = array();

    // Fetch events and store them in the array
    while ($row = $result->fetch_assoc()) {
        $existingEvents[] = $row;
    }
}// Close statement
$result->close();


// Fetch crawls information from tables crawlbars and crawls join on the barid
$crawls = array();
$sql = "SELECT c.*
        FROM crawls c
        inner join crawlbars cb ON cb.crawl_id = c.crawl_id
        WHERE cb.barid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $barid);
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

// Check if the "Add Event" button is clicked
if (isset($_POST['add-event'])) {
    // Retrieve form data
    $eventname = isset($_POST["eventname"]) ? $_POST["eventname"] : null;
    $description = isset($_POST["description"]) ? $_POST["description"] : null;
    $eventdate = isset($_POST["eventdate"]) ? $_POST["eventdate"] : null;
    $start_time = isset($_POST["start_time"]) ? $_POST["start_time"] : null;
    $end_time = isset($_POST["end_time"]) ? $_POST["end_time"] : null;

    // Prepare and execute the insert query
    $sql = "INSERT INTO events (eventname, eventdate, start_time, end_time, description, barid) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $eventname, $eventdate, $start_time, $end_time, $description, $barid);
    $stmt->execute();

    // Check if the event was added successfully
    if ($stmt->affected_rows > 0) {
        // Redirect to the same page to avoid form resubmission
        header("Location: {$_SERVER['PHP_SELF']}?managerid={$manager_id}&success=event_added");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?managerid={$manager_id}&error=event_add_failed");
        exit();
    }
}

// Check if the "Save Changes" button is clicked for editing an event
if (isset($_POST['edit-event'])) {
    // Retrieve form data
    $edit_event_id = isset($_POST["edit_event_id"]) ? $_POST["edit_event_id"] : null;
    $edit_eventname = isset($_POST["edit_eventname{$edit_event_id}"]) ? $_POST["edit_eventname{$edit_event_id}"] : null;
    $edit_description = isset($_POST["edit_description{$edit_event_id}"]) ? $_POST["edit_description{$edit_event_id}"] : null;
    $edit_eventdate = isset($_POST["edit_eventdate{$edit_event_id}"]) ? $_POST["edit_eventdate{$edit_event_id}"] : null;
    $edit_start_time = isset($_POST["edit_start_time{$edit_event_id}"]) ? $_POST["edit_start_time{$edit_event_id}"] : null;
    $edit_end_time = isset($_POST["edit_end_time{$edit_event_id}"]) ? $_POST["edit_end_time{$edit_event_id}"] : null;


// Prepare and execute the update query
    $sql = "UPDATE events 
        SET eventname=?, eventdate=?, start_time=?, end_time=?, description=?
        WHERE eventid=?";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $edit_eventname, $edit_eventdate, $edit_start_time, $edit_end_time, $edit_description, $edit_event_id);
    $stmt->execute();

    // Check if the event was updated successfully
    if ($stmt->affected_rows > 0) {
        // Redirect to the same page to avoid form resubmission
        header("Location: {$_SERVER['PHP_SELF']}?managerid={$manager_id}&success=event_updated");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?managerid={$manager_id}&error=event_update_failed");
        exit();
    }
}

// Check if the "Delete" button is clicked for deleting an event
if (isset($_POST['delete-event'])) {
    // Retrieve the event ID to be deleted
    $delete_event_id = isset($_POST["edit_event_id"]) ? $_POST["edit_event_id"] : null;

    // Prepare and execute the delete query
    $sql = "DELETE FROM events WHERE eventid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_event_id);
    $stmt->execute();

    // Check if the event was deleted successfully
    if ($stmt->affected_rows > 0) {
        // Redirect to the same page to avoid form resubmission
        header("Location: {$_SERVER['PHP_SELF']}?managerid={$manager_id}&success=event_deleted");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?managerid={$manager_id}&error=event_delete_failed");
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
        <li><a href="crawls.php">Crawl</a></li>
        <li><a href="crawls.html">Calander</a></li>
        <li><a href="aboutUs.html">FAQ</a></li>
    </ul>
</nav>

<!-- Main content container -->
<div class="main-content">
    <!-- Bar name -->
    <h1 class="bar_name"><?php echo $name; ?> Events</h1>

    <!-- Events Section -->
    <div class="events-section">

        <!-- Form for adding a new event -->
        <div class="add-event-form">
            <h2>Add New Event</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="eventname">Event Name:</label>
                <input type="text" id="eventname" name="eventname" required>
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
                <label for="eventdate">Event Date:</label>
                <input type="date" id="eventdate" name="eventdate" required>
                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required>
                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" required>
                <button type="submit" name="add-event">Add Event</button>
            </form>
        </div>

        <!-- Divider Line -->
        <div class="divider-line"></div>

        <!-- Existing Events Container -->
        <h2>Existing Events</h2>
        <div class="existing-events-container">

            <div class="events-column">

                <!-- Loop through existing events and display them in the form -->
                <?php foreach ($existingEvents as $event): ?>
                    <div class="event-container">
                        <div class="event-info">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="info-item">
                                    <label for="edit_eventname<?php echo $event['eventid']; ?>">Event Name:</label>
                                    <input type="text" id="edit_eventname<?php echo $event['eventid']; ?>" name="edit_eventname<?php echo $event['eventid']; ?>" value="<?php echo $event['eventname']; ?>" required>
                                </div>
                                <div class="info-item">
                                    <label for="edit_description<?php echo $event['eventid']; ?>">Description:</label>
                                    <textarea id="edit_description<?php echo $event['eventid']; ?>" name="edit_description<?php echo $event['eventid']; ?>" rows="4" required><?php echo $event['description']; ?></textarea>
                                </div>
                                <div class="event-datetime">
                                    <div class="info-item">
                                        <label for="edit_eventdate<?php echo $event['eventid']; ?>">Event Date:</label>
                                        <input type="date" id="edit_eventdate<?php echo $event['eventid']; ?>" name="edit_eventdate<?php echo $event['eventid']; ?>" value="<?php echo $event['eventdate']; ?>" required>
                                    </div>
                                    <div class="info-item">
                                        <label for="edit_start_time<?php echo $event['eventid']; ?>">Start Time:</label>
                                        <input type="time" id="edit_start_time<?php echo $event['eventid']; ?>" name="edit_start_time<?php echo $event['eventid']; ?>" value="<?php echo $event['start_time']; ?>" required>
                                    </div>
                                    <div class="info-item">
                                        <label for="edit_end_time<?php echo $event['eventid']; ?>">End Time:</label>
                                        <input type="time" id="edit_end_time<?php echo $event['eventid']; ?>" name="edit_end_time<?php echo $event['eventid']; ?>" value="<?php echo $event['end_time']; ?>" required>
                                    </div>
                                </div>
                                <!-- Hidden input field to store the event ID -->
                                <input type="hidden" name="edit_event_id" value="<?php echo $event['eventid']; ?>">
                                <button type="submit" name="edit-event">Save Changes</button>

                                <!-- Delete button -->
                                <button type="submit" name="delete-event" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>

                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="crawls-column">
                <div class="crawls-container">

                    <div class="crawls-list">
                        <h2>Crawls:</h2>
                        <!-- Crawls information content goes here -->
                        <ul class="tabbed-list">
                            <?php foreach ($crawls as $crawl): ?>
                                <li>
                                    <strong>Crawl Creator ID:</strong> <?php echo $crawl['creator_id']; ?><br>
                                    <strong>Name:</strong> <?php echo $crawl['name']; ?><br>
                                    <strong>Date:</strong> <?php echo $crawl['date']; ?><br>
                                    <strong>Start Time:</strong> <?php echo date('H:i', strtotime($crawl['start_time'])); ?><br>
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
