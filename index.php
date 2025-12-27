<?php 
require_once 'config.php';
require_once 'includes/functions.php';

// Get site settings
$site_settings = getAllSettings();
$site_name = $site_settings['site_name'] ?? (defined('SITE_DISPLAY_NAME') ? SITE_DISPLAY_NAME : 'M.KPACKING');
$page_title = 'Home - ' . $site_name;

// Get active products for display
$query = "SELECT p.*, c.name as category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.status = 'active' 
          ORDER BY p.display_order ASC, p.created_at DESC";
$result = mysqli_query($conn, $query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

include 'includes/header.php';
?>

<section class="hero-slider">
    <div class="slider-container">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $index => $product): ?>
                <div class="slide <?php echo $index == 0 ? 'active' : ''; ?>">
                    <?php if ($product['media_type'] == 'image'): ?>
                        <img src="<?php echo BASE_URL . $product['media_path']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                    <?php else: ?>
                        <video src="<?php echo BASE_URL . $product['media_path']; ?>" autoplay muted loop></video>
                    <?php endif; ?>
                    <div class="slide-content">
                        <h2><?php echo htmlspecialchars($product['title']); ?></h2>
                        <?php if ($product['short_description']): ?>
                            <p><?php echo htmlspecialchars($product['short_description']); ?></p>
                        <?php elseif ($product['description']): ?>
                            <p><?php echo substr(htmlspecialchars($product['description']), 0, 150); ?>...</p>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
            
            <div class="slider-dots">
                <?php foreach ($products as $index => $product): ?>
                    <span class="dot <?php echo $index == 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></span>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-products">
                <h2>Welcome to Company Machines</h2>
                <p>No products available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2 class="section-title">Why Choose Us</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="<?php echo e($site_settings['feature1_icon'] ?? 'fas fa-cogs'); ?>"></i>
                <h3><?php echo e($site_settings['feature1_title'] ?? 'Quality Machines'); ?></h3>
                <p><?php echo e($site_settings['feature1_text'] ?? 'We provide high-quality industrial machines with latest technology.'); ?></p>
            </div>
            <div class="feature-card">
                <i class="<?php echo e($site_settings['feature2_icon'] ?? 'fas fa-headset'); ?>"></i>
                <h3><?php echo e($site_settings['feature2_title'] ?? '24/7 Support'); ?></h3>
                <p><?php echo e($site_settings['feature2_text'] ?? 'Round-the-clock customer support for all your queries and issues.'); ?></p>
            </div>
            <div class="feature-card">
                <i class="<?php echo e($site_settings['feature3_icon'] ?? 'fas fa-truck'); ?>"></i>
                <h3><?php echo e($site_settings['feature3_title'] ?? 'Fast Delivery'); ?></h3>
                <p><?php echo e($site_settings['feature3_text'] ?? 'Quick and secure delivery to your location anywhere.'); ?></p>
            </div>
            <div class="feature-card">
                <i class="<?php echo e($site_settings['feature4_icon'] ?? 'fas fa-shield-alt'); ?>"></i>
                <h3><?php echo e($site_settings['feature4_title'] ?? 'Warranty'); ?></h3>
                <p><?php echo e($site_settings['feature4_text'] ?? 'All products come with comprehensive warranty coverage.'); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="products-preview">
    <div class="container">
        <h2 class="section-title">Our Products</h2>
        
        <!-- Scrolling Product Carousel - Right to Left -->
        <div class="products-scroll-wrapper">
            <div class="products-scroll-container" id="productsCarousel">
                <?php foreach ($products as $product): ?>
                    <a class="product-scroll-card" href="<?php echo BASE_URL; ?>product.php?id=<?php echo $product['id']; ?>">
                        <div class="product-scroll-image">
                            <?php if ($product['media_type'] == 'image'): ?>
                                <img src="<?php echo BASE_URL . $product['media_path']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                            <?php else: ?>
                                <video src="<?php echo BASE_URL . $product['media_path']; ?>" muted></video>
                            <?php endif; ?>
                        </div>
                        <div class="product-scroll-info">
                            <h3><?php echo strtoupper(htmlspecialchars($product['title'])); ?></h3>
                            <span class="read-more-btn">Read more</span>
                        </div>
                    </a>
                <?php endforeach; ?>
                <!-- Duplicate for infinite scroll effect -->
                <?php foreach ($products as $product): ?>
                    <a class="product-scroll-card" href="<?php echo BASE_URL; ?>product.php?id=<?php echo $product['id']; ?>">
                        <div class="product-scroll-image">
                            <?php if ($product['media_type'] == 'image'): ?>
                                <img src="<?php echo BASE_URL . $product['media_path']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                            <?php else: ?>
                                <video src="<?php echo BASE_URL . $product['media_path']; ?>" muted></video>
                            <?php endif; ?>
                        </div>
                        <div class="product-scroll-info">
                            <h3><?php echo strtoupper(htmlspecialchars($product['title'])); ?></h3>
                            <span class="read-more-btn">Read more</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="text-center" style="margin-top: 2rem;">
            <a href="products.php" class="btn btn-primary">View All Products</a>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2><?php echo e($site_settings['home_cta_title'] ?? 'Need a Custom Solution?'); ?></h2>
        <p><?php echo e($site_settings['home_cta_text'] ?? 'Contact us for custom industrial machine solutions tailored to your needs'); ?></p>
        <a href="contact.php" class="btn btn-light">Get in Touch</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
