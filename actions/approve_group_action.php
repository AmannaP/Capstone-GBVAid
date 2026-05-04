<?php
// actions/approve_group_action.php
require_once '../settings/core.php';
require_once '../controllers/chat_controller.php'; // Use the controller instead of calling the class directly

// Security check: Only admins can approve groups
requireAdmin();

$request_id = $_GET['id'] ?? null;

if ($request_id) {
    // Use the controller (ctr) to keep logic consistent
    if (approve_suggestion_ctr($request_id)) {
        // Redirect to avoid "headers already sent" errors
        header("Location: ../admin/manage_requests.php?msg=approved");
    } else {
        header("Location: ../admin/manage_requests.php?error=failed");
    }
} else {
    header("Location: ../admin/manage_requests.php");
}
exit();