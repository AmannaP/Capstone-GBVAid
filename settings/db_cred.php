<?php
/**
 * Database credentials for GBVAid
 * This script pulls from Railway Environment Variables in production
 * and falls back to localhost for development.
 */

// 1. Database Server/Host (Local - "127.0.0.1")
if (!defined("DB_SERVER")) {
    define("DB_SERVER", getenv('MYSQLHOST') ?: "centerbeam.proxy.rlwy.net");
}

// 2. Database Username
if (!defined("DB_USERNAME")) {
    define("DB_USERNAME", getenv('MYSQLUSER') ?: "root");
}

// 3. Database Password
if (!defined("DB_PASSWORD")) {
    define("DB_PASSWORD", getenv('MYSQLPASSWORD') ?: "SEzrunnqUlMUQdQgSZKVldLlFHhDTtYu");
    //define("DB_PASSWORD", getenv('MYSQLPASSWORD') ?: "");
}

// 4. Database Name (local - "capstone")
if (!defined("DB_NAME")) {
    //define("DB_NAME", getenv('MYSQLDATABASE') ?: "capstone");
    define("DB_NAME", getenv('MYSQLDATABASE') ?: "railway");
}

// 5. Database Port (Local - "3306")
if (!defined("DB_PORT")) {
    define("DB_PORT", getenv('MYSQLPORT') ?: "17600");
} 
?>
