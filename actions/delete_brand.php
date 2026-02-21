<?php
// actions/delete_brand_action.php

require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

header('Content-Type: application/json');

// Ensure user is logged in
if (!checkLogin()) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access. Please log in first."
    ]);
    exit;
}

$user_id = $_SESSION['id'];

// Receive brand ID or name
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
$brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : "";

// Validate input
if ($brand_id <= 0 && empty($brand_name)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please provide a valid brand ID or name."
    ]);
    exit;
}

// Invoke the controller function
$result = delete_brand_ctr($brand_id, $brand_name, $user_id);

// Response
if ($result) {
    echo json_encode([
        "status" => "success",
        "message" => "Brand deleted successfully."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to delete brand. Please try again."
    ]); 
}

exit;
?>