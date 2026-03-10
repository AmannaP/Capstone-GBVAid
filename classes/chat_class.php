<?php
// classes/chat_class.php
require_once '../settings/db_class.php';

class Chat extends db_conn {

    // 1. Get all chat groups
    public function get_all_groups() {
        if (!$this->db_connect()) return [];
        $sql = "SELECT * FROM chat_groups";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Get specific group details
    public function get_group_details($group_id) {
        if (!$this->db_connect()) return false;
        $sql = "SELECT * FROM chat_groups WHERE group_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$group_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Send a message
    public function send_message($group_id, $victim_id, $message) {
        if (!$this->db_connect()) return false;
        $sql = "INSERT INTO chat_messages (group_id, victim_id, message) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$group_id, $victim_id, $message]);
    }

    // 4. Fetch messages for a group
    public function get_messages($group_id) {
        if (!$this->db_connect()) return [];
        
        // Join with victim table to get Sender Name
        $sql = "SELECT m.*, v.victim_name 
                FROM chat_messages m
                JOIN victim v ON m.victim_id = v.victim_id
                WHERE m.group_id = ?
                ORDER BY m.created_at ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$group_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Create a new chat group
    public function create_group($name, $desc, $icon) {
        if (!$this->db_connect()) return false;
        
        $sql = "INSERT INTO chat_groups (group_name, description, icon) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $desc, $icon]);
    }

    // 6. Delete a chat group
    public function delete_group($id) {
        if (!$this->db_connect()) return false;
        
        // Delete messages first to maintain referential integrity
        $sqlMsg = "DELETE FROM chat_messages WHERE group_id = ?";
        $stmtMsg = $this->db->prepare($sqlMsg);
        $stmtMsg->execute([$id]);

        $sql = "DELETE FROM chat_groups WHERE group_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // 7. Suggest a new group (for Victims)
    public function suggest_new_group($victim_id, $name, $reason) {   
        if (!$this->db_connect()) return false;
        
        $sql = "INSERT INTO group_requests (victim_id, suggested_name, reason_description) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$victim_id, $name, $reason]);
    }    
    
    // 8. Get pending group suggestions (for admin)
    public function get_pending_requests() {
        if (!$this->db_connect()) return [];
        
        $sql = "SELECT r.*, v.victim_name 
                FROM group_requests r
                JOIN victim v ON r.victim_id = v.victim_id
                WHERE r.status = 'pending'
                ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 9. Approve a group suggestion (for admin)
    public function approve_suggestion($req_id) {
        if (!$this->db_connect()) return false;

        try {
            $this->db->beginTransaction();

            // Get the suggestion details
            $sqlGet = "SELECT * FROM group_requests WHERE request_id = ? AND status = 'pending'";
            $stmtGet = $this->db->prepare($sqlGet);
            $stmtGet->execute([$req_id]);
            $suggestion = $stmtGet->fetch(PDO::FETCH_ASSOC);

            if (!$suggestion) {
                throw new Exception("No pending suggestion found.");
                $this->db->rollBack();
                return false; // No pending suggestion found
            }

            // Create the new group
            $groupCreated = $this->create_group($suggestion['suggested_name'], $suggestion['reason_description'], 'bi-people');
            
            if ($groupCreated) {
                // Update the suggestion status to approved
                $sqlUpdate = "UPDATE group_requests SET status = 'approved' WHERE request_id = ?";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                if ($stmtUpdate->execute([$req_id])) {
                    $this->db->commit();
                    return true;
                }
            }

            $this->db->rollBack();
            return false; // Failed to create group or update status
        } catch (Exception $e) {
            $this->db->rollBack();
            return false; // Handle any exceptions
        }
    }

    // 10. Reject a group suggestion (for admin)
    public function reject_suggestion($req_id) {
        if (!$this->db_connect()) return false;

        $sql = "UPDATE group_requests SET status = 'rejected' WHERE request_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$req_id]);
    }

    // 11. Get all groups a victim is part of (for dashboard)
    public function get_victim_groups($victim_id) {
        if (!$this->db_connect()) return [];
        
        $sql = "SELECT g.* 
                FROM chat_groups g
                JOIN chat_messages m ON g.group_id = m.group_id
                WHERE m.victim_id = ?
                GROUP BY g.group_id
                ORDER BY g.group_name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$victim_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 12. Get recent messages for dashboard (for a victim)
    public function get_recent_messages($victim_id, $limit = 5) {
        if (!$this->db_connect()) return [];

        $sql = "SELECT m.*, g.group_name 
                FROM chat_messages m
                JOIN chat_groups g ON m.group_id = g.group_id
                WHERE m.victim_id = ?
                ORDER BY m.timestamp DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$victim_id, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 13. Get total message count for a victim (for dashboard stats)
    public function get_message_count($victim_id) {
        if (!$this->db_connect()) return 0;

        $sql = "SELECT COUNT(*) as total FROM chat_messages WHERE victim_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$victim_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }
    
    // 14. update group details (for admin)
    public function update_group($id, $name, $desc, $icon) {
        if (!$this->db_connect()) return false;

        $sql = "UPDATE chat_groups 
                SET group_name = '$name', description = '$desc', icon = '$icon' 
                WHERE group_id = '$id'";
        return $this->db_query($sql);
    }

    // 15. get user suggested groups (for victim dashboard)
    public function get_user_suggestions($victim_id) {
        $sql = "SELECT suggested_name, status FROM group_requests WHERE victim_id = '$victim_id' ORDER BY created_at DESC";
        return $this->db_fetch_all($sql);
    }
}
?>