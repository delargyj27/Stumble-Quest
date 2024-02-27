<?php
// Method to register a user
function registerUser($conn, $username, $password, $user_type) {
    // Check if the user already exists
    $checkUserQuery = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkUserQuery->bind_param("s", $username);
    $checkUserQuery->execute();
    $result = $checkUserQuery->get_result();

    if ($result->num_rows > 0) {
        return "Username already exists. Please choose another username.";
    }

    // If the user doesn't exist, proceed with registration
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($user_type == "regular") {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    } elseif ($user_type == "business") {
        $stmt = $conn->prepare("INSERT INTO managerprofile (username, password) VALUES (?, ?)");
    } else {
        return "Invalid account type"; // Handle other account types if needed
    }

    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {   
        header("Location: login.php"); // Registration successful, redirect to login.php
        exit(); // Ensure that no other code is executed after the header redirect
    } else {
    return "Registration failed. Please try again later.";
    }

    $stmt->close();
    $checkUserQuery->close();
}
?>
