<?php
require_once '../settings/core.php';
// Specifically unset only the unlocked_vault to lock the evidence, keeping the SP logged in.
if (isset($_SESSION['unlocked_vault'])) {
    unset($_SESSION['unlocked_vault']);
}
header("Location: ../sp/dashboard.php");
exit();
?>
