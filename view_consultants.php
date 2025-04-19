<?php
include 'php/db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch all consultants
$query = "SELECT id, username, email, reviewer_id FROM users";  // Fetch users and their assigned reviewers
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Consultants</title>
    <link rel="stylesheet" href="css/style.css">
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
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                // Fetch reviewer name
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
                      </tr>";
                $counter++;
            }
            ?>
        </tbody>
    </table>
</section>

</body>
</html>
