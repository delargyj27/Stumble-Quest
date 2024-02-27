<?php
// Function to perform user login
function loginUser($conn, $username, $password) {
    // Retrieve the hashed password for the given username
    $getUserQuery = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $getUserQuery->bind_param("s", $username);
    $getUserQuery->execute();
    $result = $getUserQuery->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            $_SESSION["username"] = $username;
            return "Login successful";
        } else {
            return "Invalid username or password";
        }
    } else {
        return "Invalid username or password";
    }

    $getUserQuery->close();
}
?>
