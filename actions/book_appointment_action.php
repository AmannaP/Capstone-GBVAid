<?php
// actions/book_appointment_action.php
require_once '../settings/core.php';
require_once '../controllers/appointment_controller.php';

header('Content-Type: application/json');

// Verify that the user is logged in
if (!checkLogin()) {
    echo json_encode(['status' => 'error', 'message' => 'Your session has expired. Please login again.']);
    exit();
}

// Retrieve the authenticated user ID from the session
$uid = getUserId(); 
$sid = $_POST['service_id'] ?? null;
$date = $_POST['date'] ?? null;
$time = $_POST['time'] ?? null;
$notes = htmlspecialchars($_POST['notes'] ?? '');

if (empty($date) || empty($time) || empty($sid)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in the date and time.']);
    exit();
}

// Call the controller
$result = book_appointment_ctr($uid, $sid, $date, $time, $notes);

if ($result === "taken") {
    echo json_encode(['status' => 'error', 'message' => 'Sorry, this time slot is already booked.']);
} elseif ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Appointment booked successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to book appointment. Please check your database.']);
}