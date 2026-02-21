<?php
// actions/fetch_service_action.php
require_once '../settings/core.php';
require_once '../controllers/service_controller.php';

header('Content-Type: application/json');

// Check if a single service ID is requested
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $service = view_single_service_ctr($id);

    if ($service) {
        $service['service_desc'] = html_entity_decode(stripslashes($service['service_desc'] ?? ''));
        echo json_encode([
            "status" => "success",
            "service" => $service
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Service not found."
        ]);
    }
    exit;
}

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

// Filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$brand_id = isset($_GET['brand_id']) ? intval($_GET['brand_id']) : 0;

try {
    // Fetch filtered + paginated services
    $services = fetch_filtered_services_ctr($search, $cat_id, $brand_id, $limit, $offset);

    // Count total services for pagination
    $total_count = count_total_services_ctr($search, $cat_id, $brand_id);
    $total_count = is_array($total_count) ? ($total_count['total'] ?? 0) : (int)$total_count;
    $total_count = (int)$total_count;

    // Assign default image where missing
    foreach ($services as &$s) {
        if (empty($s['service_image'])) {
            $s['service_image'] = 'default.jpg';
        }
    }

    echo json_encode([
        "status" => "success",
        "services" => $services,
        "total_count" => $total_count,
        "current_page" => $page,
        "total_pages" => ceil($total_count / $limit)
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
