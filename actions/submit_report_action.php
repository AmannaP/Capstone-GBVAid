<?php
// actions/submit_report_action.php
session_start();
require_once '../settings/db_class.php';
require_once '../classes/report_class.php';

// Return JSON response for AJAX
header('Content-Type: application/json');

// 1. Security Check: Ensure user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../login/login.php");
    exit();
}

// 2. Data Capture
$uid  = $_SESSION['id'];
$type = htmlspecialchars(strip_tags($_POST['incident_type'] ?? ''));
$date = $_POST['incident_date'] ?? null;
$loc  = htmlspecialchars(strip_tags($_POST['location'] ?? ''));
$desc = htmlspecialchars(strip_tags($_POST['description'] ?? ''));
$anon = isset($_POST['is_anonymous']) ? 1 : 0;

// Validation
if (empty($type) || empty($date) || empty($desc)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    exit();
}

$reportObj = new Report();
if ($reportObj->create($uid, $type, $date, $loc, $desc, $anon)) {
    // Return a clean JSON success message
    echo json_encode(['status' => 'success', 'message' => 'Report submitted successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error. Please try again.']);
}
exit();