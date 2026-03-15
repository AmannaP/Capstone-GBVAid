<?php
require_once '../settings/core.php';
require_once '../classes/incident_class.php';

// Allow admin dashboard polling
header('Content-Type: application/json');

$incident_id = filter_input(INPUT_GET, 'incident_id', FILTER_VALIDATE_INT);
$last_audio_id = filter_input(INPUT_GET, 'last_audio_id', FILTER_VALIDATE_INT);

// Default to 0 if not provided
if ($last_audio_id === null || $last_audio_id === false) {
    $last_audio_id = 0;
}

if (!$incident_id) {
    echo json_encode(["status" => "error", "message" => "Missing incident_id"]);
    exit;
}

$incident_obj = new Incident();
$chunks = $incident_obj->getIncidentAudio($incident_id, $last_audio_id);

echo json_encode([
    "status" => "success",
    "chunks" => $chunks
]);
?>
