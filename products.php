<?php 
require_once 'config.php';
require_once 'includes/functions.php';

// Get site settings
$site_settings = getAllSettings();
$site_name = $site_settings['site_name'] ?? 'Company Machines';
$page_title = 'Our Products - ' . $site_name;

// Get category filter
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Get all categories
$cat_query = "SELECT * FROM categories ORDER BY display_order ASC, name ASC";
$cat_result = mysqli_query($conn, $cat_query);
$categories = [];
while ($row = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $row;
}

// Get products (filtered by category if selected)
$where = "WHERE p.status = 'active'";
if ($category_id > 0) {
    $where .= " AND p.category_id = $category_id";
}

$query = "SELECT p.*, c.name as category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          $where 
          ORDER BY p.display_order ASC, p.created_at DESC";
$result = mysqli_query($conn, $query);

// Get current category name for breadcrumb
$current_category = '';
if ($category_id > 0) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $category_id) {
            $current_category = $cat['name'];
            break;
        }
    }
}

include 'includes/header.php';
?>

<section class="page-header products-page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>&gt;</span>
            <a href="products.php">Products</a>
            <?php if ($current_category): ?>
                <span>&gt;</span>
                <span><?php echo htmlspecialchars($current_category); ?></span>
            <?php endif; ?>
        </div>
        <h1><?php echo $current_category ? htmlspecialchars($current_category) : 'Our Products'; ?></h1>
    </div>
</section>

<section class="products-section">
    <div class="container">
        <div class="products-layout">
            <!-- Sidebar with Categories -->
            <aside class="products-sidebar">
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">CATEGORIES</h3>
                    <ul class="category-list">
                        <li>
                            <a href="products.php" class="<?php echo $category_id == 0 ? 'active' : ''; ?>">
                                All Products
                            </a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="products.php?category=<?php echo $cat['id']; ?>" 
                                   class="<?php echo $category_id == $cat['id'] ? 'active' : ''; ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>
            
            <!-- Products Grid -->
            <div class="products-main">
                <?php if ($current_category): ?>
                    <div class="category-description">
                        <h2><?php echo htmlspecialchars($current_category); ?></h2>
                        <p class="show-more-text">
                            Explore our range of <?php echo htmlspecialchars($current_category); ?>s designed for industrial applications.
                        </p>
                    </div>
                <?php endif; ?>
                
                <div class="products-grid-new">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($product = mysqli_fetch_assoc($result)): ?>
                            <div class="product-card-new">
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="product-link">
                                    <div class="product-image-wrapper">
                                        <?php if ($product['media_type'] == 'image'): ?>
                                            <img src="<?php echo BASE_URL . $product['media_path']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                        <?php else: ?>
                                            <video src="<?php echo BASE_URL . $product['media_path']; ?>" muted></video>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-card-content">
                                        <h3><?php echo strtoupper(htmlspecialchars($product['title'])); ?></h3>
                                        <button class="read-more-btn-new">Read more</button>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-products-message">
                            <i class="fas fa-box-open"></i>
                            <h3>No Products Available</h3>
                            <p>No products found in this category.</p>
                            <a href="products.php" class="btn btn-primary">View All Products</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2>Interested in Our Products?</h2>
        <p>Contact us for more information and pricing details</p>
        <a href="contact.php" class="btn btn-light">Get in Touch</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
