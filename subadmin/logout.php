<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['subadmin_logged_in']);
unset($_SESSION['subadmin_id']);
unset($_SESSION['subadmin_username']);
unset($_SESSION['subadmin_name']);
unset($_SESSION['subadmin_profile']);
session_destroy();

header('Location: login.php');
exit();
