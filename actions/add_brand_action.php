<?php
// actions/add_brand_action.php

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

// Sanitize brand name input
$brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';
$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;

if (empty($brand_name)|| $cat_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Please provide a valid brand name and category."
    ]);
    exit;
}

try {
    $result = add_brand_ctr($brand_name, $cat_id, $user_id);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Brand added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add brand (possibly duplicate)."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
exit;
?>
