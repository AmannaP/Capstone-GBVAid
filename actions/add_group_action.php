<?php
// actions/add_group_action.php
require_once '../settings/core.php';
require_once '../controllers/chat_controller.php';

// Restrict to Admin
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['group_name']);
    $desc = trim($_POST['description']);
    $icon = $_POST['icon'] ?? 'bi-people';

    if (!empty($name) && !empty($desc)) {
        $result = create_group_ctr($name, $desc, $icon);
        
        if ($result) {
            header("Location: ../admin/manage_groups.php?success=created");
        } else {
            header("Location: ../admin/manage_groups.php?error=failed");
        }
    } else {
        header("Location: ../admin/manage_groups.php?error=empty");
    }
}
?>