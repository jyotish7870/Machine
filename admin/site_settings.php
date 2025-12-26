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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Site Content</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .settings-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 2rem;
            background: white;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        .settings-tab {
            padding: 0.8rem 1.5rem;
            background: var(--light-color);
            border: none;
            border-radius: 5px;
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .settings-tab:hover {
            background: var(--border-color);
        }
        .settings-tab.active {
            background: var(--primary-color);
            color: white;
        }
        .settings-form .form-group {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        .settings-form .form-group:last-of-type {
            border-bottom: none;
        }
        .settings-form label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: block;
        }
        .settings-form .setting-key {
            font-size: 0.75rem;
            color: var(--text-color);
            opacity: 0.7;
            margin-bottom: 0.5rem;
        }
        .settings-form input[type="text"],
        .settings-form textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 1rem;
        }
        .settings-form textarea {
            min-height: 100px;
            resize: vertical;
        }
        .group-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary-color);
        }
        .group-header i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        .group-header h3 {
            margin: 0;
            color: var(--dark-color);
        }
    </style>
</head>
<body class="admin-page">
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-sliders-h"></i> Manage Site Content</h1>
            <div class="admin-user">
                <span>Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
    
    <div class="admin-container">
        <div class="admin-sidebar">
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Manage Products</a></li>
                <li><a href="add_product.php"><i class="fas fa-plus"></i> Add Product</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i> Categories</a></li>
                <li><a href="spare_parts.php"><i class="fas fa-cog"></i> Spare Parts</a></li>
                <li><a href="site_settings.php" class="active"><i class="fas fa-sliders-h"></i> Site Content</a></li>
                <li><a href="../index.php" target="_blank"><i class="fas fa-eye"></i> View Website</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Settings Tabs -->
            <div class="settings-tabs">
                <?php foreach ($groups as $key => $group): ?>
                    <a href="site_settings.php?group=<?php echo $key; ?>" 
                       class="settings-tab <?php echo $current_group == $key ? 'active' : ''; ?>">
                        <i class="fas <?php echo $group['icon']; ?>"></i>
                        <?php echo $group['label']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <!-- Settings Form -->
            <div class="form-container">
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
                            <a href="../index.php" target="_blank" class="btn btn-secondary">
                                <i class="fas fa-eye"></i> Preview Website
                            </a>
                        </div>
                    <?php else: ?>
                        <p>No settings found for this section.</p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
