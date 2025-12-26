<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get file path before deleting
    $query = "SELECT media_path FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $file_path = '../' . $row['media_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    $delete_query = "DELETE FROM products WHERE id = $id";
    mysqli_query($conn, $delete_query);
    header('Location: products.php');
    exit;
}

// Get all products
$query = "SELECT * FROM products ORDER BY display_order ASC, created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-page">
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-box"></i> Manage Products</h1>
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
            <div class="content-header">
                <h2>All Products</h2>
                <a href="add_product.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            </div>
            
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Preview</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <?php if ($row['media_type'] == 'image'): ?>
                                    <img src="../<?php echo $row['media_path']; ?>" alt="<?php echo $row['title']; ?>" class="table-thumb">
                                <?php else: ?>
                                    <video src="../<?php echo $row['media_path']; ?>" class="table-thumb"></video>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['title']; ?></td>
                            <td><span class="badge badge-<?php echo $row['media_type']; ?>"><?php echo ucfirst($row['media_type']); ?></span></td>
                            <td><span class="badge badge-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                            <td><?php echo $row['display_order']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn-icon btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
