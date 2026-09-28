<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Get the college ID from the URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: manage-colleges.php');
    exit();
}

// Fetch the college's current details
$result = mysqli_query($con, "SELECT * FROM colleges WHERE id = $id");
$college = mysqli_fetch_assoc($result);

if (!$college) {
    header('Location: manage-colleges.php');
    exit();
}

// Handle the update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $college_name = mysqli_real_escape_string($con, $_POST['college_name']);
    $college_code = mysqli_real_escape_string($con, $_POST['college_code']);
    $address      = mysqli_real_escape_string($con, $_POST['address']);
    $phone        = mysqli_real_escape_string($con, $_POST['phone']);
    $email        = mysqli_real_escape_string($con, $_POST['email']);

    mysqli_query($con, "
        UPDATE colleges
        SET college_name = '$college_name',
            college_code = '$college_code',
            address = '$address',
            phone = '$phone',
            email = '$email'
        WHERE id = $id
    ");

    header('Location: manage-colleges.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit College - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/sidebar.php'; ?>

        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit College</h2>
                <a href="manage-colleges.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Colleges
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">College Name</label>
                            <input type="text" name="college_name" class="form-control"
                                   value="<?php echo htmlspecialchars($college['college_name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">College Code</label>
                            <input type="text" name="college_code" class="form-control"
                                   value="<?php echo htmlspecialchars($college['college_code']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($college['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="<?php echo htmlspecialchars($college['phone'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?php echo htmlspecialchars($college['email'] ?? ''); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="manage-colleges.php" class="btn btn-outline-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>