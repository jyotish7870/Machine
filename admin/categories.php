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

// Handle category operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $slug = strtolower(str_replace(' ', '-', $name));
        $display_order = intval($_POST['display_order'] ?? 0);
        
        $query = "INSERT INTO categories (name, slug, display_order) VALUES ('$name', '$slug', $display_order)";
        if (mysqli_query($conn, $query)) {
            $success = 'Category added successfully!';
        } else {
            $error = 'Error adding category: ' . mysqli_error($conn);
        }
    } elseif ($action == 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        if (mysqli_query($conn, "DELETE FROM categories WHERE id = $id")) {
            $success = 'Category deleted successfully!';
        } else {
            $error = 'Error deleting category';
        }
    }
    
    // Refresh categories list
    $cat_result = mysqli_query($conn, $cat_query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-page">
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-folder"></i> Manage Categories</h1>
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
                <li><a href="categories.php" class="active"><i class="fas fa-folder"></i> Categories</a></li>
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
            
            <div class="content-header">
                <h2>Product Categories</h2>
            </div>
            
            <!-- Add Category Form -->
            <div class="form-container" style="margin-bottom: 2rem;">
                <h3>Add New Category</h3>
                <form method="POST" action="" class="inline-form">
                    <input type="hidden" name="action" value="add">
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Category Name" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="display_order" placeholder="Order" value="0">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                    </div>
                </form>
            </div>
            
            <!-- Categories List -->
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($cat_result) > 0): ?>
                            <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
                                <tr>
                                    <td><?php echo $cat['id']; ?></td>
                                    <td><?php echo htmlspecialchars($cat['name']); ?></td>
                                    <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                                    <td><?php echo $cat['display_order']; ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                            <button type="submit" class="btn-icon btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center;">No categories found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
