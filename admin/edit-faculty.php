<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: manage-faculty.php');
    exit();
}

$result = mysqli_query($con, "SELECT * FROM faculty WHERE id = $id");
$faculty = mysqli_fetch_assoc($result);

if (!$faculty) {
    header('Location: manage-faculty.php');
    exit();
}

$colleges = mysqli_query($con, "SELECT id, college_name FROM colleges ORDER BY college_name");
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name           = mysqli_real_escape_string($con, trim($_POST['name']));
    $username       = mysqli_real_escape_string($con, trim($_POST['username']));
    $new_password   = $_POST['new_password'];
    $college_id     = (int) $_POST['college_id'];
    $gender         = mysqli_real_escape_string($con, $_POST['gender']);
    $designation    = mysqli_real_escape_string($con, trim($_POST['designation']));
    $qualifications = mysqli_real_escape_string($con, trim($_POST['qualifications']));
    $phone          = mysqli_real_escape_string($con, trim($_POST['phone']));
    $email          = mysqli_real_escape_string($con, trim($_POST['email']));
    $joining_date   = mysqli_real_escape_string($con, $_POST['joining_date']);
    $address        = mysqli_real_escape_string($con, trim($_POST['address']));

    if ($name === '' || $designation === '') {
        $error = "Name and designation are required.";
    } elseif ($username !== '') {
        // Username must be unique across all OTHER faculty members
        $check = mysqli_query($con, "SELECT id FROM faculty WHERE username = '$username' AND id != $id");
        if (mysqli_num_rows($check) > 0) {
            $error = "That username is already taken by another faculty member.";
        }
    }

    if ($error === '' && $new_password !== '' && strlen($new_password) < 6) {
        $error = "New password must be at least 6 characters long.";
    }

    if ($error === '') {
        $college_sql  = $college_id > 0 ? $college_id : "NULL";
        $joining_sql  = $joining_date !== '' ? "'$joining_date'" : "NULL";
        $username_sql = $username !== '' ? "'$username'" : "NULL";

        // Only change the password when the admin typed a new one
        $password_sql = '';
        if ($new_password !== '') {
            $new_password_escaped = mysqli_real_escape_string($con, $new_password);
            $password_sql = ", password = '$new_password_escaped'";
        }

        mysqli_query($con, "
            UPDATE faculty
            SET name = '$name',
                username = $username_sql,
                college_id = $college_sql,
                gender = '$gender',
                designation = '$designation',
                qualifications = '$qualifications',
                phone = '$phone',
                email = '$email',
                joining_date = $joining_sql,
                address = '$address'
                $password_sql
            WHERE id = $id
        ");

        header('Location: manage-faculty.php');
        exit();
    }

    // Keep what the admin typed if validation failed
    $faculty['name']           = $_POST['name'];
    $faculty['username']       = $_POST['username'];
    $faculty['college_id']     = $college_id;
    $faculty['gender']         = $_POST['gender'];
    $faculty['designation']    = $_POST['designation'];
    $faculty['qualifications'] = $_POST['qualifications'];
    $faculty['phone']          = $_POST['phone'];
    $faculty['email']          = $_POST['email'];
    $faculty['joining_date']   = $_POST['joining_date'];
    $faculty['address']        = $_POST['address'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty - Admin Panel</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/sidebar.php'; ?>

        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Faculty</h2>
                <a href="manage-faculty.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Faculty
                </a>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control"
                               value="<?php echo htmlspecialchars($faculty['name']); ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" class="form-control"
                                   value="<?php echo htmlspecialchars($faculty['designation']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-control" required>
                                <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                                    <option value="<?php echo $g; ?>" <?php echo $faculty['gender'] === $g ? 'selected' : ''; ?>>
                                        <?php echo $g; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">College</label>
                        <select name="college_id" class="form-control">
                            <option value="">-- Select College --</option>
                            <?php while($college = mysqli_fetch_assoc($colleges)): ?>
                                <option value="<?php echo $college['id']; ?>"
                                    <?php echo $faculty['college_id'] == $college['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($college['college_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Qualifications</label>
                        <textarea name="qualifications" class="form-control" rows="2"><?php echo htmlspecialchars($faculty['qualifications'] ?? ''); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="<?php echo htmlspecialchars($faculty['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?php echo htmlspecialchars($faculty['email'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Joining Date</label>
                        <input type="date" name="joining_date" class="form-control"
                               value="<?php echo htmlspecialchars($faculty['joining_date'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($faculty['address'] ?? ''); ?></textarea>
                    </div>

                    <hr>
                    <p class="text-muted mb-3">Faculty portal login</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control"
                                   value="<?php echo htmlspecialchars($faculty['username'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password</label>
                            <input type="text" name="new_password" class="form-control" placeholder="Leave blank to keep current password">
                            <small class="text-muted">Only fill this in to reset the password (min 6 characters).</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="manage-faculty.php" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>