<?php
// classes/brand_class.php
require_once '../settings/db_class.php';

class Brand extends db_conn {

    /**
     * Fetch all Service Providers (Brands)
     */
    public function getAllBrands() {
        // FIX: Removed JOIN with categories because cat_id does not exist in brands table.
        // We simply fetch all providers.
        $sql = "SELECT brand_id, brand_name FROM brands";
        
        // Using standard db_class fetch all method for consistency with your MVC
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

        // FIX: Removed cat_id and created_by as they aren't in your schema
        $sql = "INSERT INTO brands (brand_name) VALUES ('$brand_name')";
        
        if($this->db_query($sql)) {
            return ["status" => "success", "message" => "Provider added successfully."];
        }
        
        return ["status" => "error", "message" => "Database error."];
    }

    /**
     * Update brand name
     */
    public function updateBrand($brand_id, $new_name) {
        $sql = "UPDATE brands SET brand_name = '$new_name' WHERE brand_id = '$brand_id'";
        
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