<?php
session_start();
include '../php/db_connect.php';

// Ensure only the super admin can access
if (!isset($_SESSION['super_admin_id'])) {
    header("Location: superadmin_login.php");
    exit();
}

// Fetch counts
$usersCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$reviewersCount = $conn->query("SELECT COUNT(*) AS total FROM admins")->fetch_assoc()['total'];
$hoursLogged = $conn->query("SELECT SUM(hours) AS total FROM log_hours")->fetch_assoc()['total'];

// Fetch users and reviewers
$users = $conn->query("SELECT u.id, u.username, u.email, a.username AS reviewer 
                      FROM users u 
                      LEFT JOIN admins a ON u.reviewer_id = a.id");
$reviewers = $conn->query("SELECT * FROM admins");

// Fetch logged-in super admin username
$superAdminUsername = $_SESSION['superadmin_username'] ?? 'Super Admin';

// Fetch analytics data
$usersCount = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$reviewersCount = $conn->query("SELECT COUNT(*) AS count FROM admins")->fetch_assoc()['count'];
$hoursLogged = $conn->query("SELECT SUM(hours) AS total FROM log_hours")->fetch_assoc()['total'];

// Fetch hours log data
$result_logs = $conn->query("
    SELECT id, date, user, admin, task_name, task_description, hours, status
    FROM hours_logged
    ORDER BY date DESC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">Hi, <?= htmlspecialchars($superAdminUsername) ?></div>
            <div class="nav-buttons">
                <a href="view_consultants.php" class="btn">View Consultants</a>
                <button id="showAddReviewerModal" class="btn logout-btn">Add Reviewer</button>
                <a href="superadmin_logout.php" class="btn logout-btn">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Add Reviewer Modal -->
    <div id="addReviewerModal" class="form-popup">
        <div class="form-container">
            <span class="close" onclick="closeForm()">&times;</span> <!-- Close button -->
            <h2>Add Reviewer</h2>
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
            </form>
        </div>
    </div>

    <!-- Analytics Section -->
    <section class="statistics">
        <div class="stat-box">
            <div class="stat-number"><?= $usersCount ?></div>
            <div class="stat-text">Total Consultants</div>
        </div>
        <div class="stat-box">
            <div class="stat-number"><?= $reviewersCount ?></div>
            <div class="stat-text">Total Reviewers</div>
        </div>
        <div class="stat-box">
            <div class="stat-number"><?= $hoursLogged ?></div>
            <div class="stat-text">Total Hours Logged</div>
        </div>
    </section>

    <!-- Approve Consultancy Hours Table -->
    <section class="tasks">
        <h2>Approve Consultancy Hours</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>Reviewer</th>
                    <th>Task Name</th>
                    <th>Task Description</th>
                    <th>Hours Logged</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; while ($row = $result_logs->fetch_assoc()) { ?>
                <tr>
                    <td><?= $counter ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['user']) ?></td>
                    <td><?= htmlspecialchars($row['admin'] ?? 'Not Assigned') ?></td>
                    <td><?= htmlspecialchars($row['task_name']) ?></td>
                    <td><?= htmlspecialchars($row['task_description']) ?></td>
                    <td><?= htmlspecialchars($row['hours']) ?></td>
                    <td class="<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td>
                </tr>
                <?php $counter++; } ?>
            </tbody>
        </table>
    </section>

    <script>
        // JavaScript for handling the modal form visibility
        const modal = document.getElementById("addReviewerModal");
        const btn = document.getElementById("showAddReviewerModal");
        const closeBtn = document.querySelector(".close");

        btn.onclick = function() {
            modal.style.display = "block"; // Show modal
        }

        closeBtn.onclick = function() {
            modal.style.display = "none"; // Close modal
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none"; // Close modal if clicked outside
            }
        }

        // Close the form function for Log Hours
        function closeForm() {
            modal.style.display = "none";
        }
    </script>
</body>
</html>

