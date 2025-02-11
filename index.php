<?php
session_start();
include 'php/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate if fields are empty
    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
        exit();
    }

    // Query to check the admin user in the database
    $query = "SELECT id, username, password FROM admins WHERE username = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($password, $db_password)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_username'] = $db_username;
            
            header("Location: dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signin.css">
    <title>Admin Login</title>
</head>
<body>
    <div class="auth-form">
        <div class="container">
            <div class="form-img">
                <img src="images/back.jpg" alt="Login Image">
            </div>
            <div class="content">
                <h1>Admin Sign In</h1>
                <form action="index.php" method="POST">
                    <label for="username">Username</label>
                    <input type="text" name="username" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" required>

                    <button type="submit">Log In</button>
                </form>

                <p class="account">
                    Don't have an account?
                    <a href="register.php">Sign Up</a>
                </p>
                <p class="account">
                    A Superadmin <!-- Super Admin Login Button -->
<a href="superadmin/superadmin_login.php" class="superadmin-button">Super Admin Login</a>

                </p>
            </div>
        </div>
    </div>
</body>
</html>
