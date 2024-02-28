<?php

//comment out once this is working
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
require_once 'db_conn.php';
require_once 'registration_function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    // Call the registration function
    $registrationResult = registerUser($conn, $username, $password, $user_type);

    // Check if registration was successful
    if ($registrationResult === true) {
        // Redirect to the login page
        header("Location: login.php");
        exit(); // Ensure that no other code is executed after the header redirect
    } else {
    // Output the result to the user
    echo $registrationResult;
}

// Close the database connection
$conn->close();
?>
