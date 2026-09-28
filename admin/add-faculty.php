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

// Fetch colleges for dropdown
$colleges_query = mysqli_query($con, "SELECT * FROM colleges ORDER BY college_name ASC");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $college_id = mysqli_real_escape_string($con, $_POST['college_id']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $designation = mysqli_real_escape_string($con, $_POST['designation']);
    $qualifications = mysqli_real_escape_string($con, $_POST['qualifications']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $joining_date = mysqli_real_escape_string($con, $_POST['joining_date']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    
    // Handle image upload
    $profile_image = "";
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Allow certain file formats
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if(in_array($imageFileType, $allowed_types)) {
            if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $file_name;
            }
        }
    }
    
    // Insert faculty
    $insert_query = mysqli_query($con, "
        INSERT INTO faculty (name, college_id, gender, designation, qualifications, phone, email, joining_date, address, profile_image, created_at) 
        VALUES ('$name', '$college_id', '$gender', '$designation', '$qualifications', '$phone', '$email', '$joining_date', '$address', '$profile_image', NOW())
    ");
    
    if($insert_query) {
        $message = "Faculty added successfully!";
        $message_type = "success";
    } else {
        $message = "Error adding faculty: " . mysqli_error($con);
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Add Faculty</h2>
                <a href="manage-faculty.php" class="btn btn-secondary">
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
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="college_id" class="form-label">College</label>
                            <select class="form-select" id="college_id" name="college_id" required>
                                <option value="">Select College</option>
                                <?php if(mysqli_num_rows($colleges_query) > 0): ?>
                                    <?php while($college = mysqli_fetch_assoc($colleges_query)): ?>
                                        <option value="<?php echo $college['id']; ?>">
                                            <?php echo htmlspecialchars($college['college_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" name="designation" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="qualifications" class="form-label">Qualifications</label>
                        <textarea class="form-control" id="qualifications" name="qualifications" rows="2"></textarea>
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
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="joining_date" class="form-label">Joining Date</label>
                            <input type="date" class="form-control" id="joining_date" name="joining_date">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Faculty
                    </button>
                    <a href="manage-faculty.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
