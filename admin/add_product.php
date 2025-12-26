<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

// Get all categories
$cat_query = "SELECT * FROM categories ORDER BY display_order ASC, name ASC";
$cat_result = mysqli_query($conn, $cat_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $short_description = mysqli_real_escape_string($conn, $_POST['short_description']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $media_type = mysqli_real_escape_string($conn, $_POST['media_type']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $display_order = intval($_POST['display_order']);
    $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : 'NULL';
    
    // Handle main file upload
    if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] == 0) {
        $upload_dir = '../uploads/';
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['media_file']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['media_file']['tmp_name'], $target_file)) {
            $media_path = 'uploads/' . $file_name;
            
            $cat_val = is_numeric($category_id) ? $category_id : 'NULL';
            $query = "INSERT INTO products (title, short_description, description, media_type, media_path, status, display_order, category_id) 
                     VALUES ('$title', '$short_description', '$description', '$media_type', '$media_path', '$status', $display_order, $cat_val)";
            
            if (mysqli_query($conn, $query)) {
                $product_id = mysqli_insert_id($conn);
                
                // Handle additional images
                if (isset($_FILES['additional_images'])) {
                    $img_upload_dir = '../uploads/products/';
                    if (!file_exists($img_upload_dir)) {
                        mkdir($img_upload_dir, 0777, true);
                    }
                    
                    foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
                        if ($_FILES['additional_images']['error'][$key] == 0) {
                            $img_name = time() . '_' . $key . '_' . basename($_FILES['additional_images']['name'][$key]);
                            $img_target = $img_upload_dir . $img_name;
                            
                            if (move_uploaded_file($tmp_name, $img_target)) {
                                $img_path = 'uploads/products/' . $img_name;
                                mysqli_query($conn, "INSERT INTO product_images (product_id, image_path, display_order) 
                                                    VALUES ($product_id, '$img_path', $key)");
                            }
                        }
                    }
                }
                
                // Handle specifications
                if (isset($_POST['spec_name']) && isset($_POST['spec_value'])) {
                    foreach ($_POST['spec_name'] as $key => $spec_name) {
                        $spec_name = mysqli_real_escape_string($conn, $spec_name);
                        $spec_value = mysqli_real_escape_string($conn, $_POST['spec_value'][$key]);
                        
                        if (!empty($spec_name) && !empty($spec_value)) {
                            mysqli_query($conn, "INSERT INTO product_specifications (product_id, spec_name, spec_value, display_order) 
                                                VALUES ($product_id, '$spec_name', '$spec_value', $key)");
                        }
                    }
                }
                
                $success = 'Product added successfully!';
            } else {
                $error = 'Error adding product: ' . mysqli_error($conn);
            }
        } else {
            $error = 'Error uploading file';
        }
    } else {
        $error = 'Please select a file to upload';
    }
    
    // Refresh categories
    $cat_result = mysqli_query($conn, $cat_query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .spec-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
        .spec-row input { flex: 1; }
        .spec-row .remove-spec { color: #ef4444; cursor: pointer; padding: 5px; }
        .add-spec-btn { margin-top: 10px; }
    </style>
</head>
<body class="admin-page">
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-plus"></i> Add New Product</h1>
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
                <li><a href="add_product.php" class="active"><i class="fas fa-plus"></i> Add Product</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i> Categories</a></li>
                <li><a href="spare_parts.php"><i class="fas fa-cog"></i> Spare Parts</a></li>
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
            
            <div class="form-container">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="title">Product Title *</label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id">
                                <option value="">-- Select Category --</option>
                                <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="short_description">Short Description (for slider)</label>
                        <input type="text" id="short_description" name="short_description" maxlength="200">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Full Description</label>
                        <textarea id="description" name="description" rows="5"></textarea>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="media_type">Media Type *</label>
                            <select id="media_type" name="media_type" required>
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="media_file">Main Image/Video *</label>
                            <input type="file" id="media_file" name="media_file" accept="image/*,video/*" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="additional_images">Additional Images (for gallery)</label>
                        <input type="file" id="additional_images" name="additional_images[]" accept="image/*" multiple>
                        <small>Select multiple images for product gallery</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Product Specifications</label>
                        <div id="specifications-container">
                            <div class="spec-row">
                                <input type="text" name="spec_name[]" placeholder="Specification Name (e.g., Filling Range)">
                                <input type="text" name="spec_value[]" placeholder="Value (e.g., 1kg to 10kg)">
                                <span class="remove-spec" onclick="removeSpec(this)"><i class="fas fa-times"></i></span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary add-spec-btn" onclick="addSpec()">
                            <i class="fas fa-plus"></i> Add Specification
                        </button>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="display_order">Display Order</label>
                            <input type="number" id="display_order" name="display_order" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Product</button>
                        <a href="products.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function addSpec() {
            const container = document.getElementById('specifications-container');
            const row = document.createElement('div');
            row.className = 'spec-row';
            row.innerHTML = `
                <input type="text" name="spec_name[]" placeholder="Specification Name">
                <input type="text" name="spec_value[]" placeholder="Value">
                <span class="remove-spec" onclick="removeSpec(this)"><i class="fas fa-times"></i></span>
            `;
            container.appendChild(row);
        }
        
        function removeSpec(btn) {
            const rows = document.querySelectorAll('.spec-row');
            if (rows.length > 1) {
                btn.parentElement.remove();
            }
        }
    </script>
</body>
</html>
