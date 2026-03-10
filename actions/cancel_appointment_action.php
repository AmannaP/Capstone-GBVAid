<?php
// actions/cancel_appointment_action.php
require_once '../settings/core.php';
require_once '../controllers/appointment_controller.php';

// Using the helper from core.php to get the ID
$uid = getUserId(); 
$appt_id = $_POST['appointment_id'] ?? null;

if ($uid && $appt_id) {
    if (cancel_appointment_ctr($appt_id, $uid)) {
        echo "success";
    } else {
        echo "failed";
    }
} else {
    echo "invalid";
}