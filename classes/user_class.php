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

    public function createUser($name, $email, $password, $country, $city, $phone_number, $role, $provider_category_id = null, $provider_brand_id = null)
    {
        // Ensure connection
        if (!$this->db_connect()) return false;

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Default approval: Survivors (1) and Admins (2) are automatically approved. SPs (3) are 0 (pending).
        $sp_approved = ($role == 3) ? 0 : 1; 

        // 4. FIX: PDO Insert Logic
        $sql = "INSERT INTO victim (victim_name, victim_email, victim_pass, victim_country, victim_city, victim_contact, user_role, provider_category_id, provider_brand_id, sp_approved) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        // 5. FIX: Execute with array of values
        if ($stmt->execute([$name, $email, $hashed_password, $country, $city, $phone_number, $role, $provider_category_id, $provider_brand_id, $sp_approved])) {
            // 5. FIX: PDO Last Insert ID
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    // 6. FIX: Get user by email for login
    public function getUserByEmail($email)
    {
        if (!$this->db_connect()) return false;

        $sql = "SELECT * FROM victim WHERE victim_email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPendingProviders()
    {
        if (!$this->db_connect()) return false;
        // Fetch SPs (role=3) who are pending (sp_approved=0)
        // Join categories and brands to show what they applied for
        $sql = "SELECT v.*, c.cat_name, b.brand_name 
                FROM victim v 
                LEFT JOIN categories c ON v.provider_category_id = c.cat_id
                LEFT JOIN brands b ON v.provider_brand_id = b.brand_id
                WHERE v.user_role = 3 AND v.sp_approved = 0
                ORDER BY v.victim_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveProvider($provider_id)
    {
        if (!$this->db_connect()) return false;
        $sql = "UPDATE victim SET sp_approved = 1 WHERE victim_id = ? AND user_role = 3";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$provider_id]);
    }

    public function rejectProvider($provider_id)
    {
        if (!$this->db_connect()) return false;
        // Option 1: Delete the user (cleanest if they can't re-apply with same email easily)
        // Option 2: Mark as sp_approved = -1 (better if we want to keep history)
        // Given existing schema and simplicity, I'll use deletion to allow them to re-register if it was a mistake.
        // Or better, set sp_approved = 2 to mean 'Rejected'.
        $sql = "UPDATE victim SET sp_approved = 2 WHERE victim_id = ? AND user_role = 3";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$provider_id]);
    }
}
?>