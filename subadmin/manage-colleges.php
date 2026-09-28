<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['subadmin_logged_in']) || $_SESSION['subadmin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete']) && (int) $_GET['delete'] > 0) {
    $delete_id = (int) $_GET['delete'];
    mysqli_query($con, "DELETE FROM colleges WHERE id = $delete_id");
    header('Location: manage-colleges.php');
    exit();
}

$colleges = mysqli_query($con, "SELECT * FROM colleges ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Colleges - Sub-Admin Panel</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/subadmin-sidebar.php'; ?>

        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manage Colleges</h2>
                <a href="add-college.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add College</a>
            </div>

            <div class="table-container">
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search colleges..." onkeyup="filterTable()">
                <table class="table" id="collegeTable">
                    <thead>
                        <tr>
                            <th>ID</th><th>College Name</th><th>Code</th><th>Phone</th><th>Email</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($college = mysqli_fetch_assoc($colleges)): ?>
                            <tr>
                                <td><?php echo $college['id']; ?></td>
                                <td><?php echo htmlspecialchars($college['college_name']); ?></td>
                                <td><?php echo htmlspecialchars($college['college_code']); ?></td>
                                <td><?php echo htmlspecialchars($college['phone'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($college['email'] ?? 'N/A'); ?></td>
                                <td>
                                    <a href="edit-college.php?id=<?php echo $college['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="manage-colleges.php?delete=<?php echo $college['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this college?');"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#collegeTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(input) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
