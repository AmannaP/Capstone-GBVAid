<?php
// actions/reject_group_action.php

// Use your core file instead of manual session_start()
require_once '../settings/core.php'; 
require_once '../controllers/chat_controller.php';


requireLogin();
requireAdmin();

$request_id = $_GET['id'] ?? null;

if ($request_id) {
    if (reject_suggestion_ctr($request_id)) {
        header("Location: ../admin/manage_requests.php?msg=rejected");
    } else {
        header("Location: ../admin/manage_requests.php?error=failed");
    }
} else {
    header("Location: ../admin/manage_requests.php");
}
exit();