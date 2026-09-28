<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
$message = "";
$message_type = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $college_name = mysqli_real_escape_string($con, $_POST['college_name']);
    $college_code = mysqli_real_escape_string($con, $_POST['college_code']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // Check if college code already exists
    $check_query = mysqli_query($con, "SELECT * FROM colleges WHERE college_code = '$college_code'");
    if(mysqli_num_rows($check_query) > 0) {
        $message = "College code already exists. Please choose another.";
        $message_type = "danger";
    } else {
        // Insert college
        $insert_query = mysqli_query($con, "
            INSERT INTO colleges (college_name, college_code, address, phone, email, created_at) 
            VALUES ('$college_name', '$college_code', '$address', '$phone', '$email', NOW())
        ");
        
        if($insert_query) {
            $message = "College added successfully!";
            $message_type = "success";
        } else {
            $message = "Error adding college: " . mysqli_error($con);
            $message_type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add College - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Add College</h2>
                <a href="manage-colleges.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
            
            <?php if($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> 
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="college_name" class="form-label">College Name</label>
                        <input type="text" class="form-control" id="college_name" name="college_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="college_code" class="form-label">College Code (Unique)</label>
                        <input type="text" class="form-control" id="college_code" name="college_code" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add College
                    </button>
                    <a href="manage-colleges.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
