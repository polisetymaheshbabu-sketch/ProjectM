<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['faculty_logged_in']);
unset($_SESSION['faculty_id']);
unset($_SESSION['faculty_username']);
unset($_SESSION['faculty_name']);
session_destroy();

header('Location: login.php');
exit();