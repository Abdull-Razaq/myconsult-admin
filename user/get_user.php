<?php
include "../php/db_connect.php";
session_start();

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user details from database
$query = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$username = $user_data['username'] ?? null;

$stmt->close();
$conn->close();

// Return JSON response
if ($username) {
    echo json_encode(["username" => $username]);
} else {
    echo json_encode(["error" => "User not found"]);
}
?>
