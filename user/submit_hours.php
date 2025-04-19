<?php
session_start();
include '../php/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $hours = $_POST['hours'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $status = 'Pending'; // Default

    // Insert into log_hours
    $stmt = $conn->prepare("INSERT INTO log_hours (user_id, hours, task_name, task_description, status, date) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("idsss", $user_id, $hours, $task_name, $task_description, $status);

    if ($stmt->execute()) {
        // 🔁 Fetch username and admin name
        $userQuery = $conn->prepare("SELECT username, reviewer_id FROM users WHERE id = ?");
        $userQuery->bind_param("i", $user_id);
        $userQuery->execute();
        $userResult = $userQuery->get_result();
        $userData = $userResult->fetch_assoc();

        $username = $userData['username'];
        $reviewer_id = $userData['reviewer_id'];

        $adminResult = $conn->query("SELECT username FROM admins WHERE id = $reviewer_id");
        $adminRow = $adminResult->fetch_assoc();
        $adminname = $adminRow['username'] ?? 'Unknown';

        // 🔁 Insert into hours_logged
        $stmt2 = $conn->prepare("
            INSERT INTO hours_logged (date, user, admin, task_name, task_description, hours, status)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?)
        ");
        $stmt2->bind_param("ssssds", $username, $adminname, $task_name, $task_description, $hours, $status);
        $stmt2->execute();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
