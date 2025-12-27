<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

// Get current group/tab
$current_group = isset($_GET['group']) ? $_GET['group'] : 'header';

// Available groups
$groups = [
    'header' => ['label' => 'Header Settings', 'icon' => 'fa-heading'],
    'footer' => ['label' => 'Footer Settings', 'icon' => 'fa-shoe-prints'],
    'social' => ['label' => 'Social Links', 'icon' => 'fa-share-alt'],
    'home' => ['label' => 'Home Page', 'icon' => 'fa-home'],
    'features' => ['label' => 'Features Section', 'icon' => 'fa-star'],
    'about' => ['label' => 'About Page', 'icon' => 'fa-info-circle'],
    'contact' => ['label' => 'Contact Page', 'icon' => 'fa-envelope']
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $update_group = $_POST['update_group'] ?? '';
    
    if (!empty($_POST['settings'])) {
        $all_success = true;
        
        foreach ($_POST['settings'] as $key => $value) {
            $key = mysqli_real_escape_string($conn, $key);
            $value = mysqli_real_escape_string($conn, $value);
            
            $query = "UPDATE site_settings SET setting_value = '$value' WHERE setting_key = '$key'";
            if (!mysqli_query($conn, $query)) {
                $all_success = false;
            }
        }
        
        if ($all_success) {
            $success = 'Settings updated successfully!';
        } else {
            $error = 'Some settings could not be updated.';
        }
    }
    
    $current_group = $update_group ?: $current_group;
}

// Get settings for current group
$query = "SELECT * FROM site_settings WHERE setting_group = '$current_group' ORDER BY display_order ASC";
$result = mysqli_query($conn, $query);
$settings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $settings[] = $row;
}

// Page settings
$page_title = 'Site Content';
$page_icon = 'fas fa-sliders-h';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .settings-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }
        .settings-tab {
            padding: 10px 18px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .settings-tab:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }
        .settings-tab.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-color: transparent;
        }
        .settings-form .form-group {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .settings-form .form-group:last-of-type {
            border-bottom: none;
        }
        .setting-key {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .group-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3b82f6;
        }
        .group-header i {
            font-size: 20px;
            color: #3b82f6;
        }
        .group-header h3 {
            margin: 0;
            color: #1e293b;
            font-size: 18px;
        }
        .form-actions { margin-top: 25px; display: flex; gap: 15px; flex-wrap: wrap; }
        @media (max-width: 768px) {
            .settings-tab { padding: 8px 12px; font-size: 12px; }
            .settings-tab span { display: none; }
        }
    </style>
</head>
<body>
<?php include 'includes/admin_header.php'; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Settings Tabs -->
            <div class="settings-tabs">
                <?php foreach ($groups as $key => $group): ?>
                    <a href="site_settings.php?group=<?php echo $key; ?>" 
                       class="settings-tab <?php echo $current_group == $key ? 'active' : ''; ?>">
                        <i class="fas <?php echo $group['icon']; ?>"></i>
                        <span><?php echo $group['label']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <!-- Settings Form -->
            <div class="content-card">
                <div class="group-header">
                    <i class="fas <?php echo $groups[$current_group]['icon']; ?>"></i>
                    <h3><?php echo $groups[$current_group]['label']; ?></h3>
                </div>
                
                <form method="POST" action="" class="settings-form">
                    <input type="hidden" name="update_group" value="<?php echo $current_group; ?>">
                    
                    <?php if (count($settings) > 0): ?>
                        <?php foreach ($settings as $setting): ?>
                            <div class="form-group">
                                <label for="<?php echo $setting['setting_key']; ?>">
                                    <?php echo htmlspecialchars($setting['setting_label']); ?>
                                </label>
                                <div class="setting-key">Key: <?php echo $setting['setting_key']; ?></div>
                                
                                <?php if ($setting['setting_type'] == 'textarea'): ?>
                                    <textarea 
                                        id="<?php echo $setting['setting_key']; ?>" 
                                        name="settings[<?php echo $setting['setting_key']; ?>]"
                                        rows="4"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                                <?php else: ?>
                                    <input 
                                        type="text" 
                                        id="<?php echo $setting['setting_key']; ?>" 
                                        name="settings[<?php echo $setting['setting_key']; ?>]"
                                        value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="../index.php" target="_blank" class="btn btn-success">
                                <i class="fas fa-eye"></i> Preview Website
                            </a>
                        </div>
                    <?php else: ?>
                        <p style="color: #64748b; padding: 20px 0;">No settings found for this section.</p>
                    <?php endif; ?>
                </form>
            </div>

<?php include 'includes/admin_footer.php'; ?>
</body>
</html>
