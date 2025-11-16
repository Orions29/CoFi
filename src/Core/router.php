<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'login_attempt') {
        require __DIR__ . "/../Process/handler_login.php";
    } else if ($_POST['action'] === 'regis_attempt') {
        require __DIR__ . "/../Process/handler_regis.php";
    }
    // logic buat handle post form lain di sini (register, add_cafe, dan kawan kawan)

    exit(); // Wajib ada exit() setelah redirect di handler
}

// Ngambil Routing dari browser kemudian ditampilin
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$page = $requestUri ?: 'index';
$pageTitle = "";

// Daftar Header URI yang bisa dipanggil
$routeMap = [
    'user/map' => 'map',
    'user/saved-cafe' => 'saved_cafe',

    'cafe/add' => 'add_cafe',
    'cafe/update' => 'update_cafe',

    'admin/usermanage' => 'admin_usermanage',
    'admin/dashboard' => 'admin_dashboard'
];

// Terapkan alias
if (isset($routeMap[$page])) {
    $page = $routeMap[$page];
}

// Buat Button Logout
if ($page === 'logout') {
    require __DIR__ . "/../Process/destroyer.php";
}

// Path Ke Views dan Include
$viewsDir = __DIR__ . "/../Views/";
$viewsDirAdmin = __DIR__ . "/../Views/admin/";
$includesDir = __DIR__ . "/../includes/";

// Ngambil variabel is logged in dan role admin
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';

// Auth LOGIC
if (!$isLoggedIn) {
    // Guest Logic
    // Kalo belum login, cuma boleh akses login & register
    if ($page !== 'login' && $page !== 'register') {
        header("Location: /login");
        exit();
    }
    $viewFile = $viewsDir . $page . ".php"; // Tetap di Views/
} else {
    // Sudah Login

    // Kalo dah login ga ke login dan register
    if ($page === 'index' || $page === 'login' || $page === 'register') {
        // Jika sudah login, tendang ke dashboard mereka
        if ($isAdmin) {
            header("Location: /admin/dashboard"); // Asumsi URL admin dashboard
        } else {
            header("Location: /dashboard"); // URL user dashboard
        }
        exit();
    }

    // User Restrict
    $halamanAdmin = ['admin_dashboard', 'admin_usermanage', 'add_cafe', 'update_cafe'];
    if (!$isAdmin && in_array($page, $halamanAdmin)) {
        header("Location: /dashboard"); // Tendang ke dashboard user
        exit();
    }

    // Admin Restrict
    $halamanUser = ['dashboard', 'map']; // 'user_dashboard' udah jadi 'dashboard'
    if ($isAdmin && in_array($page, $halamanUser)) {
        header("Location: /admin/dashboard"); // Tendang ke dashboard admin
        exit();
    }

    // Pengubah viewsFile yang nandi di include di index
    $viewFile = $viewsDir . $page . ".php"; // Set default ke folder Views/

    if ($isAdmin) {
        // Kalo admin, kita cek folder admin
        // Ngecek depannya ada adminnya ndak kalau belum dikasi langsung admin_
        $adminViewName = str_starts_with($page, 'admin_') ? $page : 'admin_' . $page;

        // Buat path ke Views/admin/
        $adminViewFile = $viewsDirAdmin . $adminViewName . ".php";

        // Ngecek Apakah File Admin ada di direktori Admin
        if (file_exists($adminViewFile)) {
            // Timpa viewFile dengan path admin
            $viewFile = $adminViewFile;
            $page = $adminViewName; // Update $page agar Title Tab-nya benar
        }
    }
}

// File yang akan ke load
if (!file_exists($viewFile)) {
    $viewFile = $viewsDir . "404.php";
    $page = "404";
}

// Buat Nentuin Title Tab
switch ($page) {
    case 'admin_dashboard':
        $pageTitle = "ADMIN | Dashboard";
        break;
    case 'admin_usermanage':
        $pageTitle = "ADMIN | User Management";
        break;
    case 'dashboard':
        $pageTitle = "Dashboard |";
        break;
    case 'map': // Tambahin case buat title-nya
        $pageTitle = "Peta Kedai";
        break;
    case 'add_cafe':
        $pageTitle = "Tambah Kedai";
        break;
    case 'login':
        $pageTitle = "Masuk ke CoFi";
        break;
    case 'register':
        $pageTitle = "Daftar Akun Baru";
        break;
    case '404':
        $pageTitle = "404 - Lek Nggenah Ae MAS";
        break;
    default:
        $pageTitle = "Welcome to CoFi App";
        break;
}
