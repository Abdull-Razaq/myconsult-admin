<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "myconsulthours-1";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set UTF-8 character set
$conn->set_charset("utf8mb4");
?>
