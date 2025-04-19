<?php
session_start();
include '../php/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

$data = [
    'hours_today' => 0,
    'hours_week' => 0,
    'hours_month' => 0
];

// Today
$sqlToday = "SELECT SUM(hours) as total 
             FROM log_hours 
             WHERE user_id = ? 
             AND DATE(date) = CURDATE()";
$stmt = $conn->prepare($sqlToday);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($data['hours_today']);
$stmt->fetch();
$stmt->close();

// This Week
$sqlWeek = "SELECT SUM(hours) as total 
            FROM log_hours 
            WHERE user_id = ? 
            AND YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
$stmt = $conn->prepare($sqlWeek);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($data['hours_week']);
$stmt->fetch();
$stmt->close();

// This Month
$sqlMonth = "SELECT SUM(hours) as total 
             FROM log_hours 
             WHERE user_id = ? 
             AND MONTH(date) = MONTH(CURDATE()) 
             AND YEAR(date) = YEAR(CURDATE())";
$stmt = $conn->prepare($sqlMonth);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($data['hours_month']);
$stmt->fetch();
$stmt->close();

echo json_encode($data);
?>
