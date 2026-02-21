<?php
// actions/add_product_action.php

require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

header('Content-Type: application/json');

// Auth check
if (!checkLogin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['id'] ?? null;

// Accept multipart/form-data
$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
$title = isset($_POST['product_title']) ? trim($_POST['product_title']) : '';
$price = isset($_POST['product_price']) ? trim($_POST['product_price']) : '';
$description = isset($_POST['product_desc']) ? trim($_POST['product_desc']) : '';
$keywords = isset($_POST['product_keywords']) ? trim($_POST['product_keywords']) : '';

// basic validation
if ($cat_id <= 0 || $brand_id <= 0 || empty($title) || $price === '') {
    echo json_encode(["status" => "error", "message" => "Please provide all required fields."]);
    exit;
}

// Handle product image
$upload_dir = "../uploads/products/";
$default_image = "default.jpg"; 

// Check if user uploaded a file
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['product_image']['tmp_name'];
    $file_name = basename($_FILES['product_image']['name']);
    $target_path = $upload_dir . $file_name;

    // Validate file type (optional but safe)
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (in_array($ext, $allowed)) {
        if (move_uploaded_file($file_tmp, $target_path)) {
            $product_image = $file_name;
        } else {
            $product_image = $default_image;
        }
    } else {
        $product_image = $default_image;
    }
} else {
    // No file uploaded → use default image
    $product_image = $default_image;
}

// Validate required fields
if (empty($title) || empty($price)) {
    echo json_encode(["status" => "error", "message" => "Please provide all required fields."]);
    exit;
}

// Add product via controller
$result = add_product_ctr(
    $cat_id,
    $brand_id,
    $title,
    $price,
    $description,
    $product_image,
    $keywords,
    $user_id
);


if ($result) {
    echo json_encode(["status" => "success", "message" => "Product added successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add product."]);
}
exit;
?>

