<?php
// actions/fetch_brand_action.php

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

try {
    // Fetch brands from controller
    $brands = fetch_brands_ctr();

    if ($brands && count($brands) > 0) {
        echo json_encode([
            "status" => "success",
            "brands" => $brands
        ]);
    } else {
        echo json_encode([
            "status" => "success",
            "brands" => [],
            "message" => "No brands found."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch brands: " . $e->getMessage()
    ]);
}
exit;
?>
