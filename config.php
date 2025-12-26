<?php
// Environment Detection - checks if running on localhost or production
$is_localhost = (strpos($_SERVER['HTTP_HOST'] ?? 'localhost', 'localhost') !== false || 
                 $_SERVER['HTTP_HOST'] == '127.0.0.1' ||
                 strpos($_SERVER['SERVER_NAME'] ?? '', 'localhost') !== false);

// Database configuration based on environment
if ($is_localhost) {
    // LOCAL DEVELOPMENT (XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'company_db');
    define('BASE_URL', 'http://localhost/company/');
} else {
    // PRODUCTION (HOSTINGER)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'u926020147_company');
    define('DB_PASS', 'jYOTISH7870%');
    define('DB_NAME', 'u926020147_company');
    define('BASE_URL', '/'); // Will work with any domain/subdomain
}

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

// Session configuration
session_start();
?>
