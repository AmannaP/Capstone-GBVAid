<?php
// controllers/service_controller.php
require_once '../classes/service_class.php';

function fetch_services_ctr() {
    $p = new service();
    return $p->getAllservices();
}

// Fetch services with filters and pagination
function fetch_filtered_services_ctr($search = '', $cat_id = 0, $brand_id = 0, $limit = 9, $offset = 0) {
    $service = new service();
    return $service->getFilteredservices($search, $cat_id, $brand_id, $limit, $offset);
}

// Count total services for pagination
function count_total_services_ctr($search = '', $cat_id = 0, $brand_id = 0) {
    $service = new service();
    return $service->countFilteredservices($search, $cat_id, $brand_id);
}

function add_service_ctr($cat_id, $brand_id, $title, $price, $description, $image_name, $keywords, $user_id = null) {
    $p = new service();
    return $p->addservice($cat_id, $brand_id, $title, $price, $description, $image_name, $keywords, $user_id);
}

function update_service_ctr($service_id, $cat_id, $brand_id, $title, $price, $description, $image_name = null, $keywords = null) {
    $p = new service();
    return $p->updateservice($service_id, $cat_id, $brand_id, $title, $price, $description, $image_name, $keywords);
}

function get_service_by_id_ctr($service_id) {
    $p = new service();
    return $p->getserviceById($service_id);
}
function view_all_services_ctr() {
    $service = new service();
    return $service->view_all_services();
}

function view_single_service_ctr($id) {
    $service = new service();
    return $service->view_single_service($id);
}

function search_services_ctr($query) {
    $service = new service();
    return $service->search_services($query);
}

function filter_services_by_category_ctr($cat_id) {
    $service = new service();
    return $service->filter_services_by_category($cat_id);
}

function filter_services_by_brand_ctr($brand_id) {
    $service = new service();
    return $service->filter_services_by_brand($brand_id);
}

function update_service_image_ctr($service_id, $image_path) {
    return update_service_image_cls($service_id, $image_path);
}

function delete_service_ctr($service_id) {
    $p = new service();
    return $p->deleteservice($service_id);
}

/**
 * Controller to get a single service
 */
function get_one_service_ctr($id) {
    $service_instance = new service();
    return $service_instance->get_one_service($id);
}

?>
