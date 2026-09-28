<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['subadmin_logged_in']) || $_SESSION['subadmin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$total_faculty = mysqli_query($con, "SELECT COUNT(*) as count FROM faculty");
$faculty_count = mysqli_fetch_assoc($total_faculty)['count'];

$total_colleges = mysqli_query($con, "SELECT COUNT(*) as count FROM colleges");
$college_count = mysqli_fetch_assoc($total_colleges)['count'];

$recent_faculty = mysqli_query($con, "
    SELECT f.*, c.college_name
    FROM faculty f
    LEFT JOIN colleges c ON f.college_id = c.id
    ORDER BY f.created_at DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sub-Admin Panel</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/subadmin-sidebar.php'; ?>

        <div class="admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Dashboard</h2>
                <span class="badge bg-primary">Welcome, <?php echo htmlspecialchars($_SESSION['subadmin_name']); ?> (<?php echo htmlspecialchars($_SESSION['subadmin_profile']); ?>)</span>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="stat-card success">
                        <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <h3><?php echo $faculty_count; ?></h3>
                        <p>Total Faculty</p>
                        <a href="manage-faculty.php" class="btn btn-sm btn-outline-success">View All</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card warning">
                        <div class="icon"><i class="fas fa-university"></i></div>
                        <h3><?php echo $college_count; ?></h3>
                        <p>Total Colleges</p>
                        <a href="manage-colleges.php" class="btn btn-sm btn-outline-warning">View All</a>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Faculty Additions</h5>
                            <a href="manage-faculty.php" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <?php if(mysqli_num_rows($recent_faculty) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr><th>Name</th><th>College</th><th>Date</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php while($faculty = mysqli_fetch_assoc($recent_faculty)): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($faculty['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($faculty['college_name'] ?? 'N/A'); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($faculty['created_at'])); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No recent faculty additions.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Quick Actions</h5></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <a href="add-college.php" class="btn btn-warning w-100">
                                        <i class="fas fa-building"></i> Add College
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <a href="add-faculty.php" class="btn btn-success w-100">
                                        <i class="fas fa-chalkboard-teacher"></i> Add Faculty
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <a href="update-profile-pic.php" class="btn btn-info w-100">
                                        <i class="fas fa-camera"></i> Update Profile Pic
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
