<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['faculty_logged_in']) || $_SESSION['faculty_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$faculty_id = (int) $_SESSION['faculty_id'];
$success = '';
$error = '';

// Fetch current faculty record
$result = mysqli_query($con, "SELECT * FROM faculty WHERE id = $faculty_id");
$faculty = mysqli_fetch_assoc($result);

if (!$faculty) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name           = mysqli_real_escape_string($con, trim($_POST['name']));
    $phone          = mysqli_real_escape_string($con, trim($_POST['phone']));
    $email          = mysqli_real_escape_string($con, trim($_POST['email']));
    $qualifications = mysqli_real_escape_string($con, trim($_POST['qualifications']));
    $address        = mysqli_real_escape_string($con, trim($_POST['address']));

    if ($name === '') {
        $error = "Name cannot be empty.";
    } elseif ($email !== '' && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $new_profile_image = null;

        // Handle profile picture upload, if one was provided
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
            $max_size_bytes = 2 * 1024 * 1024; // 2 MB

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

                $new_filename = 'faculty_' . $faculty_id . '_' . time() . '.' . $file_ext;
                $destination  = $upload_dir . $new_filename;

                if (move_uploaded_file($file_tmp, $destination)) {
                    // Delete the old profile picture, if one exists
                    if (!empty($faculty['profile_image'])) {
                        $old_path = $upload_dir . $faculty['profile_image'];
                        if (is_file($old_path)) {
                            unlink($old_path);
                        }
                    }
                    $new_profile_image = $new_filename;
                } else {
                    $error = "Something went wrong uploading the image. Please try again.";
                }
            }
        }

        if (empty($error)) {
            if ($new_profile_image) {
                $new_profile_image_escaped = mysqli_real_escape_string($con, $new_profile_image);
                mysqli_query($con, "
                    UPDATE faculty
                    SET name = '$name',
                        phone = '$phone',
                        email = '$email',
                        qualifications = '$qualifications',
                        address = '$address',
                        profile_image = '$new_profile_image_escaped'
                    WHERE id = $faculty_id
                ");
            } else {
                mysqli_query($con, "
                    UPDATE faculty
                    SET name = '$name',
                        phone = '$phone',
                        email = '$email',
                        qualifications = '$qualifications',
                        address = '$address'
                    WHERE id = $faculty_id
                ");
            }

            // Keep the session name in sync so the navbar/dashboard greeting updates too
            $_SESSION['faculty_name'] = $name;

            $success = "Your profile has been updated successfully.";

            // Refresh local copy so the form shows the saved values
            $result = mysqli_query($con, "SELECT * FROM faculty WHERE id = $faculty_id");
            $faculty = mysqli_fetch_assoc($result);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Faculty Portal</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
        }
        .top-bar {
            background: #1e293b;
            color: #f1f5f9;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-bar a {
            color: #f87171;
            text-decoration: none;
        }
        .top-bar a:hover {
            text-decoration: underline;
        }
        .edit-card {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            padding: 30px;
        }
        .readonly-field {
            background: #f1f5f9;
        }
        .btn-save {
            background: transparent;
            border: 2px solid #3498db;
            color: #3498db;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: background 0.3s ease, color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        }
        .btn-save:hover {
            background: #3498db;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(52, 152, 219, 0.35);
            transform: translateY(-2px);
        }
        .btn-save:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div>
            <i class="fas fa-chalkboard-teacher"></i> Faculty Portal
        </div>
        <div>
            Welcome, <?php echo htmlspecialchars($_SESSION['faculty_name']); ?> &nbsp;|&nbsp;
            <a href="dashboard.php" style="color:#22d3ee;"><i class="fas fa-arrow-left"></i> Back to Profile</a> &nbsp;|&nbsp;
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="edit-card">
        <h3 class="mb-4"><i class="fas fa-user-edit"></i> Edit Profile</h3>

        <?php if($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-4 text-center">
                <?php if (!empty($faculty['profile_image'])): ?>
                    <img src="<?php echo ASSETS_URL . 'uploads/' . htmlspecialchars($faculty['profile_image']); ?>"
                         alt="Current profile photo"
                         style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:3px solid #3498db;">
                <?php else: ?>
                    <i class="fas fa-user-circle" style="font-size: 110px; color: #cbd5e1;"></i>
                <?php endif; ?>

                <div class="mt-3">
                    <label class="form-label d-block">Profile Picture</label>
                    <input type="file" name="profile_image" class="form-control" accept=".jpg,.jpeg,.png,.webp" style="max-width: 320px; margin: 0 auto;">
                    <small class="text-muted">JPG, PNG, or WEBP. Max 2MB. Leave empty to keep current photo.</small>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($faculty['name']); ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Designation</label>
                    <input type="text" class="form-control readonly-field" value="<?php echo htmlspecialchars($faculty['designation']); ?>" disabled>
                    <small class="text-muted">Contact admin to change this.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gender</label>
                    <input type="text" class="form-control readonly-field" value="<?php echo htmlspecialchars($faculty['gender']); ?>" disabled>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Qualifications</label>
                <textarea name="qualifications" class="form-control" rows="2"><?php echo htmlspecialchars($faculty['qualifications'] ?? ''); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($faculty['phone'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($faculty['email'] ?? ''); ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($faculty['address'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-save">
                <i class="fas fa-save"></i> Save Changes
            </button>
            <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>