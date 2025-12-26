<?php 
require_once 'config.php';
require_once 'includes/functions.php';

// Get site settings
$site_settings = getAllSettings();
$site_name = $site_settings['site_name'] ?? 'Company Machines';
$page_title = ($site_settings['about_title'] ?? 'About Us') . ' - ' . $site_name;

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1><?php echo e($site_settings['about_title'] ?? 'About Us'); ?></h1>
        <p><?php echo e($site_settings['about_subtitle'] ?? 'Learn more about our company and mission'); ?></p>
    </div>
</section>

<section class="about-content">
    <div class="container">
        <div class="about-grid">
            <div class="about-text">
                <h2>Who We Are</h2>
                <p><?php echo nl2br(e($site_settings['about_content'] ?? 'We are a leading provider of industrial machinery and equipment with over 20 years of experience in the industry.')); ?></p>
                
                <?php if (!empty($site_settings['about_mission'])): ?>
                    <h3>Our Mission</h3>
                    <p><?php echo e($site_settings['about_mission']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($site_settings['about_vision'])): ?>
                    <h3>Our Vision</h3>
                    <p><?php echo e($site_settings['about_vision']); ?></p>
                <?php endif; ?>
            </div>
            <div class="about-image">
                <img src="https://via.placeholder.com/600x400/2563eb/ffffff?text=<?php echo urlencode($site_name); ?>" alt="About Us">
            </div>
        </div>
    </div>
</section>

<section class="values-section">
    <div class="container">
        <h2 class="section-title">Our Core Values</h2>
        <div class="values-grid">
            <div class="value-card">
                <i class="fas fa-star"></i>
                <h3>Quality</h3>
                <p>We never compromise on the quality of our products and services.</p>
            </div>
            <div class="value-card">
                <i class="fas fa-users"></i>
                <h3>Customer Focus</h3>
                <p>Our customers are at the heart of everything we do.</p>
            </div>
            <div class="value-card">
                <i class="fas fa-lightbulb"></i>
                <h3>Innovation</h3>
                <p>We continuously innovate to provide cutting-edge solutions.</p>
            </div>
            <div class="value-card">
                <i class="fas fa-handshake"></i>
                <h3>Integrity</h3>
                <p>We conduct our business with honesty and transparency.</p>
            </div>
        </div>
    </div>
</section>

<section class="team-section">
    <div class="container">
        <h2 class="section-title">Our Expertise</h2>
        <div class="expertise-grid">
            <div class="expertise-item">
                <i class="fas fa-industry"></i>
                <h3>20+</h3>
                <p>Years Experience</p>
            </div>
            <div class="expertise-item">
                <i class="fas fa-users"></i>
                <h3>500+</h3>
                <p>Happy Clients</p>
            </div>
            <div class="expertise-item">
                <i class="fas fa-box"></i>
                <h3>1000+</h3>
                <p>Products Delivered</p>
            </div>
            <div class="expertise-item">
                <i class="fas fa-globe"></i>
                <h3>25+</h3>
                <p>Countries Served</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2>Ready to Work With Us?</h2>
        <p>Contact us today to discuss your industrial machinery needs</p>
        <a href="contact.php" class="btn btn-light">Get in Touch</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
