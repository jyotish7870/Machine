<?php
// Get footer settings (if not already loaded)
if (!isset($site_settings)) {
    require_once __DIR__ . '/functions.php';
    $site_settings = getAllSettings();
}

$site_name = $site_settings['site_name'] ?? 'Company Machines';
$footer_about = $site_settings['footer_about'] ?? 'We provide high-quality machines and equipment for various industries.';
$footer_address = $site_settings['footer_address'] ?? '';
$footer_phone = $site_settings['footer_phone'] ?? '';
$footer_email = $site_settings['footer_email'] ?? '';
$footer_copyright = $site_settings['footer_copyright'] ?? '© ' . date('Y') . ' Company Machines. All Rights Reserved.';

// Social links
$social_facebook = $site_settings['social_facebook'] ?? '';
$social_twitter = $site_settings['social_twitter'] ?? '';
$social_instagram = $site_settings['social_instagram'] ?? '';
$social_linkedin = $site_settings['social_linkedin'] ?? '';
$social_youtube = $site_settings['social_youtube'] ?? '';
?>
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo e($site_name); ?></h3>
                    <p><?php echo e($footer_about); ?></p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about.php">About Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>products.php">Our Products</a></li>
                        <li><a href="<?php echo BASE_URL; ?>contact.php">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <?php if ($footer_phone): ?>
                        <p><i class="fas fa-phone"></i> <a href="tel:<?php echo formatPhoneLink($footer_phone); ?>"><?php echo e($footer_phone); ?></a></p>
                    <?php endif; ?>
                    <?php if ($footer_email): ?>
                        <p><i class="fas fa-envelope"></i> <a href="mailto:<?php echo e($footer_email); ?>"><?php echo e($footer_email); ?></a></p>
                    <?php endif; ?>
                    <?php if ($footer_address): ?>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo e($footer_address); ?></p>
                    <?php endif; ?>
                </div>
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <?php if ($social_facebook): ?>
                            <a href="<?php echo e($social_facebook); ?>" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if ($social_twitter): ?>
                            <a href="<?php echo e($social_twitter); ?>" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if ($social_instagram): ?>
                            <a href="<?php echo e($social_instagram); ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($social_linkedin): ?>
                            <a href="<?php echo e($social_linkedin); ?>" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if ($social_youtube): ?>
                            <a href="<?php echo e($social_youtube); ?>" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p><?php echo $footer_copyright; ?></p>
            </div>
        </div>
    </footer>
    
    <script src="<?php echo BASE_URL; ?>js/script.js"></script>
</body>
</html>
