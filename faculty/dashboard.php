<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['faculty_logged_in']) || $_SESSION['faculty_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$faculty_id = (int) $_SESSION['faculty_id'];

$result = mysqli_query($con, "
    SELECT f.*, c.college_name
    FROM faculty f
    LEFT JOIN colleges c ON f.college_id = c.id
    WHERE f.id = $faculty_id
");
$faculty = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - College Management System</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
        }
        .top-bar {
            background: #1e293b;
            color: #f1f5f9;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-bar a {
            color: #f87171;
            text-decoration: none;
        }
        .top-bar a:hover {
            text-decoration: underline;
        }
        .profile-card {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            padding: 30px;
        }
        .profile-card img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #22d3ee;
        }
        .profile-info p {
            margin-bottom: 10px;
        }
        .profile-info strong {
            color: #0f172a;
            display: inline-block;
            width: 140px;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div>
            <i class="fas fa-chalkboard-teacher"></i> Faculty Portal
        </div>
        <div>
            Welcome, <?php echo htmlspecialchars($_SESSION['faculty_name']); ?> &nbsp;|&nbsp;
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="profile-card">
        <div class="text-center mb-4">
            <?php if (!empty($faculty['profile_image'])): ?>
                <img src="<?php echo ASSETS_URL . 'uploads/' . htmlspecialchars($faculty['profile_image']); ?>" alt="Profile Photo">
            <?php else: ?>
                <i class="fas fa-user-circle" style="font-size: 130px; color: #cbd5e1;"></i>
            <?php endif; ?>
            <h3 class="mt-3 mb-0"><?php echo htmlspecialchars($faculty['name']); ?></h3>
            <p class="text-muted"><?php echo htmlspecialchars($faculty['designation']); ?></p>
        </div>

        <div class="text-center mt-4">
           <a href="edit-profile.php" class="btn btn-outline-primary">
               <i class="fas fa-user-edit"></i> Edit Profile
           </a>
        </div>
        
        <div class="profile-info">
            <p><strong>College:</strong> <?php echo htmlspecialchars($faculty['college_name'] ?? 'N/A'); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($faculty['gender']); ?></p>
            <p><strong>Qualifications:</strong> <?php echo htmlspecialchars($faculty['qualifications'] ?? 'N/A'); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($faculty['phone'] ?? 'N/A'); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($faculty['email'] ?? 'N/A'); ?></p>
            <p><strong>Joining Date:</strong> <?php echo $faculty['joining_date'] ? date('M d, Y', strtotime($faculty['joining_date'])) : 'N/A'; ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($faculty['address'] ?? 'N/A'); ?></p>
        </div>
    </div>
</body>
</html>