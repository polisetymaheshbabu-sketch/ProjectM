<?php
require_once __DIR__ . '/config.php';
include 'includes/header.php';

// Check if faculty ID is provided
if(!isset($_GET['fid']) || empty($_GET['fid'])) {
    header('Location: faculty.php');
    exit();
}

$faculty_id = mysqli_real_escape_string($con, $_GET['fid']);

// Fetch faculty details with college information
$faculty_query = mysqli_query($con, "
    SELECT f.*, c.college_name, c.college_code 
    FROM faculty f 
    LEFT JOIN colleges c ON f.college_id = c.id 
    WHERE f.id = '$faculty_id'
");

$faculty = mysqli_fetch_assoc($faculty_query);

if(!$faculty) {
    echo '<div class="container my-5"><div class="alert alert-danger">Faculty member not found.</div></div>';
    include 'includes/footer.php';
    exit();
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>faculty.php">Faculty</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($faculty['name']); ?></li>
                </ol>
            </nav>
            
            <div class="faculty-details">
                <div class="row">
                   <div class="col-md-4 text-center">
                         <?php if(!empty($faculty['profile_image'])): ?>
                             <img src="<?php echo ASSETS_URL; ?>uploads/<?php echo htmlspecialchars($faculty['profile_image']); ?>" 
                                  alt="<?php echo htmlspecialchars($faculty['name']); ?>" 
                                  class="img-fluid">
                         <?php else: ?>
                             <i class="fas fa-user-circle" style="font-size: 150px; color: #cbd5e1;"></i>
                         <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <div class="faculty-info">
                            <h3><?php echo htmlspecialchars($faculty['name']); ?></h3>
                            
                            <p><strong>Designation:</strong> <?php echo htmlspecialchars($faculty['designation']); ?></p>
                            
                            <?php if($faculty['college_name']): ?>
                                <p><strong>College:</strong> <?php echo htmlspecialchars($faculty['college_name']); ?></p>
                            <?php endif; ?>
                            
                            <?php if($faculty['college_code']): ?>
                                <p><strong>College Code:</strong> <?php echo htmlspecialchars($faculty['college_code']); ?></p>
                            <?php endif; ?>
                            
                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($faculty['gender']); ?></p>
                            
                            <?php if($faculty['qualifications']): ?>
                                <p><strong>Qualifications:</strong> <?php echo htmlspecialchars($faculty['qualifications']); ?></p>
                            <?php endif; ?>
                            
                            <?php if($faculty['phone']): ?>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($faculty['phone']); ?></p>
                            <?php endif; ?>
                            
                            <?php if($faculty['email']): ?>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($faculty['email']); ?></p>
                            <?php endif; ?>
                            
                            <?php if($faculty['joining_date']): ?>
                                <p><strong>Joining Date:</strong> <?php echo date('F j, Y', strtotime($faculty['joining_date'])); ?></p>
                            <?php endif; ?>
                            
                            <?php if($faculty['address']): ?>
                                <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($faculty['address'])); ?></p>
                            <?php endif; ?>
                            
                            <div class="mt-4">
                                <a href="<?php echo BASE_URL; ?>faculty.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Faculty
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
