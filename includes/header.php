<?php
// Include helper functions
require_once __DIR__ . '/functions.php';

// Get all settings for use in header
$site_settings = getAllSettings();
$site_name = $site_settings['site_name'] ?? (defined('SITE_DISPLAY_NAME') ? SITE_DISPLAY_NAME : 'M.KPACKING');
$whatsapp_number = $site_settings['social_whatsapp'] ?? '';
$header_phone = $site_settings['header_phone'] ?? '';
$header_email = $site_settings['header_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : $site_name; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Top Bar (Optional - with phone and email) -->
    <?php if ($header_phone || $header_email): ?>
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <?php if ($header_phone): ?>
                    <a href="tel:<?php echo formatPhoneLink($header_phone); ?>">
                        <i class="fas fa-phone"></i> <?php echo e($header_phone); ?>
                    </a>
                <?php endif; ?>
                <?php if ($header_email): ?>
                    <a href="mailto:<?php echo e($header_email); ?>">
                        <i class="fas fa-envelope"></i> <?php echo e($header_email); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>">
                        <h1><?php echo e($site_name); ?></h1>
                    </a>
                </div>
                <nav class="main-nav" id="mainNav">
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About</a></li>
                        <li><a href="<?php echo BASE_URL; ?>products.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'products.php' || basename($_SERVER['PHP_SELF']) == 'product.php') ? 'active' : ''; ?>">Our Products</a></li>
                        <li><a href="<?php echo BASE_URL; ?>contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                        <li><a href="<?php echo BASE_URL; ?>admin/login.php">Admin</a></li>
                    </ul>
                </nav>
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Mobile Navigation -->
    <nav class="mobile-nav" id="mobileNav">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
            <li><a href="<?php echo BASE_URL; ?>about.php">About</a></li>
            <li><a href="<?php echo BASE_URL; ?>products.php">Our Products</a></li>
            <li><a href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>
            <li><a href="<?php echo BASE_URL; ?>admin/login.php">Admin</a></li>
        </ul>
    </nav>
    
    <!-- WhatsApp Floating Button -->
    <?php if ($whatsapp_number): ?>
    <a href="https://wa.me/<?php echo formatPhoneLink($whatsapp_number); ?>" class="whatsapp-float" target="_blank" title="Contact us on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <?php endif; ?>
    
    <!-- Scroll to Top Button -->
    <button class="scroll-top-btn" id="scrollTopBtn" onclick="scrollToTop()" title="Go to top" style="display: none;">
        <i class="fas fa-arrow-up"></i>
    </button>
