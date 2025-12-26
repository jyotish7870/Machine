<?php
/**
 * Helper functions for the website
 */

/**
 * Get a site setting by key
 */
function getSetting($key, $default = '') {
    global $conn;
    $key = mysqli_real_escape_string($conn, $key);
    $query = "SELECT setting_value FROM site_settings WHERE setting_key = '$key' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['setting_value'];
    }
    
    return $default;
}

/**
 * Get multiple settings by group
 */
function getSettingsByGroup($group) {
    global $conn;
    $group = mysqli_real_escape_string($conn, $group);
    $query = "SELECT setting_key, setting_value FROM site_settings WHERE setting_group = '$group' ORDER BY display_order ASC";
    $result = mysqli_query($conn, $query);
    
    $settings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    
    return $settings;
}

/**
 * Get all settings as an associative array
 */
function getAllSettings() {
    global $conn;
    $query = "SELECT setting_key, setting_value FROM site_settings";
    $result = mysqli_query($conn, $query);
    
    $settings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    
    return $settings;
}

/**
 * Format phone number for tel: link
 */
function formatPhoneLink($phone) {
    return preg_replace('/[^0-9+]/', '', $phone);
}

/**
 * Escape HTML output
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
