<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['subadmin_logged_in']) || $_SESSION['subadmin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$colleges = mysqli_query($con, "SELECT id, college_name FROM colleges ORDER BY college_name");
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name           = mysqli_real_escape_string($con, trim($_POST['name']));
    $username       = mysqli_real_escape_string($con, trim($_POST['username']));
    $password       = mysqli_real_escape_string($con, $_POST['password']);
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
        $check = mysqli_query($con, "SELECT id FROM faculty WHERE username = '$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "That username is already taken.";
        }
    }

    if (!$error) {
        $username_sql = $username !== '' ? "'$username'" : "NULL";
        $password_sql = $password !== '' ? "'$password'" : "NULL";
        $college_sql  = $college_id > 0 ? $college_id : "NULL";
        $joining_sql  = $joining_date !== '' ? "'$joining_date'" : "NULL";

        mysqli_query($con, "
            INSERT INTO faculty (name, username, password, college_id, gender, designation, qualifications, phone, email, joining_date, address)
            VALUES ('$name', $username_sql, $password_sql, $college_sql, '$gender', '$designation', '$qualifications', '$phone', '$email', $joining_sql, '$address')
        ");
        header('Location: manage-faculty.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty - Sub-Admin Panel</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/subadmin-sidebar.php'; ?>

        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Add Faculty</h2>
                <a href="manage-faculty.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Faculty</a>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Professor" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-control" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">College</label>
                        <select name="college_id" class="form-control">
                            <option value="">-- Select College --</option>
                            <?php while($college = mysqli_fetch_assoc($colleges)): ?>
                                <option value="<?php echo $college['id']; ?>"><?php echo htmlspecialchars($college['college_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Qualifications</label>
                        <textarea name="qualifications" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Joining Date</label>
                        <input type="date" name="joining_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>

                    <hr>
                    <p class="text-muted">Optional: set up portal login for this faculty member</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="text" name="password" class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Faculty</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
