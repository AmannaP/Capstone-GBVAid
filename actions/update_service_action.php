<?php
// actions/update_service_action.php

require_once '../settings/core.php';
require_once '../controllers/service_controller.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}
$user_id = $_SESSION['id'];

$service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
$title = isset($_POST['service_title']) ? trim($_POST['service_title']) : '';
$price = isset($_POST['service_price']) ? trim($_POST['service_price']) : '';
$description = isset($_POST['service_desc']) ? trim($_POST['service_desc']) : '';
$keywords = isset($_POST['service_keywords']) ? trim($_POST['service_keywords']) : '';
$upload_dir = "../uploads/services/";
$default_image = "default.jpg";

if ($service_id <= 0 || $cat_id <= 0 || $brand_id <= 0 || empty($title) || $price === '') {
    echo json_encode(["status" => "error", "message" => "Please provide valid service details."]);
    exit;
}
// Handle new image upload
if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['service_image']['tmp_name'];
    $file_name = basename($_FILES['service_image']['name']);
    $target_path = $upload_dir . $file_name;

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {
        if (move_uploaded_file($file_tmp, $target_path)) {
            $service_image = $file_name;
        } else {
            $service_image = $default_image;
        }
    } else {
        $service_image = $default_image;
    }
} else {
    // No new upload → keep current image from DB
    $current = get_service_by_id_ctr($service_id);
    $service_image = $current ? $current['service_image'] : $default_image;
}

// Update the service
$result = update_service_ctr(
    $service_id,
    $cat_id,
    $brand_id,
    $title,
    $price,
    $description,
    $service_image,
    $keywords,
    $user_id
);

if ($result) {
    echo json_encode(["status" => "success", "message" => "Service updated successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update service."]);
}
exit;
?>