<?php
session_start();
include '../php/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check the super admin user in the database (using the correct table: super_admins)
    $query = "SELECT * FROM super_admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Check if the super admin exists and verify the password
    if ($admin && password_verify($password, $admin['password'])) {
        // Store the superadmin's ID in session to keep track of login state
        $_SESSION['super_admin_id'] = $admin['id'];
        // Redirect to the super admin dashboard after successful login
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login</title>
    <link rel="stylesheet" href="../css/signin.css">
</head>
<body>
    <div class="auth-form">
        <div class="container">
            <div class="form-img">
                <img src="../images/back.jpg" alt="Login Image">
            </div>
            <div class="content">
                <h1>Super Admin Sign In</h1>
                <form action="superadmin_login.php" method="POST">
                    <label for="email">Email</label>
                    <input type="email" name="email" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" required>

                    <button type="submit">Log In</button>
                </form>

                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

                <p class="account">
                    Don't have an account?
                    <a href="superadmin_register.php">Register</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
