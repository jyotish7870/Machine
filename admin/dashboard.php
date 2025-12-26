<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get statistics
$products_query = "SELECT COUNT(*) as total FROM products";
$products_result = mysqli_query($conn, $products_query);
$total_products = mysqli_fetch_assoc($products_result)['total'];

$active_query = "SELECT COUNT(*) as total FROM products WHERE status = 'active'";
$active_result = mysqli_query($conn, $active_query);
$active_products = mysqli_fetch_assoc($active_result)['total'];

$images_query = "SELECT COUNT(*) as total FROM products WHERE media_type = 'image'";
$images_result = mysqli_query($conn, $images_query);
$total_images = mysqli_fetch_assoc($images_result)['total'];

$videos_query = "SELECT COUNT(*) as total FROM products WHERE media_type = 'video'";
$videos_result = mysqli_query($conn, $videos_query);
$total_videos = mysqli_fetch_assoc($videos_result)['total'];

$categories_query = "SELECT COUNT(*) as total FROM categories";
$categories_result = mysqli_query($conn, $categories_query);
$total_categories = mysqli_fetch_assoc($categories_result)['total'];

$spare_query = "SELECT COUNT(*) as total FROM spare_parts";
$spare_result = mysqli_query($conn, $spare_query);
$total_spare_parts = mysqli_fetch_assoc($spare_result)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-page">
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <div class="admin-user">
                <span>Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
    
    <div class="admin-container">
        <div class="admin-sidebar">
            <ul>
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Manage Products</a></li>
                <li><a href="add_product.php"><i class="fas fa-plus"></i> Add Product</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i> Categories</a></li>
                <li><a href="spare_parts.php"><i class="fas fa-cog"></i> Spare Parts</a></li>
                <li><a href="site_settings.php"><i class="fas fa-sliders-h"></i> Site Content</a></li>
                <li><a href="../index.php" target="_blank"><i class="fas fa-eye"></i> View Website</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-box"></i>
                    <h3><?php echo $total_products; ?></h3>
                    <p>Total Products</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <h3><?php echo $active_products; ?></h3>
                    <p>Active Products</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-folder"></i>
                    <h3><?php echo $total_categories; ?></h3>
                    <p>Categories</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-cog"></i>
                    <h3><?php echo $total_spare_parts; ?></h3>
                    <p>Spare Parts</p>
                </div>
            </div>
            
            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="actions-grid">
                    <a href="add_product.php" class="action-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add New Product</span>
                    </a>
                    <a href="products.php" class="action-btn">
                        <i class="fas fa-edit"></i>
                        <span>Manage Products</span>
                    </a>
                    <a href="categories.php" class="action-btn">
                        <i class="fas fa-folder-plus"></i>
                        <span>Manage Categories</span>
                    </a>
                    <a href="spare_parts.php" class="action-btn">
                        <i class="fas fa-cogs"></i>
                        <span>Manage Spare Parts</span>
                    </a>
                    <a href="../index.php" target="_blank" class="action-btn">
                        <i class="fas fa-globe"></i>
                        <span>View Website</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
