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

// Page settings
$page_title = 'Manage Products';
$page_icon = 'fas fa-box';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include 'includes/admin_header.php'; ?>

        <div class="content-card">
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
                                <div class="action-btns">
                                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="action-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php include 'includes/admin_footer.php'; ?>
</body>
</html>
