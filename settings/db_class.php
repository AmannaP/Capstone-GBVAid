<?php
// settings/db_class.php

require_once 'db_cred.php';

/**
 *@version 1.1
 * Database connection class using PDO
 */
if (!class_exists('db_conn')) {
    class db_conn
    {
        //properties
        public $db = null;
        public $results = null;

        public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME,
                DB_USERNAME,
                DB_PASSWORD
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("DB Connection failed: " . $e->getMessage());
        }
        }

        //connect
        /**
         * Database connection
         * @return boolean
         **/
        function db_connect()
        {
            if ($this->db !== null) {
                return true;
            }
            try {
                // PDO Connection
                $this->db = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
                // Set error mode to exception
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return true;
            } catch (PDOException $e) {
                error_log("DB Connection failed: " . $e->getMessage());
                return false;
            }
        }

        /**
         * Return the PDO connection object
         */
        function db_conn()
        {
            if ($this->db_connect()) {
                return $this->db;
            }
            return false;
        }
        //execute a query for SELECT statements
        /**
         * Query the Database for SELECT statements
         * @param string $sqlQuery
         * @return boolean
         **/
        function db_query($sqlQuery, $params = [])
        {
            if (!$this->db_connect()) return false;

            try {
                // Prepare handles sql injection automatically if params are used
                // But for compatibility with your existing string-based SQL, we use query() if no params
                if (empty($params)) {
                    $this->results = $this->db->query($sqlQuery);
                } else {
                    $stmt = $this->db->prepare($sqlQuery);
                    $stmt->execute($params);
                    $this->results = $stmt;
                }
                return true;
            } catch (PDOException $e) {
                error_log("Query Failed: " . $e->getMessage());
                return false;
            }
        }


        /**
         * Query the Database for INSERT, UPDATE, DELETE statements
         * @param string $sqlQuery
         * @return boolean
         **/
        function db_write_query($sqlQuery, $params = [])
        {
            if (!$this->db_connect()) return false;

            try {
                if (empty($params)) {
                    // exec() returns number of affected rows
                    $count = $this->db->exec($sqlQuery);
                    return ($count !== false);
                } else {
                    $stmt = $this->db->prepare($sqlQuery);
                    return $stmt->execute($params);
                }
            } catch (PDOException $e) {
                error_log("Write Query Failed: " . $e->getMessage());
                return false;
            }
        }

        //fetch a single record
        /**
         * Get a single record
         * @param string $sql
         * @return array|false
         **/
        function db_fetch_one($sql)
        {
            if (!$this->db_query($sql)) return false;
            return $this->results->fetch(PDO::FETCH_ASSOC);
        }

        //fetch all records
        /**
         * Get all records
         * @param string $sql
         * @return array|false
         **/
        function db_fetch_all($sql)
        {
            if (!$this->db_query($sql)) return false;
            return $this->results->fetchAll(PDO::FETCH_ASSOC);
        }

        //count data
        /**
         * Get count of records
         * @return int|false
         **/
        function db_count()
        {
            if ($this->results) {
                return $this->results->rowCount();
            }
            return false;
        }

        /**
         * Get last inserted ID
         */
        function last_insert_id()
        {
            if ($this->db) {
                return $this->db->lastInsertId();
            }
            return false;
        }
    }
}
?>