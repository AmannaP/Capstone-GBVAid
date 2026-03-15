<?php
require_once '../settings/core.php';
require_once '../controllers/user_controller.php';
requireAdmin();

if (isset($_GET['id'])) {
    $provider_id = intval($_GET['id']);
    
    if (reject_provider_ctr($provider_id)) {
        header("Location: ../admin/manage_providers.php?msg=rejected");
    } else {
        header("Location: ../admin/manage_providers.php?msg=error");
    }
} else {
    header("Location: ../admin/manage_providers.php");
}
?>
