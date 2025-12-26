<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

// Get all products for dropdown
$products_query = "SELECT id, title FROM products ORDER BY title ASC";
$products_result = mysqli_query($conn, $products_query);

// Handle spare part operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        $product_id = intval($_POST['product_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $display_order = intval($_POST['display_order'] ?? 0);
        
        $image_path = '';
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = '../uploads/spare_parts/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = 'uploads/spare_parts/' . $file_name;
            }
        }
        
        $query = "INSERT INTO spare_parts (product_id, name, description, image_path, price, status, display_order) 
                  VALUES ($product_id, '$name', '$description', '$image_path', '$price', '$status', $display_order)";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Spare part added successfully!';
        } else {
            $error = 'Error adding spare part: ' . mysqli_error($conn);
        }
    } elseif ($action == 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        
        // Get image path before deleting
        $img_query = mysqli_query($conn, "SELECT image_path FROM spare_parts WHERE id = $id");
        $img_data = mysqli_fetch_assoc($img_query);
        
        if (mysqli_query($conn, "DELETE FROM spare_parts WHERE id = $id")) {
            // Delete image file
            if ($img_data && $img_data['image_path'] && file_exists('../' . $img_data['image_path'])) {
                unlink('../' . $img_data['image_path']);
            }
            $success = 'Spare part deleted successfully!';
        } else {
            $error = 'Error deleting spare part';
        }
    }
    
    // Refresh products list
    $products_result = mysqli_query($conn, $products_query);
}

// Get all spare parts with product names
$spare_query = "SELECT sp.*, p.title as product_title 
                FROM spare_parts sp 
                LEFT JOIN products p ON sp.product_id = p.id 
                ORDER BY p.title ASC, sp.display_order ASC";
$spare_result = mysqli_query($conn, $spare_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Spare Parts</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-page">
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-cog"></i> Manage Spare Parts</h1>
            <div class="admin-user">
                <span>Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
    
    <div class="admin-container">
        <div class="admin-sidebar">
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> Manage Products</a></li>
                <li><a href="add_product.php"><i class="fas fa-plus"></i> Add Product</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i> Categories</a></li>
                <li><a href="spare_parts.php" class="active"><i class="fas fa-cog"></i> Spare Parts</a></li>
                <li><a href="site_settings.php"><i class="fas fa-sliders-h"></i> Site Content</a></li>
                <li><a href="../index.php" target="_blank"><i class="fas fa-eye"></i> View Website</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="content-header">
                <h2>Spare Parts & Accessories</h2>
            </div>
            
            <!-- Add Spare Part Form -->
            <div class="form-container" style="margin-bottom: 2rem;">
                <h3>Add New Spare Part</h3>
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="product_id">Select Product *</label>
                            <select id="product_id" name="product_id" required>
                                <option value="">-- Select Product --</option>
                                <?php 
                                mysqli_data_seek($products_result, 0);
                                while ($prod = mysqli_fetch_assoc($products_result)): 
                                ?>
                                    <option value="<?php echo $prod['id']; ?>">
                                        <?php echo htmlspecialchars($prod['title']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Spare Part Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" id="price" name="price" placeholder="e.g., ₹500 or Contact for price">
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <option value="available">Available</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="image">Image (small size recommended)</label>
                            <input type="file" id="image" name="image" accept="image/*">
                            <small>Recommended: 150x150px or smaller</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="display_order">Display Order</label>
                            <input type="number" id="display_order" name="display_order" value="0">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Spare Part</button>
                    </div>
                </form>
            </div>
            
            <!-- Spare Parts List -->
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($spare_result) > 0): ?>
                            <?php while ($part = mysqli_fetch_assoc($spare_result)): ?>
                                <tr>
                                    <td>
                                        <?php if ($part['image_path']): ?>
                                            <img src="../<?php echo $part['image_path']; ?>" class="table-thumb-small" alt="">
                                        <?php else: ?>
                                            <span class="no-image"><i class="fas fa-image"></i></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($part['product_title'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($part['name']); ?></td>
                                    <td><?php echo htmlspecialchars($part['price'] ?: '-'); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $part['status'] == 'available' ? 'active' : 'inactive'; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $part['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this spare part?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $part['id']; ?>">
                                            <button type="submit" class="btn-icon btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;">No spare parts found. Add products first, then add spare parts.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
