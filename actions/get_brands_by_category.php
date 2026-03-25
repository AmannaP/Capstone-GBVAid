<?php
/**
 * AJAX Endpoint: Fetch brands for a specific category
 * This file is called by register.js when the Category dropdown changes.
 */

// 1. Database Connection
require_once '../settings/db_class.php';

// Set header to JSON for AJAX compatibility
header('Content-Type: application/json');

// 2. Check for Input
if (isset($_GET['cat_id']) && !empty($_GET['cat_id'])) {
    
    $db = new db_conn();
    
    if (!$db->db_connect()) {
        // Return 500 error for AJAX to catch in 'error' block
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit();
    }

    $cat_id = (int)$_GET['cat_id'];

    /**
     * 3. SQL Query
     * IMPORTANT: Check your 'brands' table in the database.
     * If your columns are named differently, change 'brand_id' and 'brand_name' below.
     */
    $sql = "SELECT brand_id AS id, brand_name AS name 
            FROM brands 
            WHERE cat_id = $cat_id 
            ORDER BY brand_name ASC";

    $results = $db->db_fetch_all($sql);

    // 4. Return Output
    // If no results, returns empty array [] which JS handles as "No organizations found"
    echo json_encode($results ?: []);

} else {
    // No category ID provided
    echo json_encode([]);
}
?>