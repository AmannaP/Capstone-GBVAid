<?php
// classes/appointment_class.php
require_once '../settings/db_class.php';

class Appointment extends db_conn {

    /**
     * Create new appointment
     */
    public function book_appointment($victim_id, $service_id, $date, $time, $notes) {
        if (!$this->db_connect()) return false;

        $sql = "INSERT INTO appointments (victim_id, service_id, appointment_date, appointment_time, notes, status) 
                VALUES (?, ?, ?, ?, ?, 'Pending')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$victim_id, $service_id, $date, $time, $notes]);
    }

    /**
     * Check if a slot is already taken (Basic conflict check)
     */
    public function is_slot_taken($service_id, $date, $time) {
        if (!$this->db_connect()) return false;

        $sql = "SELECT count(*) as count FROM appointments 
                WHERE service_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'Cancelled'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$service_id, $date, $time]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] > 0;
    }

    /**
     * Get all appointments for a specific user
     */
    public function get_user_appointments($victim_id) {
        if (!$this->db_connect()) return [];

        // Join with 'services' table to get the Service Name (service_title)
        $sql = "SELECT a.*, p.service_title, p.service_image 
                FROM appointments a
                JOIN services p ON a.service_id = p.service_id
                WHERE a.victim_id = ?
                ORDER BY a.appointment_date DESC, a.appointment_time ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$victim_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cancel an appointment
     */
    public function cancel_appointment($appt_id, $victim_id) {
        if (!$this->db_connect()) return false;
        
        $sql = "UPDATE appointments SET status = 'Cancelled' WHERE appointment_id = ? AND victim_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$appt_id, $victim_id]);
    }

    /**
     * Get ALL bookings for Admin (Joined with Category & Brand)
     */
    public function get_all_bookings_admin() {
        if (!$this->db_connect()) return [];

        $sql = "SELECT 
                    a.*, 
                    c.victim_name, 
                    c.victim_contact,
                    p.service_title, 
                    cat.cat_name, 
                    b.brand_name
                FROM appointments a
                JOIN victim c ON a.victim_id = c.victim_id
                JOIN services p ON a.service_id = p.service_id
                JOIN categories cat ON p.service_cat = cat.cat_id
                JOIN brands b ON p.service_brand = b.brand_id
                ORDER BY a.appointment_date DESC, a.appointment_time ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>