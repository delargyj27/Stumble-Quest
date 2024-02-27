<?php
session_start();
require_once 'db_connection.php';
require_once 'login_function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Call the login function
    $loginResult = loginUser($conn, $username, $password);

    // Output the result to the user
    echo $loginResult;
}

// Close the database connection
$conn->close();
?>
