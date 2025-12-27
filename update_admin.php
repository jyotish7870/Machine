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

echo "<div style='font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 30px; background: #f9f9f9; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);'>";

// Delete ALL existing admin users first
$delete_query = "DELETE FROM admin_users";
mysqli_query($conn, $delete_query);
echo "<p style='color: #666;'>✓ Cleared existing admin users</p>";

// Insert new admin user
$insert_query = "INSERT INTO admin_users (username, password, email) VALUES ('$new_username', '$password_hash', 'admin@mkpacking.in')";

if (mysqli_query($conn, $insert_query)) {
    echo "<h2 style='color: #10b981; margin-bottom: 20px;'>✅ Success!</h2>";
    echo "<div style='background: white; padding: 20px; border-radius: 8px; margin: 15px 0;'>";
    echo "<p style='margin: 10px 0;'><strong>Username:</strong> <code style='background: #e5e7eb; padding: 5px 10px; border-radius: 4px;'>$new_username</code></p>";
    echo "<p style='margin: 10px 0;'><strong>Password:</strong> <code style='background: #e5e7eb; padding: 5px 10px; border-radius: 4px;'>$new_password</code></p>";
    echo "</div>";
} else {
    echo "<h2 style='color: #dc2626;'>❌ Error!</h2>";
    echo "<p>" . mysqli_error($conn) . "</p>";
}

// Also update site name to M.KPACKING
$site_name_check = mysqli_query($conn, "SELECT id FROM site_settings WHERE setting_key = 'site_name'");
if (mysqli_num_rows($site_name_check) > 0) {
    mysqli_query($conn, "UPDATE site_settings SET setting_value = 'M.KPACKING' WHERE setting_key = 'site_name'");
} else {
    mysqli_query($conn, "INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, setting_label) VALUES ('site_name', 'M.KPACKING', 'text', 'general', 'Site Name')");
}
echo "<p style='color: #666;'>✓ Site name updated to: <strong>M.KPACKING</strong></p>";

echo "<br>";
echo "<p style='color: #dc2626; font-weight: bold; background: #fef2f2; padding: 15px; border-radius: 8px;'>⚠️ IMPORTANT: Delete this file after running!</p>";
echo "<br>";
echo "<a href='admin/login.php' style='display: inline-block; background: #1e3c72; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;'>Go to Admin Login →</a>";
echo "</div>";

mysqli_close($conn);
?>
