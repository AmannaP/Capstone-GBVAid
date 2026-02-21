<?php
// actions/delete_service_action.php
require_once '../settings/core.php';
require_once '../controllers/service_controller.php';

header('Content-Type: application/json');

// Ensure admin access
if (!checkLogin() || !isAdmin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid service ID."]);
    exit;
}

$service_id = intval($_POST['id']);

try {
    $result = delete_service_ctr($service_id);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Service deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete service."]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
