<?php
// actions/fetch_service_action.php
require_once '../settings/core.php';
require_once '../controllers/service_controller.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'view_all':
        echo json_encode([
            "status" => "success",
            "services" => view_all_services_ctr()
        ]);
        break;

    case 'view_single':
        $id = intval($_GET['id'] ?? 0);
        echo json_encode(view_single_service_ctr($id));
        break;

    case 'search':
        $query = trim($_GET['query'] ?? '');
        echo json_encode([
            "status" => "success",
            "services" => search_services_ctr($query)
        ]);
        break;

    case 'filter_category':
        $cat_id = intval($_GET['cat_id'] ?? 0);
        echo json_encode([
            "status" => "success",
            "services" => filter_services_by_category_ctr($cat_id)
        ]);
        break;

    case 'filter_brand':
        $brand_id = intval($_GET['brand_id'] ?? 0);
        echo json_encode([
            "status" => "success",
            "services" => filter_services_by_brand_ctr($brand_id)
        ]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action"]);
        break;
}
?>
