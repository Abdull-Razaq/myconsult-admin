<?php
include 'php/db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$admin_id = $_SESSION['admin_id'];
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['log_id'])) {
    $log_id = $data['log_id'];

    // Verify that the log belongs to a consultant assigned to this reviewer
    $query_verify = "SELECT lh.id FROM log_hours lh
                     JOIN users u ON lh.user_id = u.id
                     WHERE lh.id = ? AND u.reviewer_id = ?";
    $stmt_verify = $conn->prepare($query_verify);
    $stmt_verify->bind_param("ii", $log_id, $admin_id);
    $stmt_verify->execute();
    $result_verify = $stmt_verify->get_result();

    if ($result_verify->num_rows > 0) {
        // Update the status to 'Approved'
        $query_update = "UPDATE log_hours SET status = 'Approved' WHERE id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("i", $log_id);
        if ($stmt_update->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Not authorized to approve this log."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
