<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get product ID
if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$id = intval($_GET['id']);
$success = '';
$error = '';

// Get product data
$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: products.php');
    exit;
}

$product = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $media_type = mysqli_real_escape_string($conn, $_POST['media_type']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $display_order = intval($_POST['display_order']);
    
    // Handle file upload if new file is provided
    if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] == 0) {
        $upload_dir = '../uploads/';
        $file_name = time() . '_' . basename($_FILES['media_file']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['media_file']['tmp_name'], $target_file)) {
            // Delete old file
            $old_file = '../' . $product['media_path'];
            if (file_exists($old_file)) {
                unlink($old_file);
            }
            
            $media_path = 'uploads/' . $file_name;
            
            $query = "UPDATE products SET title='$title', description='$description', media_type='$media_type', 
                     media_path='$media_path', status='$status', display_order=$display_order WHERE id=$id";
        } else {
            $error = 'Error uploading file';
        }
    } else {
        $query = "UPDATE products SET title='$title', description='$description', media_type='$media_type', 
                 status='$status', display_order=$display_order WHERE id=$id";
    }
    
    if (!$error && mysqli_query($conn, $query)) {
        $success = 'Product updated successfully!';
        // Refresh product data
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
        $product = mysqli_fetch_assoc($result);
    } else if (!$error) {
        $error = 'Error updating product: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-page">
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-edit"></i> Edit Product</h1>
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
                <li><a href="products.php" class="active"><i class="fas fa-box"></i> Manage Products</a></li>
                <li><a href="add_product.php"><i class="fas fa-plus"></i> Add Product</a></li>
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
                    <div class="form-group">
                        <label>Current Media</label>
                        <div class="current-media">
                            <?php if ($product['media_type'] == 'image'): ?>
                                <img src="../<?php echo $product['media_path']; ?>" alt="<?php echo $product['title']; ?>">
                            <?php else: ?>
                                <video src="../<?php echo $product['media_path']; ?>" controls></video>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Product Title *</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="media_type">Media Type *</label>
                        <select id="media_type" name="media_type" required>
                            <option value="image" <?php echo $product['media_type'] == 'image' ? 'selected' : ''; ?>>Image</option>
                            <option value="video" <?php echo $product['media_type'] == 'video' ? 'selected' : ''; ?>>Video</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="media_file">Upload New File (optional)</label>
                        <input type="file" id="media_file" name="media_file" accept="image/*,video/*">
                        <small>Leave empty to keep current file</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" value="<?php echo $product['display_order']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="active" <?php echo $product['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $product['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Product</button>
                        <a href="products.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
