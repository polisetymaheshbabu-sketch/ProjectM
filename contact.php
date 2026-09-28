<?php
require_once __DIR__ . '/config.php';
include 'includes/header.php';

// Fetch contact information from database
$contact_query = mysqli_query($con, "SELECT * FROM pages WHERE page_name = 'contact'");
$contact_content = mysqli_fetch_assoc($contact_query);
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                    <li class="breadcrumb-item active">Contact Us</li>
                </ol>
            </nav>
            
            <h2 class="mb-4">Contact Us</h2>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-info">
                        <h4>Get in Touch</h4>
                        
                        <?php if($contact_content): ?>
                            <?php echo nl2br(htmlspecialchars($contact_content['content'])); ?>
                        <?php else: ?>
                            <div class="mb-4">
                                <p><i class="fas fa-map-marker-alt"></i> 
                                    <strong>Address:</strong><br>
                                    123 Education Street, Knowledge City<br>
                                    State - 123456
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <p><i class="fas fa-phone"></i> 
                                    <strong>Support Phone:</strong><br>
                                    +91 98765 43210
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <p><i class="fas fa-envelope"></i> 
                                    <strong>Support Email:</strong><br>
                                    support@collegemanagement.com
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <p><i class="fas fa-clock"></i> 
                                    <strong>Working Hours:</strong><br>
                                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                                    Saturday: 9:00 AM - 1:00 PM
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4>Send us a Message</h4>
                            <form action="contact-process.php" method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
