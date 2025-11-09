<?php
session_start();

// Ngambil Koneksi Database
require __DIR__ . "/database.php";
include __DIR__ . "/../Process/handle_login.php";

// Path Section
$pathToHeader = __DIR__ . "/../includes/header.php";
// $pathToFooter = __DIR__ . "/../includes/footer.php";

// Status Admin
$adminStatus = true;
