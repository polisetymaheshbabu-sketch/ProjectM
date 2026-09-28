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

// Fetch all faculty for dropdown
$faculty_query = mysqli_query($con, "
    SELECT f.*, c.college_name 
    FROM faculty f 
    LEFT JOIN colleges c ON f.college_id = c.id 
    ORDER BY f.name ASC
");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $faculty_id = mysqli_real_escape_string($con, $_POST['faculty_id']);
    
    // Handle image upload
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Allow certain file formats
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if(in_array($imageFileType, $allowed_types)) {
            if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // Update faculty profile image
                $update_query = mysqli_query($con, "
                    UPDATE faculty 
                    SET profile_image = '$file_name' 
                    WHERE id = '$faculty_id'
                ");
                
                if($update_query) {
                    $message = "Profile picture updated successfully!";
                    $message_type = "success";
                } else {
                    $message = "Error updating profile picture: " . mysqli_error($con);
                    $message_type = "danger";
                }
            } else {
                $message = "Error uploading file.";
                $message_type = "danger";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.";
            $message_type = "danger";
        }
    } else {
        $message = "Please select a file to upload.";
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile Picture - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Update Profile Picture</h2>
                <a href="manage-faculty.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Faculty List
                </a>
            </div>
            
            <?php if($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> 
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-container">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="faculty_id" class="form-label">Select Faculty Member</label>
                                <select class="form-select" id="faculty_id" name="faculty_id" required onchange="showCurrentImage()">
                                    <option value="">Select Faculty</option>
                                    <?php if(mysqli_num_rows($faculty_query) > 0): ?>
                                        <?php while($faculty = mysqli_fetch_assoc($faculty_query)): ?>
                                            <option value="<?php echo $faculty['id']; ?>" 
                                                    data-image="<?php echo $faculty['profile_image']; ?>">
                                                <?php echo htmlspecialchars($faculty['name']); ?> - 
                                                <?php echo htmlspecialchars($faculty['college_name'] ?? 'N/A'); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">New Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required onchange="previewImage()">
                            </div>
                            
                            <div class="mb-3" id="imagePreviewContainer" style="display: none;">
                                <label class="form-label">Image Preview:</label>
                                <img id="imagePreview" src="" alt="Preview" class="img-fluid" style="max-width: 200px; border-radius: 50%;">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-camera"></i> Update Profile Picture
                            </button>
                            <a href="manage-faculty.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-container">
                        <h4>Current Profile Picture</h4>
                        <div id="currentImageContainer" class="text-center">
                            <p class="text-muted">Select a faculty member to view their current profile picture.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function previewImage() {
            const file = document.getElementById('profile_image').files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
        
        function showCurrentImage() {
            const select = document.getElementById('faculty_id');
            const selectedOption = select.options[select.selectedIndex];
            const imageName = selectedOption.getAttribute('data-image');
            const container = document.getElementById('currentImageContainer');
            
            if (imageName && select.value) {
                container.innerHTML = `
                    <img src="../assets/images/${imageName}" alt="Current Profile" 
                         class="img-fluid" style="max-width: 200px; border-radius: 50%; border: 3px solid #3498db;">
                `;
            } else {
                container.innerHTML = '<p class="text-muted">Select a faculty member to view their current profile picture.</p>';
            }
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
