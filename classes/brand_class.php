<?php
// classes/brand_class.php
require_once '../settings/db_class.php';

class Brand extends db_conn {

    /**
     * Fetch all Service Providers (Brands)
     */
    public function getAllBrands() {
        // fetch all providers.
        $sql = "SELECT b.brand_id, b.brand_name, b.cat_id, c.cat_name 
                FROM brands b 
                LEFT JOIN categories c ON b.cat_id = c.cat_id 
                ORDER BY b.brand_id DESC";        
        // Using standard db_class fetch all method for consistency
        return $this->db_fetch_all($sql);
    }

    /**
     * Add a new Service Provider
     */
    public function addBrand($brand_name) {
        // Check if brand already exists
        $checkSql = "SELECT * FROM brands WHERE brand_name = '$brand_name'";
        $exists = $this->db_fetch_one($checkSql);

        if ($exists) {
            return ["status" => "error", "message" => "This provider already exists."];
        }
        $sql = "INSERT INTO brands (brand_name) VALUES ('$brand_name')";
        if($this->db_query($sql)) {
            return ["status" => "success", "message" => "Provider added successfully."];
        }
        
        return ["status" => "error", "message" => "Database error."];
    }

    /**
     * Update brand name
     */
    public function updateBrand($brand_id, $new_name, $cat_id) {
        $sql = "UPDATE brands SET brand_name = '$new_name', cat_id = '$cat_id' WHERE brand_id = '$brand_id'";
        
        if($this->db_query($sql)) {
            return ["status" => "success", "message" => "Provider updated successfully."];
        }

        return ["status" => "error", "message" => "Update failed."];
    }

    /**
     * Delete a brand
     */
    public function deleteBrand($brand_id) {
        $sql = "DELETE FROM brands WHERE brand_id = '$brand_id'";
        
        if($this->db_query($sql)) {
            return ["status" => "success", "message" => "Provider deleted successfully."];
        }

        return ["status" => "error", "message" => "Delete failed."];
    }

    /**
     * Fetch a single brand
     */
    public function getBrandById($brand_id) {
        $sql = "SELECT * FROM brands WHERE brand_id = '$brand_id'";
        return $this->db_fetch_one($sql);
    }
}
?>