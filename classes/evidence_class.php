<?php
// classes/evidence_class.php
require_once '../settings/db_class.php';

class Evidence extends db_conn {
    
    // Add new evidence (File or Text)
    public function addEvidence($victim_id, $title, $description, $file_path, $file_type, $raw_text_content) {
        $sql = "INSERT INTO evidence (victim_id, title, description, file_path, file_type, raw_text_content) 
                VALUES (?, ?, ?, ?, ?, ?)";
        return $this->db_query($sql, [$victim_id, $title, $description, $file_path, $file_type, $raw_text_content]);
    }
    
    // Get all evidence for a specific victim
    public function getVictimEvidence($victim_id) {
        $sql = "SELECT * FROM evidence WHERE victim_id = ? ORDER BY uploaded_at DESC";
        $this->db_query($sql, [$victim_id]);
        return $this->results->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Delete evidence
    public function deleteEvidence($evidence_id, $victim_id) {
        // We check victim_id to ensure a user only deletes their own evidence
        $sql = "DELETE FROM evidence WHERE evidence_id = ? AND victim_id = ?";
        return $this->db_query($sql, [$evidence_id, $victim_id]);
    }
    
    // Get single evidence details (useful for deleting files off server)
    public function getEvidenceById($evidence_id, $victim_id) {
        $sql = "SELECT * FROM evidence WHERE evidence_id = ? AND victim_id = ?";
        $this->db_query($sql, [$evidence_id, $victim_id]);
        return $this->results->fetch(PDO::FETCH_ASSOC);
    }
}
?>
