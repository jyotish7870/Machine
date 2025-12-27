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

$categories_query = "SELECT COUNT(*) as total FROM categories";
$categories_result = mysqli_query($conn, $categories_query);
$total_categories = mysqli_fetch_assoc($categories_result)['total'];

$spare_query = "SELECT COUNT(*) as total FROM spare_parts";
$spare_result = mysqli_query($conn, $spare_query);
$total_spare_parts = mysqli_fetch_assoc($spare_result)['total'];

// Page settings
$page_title = 'Dashboard';
$page_icon = 'fas fa-home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Dashboard specific styles */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .action-card {
            background: white;
            border-radius: 16px;
            padding: 25px 20px;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-color: #3b82f6;
        }
        
        .action-card .icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }
        
        .action-card h3 {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 15px;
        }
        
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.green { background: #d1fae5; color: #059669; }
        .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
        .stat-icon.orange { background: #ffedd5; color: #ea580c; }
        
        .stat-card h2 {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            font-size: 14px;
            color: #64748b;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .action-card {
                padding: 20px 15px;
            }
            
            .action-card .icon {
                font-size: 26px;
            }
            
            .action-card h3 {
                font-size: 12px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-card h2 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
<?php include 'includes/admin_header.php'; ?>

        <!-- Quick Actions -->
        <h2 class="section-title">Quick Actions</h2>
        <div class="actions-grid">
            <a href="products.php" class="action-card">
                <div class="icon">📦</div>
                <h3>Manage Products</h3>
            </a>
            <a href="add_product.php" class="action-card">
                <div class="icon">➕</div>
                <h3>Add Product</h3>
            </a>
            <a href="categories.php" class="action-card">
                <div class="icon">📁</div>
                <h3>Categories</h3>
            </a>
            <a href="spare_parts.php" class="action-card">
                <div class="icon">⚙️</div>
                <h3>Spare Parts</h3>
            </a>
            <a href="site_settings.php" class="action-card">
                <div class="icon">🎨</div>
                <h3>Site Settings</h3>
            </a>
            <a href="../index.php" target="_blank" class="action-card">
                <div class="icon">🌐</div>
                <h3>View Website</h3>
            </a>
        </div>
        
        <!-- Statistics -->
        <h2 class="section-title">Statistics</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-box"></i>
                </div>
                <h2><?php echo $total_products; ?></h2>
                <p>Total Products</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2><?php echo $active_products; ?></h2>
                <p>Active Products</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-folder"></i>
                </div>
                <h2><?php echo $total_categories; ?></h2>
                <p>Categories</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-cog"></i>
                </div>
                <h2><?php echo $total_spare_parts; ?></h2>
                <p>Spare Parts</p>
            </div>
        </div>

<?php include 'includes/admin_footer.php'; ?>
</body>
</html>
