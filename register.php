<?php
session_start();
require_once 'db_conn.php';
require_once 'registration_function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    // Call the registration function
    $registrationResult = registerUser($conn, $username, $password, $user_type);

    // Output the result to the user
    echo $registrationResult;
}

// Close the database connection
$conn->close();
?>
