<?php
require_once '../settings/core.php';
require_once '../controllers/appointment_controller.php';

// Keep errors on for debugging so we can see the REAL error in Console -> Response
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if (!isset($_SESSION['id']) || $_SESSION['role'] != 3 || $_SESSION['sp_approved'] == 0) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appt_id = intval($_POST['appointment_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    $allowed_statuses = ['Assigned', 'Investigating', 'Resolved', 'Cancelled'];

    if ($appt_id > 0 && in_array($status, $allowed_statuses)) {
        if (update_booking_status_ctr($appt_id, $status)) {
            echo json_encode(["success" => true, "message" => "Status updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error or status unchanged."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid parameters."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
