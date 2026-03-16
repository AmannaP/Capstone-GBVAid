<?php
/**
 * Database credentials for GBVAid
 * This script pulls from Railway Environment Variables in production
 * and falls back to localhost for development.
 */

// 1. Database Server/Host
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
}

// 4. Database Name
if (!defined("DB_NAME")) {
    define("DB_NAME", getenv('MYSQLDATABASE') ?: "railway");
}

// 5. Database Port (The missing piece)
if (!defined("DB_PORT")) {
    define("DB_PORT", getenv('MYSQLPORT') ?: "17600");
} 
?>



//     define("DB_SERVER", getenv('MYSQLHOST') ?: "127.0.0.1");
//     define("DB_USERNAME", getenv('MYSQLUSER') ?: "root");
//     define("DB_PASSWORD", getenv('MYSQLPASSWORD') ?: "");
//     define("DB_NAME", getenv('MYSQLDATABASE') ?: "capstone");
//     define("DB_PORT", getenv('MYSQLPORT') ?: "3306");
