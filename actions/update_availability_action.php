<?php
/**
 * actions/update_availability_action.php
 * Updates SP availability status, note, and weekly schedule.
 */
require_once '../settings/core.php';
require_once '../settings/db_class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id']) || $_SESSION['role'] != 3) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit();
}

$sp_id   = (int) $_SESSION['id'];
$status  = in_array($_POST['status'] ?? '', ['available','busy','unavailable'])
           ? $_POST['status']
           : 'available';
$note    = trim(substr($_POST['note'] ?? '', 0, 120));

// Build schedule JSON
$raw_days = $_POST['days'] ?? [];
$schedule = [];
$day_names = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
foreach ($day_names as $d) {
    $schedule[$d] = [
        'on'    => isset($raw_days[$d]['on']) ? 1 : 0,
        'start' => $raw_days[$d]['start'] ?? '09:00',
        'end'   => $raw_days[$d]['end']   ?? '17:00',
    ];
}
$schedule_json = json_encode($schedule);

try {
    $db = new db_conn();
    $stmt = $db->db->prepare(
        "UPDATE victim 
         SET sp_availability = ?, sp_availability_note = ?, sp_schedule = ?
         WHERE victim_id = ?"
    );
    $stmt->execute([$status, $note, $schedule_json, $sp_id]);

    echo json_encode(['status' => 'success', 'message' => 'Availability updated successfully.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
