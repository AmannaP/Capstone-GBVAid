<?php
// actions/fetch_booked_slots.php
require_once '../settings/core.php';
require_once '../settings/db_class.php';

header('Content-Type: application/json');

if (!isset($_GET['service_id']) || !isset($_GET['date'])) {
    echo json_encode([]);
    exit;
}

$service_id = $_GET['service_id'];
$date = $_GET['date'];

$db = new db_conn();
if ($db->db_connect()) {
    $sql = "SELECT appointment_time FROM appointments WHERE service_id = ? AND appointment_date = ? AND status != 'Cancelled'";
    $stmt = $db->db->prepare($sql);
    $stmt->execute([$service_id, $date]);
    $slots = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Convert generic times like "09:00:00" to "09:00"
    $formatted = array_map(function($t) {
        return substr($t, 0, 5);
    }, $slots);
    
    echo json_encode($formatted);
} else {
    echo json_encode([]);
}
?>
