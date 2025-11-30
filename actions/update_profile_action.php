<?php
// actions/update_profile_action.php
session_start();
require_once '../controllers/customer_controller.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['id'];
$name = $_POST['full_name'] ?? '';
$contact = $_POST['phone_number'] ?? '';
$city = $_POST['city'] ?? '';
$country = $_POST['country'] ?? '';

// Basic validation
if (empty($name) || empty($contact)) {
    echo json_encode(['status' => 'error', 'message' => 'Name and Phone are required']);
    exit();
}

// You need to add this function to your controller/class
// Assuming you will add update_customer_ctr($id, $name, $contact, $city, $country)
$result = update_customer_ctr($user_id, $name, $contact, $city, $country);

if ($result) {
    // Update session name if changed
    $_SESSION['name'] = $name;
    echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
}
?>