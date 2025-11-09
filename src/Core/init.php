<?php
session_start();

// Ngambil Koneksi Database
require __DIR__ . "/database.php";

// Path Section
$pathToHeader = __DIR__ . "/../includes/header.php";
// $pathToFooter = __DIR__ . "/../includes/footer.php";

// Status Admin
$adminStatus = true;

// Buat Debugging 
// Buat Nge Verif Auth nya
$_SESSION['user_role'] = 'admin';
// $_SESSION['is_logged_in'] = false;
