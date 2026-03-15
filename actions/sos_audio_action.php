<?php
// actions/sos_audio_action.php
require_once '../settings/core.php';
require_once '../classes/incident_class.php';

// Allow from any origin (to handle chunking gracefully if CORS kicks in somehow)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // We expect 'incident_id' via POST and an 'audio_data' file upload
    $incident_id = filter_input(INPUT_POST, 'incident_id', FILTER_VALIDATE_INT);
    
    if (!$incident_id) {
        echo json_encode(["status" => "error", "message" => "Invalid Incident ID."]);
        exit;
    }

    if (!isset($_FILES['audio_data']) || $_FILES['audio_data']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["status" => "error", "message" => "No audio received or upload error."]);
        exit;
    }

    // Set up the upload directory
    $uploadDir = '../uploads/sos_audio/';
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate a unique filename: incidentID_timestamp.webm
    $timestamp = time();
    $uniq = uniqid();
    $fileName = "incident_{$incident_id}_{$timestamp}_{$uniq}.webm";
    $uploadPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['audio_data']['tmp_name'], $uploadPath)) {
        // Save to DB
        $incident_obj = new Incident();
        $db_result = $incident_obj->saveIncidentAudio($incident_id, $fileName);

        if ($db_result) {
            echo json_encode(["status" => "success", "message" => "Audio chunk saved safely.", "file" => $fileName]);
        } else {
            // If DB insert fails, we should probably delete the physical file to save space
            unlink($uploadPath);
            echo json_encode(["status" => "error", "message" => "Failed to log audio in database."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to move uploaded audio file."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
