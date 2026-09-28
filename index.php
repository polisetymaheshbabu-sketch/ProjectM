<?php
require_once __DIR__ . '/config.php';
include 'includes/header.php';
?>
<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1562774053-701939374585?w=1200');">
            <div class="carousel-caption">
                <h1>Welcome to College Management System</h1>
                <p>Empowering Education Through Technology</p>
                <a href="<?php echo BASE_URL; ?>faculty.php" class="btn btn-primary btn-lg">View Faculty</a>
            </div>
        </div>
        <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=1200');">
            <div class="carousel-caption">
                <h1>Excellence in Education</h1>
                <p>Building Tomorrow's Leaders Today</p>
                <a href="<?php echo BASE_URL; ?>about.php" class="btn btn-primary btn-lg">Learn More</a>
            </div>
        </div>
        <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1200');">
            <div class="carousel-caption">
                <h1>Join Our Community</h1>
                <p>Connect with Top Faculty and Institutions</p>
                <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-primary btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Introduction Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h2 class="mb-4">About Our System</h2>
            <p class="lead">
                Welcome to the College Management System - a comprehensive platform designed to streamline 
                educational institution management. Our system provides efficient tools for managing faculty, 
                colleges, and administrative tasks in one centralized location.
            </p>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Faculty Management</h5>
                    <p class="card-text">Comprehensive faculty directory with detailed profiles and information.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-university fa-3x text-success mb-3"></i>
                    <h5 class="card-title">College Directory</h5>
                    <p class="card-text">Complete database of affiliated colleges and institutions.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-cogs fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Admin Control</h5>
                    <p class="card-text">Powerful admin panel for managing all system components.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">System Statistics</h3>
                    <div class="row text-center">
                        <?php
                        // Get statistics
                        $total_faculty = mysqli_query($con, "SELECT COUNT(*) as count FROM faculty");
                        $faculty_count = mysqli_fetch_assoc($total_faculty)['count'];
                        
                        $total_colleges = mysqli_query($con, "SELECT COUNT(*) as count FROM colleges");
                        $college_count = mysqli_fetch_assoc($total_colleges)['count'];
                        
                        $total_subadmins = mysqli_query($con, "SELECT COUNT(*) as count FROM subadmins");
                        $subadmin_count = mysqli_fetch_assoc($total_subadmins)['count'];
                        ?>
                        <div class="col-md-4">
                            <h1 class="text-primary"><?php echo $faculty_count; ?></h1>
                            <p>Total Faculty</p>
                        </div>
                        <div class="col-md-4">
                            <h1 class="text-success"><?php echo $college_count; ?></h1>
                            <p>Total Colleges</p>
                        </div>
                        <div class="col-md-4">
                            <h1 class="text-warning"><?php echo $subadmin_count; ?></h1>
                            <p>Sub-Admins</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>