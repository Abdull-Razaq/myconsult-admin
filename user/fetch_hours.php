<?php
session_start();
include '../php/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT date, task_name, hours, task_description, status 
        FROM log_hours 
        WHERE user_id = ? 
        ORDER BY date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$hours = [];

while ($row = $result->fetch_assoc()) {
    $hours[] = $row;
}

echo json_encode($hours);
?>
