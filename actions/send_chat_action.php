<?php
// actions/send_chat_action.php
require_once '../settings/core.php'; // Ensures session is started and validated
require_once '../controllers/chat_controller.php';

// Check if user is actually logged in
$uid = $_SESSION['id'] ?? null;
$gid = $_POST['group_id'] ?? null;
$msg = $_POST['message'] ?? '';

// Sanitize the message to prevent XSS (Script Injection)
$clean_msg = htmlspecialchars(strip_tags(trim($msg)));

if ($uid && $gid && !empty($clean_msg)) {
    // Call the controller function
    $result = send_message_ctr($gid, $uid, $clean_msg);
    
    if ($result) {
        echo "success";
    } else {
        // This helps you debug if the database query fails
        echo "database_error";
    }
} else {
    echo "missing_data";
}
exit();