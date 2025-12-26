<?php
// Show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Website Setup</h2>";

// Test 1: PHP Works
echo "<p>✅ PHP is working</p>";

// Test 2: Check Environment
echo "<p>Environment: PRODUCTION</p>";
echo "<p>Host: " . ($_SERVER['HTTP_HOST'] ?? 'Not Set') . "</p>";

// Test 3: Database Connection
echo "<h3>Database Test:</h3>";

// Direct hardcoded test
$db_host = 'localhost';
$db_user = 'u926020147_company';
$db_pass = 'Jyotish7870';
$db_name = 'u926020147_company';

echo "<p><b>Trying:</b></p>";
echo "<ul>";
echo "<li>Host: $db_host</li>";
echo "<li>User: $db_user</li>";
echo "<li>DB: $db_name</li>";
echo "<li>Pass: " . str_repeat('*', strlen($db_pass)) . " (Length: " . strlen($db_pass) . ")</li>";
echo "</ul>";

// Try connection with detailed error
mysqli_report(MYSQLI_REPORT_OFF); // Disable exceptions temporarily

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if ($conn) {
    echo "<p style='color:green;font-size:20px;'>✅ DATABASE CONNECTED SUCCESSFULLY!</p>";
    
    // Check tables
    $result = mysqli_query($conn, "SHOW TABLES");
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<p>✅ Tables found: " . mysqli_num_rows($result) . "</p>";
        echo "<ul>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>⚠️ No tables found - Please import database.sql via phpMyAdmin</p>";
    }
    mysqli_close($conn);
} else {
    echo "<p style='color:red;font-size:18px;'>❌ Connection Failed!</p>";
    echo "<p><b>Error:</b> " . mysqli_connect_error() . "</p>";
    echo "<p><b>Error Code:</b> " . mysqli_connect_errno() . "</p>";
    
    echo "<h4>🔧 Please do this in Hostinger:</h4>";
    echo "<ol>";
    echo "<li>Go to <b>Databases → MySQL Databases</b></li>";
    echo "<li>Find database: <b>u926020147_company</b></li>";
    echo "<li>Make sure user <b>u926020147_company</b> is linked to this database</li>";
    echo "<li>If not linked, click <b>'Add User to Database'</b></li>";
    echo "<li>Reset password again to: <b>Jyotish7870</b></li>";
    echo "</ol>";
}

// File Check
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
