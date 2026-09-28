<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$admin_id = (int) $_SESSION['admin_id'];
$success = '';
$error = '';

// Fetch current admin record
$result = mysqli_query($con, "SELECT * FROM admins WHERE id = $admin_id");
$admin = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password      = $_POST['new_password'];
    $confirm_password  = $_POST['confirm_password'];

    $stored_password = $admin['password'];
    $is_md5 = (bool) preg_match('/^[a-f0-9]{32}$/i', $stored_password);
    $current_matches = $is_md5
        ? (md5($current_password) === $stored_password)
        : ($current_password === $stored_password);

    if (!$current_matches) {
        $error = "Current password is incorrect.";
    } elseif (strlen($new_password) < 6) {
        $error = "New password must be at least 6 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } else {
        // Store the new password in the same format the old one was stored in,
        // so existing plain-text or MD5 setups keep working consistently.
        $new_stored_password = $is_md5 ? md5($new_password) : $new_password;
        $new_stored_password_escaped = mysqli_real_escape_string($con, $new_stored_password);

        mysqli_query($con, "UPDATE admins SET password = '$new_stored_password_escaped' WHERE id = $admin_id");

        $success = "Password updated successfully.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Admin Panel</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .password-field {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        .toggle-password:hover {
            color: #3498db;
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
    <div class="admin-wrapper">
        <?php include '../includes/sidebar.php'; ?>

        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Change Password</h2>
            </div>

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

            <div class="form-container" style="max-width: 500px;">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <span class="toggle-password" onclick="togglePassword('current_password', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="new_password" name="new_password" minlength="6" required>
                            <span class="toggle-password" onclick="togglePassword('new_password', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <small class="text-muted">At least 6 characters.</small>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
                            <span class="toggle-password" onclick="togglePassword('confirm_password', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, iconSpan) {
            const input = document.getElementById(inputId);
            const icon = iconSpan.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>