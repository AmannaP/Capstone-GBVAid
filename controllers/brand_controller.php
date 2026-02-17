<?php
// controllers/brand_controller.php
require_once '../classes/brand_class.php';

/**
 * Controller layer for managing Brand operations
 * Connects the front-end action scripts to Brand class methods
 */

// ADD a new brand
function add_brand_ctr($brand_name, $cat_id, $user_id) {
    $brand = new Brand();
    return $brand->addBrand($brand_name, $cat_id, $user_id);
}
// FETCH all brands
function fetch_brands_ctr() {
    $brand = new Brand();
    return $brand->getAllBrands();
}

// UPDATE a brand
function update_brand_ctr($brand_id, $new_name) {
    $brand = new Brand();
    return $brand->updateBrand($brand_id, $new_name);
}

// DELETE a brand
function delete_brand_ctr($brand_id) {
    $brand = new Brand();
    return $brand->deleteBrand($brand_id);
}

// GET a single brand (optional helper)
function get_brand_by_id_ctr($brand_id) {
    $brand = new Brand();
    return $brand->getBrandById($brand_id);
}
?>
