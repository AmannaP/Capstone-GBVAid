<?php
require_once '../settings/core.php';
require_once '../controllers/awareness_controller.php';

requireAdmin();

if (isset($_POST['awareness_id'])) {
    $id = $_POST['awareness_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Ensure this function exists in awareness_controller.php
    if(update_awareness_ctr($id, $title, $content)) {
        header("Location: ../admin/awareness.php?msg=updated");
    } else {
        header("Location: ../admin/awareness.php?msg=error");
    }
    exit();
} else {
    header("Location: ../admin/awareness.php");
    exit();
}