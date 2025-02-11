<?php
include '../php/db_connect.php';
session_start();

// Check if the super admin is logged in
if (!isset($_SESSION['super_admin_id'])) {
    header("Location: superadmin_login.php");
    exit();
}

// Handle form submission to add a reviewer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insert new reviewer (admin) into the database
    $query = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header("Location: dashboard.php");  // Redirect to the dashboard after successful registration
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
    <link rel="stylesheet" href="../css/signin.css">
    <title>Add Reviewer</title>
</head>
<body>

    <!-- Add Reviewer Form -->
    <section class="form-section">
        <div class="form-container">
            <h1>Add Reviewer</h1>
            <form action="add_reviewer.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="submit-btn">Add Reviewer</button>

                <p class="account">
                    <a href="dashboard.php">Back to Dashboard</a>
                </p>
            </form>
        </div>
    </section>

</body>
</html>
