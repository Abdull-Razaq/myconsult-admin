<?php
session_start();
include '../php/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $reviewer_id = $_POST['reviewer_id'];

    $query = "UPDATE users SET reviewer_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $reviewer_id, $user_id);

    if ($stmt->execute()) {
        header("Location: view_consultants.php?success=Reviewer assigned successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
