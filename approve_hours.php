<?php
include 'php/db_connect.php';

if (isset($_POST['id'])) {
    $log_id = $_POST['id'];

    // Update the status to 'Approved'
    $query = "UPDATE log_hours SET status = 'Approved' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $log_id);
    
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conn->close();
}
?>
