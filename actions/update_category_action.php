<?php
// actions/update_category_action.php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = $_POST['cat_id'] ?? null;
    $new_name = trim($_POST['new_name'] ?? '');

    if (!$cat_id || empty($new_name)) {
        echo json_encode(["status" => "error", "message" => "Category ID and new name required"]);
        exit;
    }

    $user_id = $_SESSION['id'];
    $result = update_category_ctr($cat_id, $new_name, $user_id);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Category updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update category"]);
    }
}
