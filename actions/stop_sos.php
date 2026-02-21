<?php
// actions/stop_sos.php
require_once '../settings/core.php';
require_once '../controllers/incident_controller.php';

// Wipe accidental spaces
if (ob_get_length()) ob_clean(); 
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $incident_id = $_POST['incident_id'] ?? null;

    if ($incident_id) {
        $result = stop_sos_ctr($incident_id, "RESPONDER_ARRIVED");
        
        if ($result) {
            echo json_encode(["status" => "success"]);
        } else {
            // This helps you find the error in the Network Tab
            http_response_code(500); 
            echo json_encode(["status" => "error", "message" => "Database rejected the update."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "No ID provided."]);
    }
    exit;
}