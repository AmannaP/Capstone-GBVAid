<?php

// classes/service_class.php
require_once '../settings/db_class.php';

class Service extends db_conn {

    // Get all services (join category & brand names)
    public function getAllServices() {
        $sql = "
            SELECT p.*, c.cat_name, b.brand_name
            FROM services p
            LEFT JOIN categories c ON p.service_cat = c.cat_id
            LEFT JOIN brands b ON p.service_brand = b.brand_id
            ORDER BY p.service_id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add service (returns array with status/message)
    public function addService($cat_id, $brand_id, $title, $price, $description, $image_name, $keywords, $user_id) {
        try {
            $sql = "INSERT INTO services (service_cat, service_brand, service_title, service_price, service_desc, service_image, service_keywords, created_by)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$cat_id, $brand_id, $title, $price, $description, $image_name, $keywords, $user_id]);
            return ["status" => "success", "message" => "Service added."];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => "DB error: " . $e->getMessage()];
        }
    }

    // Update service image
    public function updateServiceImage($service_id, $image_name) {
        try {
            $sql = "UPDATE services SET service_image = ? WHERE service_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$image_name, $service_id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Update service (if $image_name is null, don't change)
    public function updateService($service_id, $cat_id, $brand_id, $title, $price, $description, $image_name = null, $keywords = null) {
        try {
            if ($image_name !== null) {
                $sql = "UPDATE services SET service_cat = ?, service_brand = ?, service_title = ?, service_price = ?, service_desc = ?, service_image = ?, service_keywords = ? WHERE service_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$cat_id, $brand_id, $title, $price, $description, $image_name, $keywords, $service_id]);
            } else {
                $sql = "UPDATE services SET service_cat = ?, service_brand = ?, service_title = ?, service_price = ?, service_desc = ?, service_keywords = ? WHERE service_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$cat_id, $brand_id, $title, $price, $description, $keywords, $service_id]);
            }

            if ($stmt->rowCount() > 0) {
                return ["status" => "success", "message" => "Service updated."];
            } else {
                return ["status" => "error", "message" => "No changes made or service not found."];
            }
        } catch (PDOException $e) {
            return ["status" => "error", "message" => "DB error: " . $e->getMessage()];
        }
    }

    // Get single service
    public function getServiceById($service_id) {
        $sql = "SELECT * FROM services WHERE service_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$service_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // View all services
    public function view_all_services() {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM services p
                LEFT JOIN categories c ON p.service_cat = c.cat_id
                LEFT JOIN brands b ON p.service_brand = b.brand_id
                ORDER BY p.service_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a single service by ID
     */
    public function get_one_service($id) {
        // Prepare query (using join to get category/brand names if needed)
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM services p
                LEFT JOIN categories c ON p.service_cat = c.cat_id
                LEFT JOIN brands b ON p.service_brand = b.brand_id
                WHERE p.service_id = '$id'";
        
        return $this->db_fetch_one($sql);
    }

    // View single service
    public function view_single_service($id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM services p
                LEFT JOIN categories c ON p.service_cat = c.cat_id
                LEFT JOIN brands b ON p.service_brand = b.brand_id
                WHERE p.service_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Search services by title or keywords
    public function search_services($query) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM services p
                LEFT JOIN categories c ON p.service_cat = c.cat_id
                LEFT JOIN brands b ON p.service_brand = b.brand_id
                WHERE p.service_title LIKE ? OR p.service_keywords LIKE ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $query . '%', '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Filter by category
    public function filter_services_by_category($cat_id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM services p
                LEFT JOIN categories c ON p.service_cat = c.cat_id
                LEFT JOIN brands b ON p.service_brand = b.brand_id
                WHERE p.service_cat = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cat_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Filter by brand
    public function filter_services_by_brand($brand_id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM services p
                LEFT JOIN categories c ON p.service_cat = c.cat_id
                LEFT JOIN brands b ON p.service_brand = b.brand_id
                WHERE p.service_brand = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$brand_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Fetch services with filters and pagination
    public function getFilteredServices($search = '', $cat_id = 0, $brand_id = 0, $limit = 9, $offset = 0) {
    $sql = "SELECT p.*, c.cat_name, b.brand_name 
            FROM services p
            LEFT JOIN categories c ON p.service_cat = c.cat_id
            LEFT JOIN brands b ON p.service_brand = b.brand_id
            WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (p.service_title LIKE :search OR p.service_keywords LIKE :search2)";
        $params[':search'] = "%$search%";
        $params[':search2'] = "%$search%";
    }

    if ($cat_id > 0) {
        $sql .= " AND p.service_cat = :cat_id";
        $params[':cat_id'] = $cat_id;
    }

    if ($brand_id > 0) {
        $sql .= " AND p.service_brand = :brand_id";
        $params[':brand_id'] = $brand_id;
    }

    $sql .= " ORDER BY p.service_id DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($sql);
    // Bind values safely
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count total services for pagination
    public function countFilteredServices($search = '', $cat_id = 0, $brand_id = 0) {
        $sql = "SELECT COUNT(*) AS total 
                FROM services p
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (p.service_title LIKE :search OR p.service_keywords LIKE :search2)";
            $params[':search'] = "%$search%";
            $params[':search2'] = "%$search%";
        }

        if ($cat_id > 0) {
            $sql .= " AND p.service_cat = :cat_id";
            $params[':cat_id'] = $cat_id;
        }

        if ($brand_id > 0) {
            $sql .= " AND p.service_brand = :brand_id";
            $params[':brand_id'] = $brand_id;
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    }

    // Delete service
    public function deleteService($service_id) {
        try {
            $sql = "DELETE FROM services WHERE service_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$service_id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>
