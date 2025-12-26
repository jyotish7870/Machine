<?php
// Show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Website Setup</h2>";

// Test 1: PHP Works
echo "<p>✅ PHP is working</p>";

// Test 2: Check Environment
$is_localhost = (
    (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || $_SERVER['HTTP_HOST'] == '127.0.0.1')) ||
    (isset($_SERVER['SERVER_NAME']) && strpos($_SERVER['SERVER_NAME'], 'localhost') !== false)
);
echo "<p>Environment: " . ($is_localhost ? "LOCAL" : "PRODUCTION") . "</p>";
echo "<p>Host: " . ($_SERVER['HTTP_HOST'] ?? 'Not Set') . "</p>";

// Test 3: Database Connection
echo "<h3>Database Test:</h3>";
$db_host = 'localhost';
$db_user = 'u926020147_company';
$db_pass = 'jYOTISH7870%';
$db_name = 'u926020147_company';

echo "<p>Trying: DB=$db_name, User=$db_user</p>";

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        echo "<p>❌ Connection Error: " . $conn->connect_error . "</p>";
    } else {
        echo "<p>✅ Database connected successfully!</p>";
        
        // Check tables
        $result = $conn->query("SHOW TABLES");
        if ($result && $result->num_rows > 0) {
            echo "<p>✅ Tables found: " . $result->num_rows . "</p>";
            echo "<ul>";
            while ($row = $result->fetch_array()) {
                echo "<li>" . $row[0] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>❌ No tables found - Please import database.sql</p>";
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "<p>❌ Exception: " . $e->getMessage() . "</p>";
}

// Test 4: Check if files exist
echo "<h3>File Check:</h3>";
$files = ['config.php', 'includes/header.php', 'includes/footer.php', 'includes/functions.php'];
foreach ($files as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "<p>✅ $file exists</p>";
    } else {
        echo "<p>❌ $file NOT FOUND</p>";
    }
}

echo "<hr><p><a href='index.php'>Try Homepage</a></p>";
?>
