<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Get the sub-admin ID from the URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: manage-subadmins.php');
    exit();
}

// Fetch the sub-admin's current details
$result = mysqli_query($con, "SELECT * FROM subadmins WHERE id = $id");
$subadmin = mysqli_fetch_assoc($result);

if (!$subadmin) {
    header('Location: manage-subadmins.php');
    exit();
}

// Handle the update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $username = mysqli_real_escape_string($con, $_POST['username']);

    mysqli_query($con, "UPDATE subadmins SET name = '$name', email = '$email', username = '$username' WHERE id = $id");

    header('Location: manage-subadmins.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Sub-Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/sidebar.php'; ?>
        <div class="admin-content">
            <h2>Edit Sub-Admin</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($subadmin['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($subadmin['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($subadmin['username']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="manage-subadmins.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>