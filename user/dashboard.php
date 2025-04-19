<?php
session_start();
include '../php/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user name
$query = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$username = $user['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="greeting-container">
                <h1 class="logo" id="greeting">Hi, <?php echo htmlspecialchars($username); ?></h1>
            </div>
            <div class="nav-buttons">
                <button id="logHoursBtn" class="btn yellow-btn">Log Hours</button>
                <button class="btn logout-btn">
                    <a href="user_logout.php">Log Out</a>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Analytics Section -->
    <section class="statistics">
        <div class="stat-box">
            <span class="stat-number" id="hoursToday">0</span> 
            <p class="stat-text">Hours Today</p>
        </div>
        <div class="stat-box">
            <span class="stat-number" id="hoursThisWeek">0</span> 
            <p class="stat-text">Hours This Week</p>
        </div>
        <div class="stat-box">
            <span class="stat-number" id="hoursThisMonth">0</span> 
            <p class="stat-text">Hours This Month</p>
        </div>
    </section>

    <!-- Table Section -->
    <section class="tasks">
        <h2>Consultancy Hours</h2>
        <table id="hoursTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Task Name</th>
                    <th>Hours</th>
                    <th>Task Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be populated by JavaScript -->
            </tbody>
        </table>
    </section>

    <!-- Log Hours Form (Hidden Initially) -->
    <div id="logHoursForm" class="form-popup" style="display: none;">
        <div class="form-container">
            <span class="close" onclick="closeForm()">&times;</span>
            <h2>Submit Consultancy Hours</h2>
            <form action="submit_hours.php" method="POST">
                <label for="hours">Enter Hours</label>
                <input type="number" name="hours" id="hours" placeholder="Enter hours" required>

                <label for="task_name">Task Name</label>
                <input type="text" name="task_name" id="task_name" placeholder="Enter task name" required>

                <label for="task_description">Task Description</label>
                <textarea name="task_description" id="task_description" placeholder="Enter task description" required></textarea>

                <div class="button-container">
                    <button type="submit" class="btn submit-btn">Submit</button>
                    <button type="button" class="btn close-btn" onclick="closeForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <script src="../script.js"></script>
</body>
</html>
