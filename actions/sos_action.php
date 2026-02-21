<?php
// actions/sos_action.php
require_once '../settings/core.php';
require_once '../controllers/incident_controller.php';

// Ensure session is started if core.php doesn't do it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $lat = filter_input(INPUT_POST, 'lat', FILTER_VALIDATE_FLOAT);
    $lon = filter_input(INPUT_POST, 'lon', FILTER_VALIDATE_FLOAT);
    
    // Get victim ID from session (Ensure your login script sets $_SESSION['user_id'] or 'id')
    $victim_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? null; 

    if ($lat !== false && $lon !== false) {
        // Call the controller function
        $result = save_sos_ctr($victim_id, $lat, $lon);

        if ($result) {
            echo json_encode([
                "status" => "success", 
                "message" => "SOS Signal Transmitted. Help is being dispatched to your location."
            ]);
        } else {
            echo json_encode([
                "status" => "error", 
                "message" => "Database error: Could not record signal."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Incomplete or invalid coordinate data."
        ]);
    }
    exit;
}