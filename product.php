<?php
require_once 'config.php';
require_once 'includes/functions.php';

// Get site settings
$site_settings = getAllSettings();
$site_name = $site_settings['site_name'] ?? 'Company Machines';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$id = intval($_GET['id']);

// Get product with category
$query = "SELECT p.*, c.name as category_name, c.id as cat_id 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.id = $id AND p.status = 'active' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    $page_title = 'Product Not Found - Company Machines';
    include 'includes/header.php';
    echo '<div class="container" style="padding: 4rem 0; text-align: center;">
            <h2>Product Not Found</h2>
            <p>The product you requested does not exist or is not available.</p>
            <a href="products.php" class="btn btn-primary">Back to Products</a>
          </div>';
    include 'includes/footer.php';
    exit;
}

$product = mysqli_fetch_assoc($result);
$page_title = htmlspecialchars($product['title']) . ' - ' . $site_name;

// Get additional product images
$images_query = "SELECT * FROM product_images WHERE product_id = $id ORDER BY is_primary DESC, display_order ASC";
$images_result = mysqli_query($conn, $images_query);
$product_images = [];
while ($img = mysqli_fetch_assoc($images_result)) {
    $product_images[] = $img;
}

// Add main product image to gallery if no additional images
if (empty($product_images)) {
    $product_images[] = [
        'id' => 0,
        'image_path' => $product['media_path'],
        'is_primary' => 1
    ];
}

// Get product specifications
$specs_query = "SELECT * FROM product_specifications WHERE product_id = $id ORDER BY display_order ASC";
$specs_result = mysqli_query($conn, $specs_query);
$specifications = [];
while ($spec = mysqli_fetch_assoc($specs_result)) {
    $specifications[] = $spec;
}

// Get spare parts
$parts_query = "SELECT * FROM spare_parts WHERE product_id = $id ORDER BY display_order ASC";
$parts_result = mysqli_query($conn, $parts_query);
$spare_parts = [];
while ($part = mysqli_fetch_assoc($parts_result)) {
    $spare_parts[] = $part;
}

// Get related products
$related_query = "SELECT * FROM products WHERE id != $id AND status = 'active' ";
if ($product['cat_id']) {
    $related_query .= "AND category_id = " . $product['cat_id'] . " ";
}
$related_query .= "ORDER BY RAND() LIMIT 4";
$related_result = mysqli_query($conn, $related_query);

include 'includes/header.php';
?>

<section class="product-detail-page">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>&gt;</span>
            <a href="products.php">Products</a>
            <?php if ($product['category_name']): ?>
                <span>&gt;</span>
                <a href="products.php?category=<?php echo $product['cat_id']; ?>"><?php echo htmlspecialchars($product['category_name']); ?></a>
            <?php endif; ?>
            <span>&gt;</span>
            <span><?php echo htmlspecialchars($product['title']); ?></span>
        </div>
        
        <div class="product-detail-wrapper">
            <!-- Product Images Gallery -->
            <div class="product-gallery">
                <div class="gallery-thumbnails">
                    <?php foreach ($product_images as $index => $img): ?>
                        <div class="thumb-item <?php echo $index == 0 ? 'active' : ''; ?>" 
                             onclick="changeMainImage('<?php echo BASE_URL . $img['image_path']; ?>', this)">
                            <img src="<?php echo BASE_URL . $img['image_path']; ?>" alt="Thumbnail <?php echo $index + 1; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="gallery-main">
                    <img id="mainProductImage" src="<?php echo BASE_URL . $product_images[0]['image_path']; ?>" 
                         alt="<?php echo htmlspecialchars($product['title']); ?>">
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="product-info-detail">
                <h1><?php echo htmlspecialchars($product['title']); ?></h1>
                
                <?php if (!empty($specifications)): ?>
                    <div class="specifications-table">
                        <table>
                            <tbody>
                                <?php foreach ($specifications as $spec): ?>
                                    <tr>
                                        <td class="spec-name"><?php echo htmlspecialchars($spec['spec_name']); ?></td>
                                        <td class="spec-value"><?php echo htmlspecialchars($spec['spec_value']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <div class="product-contact-info">
                    <p><strong>Call us on:</strong> <a href="tel:+919999999999">+91-9999999999</a></p>
                    <p><strong>Email us:</strong> <a href="mailto:info@company.com">info@company.com</a></p>
                </div>
                
                <div class="product-actions">
                    <a href="contact.php" class="btn btn-primary"><i class="fas fa-envelope"></i> Get Quote</a>
                    <a href="tel:+919999999999" class="btn btn-success"><i class="fas fa-phone"></i> Call Now</a>
                </div>
            </div>
        </div>
        
        <!-- Product Description -->
        <div class="product-description-section">
            <h2>Product Description</h2>
            <div class="description-content">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>
        </div>
        
        <!-- Spare Parts Section -->
        <?php if (!empty($spare_parts)): ?>
            <div class="spare-parts-section">
                <h2>Spare Parts & Accessories</h2>
                <div class="spare-parts-grid">
                    <?php foreach ($spare_parts as $part): ?>
                        <div class="spare-part-card">
                            <?php if ($part['image_path']): ?>
                                <div class="spare-part-image">
                                    <img src="<?php echo BASE_URL . $part['image_path']; ?>" alt="<?php echo htmlspecialchars($part['name']); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="spare-part-info">
                                <h4><?php echo htmlspecialchars($part['name']); ?></h4>
                                <?php if ($part['description']): ?>
                                    <p><?php echo htmlspecialchars($part['description']); ?></p>
                                <?php endif; ?>
                                <?php if ($part['price']): ?>
                                    <span class="spare-part-price"><?php echo htmlspecialchars($part['price']); ?></span>
                                <?php endif; ?>
                                <span class="spare-part-status badge-<?php echo $part['status'] == 'available' ? 'active' : 'inactive'; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $part['status'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Related Products -->
        <?php if (mysqli_num_rows($related_result) > 0): ?>
            <div class="related-products-section">
                <h2>Related Products</h2>
                <div class="related-products-grid">
                    <?php while ($related = mysqli_fetch_assoc($related_result)): ?>
                        <a href="product.php?id=<?php echo $related['id']; ?>" class="related-product-card">
                            <div class="related-product-image">
                                <?php if ($related['media_type'] == 'image'): ?>
                                    <img src="<?php echo BASE_URL . $related['media_path']; ?>" alt="<?php echo htmlspecialchars($related['title']); ?>">
                                <?php else: ?>
                                    <video src="<?php echo BASE_URL . $related['media_path']; ?>" muted></video>
                                <?php endif; ?>
                            </div>
                            <h4><?php echo htmlspecialchars($related['title']); ?></h4>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function changeMainImage(src, thumbElement) {
    document.getElementById('mainProductImage').src = src;
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    thumbElement.classList.add('active');
}
</script>

<?php include 'includes/footer.php'; ?>
