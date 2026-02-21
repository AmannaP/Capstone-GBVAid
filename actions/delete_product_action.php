<?php
// actions/delete_product_action.php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

header('Content-Type: application/json');

// Ensure admin access
if (!checkLogin() || !isAdmin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid product ID."]);
    exit;
}

$product_id = intval($_POST['id']);

try {
    $result = delete_product_ctr($product_id);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Product deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete product."]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
