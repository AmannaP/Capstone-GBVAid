<?php
// actions/fetch_product_action.php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

header('Content-Type: application/json');

// Check if a single product ID is requested
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $product = view_single_product_ctr($id);

    if ($product) {
        $product['product_desc'] = html_entity_decode(stripslashes($product['product_desc'] ?? ''));
        echo json_encode([
            "status" => "success",
            "product" => $product
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Product not found."
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
    // Fetch filtered + paginated products
    $products = fetch_filtered_products_ctr($search, $cat_id, $brand_id, $limit, $offset);

    // Count total products for pagination
    $total_count = count_total_products_ctr($search, $cat_id, $brand_id);
    $total_count = is_array($total_count) ? ($total_count['total'] ?? 0) : (int)$total_count;
    $total_count = (int)$total_count;

    // Assign default image where missing
    foreach ($products as &$p) {
        if (empty($p['product_image'])) {
            $p['product_image'] = 'default.jpg';
        }
    }

    echo json_encode([
        "status" => "success",
        "products" => $products,
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
