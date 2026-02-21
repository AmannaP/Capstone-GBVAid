<?php
// classes/incident_class.php
require_once '../settings/db_class.php';

class Incident extends db_conn {

    public function saveSOSLocation($victim_id, $lat, $lon) {
        // Ensure table name 'incidents' matches your phpMyAdmin
        $sql = "INSERT INTO incidents (victim_id, lat, lon, status) VALUES (?, ?, ?, 'ACTIVE')";
        return $this->db_query($sql, [$victim_id, $lat, $lon]);
    }

    /**
     * Stop sharing / Close incident
     */

    public function closeIncident($incident_id, $reason = "ARRIVED") {
        $id = intval($incident_id);
        
        // Use the exact SQL that worked in your phpMyAdmin test
        $sql = "UPDATE incidents SET status = 'CLOSED' WHERE incident_id = ?";
        
        // Ensure this returns the result of the execution
        return $this->db_query($sql, [$id]);
    }

    /**
     * Get count of active incidents for Admin notification
     */
    public function getActiveCount() {
        $sql = "SELECT COUNT(*) as total FROM incidents WHERE status = 'ACTIVE'";
        $result = $this->db_fetch_one($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Get the latest ID for dynamic simulation
     */
    public function getLatestActiveId() {
        $sql = "SELECT incident_id FROM incidents WHERE status = 'ACTIVE' ORDER BY incident_id DESC LIMIT 1";
        $result = $this->db_fetch_one($sql);
        return $result['incident_id'] ?? 0;
    }

    /**
     * Get all active incidents with victim details for Admin dashboard
     */
    public function getActiveIncidents() {
        $sql = "SELECT i.incident_id, i.lat, i.lon, i.created_at, v.victim_name, v.victim_contact 
                FROM incidents i 
                JOIN victim v ON i.victim_id = v.victim_id 
                WHERE i.status = 'ACTIVE' 
                ORDER BY i.created_at DESC";
        
        // Using db_fetch_all to be consistent with your architecture
        return $this->db_fetch_all($sql);
    } 

} 