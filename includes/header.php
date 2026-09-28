<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Management System</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">
                <i class="fas fa-university"></i> College Management System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>faculty.php">Faculty</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>contact.php">Contact Us</a>
                    </li>
                    <?php if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <!-- Admin is logged in, but Admin Panel/Logout links are kept out of the public navbar.
                             The admin can still reach the panel via its own URL or bookmark. -->
                    <?php elseif(isset($_SESSION['faculty_logged_in']) && $_SESSION['faculty_logged_in'] === true): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>faculty/dashboard.php">My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>faculty/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" id="loginNavLink" href="<?php echo BASE_URL; ?>login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <style>
        body {
            transition: opacity 0.25s cubic-bezier(0.55, 0, 0.85, 0.35), transform 0.25s cubic-bezier(0.55, 0, 0.85, 0.35);
            transform: scale(1);
        }
        body.page-fade-out {
            opacity: 0;
            transform: scale(0.85);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginLink = document.getElementById('loginNavLink');
            if (loginLink) {
                loginLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = this.href;
                    document.body.classList.add('page-fade-out');
                    setTimeout(function () {
                        window.location.href = target;
                    }, 250);
                });
            }
        });

        // Fixes a blank white page when the browser restores this page from
        // its back/forward cache still carrying the punch-out class.
        window.addEventListener('pageshow', function (event) {
            document.body.classList.remove('page-fade-out');
        });
    </script>