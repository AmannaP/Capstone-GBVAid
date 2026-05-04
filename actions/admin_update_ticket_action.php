<?php
// actions/admin_update_ticket_action.php
require_once '../settings/core.php';
require_once '../controllers/help_desk_controller.php';

header('Content-Type: application/json');

// Ensure user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'] ?? '';
    $status = $_POST['status'] ?? 'Pending';
    $admin_reply = $_POST['admin_reply'] ?? '';

    if (empty($ticket_id) || empty($status)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing ticket ID or status.']);
        exit();
    }

    $result = update_ticket_ctr($ticket_id, $status, $admin_reply);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Ticket updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update ticket.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request base.']);
}
?>
