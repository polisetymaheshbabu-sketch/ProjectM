<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Handle delete operation
if(isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($con, $_GET['delete']);
    $delete_query = mysqli_query($con, "DELETE FROM subadmins WHERE id = '$delete_id'");
    
    if($delete_query) {
        $message = "Sub-Admin deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Error deleting sub-admin: " . mysqli_error($con);
        $message_type = "danger";
    }
}

// Fetch all sub-admins
$subadmins_query = mysqli_query($con, "SELECT * FROM subadmins ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sub-Admins - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manage Sub-Admins</h2>
                <a href="add-subadmin.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Add Sub-Admin
                </a>
            </div>
            
            <?php if(isset($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> 
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="table-container">
                <div class="mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search sub-admins...">
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Profile</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($subadmins_query) > 0): ?>
                                <?php while($subadmin = mysqli_fetch_assoc($subadmins_query)): ?>
                                    <tr>
                                        <td><?php echo $subadmin['id']; ?></td>
                                        <td><?php echo htmlspecialchars($subadmin['name']); ?></td>
                                        <td><?php echo htmlspecialchars($subadmin['email']); ?></td>
                                        <td><?php echo htmlspecialchars($subadmin['username']); ?></td>
                                        <td><?php echo htmlspecialchars($subadmin['profile']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($subadmin['created_at'])); ?></td>
                                        <td>
                                            <a href="edit-subadmin.php?id=<?php echo $subadmin['id']; ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="?delete=<?php echo $subadmin['id']; ?>" class="btn btn-sm btn-danger delete-btn">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No sub-admins found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
