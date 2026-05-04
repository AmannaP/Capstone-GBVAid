<?php
// classes/help_desk_class.php

require_once __DIR__ . '/../settings/db_class.php';

class HelpDesk extends db_conn {

    // 1. Submit a new support ticket
    public function add_ticket($victim_id, $category, $message) {
        $sql = "INSERT INTO help_desk_tickets (victim_id, category, message, status) VALUES (?, ?, ?, 'Pending')";
        return $this->db_query($sql, [$victim_id, $category, $message]);
    }

    // 2. Get all tickets (for admin)
    public function get_all_tickets() {
        $sql = "SELECT h.*, v.victim_name, v.victim_email, v.victim_image 
                FROM help_desk_tickets h 
                JOIN victim v ON h.victim_id = v.victim_id 
                ORDER BY CASE WHEN h.status = 'Pending' THEN 1 ELSE 2 END, h.created_at DESC";
        return $this->db_fetch_all($sql);
    }

    // 3. Get tickets for a specific user
    public function get_user_tickets($victim_id) {
        $sql = "SELECT * FROM help_desk_tickets WHERE victim_id = ? ORDER BY created_at DESC";
        return $this->db_fetch_all($sql, [$victim_id]);
    }

    // 4. Update ticket status and admin reply
    public function update_ticket($ticket_id, $status, $admin_reply) {
        $resolved_at = ($status === 'Resolved') ? date('Y-m-d H:i:s') : null;
        $sql = "UPDATE help_desk_tickets SET status = ?, admin_reply = ?, resolved_at = ? WHERE ticket_id = ?";
        return $this->db_query($sql, [$status, $admin_reply, $resolved_at, $ticket_id]);
    }

    // 5. Get a specific ticket details
    public function get_ticket_by_id($ticket_id) {
        $sql = "SELECT h.*, v.victim_name, v.victim_email 
                FROM help_desk_tickets h 
                JOIN victim v ON h.victim_id = v.victim_id 
                WHERE h.ticket_id = ?";
        return $this->db_fetch_one($sql, [$ticket_id]);
    }
}
?>
