<?php
// controllers/category_controller.php
require_once '../classes/category_class.php';

// Fetch all categories
function fetch_categories_ctr() {
    $category = new Category();
    return $category->getCategories();
}

// Add category
function add_category_ctr($cat_name, $user_id) {
    $category = new Category();
    return $category->addCategory($cat_name, $user_id);
}

// Update category
function update_category_ctr($cat_id, $new_name) {
    $category = new Category();
    return $category->updateCategory($cat_id, $new_name);
}

// Delete category
function delete_category_ctr($cat_id) {
    $category = new Category();
    return $category->deleteCategory($cat_id);
}
?>
