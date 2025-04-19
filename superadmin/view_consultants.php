<?php
include '../php/db_connect.php';
session_start();

if (!isset($_SESSION['super_admin_id'])) {
    header("Location: superadmin_login.php");
    exit();
}

// Fetch all consultants
$query = "SELECT id, username, email, reviewer_id FROM users"; 
$result = $conn->query($query);

// Fetch all reviewers (Admins)
$reviewersQuery = "SELECT id, username FROM admins";
$reviewersResult = $conn->query($reviewersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Consultants</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <h1 class="logo">Consultants List</h1>
        <div class="nav-buttons">
            <a href="dashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </div>
</nav>

<section class="consultants">
    <h2>Consultants</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Consultant Name</th>
                <th>Email</th>
                <th>Reviewer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                $reviewer_id = $row['reviewer_id'];
                $reviewer_name = "Not Assigned";
                
                if ($reviewer_id) {
                    $query_reviewer = "SELECT username FROM admins WHERE id = ?";
                    $stmt = $conn->prepare($query_reviewer);
                    $stmt->bind_param("i", $reviewer_id);
                    $stmt->execute();
                    $reviewer_result = $stmt->get_result();
                    if ($reviewer_row = $reviewer_result->fetch_assoc()) {
                        $reviewer_name = $reviewer_row['username'];
                    }
                }

                echo "<tr>
                        <td>{$counter}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['email']}</td>
                        <td>{$reviewer_name}</td>
                        <td>
                            <button class='action-btn' onclick='openAssignPopup({$row['id']})'>Assign</button>
                        </td>
                      </tr>";
                $counter++;
            }
            ?>
        </tbody>
    </table>
</section>

<!-- Assign Reviewer Pop-up -->
<div id="assignReviewerPopup" class="popup-form">
    <div class="popup-content">
        <span class="submit-btn" onclick="closeAssignPopup()">&times;</span>
        <h2>Assign Reviewer</h2>
        <form action="assign_reviewer.php" method="POST">
            <input type="hidden" name="user_id" id="user_id">
            <label for="reviewer_id">Select Reviewer:</label>
            <select name="reviewer_id" id="reviewer_id" required>
                <option value="">Reviewer Name</option>
                <?php
                // Fetch all reviewers
                $reviewersQuery = "SELECT id, username FROM admins";
                $reviewersResult = $conn->query($reviewersQuery);
                while ($reviewer = $reviewersResult->fetch_assoc()) {
                    echo "<option value='{$reviewer['id']}'>{$reviewer['username']}</option>";
                }
                ?>
            </select>
            <button type="submit" class="submit-btn">Assign</button>
        </form>
    </div>
</div>

<script>
function openAssignPopup(userId) {
    document.getElementById('user_id').value = userId;
    document.getElementById('assignReviewerPopup').style.display = "flex";
}

function closeAssignPopup() {
    document.getElementById('assignReviewerPopup').style.display = "none";
}

// Hide popup on page load (ensures it stays hidden)
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('assignReviewerPopup').style.display = "none";
});
</script>


</body>
</html>
