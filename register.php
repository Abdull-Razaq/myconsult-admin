<?php
include 'php/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insert new admin user into the database
    $query = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header("Location: index.php");  // Redirect to login after successful registration
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signin.css">
    <title>Admin Registration</title>
</head>
<body>
    <div class="auth-form">
        <div class="container">
            <div class="form-img">
                <img src="images/back.jpg" alt="Register Image">
            </div>
            <div class="content">
                <h1>Admin Sign Up</h1>
                <form action="register.php" method="POST">
                    <label for="username">Username</label>
                    <input type="text" name="username" required>

                    <label for="email">Email</label>
                    <input type="email" name="email" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" required>

                    <button type="submit">Register</button>
                </form>

                <p class="account">
                    Already have an account?
                    <a href="index.php">Sign In</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
