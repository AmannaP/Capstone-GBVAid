<?php
// classes/report_class.php
require_once '../settings/db_class.php';

class Report extends db_conn {
    
    // Get all reports joined with victim data
    public function get_all_reports() {
        if (!$this->db_connect()) return [];
        
        $sql = "SELECT r.*, v.victim_name, v.victim_contact 
                FROM reports r
                LEFT JOIN victim v ON r.victim_id = v.victim_id
                ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update report status
    public function update_status($id, $status) {
        if (!$this->db_connect()) return false;
        
        $sql = "UPDATE reports SET status = ? WHERE report_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    // Class for creating new Report record
    public function create($uid, $type, $date, $loc, $desc, $anon) {
        if (!$this->db_connect()) return false;
        
        $sql = "INSERT INTO reports (victim_id, incident_type, incident_date, location, description, is_anonymous) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$uid, $type, $date, $loc, $desc, $anon]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>