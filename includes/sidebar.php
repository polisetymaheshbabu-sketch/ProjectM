<!-- Admin Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h4>Admin Panel</h4>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="<?php echo ADMIN_URL; ?>dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="menu-header">Sub-Admin Management</li>
        <li>
            <a href="<?php echo ADMIN_URL; ?>add-subadmin.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-subadmin.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-plus"></i> Add Sub-Admin
            </a>
        </li>
        <li>
            <a href="<?php echo ADMIN_URL; ?>manage-subadmins.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-subadmins.php' ? 'active' : ''; ?>">
                <i class="fas fa-users-cog"></i> Manage Sub-Admins
            </a>
        </li>
        <li class="menu-header">College Management</li>
        <li>
            <a href="<?php echo ADMIN_URL; ?>add-college.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-college.php' ? 'active' : ''; ?>">
                <i class="fas fa-building"></i> Add College
            </a>
        </li>
        <li>
            <a href="<?php echo ADMIN_URL; ?>manage-colleges.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-colleges.php' ? 'active' : ''; ?>">
                <i class="fas fa-university"></i> Manage Colleges
            </a>
        </li>
        <li class="menu-header">Faculty Management</li>
        <li>
            <a href="<?php echo ADMIN_URL; ?>add-faculty.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-faculty.php' ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher"></i> Add Faculty
            </a>
        </li>
        <li>
            <a href="<?php echo ADMIN_URL; ?>manage-faculty.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-faculty.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-tie"></i> Manage Faculty
            </a>
        </li>
        <li>
            <a href="<?php echo ADMIN_URL; ?>update-profile-pic.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'update-profile-pic.php' ? 'active' : ''; ?>">
                <i class="fas fa-camera"></i> Update Profile Picture
            </a>
        </li>
        <li>
            <a href="<?php echo ADMIN_URL; ?>change-password.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'change-password.php' ? 'active' : ''; ?>">
                <i class="fas fa-key"></i> Change Password
            </a>
        </li>
                        <li style="margin-top: 20px; border-top: 1px solid #34495e;">
                    <a href="<?php echo ADMIN_URL; ?>logout.php" style="color:  #f39c12;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>

            </ul>
    </ul>
</div>
