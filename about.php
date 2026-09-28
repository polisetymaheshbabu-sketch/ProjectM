<?php
require_once __DIR__ . '/config.php';
include 'includes/header.php';

// Fetch about content from database
$about_query = mysqli_query($con, "SELECT * FROM pages WHERE page_name = 'about'");
$about_content = mysqli_fetch_assoc($about_query);
?>

<div class="container about-section">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                    <li class="breadcrumb-item active">About Us</li>
                </ol>
            </nav>
            
            <h2>About Our College Platform</h2>
            
            <?php if($about_content): ?>
                <div class="card">
                    <div class="card-body">
                        <?php echo nl2br(htmlspecialchars($about_content['content'])); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body">
                        <p>
                            Welcome to our College Management System - a cutting-edge platform designed to revolutionize 
                            how educational institutions manage their faculty, colleges, and administrative operations.
                        </p>
                        <p>
                            Our mission is to provide a seamless, efficient, and user-friendly system that connects 
                            educational institutions, faculty members, and administrators in one unified platform.
                        </p>
                        <h4>Our Vision</h4>
                        <p>
                            To become the leading college management platform that empowers educational institutions 
                            with modern technology, enabling them to focus on what matters most - providing quality 
                            education to students.
                        </p>
                        <h4>What We Offer</h4>
                        <ul>
                            <li>Comprehensive faculty management system</li>
                            <li>Centralized college directory</li>
                            <li>Advanced administrative controls</li>
                            <li>Real-time statistics and reporting</li>
                            <li>Secure and scalable infrastructure</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Mission and Vision Cards -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                    <h4>Our Mission</h4>
                    <p>
                        To streamline educational institution management through innovative technology solutions, 
                        making administrative tasks efficient and allowing institutions to focus on academic excellence.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-eye fa-3x text-success mb-3"></i>
                    <h4>Our Vision</h4>
                    <p>
                        To be the most trusted and comprehensive college management platform, serving educational 
                        institutions worldwide with cutting-edge technology and exceptional service.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>