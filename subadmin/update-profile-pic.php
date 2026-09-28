<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['subadmin_logged_in']) || $_SESSION['subadmin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$subadmin_id = (int) $_SESSION['subadmin_id'];
$success = '';
$error = '';

$result = mysqli_query($con, "SELECT * FROM subadmins WHERE id = $subadmin_id");
$subadmin = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
        $max_size_bytes = 2 * 1024 * 1024;

        $file_tmp  = $_FILES['profile_image']['tmp_name'];
        $file_type = mime_content_type($file_tmp);
        $file_size = $_FILES['profile_image']['size'];
        $file_ext  = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_type, $allowed_types)) {
            $error = "Profile picture must be a JPG, PNG, or WEBP image.";
        } elseif ($file_size > $max_size_bytes) {
            $error = "Profile picture must be smaller than 2MB.";
        } else {
            $upload_dir = __DIR__ . '/../assets/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $new_filename = 'subadmin_' . $subadmin_id . '_' . time() . '.' . $file_ext;
            $destination  = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $destination)) {
                if (!empty($subadmin['profile_image'])) {
                    $old_path = $upload_dir . $subadmin['profile_image'];
                    if (is_file($old_path)) {
                        unlink($old_path);
                    }
                }

                mysqli_query($con, "UPDATE subadmins SET profile_image = '$new_filename' WHERE id = $subadmin_id");
                $success = "Profile picture updated successfully.";

                $result = mysqli_query($con, "SELECT * FROM subadmins WHERE id = $subadmin_id");
                $subadmin = mysqli_fetch_assoc($result);
            } else {
                $error = "Something went wrong uploading the image. Please try again.";
            }
        }
    } else {
        $error = "Please choose an image to upload.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile Picture - Sub-Admin Panel</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/subadmin-sidebar.php'; ?>

        <div class="admin-content">
            <h2 class="mb-4">Update Profile Picture</h2>

            <?php if($success): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="form-container" style="max-width: 500px;">
                <div class="text-center mb-4">
                    <?php if (!empty($subadmin['profile_image'])): ?>
                        <img src="<?php echo ASSETS_URL . 'uploads/' . htmlspecialchars($subadmin['profile_image']); ?>"
                             style="width:130px;height:130px;border-radius:50%;object-fit:cover;border:3px solid #3498db;">
                    <?php else: ?>
                        <i class="fas fa-user-circle" style="font-size: 130px; color: #cbd5e1;"></i>
                    <?php endif; ?>
                </div>

                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Choose New Picture</label>
                        <input type="file" name="profile_image" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                        <small class="text-muted">JPG, PNG, or WEBP. Max 2MB.</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
