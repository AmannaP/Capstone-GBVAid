<?php
// actions/delete_category_action.php

require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = $_POST['cat_id'] ?? null;

    if (!$cat_id) {
        echo json_encode(["status" => "error", "message" => "Category ID is required"]);
        exit;
    }

    $user_id = $_SESSION['id'];
    $result = delete_category_ctr($cat_id, $user_id);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Category deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete category"]);
    }
}
