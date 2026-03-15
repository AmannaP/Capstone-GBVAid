<?php
// Classes/victim_class.php
require_once '../settings/db_class.php';

class Victim extends db_conn {

    /**
     * Get Victim by Email using Prepared Statements
     */
    // classes/victim_class.php

    public function getVictimByEmail($email) {
        $sql = "SELECT * FROM victim WHERE victim_email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function verifyPassword($email, $password) {
        $victim = $this->getVictimByEmail($email);
        
        if (!$victim) {
            return false; 
        }

        $hash = $victim['victim_pass'] ?? $victim['victim_password'] ?? null;

        if ($hash && password_verify($password, $hash)) {
            return $victim; 
        }
        
        return false;
    }

    /**
     * Get Victim by ID
     */
    public function get_victim($victim_id) {
        $sql = "SELECT * FROM victim WHERE victim_id = ?";
        if ($this->db_query($sql, [$victim_id])) {
            return $this->results->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Update Victim Details
     */
    public function update_victim($id, $name, $contact, $city, $country, $image = null) {
        if ($image) {
            $sql = "UPDATE victim SET 
                    victim_name = ?, 
                    victim_contact = ?, 
                    victim_city = ?, 
                    victim_country = ?,
                    victim_image = ?
                    WHERE victim_id = ?";
            $params = [$name, $contact, $city, $country, $image, $id];
        } else {
            $sql = "UPDATE victim SET 
                    victim_name = ?, 
                    victim_contact = ?, 
                    victim_city = ?, 
                    victim_country = ? 
                    WHERE victim_id = ?";
            $params = [$name, $contact, $city, $country, $id];
        }
        
        return $this->db_write_query($sql, $params);
    }

    /**
     * Update Quick Exit URLs
     */
    public function update_quick_exit($id, $url1, $url2) {
        $sql = "UPDATE victim SET quick_exit_url1 = ?, quick_exit_url2 = ? WHERE victim_id = ?";
        return $this->db_write_query($sql, [$url1, $url2, $id]);
    }
}
?>