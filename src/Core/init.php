<?php

use Dotenv\Dotenv;

require __DIR__ . "/../../vendor/autoload.php";

// Buat Ngeload ENV
$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->safeLoad();

session_start();

// Ngambil Router
require __DIR__ . "/router.php";

// Buat Debugging 
// Buat Nge Verif Auth nya
// $_SESSION['user_role'] = 'admin';
// $_SESSION['is_logged_in'] = false;
