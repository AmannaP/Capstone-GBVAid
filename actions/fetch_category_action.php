<?php
// actions/fetch_category_action.php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access. Please login first."
    ]);
    exit;
}

$categories = fetch_categories_ctr();

if ($categories && count($categories) > 0) {
    echo json_encode([
        "status" => "success",
        "categories" => $categories
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "categories" => [],
        "message" => "No categories found."
    ]);
}
exit;
?>
