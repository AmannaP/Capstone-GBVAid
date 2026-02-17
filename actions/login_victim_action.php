<?php
// actions/login_victim_action.php

// Keep errors on for debugging so we can see the REAL error in Console -> Response
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../settings/core.php';
require_once '../controllers/victim_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Authenticate user
    $user = login_victim_ctr($email, $password);

    if ($user && (isset($user['victim_id']) || isset($user['id']))){
        // Create session
        $_SESSION['id'] = $user['victim_id'];
        $_SESSION['name'] = $user['victim_name'];
        // Using a fallback for role in case it's not in your DB yet
        $_SESSION['role'] = $user['user_role'] ?? 2; 
        $_SESSION['user_image'] = $user['victim_image'] ?? null;

        // --- SUCCESS RESPONSE (This was commented out!) ---
        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "role" => $_SESSION['role']
        ]);
        exit; // Exit here after successful response
    } else {
        // --- FAILURE RESPONSE ---
        echo json_encode([
            "success" => false,
            "message" => "Invalid email or password"
        ]);
        exit;
    }
}

// Default response if not POST
echo json_encode(["success" => false, "message" => "Invalid request method"]);
?>