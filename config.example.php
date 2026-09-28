<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/ProjectM/');
}
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', BASE_URL . 'assets/');
}
if (!defined('ADMIN_URL')) {
    define('ADMIN_URL', BASE_URL . 'admin/');
}

$con = mysqli_connect("localhost", "root", "", "college_management");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}