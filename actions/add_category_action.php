<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

header('Content-Type: application/json');

// Check if logged in
if (!checkLogin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = trim($_POST['cat_name'] ?? '');

    if (empty($cat_name)) {
        echo json_encode(["status" => "error", "message" => "Category name cannot be empty"]);
        exit;
    }

    $user_id = $_SESSION['id'];
    $result = add_category_ctr($cat_name, $user_id);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Category added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add category (maybe duplicate?)"]);
    }
}
