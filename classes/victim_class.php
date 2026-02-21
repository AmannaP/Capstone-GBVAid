<?php
// Classes/victim_class.php
require_once '../settings/db_class.php';

class Victim extends db_conn {

    /**
     * Get Victim by Email using Prepared Statements
     */
    // classes/victim_class.php

    public function getVictimByEmail($email) {
        // We use the PDO connection directly ($this->db) to bypass any db_class quirks
        $sql = "SELECT * FROM victim WHERE victim_email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // DEBUG: If you want to see if the database actually found the user
        // error_log("Database result for $email: " . print_r($user, true));

        return $user;
    }

    public function verifyPassword($email, $password) {
        $victim = $this->getVictimByEmail($email);
        
        if (!$victim) {
            // This means the email itself wasn't found
            return false; 
        }

        // Double check: is the column 'victim_pass' or 'victim_password'?
        // We'll check both just to be safe
        $hash = $victim['victim_pass'] ?? $victim['victim_password'] ?? null;

        if ($hash && password_verify($password, $hash)) {
            return $victim; 
        }
        
        return false;
    }

    // /**
    //  * Verify Password
    //  */
    // public function verifyPassword($email, $password) {
    //     $victim = $this->getVictimByEmail($email);
        
    //     if ($victim) {
    //         // Check the hashed password from the database
    //         if (password_verify($password, $victim['victim_pass'])) {
    //             return $victim; 
    //         }
    //     }
    //     return false;
    // }

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
}
?>