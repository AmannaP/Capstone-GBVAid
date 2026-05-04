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

    /**
     * Constructor to establish DB connection and optionally load user data.
     * 
     * @param int|null $user_id Optional user ID to initialize the object.
     */
    public function __construct($user_id = null)
    {
        $this->db_connect();
        
        if ($user_id) {
            $this->user_id = $user_id;
            $this->loadUser();
        }
    }

    /**
     * Loads user details from the database into the object properties.
     * 
     * @return bool False if user_id is not set.
     */
    private function loadUser()
    {
        if (!$this->user_id) return false;

        $sql = "SELECT * FROM victim WHERE victim_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->user_id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $this->name = $result['victim_name'];
            $this->email = $result['victim_email'];
            $this->role = $result['user_role'];
            $this->date_created = isset($result['date_created']) ? $result['date_created'] : null;
            $this->phone_number = $result['victim_contact'];
        }
    }

    /**
     * Registers a new user or service provider in the database.
     *
     * @return int|bool Returns the newly inserted user ID on success, or false on failure.
     */
    public function createUser($name, $email, $password, $country, $city, $phone_number, $role, $provider_category_id = null, $provider_brand_id = null)
    {
        // Ensure connection
        if (!$this->db_connect()) return false;

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Default approval: Survivors (1) and Admins (2) are automatically approved. SPs (3) are 0 (pending).
        $sp_approved = ($role == 3) ? 0 : 1; 

        $sql = "INSERT INTO victim (victim_name, victim_email, victim_pass, victim_country, victim_city, victim_contact, user_role, provider_category_id, provider_brand_id, sp_approved) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute([$name, $email, $hashed_password, $country, $city, $phone_number, $role, $provider_category_id, $provider_brand_id, $sp_approved])) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Retrieves a user record by their email address for authentication purposes.
     * 
     * @param string $email The email address to look up.
     * @return array|bool Returns associative array of user details or false if not found.
     */
    public function getUserByEmail($email)
    {
        if (!$this->db_connect()) return false;

        $sql = "SELECT * FROM victim WHERE victim_email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a list of service providers awaiting administrative approval.
     * 
     * @return array Returns an array of pending service provider records.
     */
    public function getPendingProviders()
    {
        if (!$this->db_connect()) return false;
        
        // Fetch SPs (role=3) who are pending (sp_approved=0) and join categories/brands
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

    /**
     * Rejects a service provider's application by updating their status.
     * 
     * @param int $provider_id The ID of the provider to reject.
     * @return bool Returns true on success, false on failure.
     */
    public function rejectProvider($provider_id)
    {
        if (!$this->db_connect()) return false;
        
        // Status 2 denotes a rejected application
        $sql = "UPDATE victim SET sp_approved = 2 WHERE victim_id = ? AND user_role = 3";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$provider_id]);
    }
}
?>