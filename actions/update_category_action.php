<?php
// actions/update_brand_action.php

require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

header('Content-Type: application/json');

// Ensure user is logged in
if (!checkLogin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

// Sanitize inputs
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
$brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';

if ($brand_id <= 0 || empty($brand_name)) {
    echo json_encode(["status" => "error", "message" => "Please provide valid brand details to update."]);
    exit;
}

try {
    $result = update_brand_ctr($brand_id, $brand_name);
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Server error: " . $e->getMessage()]);
}
exit;
?>
