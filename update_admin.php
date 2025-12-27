<?php
/**
 * Admin Password Update Script
 * Run this once to update admin credentials then DELETE this file for security
 */

require_once 'config.php';

// New admin credentials
$new_username = 'jyotish';
$new_password = 'jyotish7870%';
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Check if new user already exists
$check_query = "SELECT id FROM admin_users WHERE username = '$new_username'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // Update existing user
    $update_query = "UPDATE admin_users SET password = '$password_hash' WHERE username = '$new_username'";
    if (mysqli_query($conn, $update_query)) {
        echo "<h2 style='color: green;'>Success!</h2>";
        echo "<p>Password updated for user: <strong>$new_username</strong></p>";
    } else {
        echo "<h2 style='color: red;'>Error!</h2>";
        echo "<p>" . mysqli_error($conn) . "</p>";
    }
} else {
    // Delete old admin and create new one
    mysqli_query($conn, "DELETE FROM admin_users WHERE username = 'admin'");
    
    $insert_query = "INSERT INTO admin_users (username, password, email) VALUES ('$new_username', '$password_hash', 'admin@mkpacking.in')";
    if (mysqli_query($conn, $insert_query)) {
        echo "<h2 style='color: green;'>Success!</h2>";
        echo "<p>New admin user created!</p>";
        echo "<p>Username: <strong>$new_username</strong></p>";
        echo "<p>Password: <strong>$new_password</strong></p>";
    } else {
        echo "<h2 style='color: red;'>Error!</h2>";
        echo "<p>" . mysqli_error($conn) . "</p>";
    }
}

// Also update site name to M.KPACKING
$site_name_check = mysqli_query($conn, "SELECT id FROM site_settings WHERE setting_key = 'site_name'");
if (mysqli_num_rows($site_name_check) > 0) {
    mysqli_query($conn, "UPDATE site_settings SET setting_value = 'M.KPACKING' WHERE setting_key = 'site_name'");
    echo "<p>Site name updated to: <strong>M.KPACKING</strong></p>";
} else {
    mysqli_query($conn, "INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, setting_label) VALUES ('site_name', 'M.KPACKING', 'text', 'general', 'Site Name')");
    echo "<p>Site name set to: <strong>M.KPACKING</strong></p>";
}

echo "<br><br>";
echo "<p style='color: red; font-weight: bold;'>⚠️ IMPORTANT: Delete this file (update_admin.php) after running for security!</p>";
echo "<p><a href='admin/login.php'>Go to Admin Login</a></p>";

mysqli_close($conn);
?>
