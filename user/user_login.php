<?php
session_start();
include "../php/db_connect.php";

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    // Check if username or password is empty
    if (empty($username) || empty($password)) {
        $loginError = "Username and password are required!";
    } else {
        // Query the database for the user by username
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify user and password
        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            header("Location: dashboard.php");
            exit();
        } else {
            $loginError = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Log In</title>
    <link rel="stylesheet" href="../css/signin.css">
</head>
<body>
    <div class="auth-form">
        <div class="container">
            <div class="form-img">
                <img src="../images/back.jpg" alt="Log In Image">
            </div>
            <div class="content">
                <h1>Log In</h1>

                <?php if (!empty($loginError)) : ?>
                    <div style="color: red; margin-bottom: 10px;">
                        <?php echo htmlspecialchars($loginError); ?>
                    </div>
                <?php endif; ?>

                <form action="user_login.php" method="POST">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>

                    <button class="btn" type="submit">Log In</button>
                </form>

                <p class="account">
                    Not registered yet?
                    <a href="user_register.php">Sign Up</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
