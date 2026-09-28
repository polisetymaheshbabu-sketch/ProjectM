<div class="sidebar">
    <div class="sidebar-header">
        <h3>Sub-Admin Panel</h3>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="<?php echo BASE_URL; ?>subadmin/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>

        <li class="menu-header">College Management</li>
        <li>
            <a href="<?php echo BASE_URL; ?>subadmin/add-college.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-college.php' ? 'active' : ''; ?>">
                <i class="fas fa-building"></i> Add College
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>subadmin/manage-colleges.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-colleges.php' ? 'active' : ''; ?>">
                <i class="fas fa-university"></i> Manage Colleges
            </a>
        </li>

        <li class="menu-header">Faculty Management</li>
        <li>
            <a href="<?php echo BASE_URL; ?>subadmin/add-faculty.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-faculty.php' ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher"></i> Add Faculty
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>subadmin/manage-faculty.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-faculty.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-tie"></i> Manage Faculty
            </a>
        </li>

        <li class="menu-header">Account</li>
        <li>
            <a href="<?php echo BASE_URL; ?>subadmin/update-profile-pic.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'update-profile-pic.php' ? 'active' : ''; ?>">
                <i class="fas fa-camera"></i> Update Profile Picture
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>subadmin/change-password.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'change-password.php' ? 'active' : ''; ?>">
                <i class="fas fa-key"></i> Change Password
            </a>
        </li>
        <li style="margin-top: 20px; border-top: 1px solid #34495e;">
            <a href="<?php echo BASE_URL; ?>subadmin/logout.php" style="color: #f39c12;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>
