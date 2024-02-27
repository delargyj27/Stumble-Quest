<?php
session_start();
require_once 'db_connection.php';
require_once 'login_function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Call the login function
    $loginResult = loginUser($conn, $username, $password);
    
    // Check if login was successful
    if ($loginResult === true) {
        // Redirect to the main menu
        header("Location: http://ec2-52-91-186-216.compute-1.amazonaws.com/index.html");
        exit(); // Ensure that no other code is executed after the header redirect
    } else {
    // Output the result to the user
        echo $loginResult;
}

// Close the database connection
$conn->close();
?>
