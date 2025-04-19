<?php
session_start();
include "../php/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (!empty($username) && !empty($email) && !empty($password) && $password === $confirm_password) {
        // Check if user already exists
        $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            $stmt->execute();

            // Redirect to login after successful registration
            header("Location: user_login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Register</title>
    <link rel="stylesheet" href="../css/signin.css">
</head>
<body>
    <div class="auth-form">
        <div class="container">
            <div class="form-img">
                <img src="../images/back.jpg" alt="Register Image">
            </div>
            <div class="content">
                <h1>Sign Up</h1>

                <form action="user_register.php" method="POST">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>

                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>

                    <button class="btn" type="submit">Sign Up</button>
                </form>

                <p class="account">
                    Already have an account?
                    <a href="user_login.php">Log In</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
