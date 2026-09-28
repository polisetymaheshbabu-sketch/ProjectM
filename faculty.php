<?php
require_once __DIR__ . '/config.php';
include 'includes/header.php';

// Fetch all faculty with college information
$faculty_query = mysqli_query($con, "
    SELECT f.*, c.college_name 
    FROM faculty f 
    LEFT JOIN colleges c ON f.college_id = c.id 
    ORDER BY f.created_at DESC
");
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                    <li class="breadcrumb-item active">Faculty Directory</li>
                </ol>
            </nav>
            
            <h2 class="mb-4">Faculty Directory</h2>
            
            <?php if(mysqli_num_rows($faculty_query) > 0): ?>
                <div class="row">
                    <?php while($faculty = mysqli_fetch_assoc($faculty_query)): ?>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card faculty-card">
                                <?php if(!empty($faculty['profile_image'])): ?>
                                <img src="<?php echo ASSETS_URL; ?>uploads/<?php echo htmlspecialchars($faculty['profile_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($faculty['name']); ?>" 
                                     class="img-fluid">
                            <?php else: ?>
                                <i class="fas fa-user-circle" style="font-size: 100px; color: #cbd5e1;"></i>
                            <?php endif; ?>
                                
                                <h4><?php echo htmlspecialchars($faculty['name']); ?></h4>
                                <p class="text-muted"><?php echo htmlspecialchars($faculty['designation']); ?></p>
                                
                                <?php if($faculty['college_name']): ?>
                                    <p class="text-primary">
                                        <i class="fas fa-university"></i> 
                                        <?php echo htmlspecialchars($faculty['college_name']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <a href="<?php echo BASE_URL; ?>view-faculty-details.php?fid=<?php echo $faculty['id']; ?>" 
                                   class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No faculty members found in the system.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
