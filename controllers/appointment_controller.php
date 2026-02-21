<?php
// classes/user_class.php

require_once '../settings/db_class.php';

class User extends db_conn
{
    private $user_id;
    private $name;
    private $email;
    private $role;
    private $date_created;
    private $phone_number;

    public function __construct($user_id = null)
    {
        // 1. FIX: Use $this->db_connect() instead of parent::
        $this->db_connect();
        
        if ($user_id) {
            $this->user_id = $user_id;
            $this->loadUser();
        }
    }

    private function loadUser()
    {
        if (!$this->user_id) return false;

        // 2. FIX: PDO Syntax (No bind_param)
        $sql = "SELECT * FROM victim WHERE victim_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->user_id]);
        
        // 3. FIX: PDO Fetch
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $this->name = $result['victim_name'];
            $this->email = $result['victim_email'];
            $this->role = $result['user_role'];
            $this->date_created = isset($result['date_created']) ? $result['date_created'] : null;
            $this->phone_number = $result['victim_contact'];
        }
    }

    public function createUser($name, $email, $password, $country, $city, $phone_number, $role)
    {
        // Ensure connection
        if (!$this->db_connect()) return false;

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 4. FIX: PDO Insert Logic
        $sql = "INSERT INTO victim (victim_name, victim_email, victim_pass, victim_country, victim_city, victim_contact, user_role) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        // Execute with array of values
        if ($stmt->execute([$name, $email, $hashed_password, $country, $city, $phone_number, $role])) {
            // 5. FIX: PDO Last Insert ID
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    public function getUserByEmail($email)
    {
        if (!$this->db_connect()) return false;

        $sql = "SELECT * FROM victim WHERE victim_email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>