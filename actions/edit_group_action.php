<?php
require_once '../settings/core.php';
require_once '../controllers/chat_controller.php';

// 1. Restrict to Admin
requireAdmin();

// 2. Check if the form was submitted
if (isset($_POST['group_id'])) {
    
    // 3. Collect and sanitize input data
    $group_id = $_POST['group_id'];
    $group_name = htmlspecialchars($_POST['group_name']);
    $description = htmlspecialchars($_POST['description']);
    $icon = htmlspecialchars($_POST['icon']);

    // 4. Call the controller function to update the group
    // Make sure this function exists in your chat_controller.php
    $result = update_group_ctr($group_id, $group_name, $description, $icon);

    // 5. Redirect based on success or failure
    if ($result) {
        header("Location: ../admin/manage_groups.php?msg=updated");
    } else {
        header("Location: ../admin/manage_groups.php?msg=error");
    }
    exit();
} else {
    // If someone tries to access this file directly without POSTing
    header("Location: ../admin/manage_groups.php");
    exit();
}
?>