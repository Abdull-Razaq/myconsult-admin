<?php
include 'php/db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Fetch Admin Name
$admin_id = $_SESSION['admin_id'];
$query_admin = "SELECT username FROM admins WHERE id = ?";
$stmt = $conn->prepare($query_admin);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$admin_name = $admin['username']; // Store admin name

// Fetch number of consultants
$query_consultants = "SELECT COUNT(*) FROM users";  // Get total number of users
$result_consultants = $conn->query($query_consultants);
$consultants_count = $result_consultants->fetch_row()[0];

// Fetch hours logged today
$query_today = "SELECT SUM(hours) FROM log_hours WHERE DATE(date) = CURDATE()";  // Get today's hours
$result_today = $conn->query($query_today);
$hours_today = $result_today->fetch_row()[0];

// Fetch hours logged this week
$query_weekly = "SELECT SUM(hours) FROM log_hours WHERE WEEK(date) = WEEK(CURDATE())";  // Get hours this week
$result_weekly = $conn->query($query_weekly);
$hours_weekly = $result_weekly->fetch_row()[0];

// Fetch hours logged this month
$query_monthly = "SELECT SUM(hours) FROM log_hours WHERE MONTH(date) = MONTH(CURDATE())";  // Get hours this month
$result_monthly = $conn->query($query_monthly);
$hours_monthly = $result_monthly->fetch_row()[0];

// Fetch log entries to display in the table
$query_logs = "SELECT lh.id, lh.date, lh.task_name, lh.hours, lh.task_description, lh.status, u.username 
               FROM log_hours lh
               JOIN users u ON lh.user_id = u.id
               WHERE u.reviewer_id = ?";
$stmt_logs = $conn->prepare($query_logs);
$stmt_logs->bind_param("i", $admin_id);
$stmt_logs->execute();
$result_logs = $stmt_logs->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

      <!-- Navbar -->
      <nav class="navbar">
    <div class="nav-container">
        <h1 class="logo" id="greeting">Hi, <?php echo htmlspecialchars($admin_name); ?></h1>
        <div class="nav-buttons">
            <a href="view_consultants.php" class="btn">View Consultants</a>
            <a href="logout.php" class="btn logout-btn">Log Out</a>
        </div>
    </div>
</nav>



    <!-- Analytics Section -->
    <section class="statistics">
        <div class="stat-box">
            <span class="stat-number"><?php echo $consultants_count; ?></span>
            <p class="stat-text">Consultants</p>
        </div>
        <div class="stat-box">
            <span class="stat-number"><?php echo $hours_today; ?>0</span>
            <p class="stat-text">Hours Today</p>
        </div>
        <div class="stat-box">
            <span class="stat-number"><?php echo $hours_weekly; ?>0</span>
            <p class="stat-text">Hours This Week</p>
        </div>
        <div class="stat-box">
            <span class="stat-number"><?php echo $hours_monthly; ?></span>
            <p class="stat-text">Hours This Month</p>
        </div>
    </section>

    <!-- Approve Consultancy Hours Section -->
    <section class="tasks">
        <h2>Approve Consultancy Hours</h2>
        <table id="hoursTable">
            <thead>
                <tr>
                    <th>#</th>  <!-- Numbering column -->
                    <th>Name</th>
                    <th>Date</th>
                    <th>Task Name</th>
                    <th>Hours</th>
                    <th>Task Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through and display rows from the log_hours table
                $counter = 1;  // For numbering rows
                while ($row = $result_logs->fetch_assoc()) {
                    echo "<tr>
                            <td>{$counter}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['task_name']}</td>
                            <td>{$row['hours']}</td>
                            <td>{$row['task_description']}</td>
                            <td class='{$row['status']}'>{$row['status']}</td>
                            <td>
                                <button class='action-btn' data-id='{$row['id']}' " . ($row['status'] == 'Approved' ? 'disabled' : '') . ">Approve</button>
                            </td>
                          </tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>
    </section>

    <script src="script.js"></script>
</body>
</html>
