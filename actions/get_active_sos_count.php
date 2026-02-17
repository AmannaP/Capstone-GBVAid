<?php
// actions/get_active_sos_count.php
require_once '../settings/core.php';
require_once '../controllers/incident_controller.php';

// Wipe any accidental output (spaces, notices) to ensure clean JSON
if (ob_get_length()) ob_clean(); 

header('Content-Type: application/json');

try {
    $incident_obj = new Incident();
    $count = (int)$incident_obj->getActiveCount();
    $latestId = (int)$incident_obj->getLatestActiveId();

    echo json_encode([
        "count" => $count,
        "latest_incident_id" => $latestId
    ]);
} catch (Exception $e) {
    // If there is a DB error, it will show up in your browser console
    echo json_encode(["count" => 0, "error" => $e->getMessage()]);
}
exit;