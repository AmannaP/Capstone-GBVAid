<?php
// actions/register_user_action.php

// ENABLE ERROR REPORTING FOR DEBUGGING
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../controllers/user_controller.php';

header('Content-Type: application/json');

session_start();

$response = array();

// Check login status
if (isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You are already logged in']);
    exit();
}

// Check if POST variables exist
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit();
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$country = $_POST['country'];
$city = $_POST['city'];
$phone_number = $_POST['phone_number'];
$role = $_POST['role'];

// Call Controller
try {
    $user_id = register_user_ctr($name, $email, $password, $country, $city, $phone_number, $role);

    if ($user_id) {
        $response['status'] = 'success';
        $response['message'] = 'Registered successfully';
        $response['victim_id'] = $user_id;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to register. Email might already exist.';
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Server Error: ' . $e->getMessage();
}

echo json_encode($response);
?>