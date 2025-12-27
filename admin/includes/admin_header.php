<?php
// Admin Header & Sidebar Component
// Include this at the top of admin pages after the opening body tag

$site_name = defined('SITE_DISPLAY_NAME') ? SITE_DISPLAY_NAME : 'M.KPACKING';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Admin Layout Styles -->
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #f0f2f5;
        min-height: 100vh;
    }
    
    /* Sidebar */
    .admin-sidebar {
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
        overflow-x: hidden;
    }
    
    .admin-sidebar.collapsed {
        width: 70px;
    }
    
    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 15px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        min-height: 80px;
    }
    
    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        overflow: hidden;
    }
    
    .sidebar-logo .logo-icon {
        min-width: 42px;
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    
    .sidebar-logo h2 {
        font-size: 16px;
        font-weight: 600;
        white-space: nowrap;
        transition: opacity 0.3s, width 0.3s;
    }
    
    .admin-sidebar.collapsed .sidebar-logo h2 {
        opacity: 0;
        width: 0;
        overflow: hidden;
    }
    
    .toggle-btn {
        background: rgba(255,255,255,0.1);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .toggle-btn:hover {
        background: rgba(255,255,255,0.2);
    }
    
    .admin-sidebar.collapsed .toggle-btn {
        transform: rotate(180deg);
    }
    
    .sidebar-nav {
        padding: 15px 0;
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
        white-space: nowrap;
    }
    
    .nav-item:hover, .nav-item.active {
        background: rgba(59, 130, 246, 0.2);
        color: white;
        border-left-color: #3b82f6;
    }
    
    .nav-item i {
        font-size: 18px;
        min-width: 24px;
        text-align: center;
    }
    
    .nav-item span {
        transition: opacity 0.3s, width 0.3s;
        overflow: hidden;
    }
    
    .admin-sidebar.collapsed .nav-item {
        justify-content: center;
        padding: 14px 0;
    }
    
    .admin-sidebar.collapsed .nav-item span {
        opacity: 0;
        width: 0;
        position: absolute;
    }
    
    .nav-divider {
        height: 1px;
        background: rgba(255,255,255,0.1);
        margin: 15px 20px;
    }
    
    .admin-sidebar.collapsed .nav-divider {
        margin: 15px 10px;
    }
    
    /* Main Content */
    .admin-main {
        margin-left: 260px;
        transition: margin-left 0.3s ease;
        min-height: 100vh;
    }
    
    .admin-sidebar.collapsed ~ .admin-main {
        margin-left: 70px;
    }
    
    /* Top Header */
    .admin-top-header {
        background: white;
        padding: 15px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 100;
        gap: 15px;
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
        padding: 5px;
    }
    
    .page-title {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-title i {
        color: #3b82f6;
    }
    
    .header-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .user-profile {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 15px;
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
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .logout-btn:hover {
        background: #dc2626;
        color: white;
    }
    
    /* Admin Content Area */
    .admin-content {
        padding: 25px;
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
    
    .sidebar-overlay.active {
        display: block;
    }
    
    /* Cards and common elements */
    .content-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    
    .content-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .content-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
    }
    
    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }
    
    .btn-primary:hover {
        box-shadow: 0 5px 20px rgba(59, 130, 246, 0.4);
        transform: translateY(-2px);
    }
    
    .btn-danger {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .btn-danger:hover {
        background: #dc2626;
        color: white;
    }
    
    .btn-success {
        background: #d1fae5;
        color: #059669;
    }
    
    .btn-success:hover {
        background: #059669;
        color: white;
    }
    
    /* Alerts */
    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    
    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    /* Tables */
    .table-responsive {
        overflow-x: auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th,
    .data-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .data-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #374151;
        font-size: 13px;
        text-transform: uppercase;
    }
    
    .data-table tr:hover {
        background: #f8fafc;
    }
    
    .table-thumb {
        width: 60px;
        height: 45px;
        object-fit: cover;
        border-radius: 6px;
    }
    
    /* Badges */
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .badge-active, .badge-available {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-inactive, .badge-out_of_stock {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .badge-image {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .badge-video {
        background: #fce7f3;
        color: #9d174d;
    }
    
    /* Forms */
    .form-container {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #374151;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    /* Action buttons in tables */
    .action-btns {
        display: flex;
        gap: 8px;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        text-decoration: none;
    }
    
    .action-btn.edit {
        background: #dbeafe;
        color: #1d4ed8;
    }
    
    .action-btn.edit:hover {
        background: #1d4ed8;
        color: white;
    }
    
    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .action-btn.delete:hover {
        background: #dc2626;
        color: white;
    }
    
    /* Mobile Responsive */
    @media (max-width: 992px) {
        .admin-sidebar {
            left: -260px;
        }
        
        .admin-sidebar.mobile-open {
            left: 0;
        }
        
        .admin-main {
            margin-left: 0 !important;
        }
        
        .mobile-toggle {
            display: block;
        }
        
        .user-info {
            display: none;
        }
        
        .toggle-btn {
            display: none;
        }
    }
    
    @media (max-width: 768px) {
        .admin-top-header {
            padding: 12px 15px;
        }
        
        .page-title {
            font-size: 16px;
        }
        
        .page-title span {
            display: none;
        }
        
        .admin-content {
            padding: 15px;
        }
        
        .content-card {
            padding: 15px;
        }
        
        .logout-btn span {
            display: none;
        }
        
        .logout-btn {
            padding: 10px;
        }
        
        .data-table th,
        .data-table td {
            padding: 10px 8px;
            font-size: 13px;
        }
        
        .table-thumb {
            width: 45px;
            height: 35px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .content-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
    
    @media (max-width: 480px) {
        .action-btns {
            flex-direction: column;
            gap: 5px;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
        }
    }
</style>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<!-- Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i class="fas fa-cogs"></i>
            </div>
            <h2><?php echo $site_name; ?></h2>
        </div>
        <button class="toggle-btn" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="products.php" class="nav-item <?php echo ($current_page == 'products.php' || $current_page == 'edit_product.php') ? 'active' : ''; ?>">
            <i class="fas fa-box"></i>
            <span>Manage Products</span>
        </a>
        <a href="add_product.php" class="nav-item <?php echo $current_page == 'add_product.php' ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i>
            <span>Add Product</span>
        </a>
        <a href="categories.php" class="nav-item <?php echo $current_page == 'categories.php' ? 'active' : ''; ?>">
            <i class="fas fa-folder"></i>
            <span>Categories</span>
        </a>
        <a href="spare_parts.php" class="nav-item <?php echo $current_page == 'spare_parts.php' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i>
            <span>Spare Parts</span>
        </a>
        
        <div class="nav-divider"></div>
        
        <a href="site_settings.php" class="nav-item <?php echo $current_page == 'site_settings.php' ? 'active' : ''; ?>">
            <i class="fas fa-sliders-h"></i>
            <span>Site Content</span>
        </a>
        <a href="../index.php" target="_blank" class="nav-item">
            <i class="fas fa-globe"></i>
            <span>View Website</span>
        </a>
    </nav>
</aside>

<!-- Main Content Wrapper -->
<main class="admin-main">
    <!-- Top Header -->
    <header class="admin-top-header">
        <div class="header-left">
            <button class="mobile-toggle" onclick="toggleMobileSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="page-title">
                <i class="<?php echo $page_icon ?? 'fas fa-tachometer-alt'; ?>"></i>
                <span><?php echo $page_title ?? 'Dashboard'; ?></span>
            </h1>
        </div>
        
        <div class="header-right">
            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)); ?>
                </div>
                <div class="user-info">
                    <h4><?php echo ucfirst($_SESSION['admin_username'] ?? 'Admin'); ?></h4>
                    <p>Administrator</p>
                </div>
            </div>
            
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </header>
    
    <!-- Page Content -->
    <div class="admin-content">
