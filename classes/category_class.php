<?php
require_once '../settings/db_class.php';

class Category extends db_conn {

    // Get all categories (since there’s no created_by)
    public function getCategories() {
        $sql = "SELECT cat_id, cat_name FROM categories ORDER BY cat_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add category
    public function addCategory($cat_name, $user_id) {
        try {
            $sql = "INSERT INTO categories (cat_name, created_by) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$cat_name, $user_id]);
        } catch (PDOException $e) {
            return false; // likely duplicate name
        }
    }

    // Update category
    public function updateCategory($cat_id, $new_name) {
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$new_name, $cat_id]);
    }

    // Delete category
    public function deleteCategory($cat_id) {
        $sql = "DELETE FROM categories WHERE cat_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cat_id]);
    }
}
?>
