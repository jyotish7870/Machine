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

// Page settings
$page_title = 'Categories';
$page_icon = 'fas fa-folder';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .inline-form .form-row { display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
        .inline-form .form-group { margin-bottom: 0; flex: 1; min-width: 150px; }
    </style>
</head>
<body>
<?php include 'includes/admin_header.php'; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Add Category Form -->
            <div class="content-card">
                <h3 style="margin-bottom: 20px;">Add New Category</h3>
                <form method="POST" action="" class="inline-form">
                    <input type="hidden" name="action" value="add">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="name" placeholder="Enter category name" required>
                        </div>
                        <div class="form-group">
                            <label>Order</label>
                            <input type="number" name="display_order" placeholder="0" value="0">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</button>
                    </div>
                </form>
            </div>
            
            <!-- Categories List -->
            <div class="content-card">
                <h3 style="margin-bottom: 20px;">All Categories</h3>
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
                                                <button type="submit" class="action-btn delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align:center; padding: 30px; color: #64748b;">No categories found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

<?php include 'includes/admin_footer.php'; ?>
</body>
</html>
