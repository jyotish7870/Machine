<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get site name
$site_name = defined('SITE_DISPLAY_NAME') ? SITE_DISPLAY_NAME : 'M.KPACKING';

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
    <title>Admin Dashboard - <?php echo $site_name; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidebar-logo .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .sidebar-logo h2 {
            font-size: 18px;
            font-weight: 600;
            white-space: nowrap;
            transition: opacity 0.3s;
        }
        
        .sidebar.collapsed .sidebar-logo h2 {
            opacity: 0;
            width: 0;
        }
        
        .toggle-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .toggle-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .sidebar.collapsed .toggle-btn {
            transform: rotate(180deg);
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            gap: 15px;
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(59, 130, 246, 0.2);
            color: white;
            border-left-color: #3b82f6;
        }
        
        .nav-item i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .nav-item span {
            white-space: nowrap;
            transition: opacity 0.3s;
        }
        
        .sidebar.collapsed .nav-item span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 15px 20px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        
        .sidebar.collapsed + .main-content {
            margin-left: 80px;
        }
        
        /* Top Header */
        .top-header {
            background: white;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #1e293b;
            cursor: pointer;
        }
        
        .page-title {
            font-size: 22px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .notification-btn {
            position: relative;
            background: #fef3c7;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 18px;
            color: #d97706;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }
        
        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .user-info p {
            font-size: 12px;
            color: #64748b;
        }
        
        .logout-btn {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 14px;
        }
        
        .logout-btn:hover {
            background: #dc2626;
            color: white;
        }
        
        /* Dashboard Content */
        .dashboard-content {
            padding: 30px;
        }
        
        /* Quick Actions Grid */
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
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
            font-size: 28px;
        }
        
        .action-card h3 {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            line-height: 1.4;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
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
        
        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        /* Mobile Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -260px;
            }
            
            .sidebar.mobile-open {
                left: 0;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .mobile-toggle {
                display: block;
            }
            
            .user-info {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .top-header {
                padding: 15px 20px;
            }
            
            .page-title {
                font-size: 18px;
            }
            
            .dashboard-content {
                padding: 20px;
            }
            
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .action-card {
                padding: 20px 15px;
            }
            
            .action-card .icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
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
            
            .logout-btn span {
                display: none;
            }
            
            .logout-btn i {
                margin: 0;
            }
        }
        
        @media (max-width: 480px) {
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="logo-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <h2><?php echo $site_name; ?></h2>
            </div>
            <button class="toggle-btn" onclick="toggleSidebarCollapse()">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="products.php" class="nav-item">
                <i class="fas fa-box"></i>
                <span>Products</span>
            </a>
            <a href="add_product.php" class="nav-item">
                <i class="fas fa-plus-circle"></i>
                <span>Add Product</span>
            </a>
            <a href="categories.php" class="nav-item">
                <i class="fas fa-folder"></i>
                <span>Categories</span>
            </a>
            <a href="spare_parts.php" class="nav-item">
                <i class="fas fa-cog"></i>
                <span>Spare Parts</span>
            </a>
            
            <div class="nav-divider"></div>
            
            <a href="site_settings.php" class="nav-item">
                <i class="fas fa-sliders-h"></i>
                <span>Site Settings</span>
            </a>
            <a href="../index.php" target="_blank" class="nav-item">
                <i class="fas fa-globe"></i>
                <span>View Website</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <header class="top-header">
            <div class="header-left">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">Dashboard</h1>
            </div>
            
            <div class="header-right">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                </button>
                
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h4><?php echo ucfirst($_SESSION['admin_username']); ?></h4>
                        <p>Administrator</p>
                    </div>
                </div>
                
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </header>
        
        <div class="dashboard-content">
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
                    <div class="stat-card-header">
                        <div class="stat-icon blue">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <h2><?php echo $total_products; ?></h2>
                    <p>Total Products</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <h2><?php echo $active_products; ?></h2>
                    <p>Active Products</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon purple">
                            <i class="fas fa-folder"></i>
                        </div>
                    </div>
                    <h2><?php echo $total_categories; ?></h2>
                    <p>Categories</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon orange">
                            <i class="fas fa-cog"></i>
                        </div>
                    </div>
                    <h2><?php echo $total_spare_parts; ?></h2>
                    <p>Spare Parts</p>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        // Toggle sidebar collapse (desktop)
        function toggleSidebarCollapse() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
        
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }
        
        // Restore sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 992) {
                sidebar.classList.add('collapsed');
            }
        });
        
        // Close sidebar on window resize if in mobile mode
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (window.innerWidth > 992) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>
