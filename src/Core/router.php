<?php

// Nangkep Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $handlerAction = $_POST['action'];
    switch ($handlerAction) {
        case 'login_attempt':
            require __DIR__ . "/../Process/handler_login.php";
            break;
        case 'regis_attempt':
            require __DIR__ . "/../Process/handler_regis.php";
            break;
        case 'add_cafe_attempt':
            require __DIR__ . "/../Process/handler_add_cafe.php";
            break;
        default:
            break;
    }
    exit();
}

//NOTE - Daftar Halaman Yang bisa di buka
/* 
Format
'reqURI' => [
        'file' => 'Nama_file',
        'title' => 'Judul Filenya apa',
        'auth' => 'user'/ 'guest_only' / 'admin'
    ]
*/
$routes = [
    'index' => [
        'file' => 'dashboard',
        'title' => 'Welcome',
        'auth' => 'user'
    ],
    'login' => [
        'file' => 'login',
        'title' => 'Masuk ke CoFi',
        'auth' => 'guest_only'
    ],
    'register' => [
        'file' => 'register',
        'title' => 'Buat Akun CoFi',
        'auth' => 'guest_only'
    ],
    'dashboard' => [
        'file' => 'dashboard',
        'title' => 'Dashboard',
        'auth' => 'user'
    ],
    'admin/dashboard' => [
        'file' => 'admin_dashboard',
        'title' => 'ADMIN | Dashboard',
        'auth' => 'admin'
    ],
    'admin/usermanage' => [
        'file' => 'admin_usermanage',
        'title' => 'ADMIN | User Management',
        'auth' => 'admin'
    ],
    'cafe/add' => [
        'file' => 'admin_add_cafe',
        'title' => 'ADMIN | Tambah Cafe',
        'auth' => 'admin'
    ],
    'cafe/update' => [
        'file' => 'admin_update_cafe',
        'title' => 'ADMIN | Update Cafe',
        'auth' => 'admin'
    ],
    'logout' => [
        'file' => 'logout_handler',
        'title' => 'Logout',
        'auth' => 'user_or_admin'
    ],
    '404' => [
        'file' => '404',
        'title' => '404 - Nggak Ketemu',
        'auth' => 'public'
    ]
];

// Ngambil Request URI
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$routeKey = $requestUri ?: 'index';

if (!isset($routes[$routeKey])) {
    $routeKey = '404';
}

// Ambil info rute yang valid
$currentRoute = $routes[$routeKey];
// Nama FIle
$page = $currentRoute['file'];
// Nama Title
$pageTitle = $currentRoute['title'];
// Auth Role
$authRule = $currentRoute['auth'];

// Ngecek Status Logged In
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';

// Ngeset User Role
$userRole = 'guest';
if ($isLoggedIn && $isAdmin) {
    $userRole = 'admin';
} else if ($isLoggedIn) {
    $userRole = 'user';
}

// Role AUTH
// Guest (login, register)
if ($authRule === 'guest_only' && $userRole !== 'guest') {
    if ($userRole === 'admin') {
        header("Location: /admin/dashboard");
    } else { // User
        header("Location: /dashboard");
    }
    exit();
}
// User (dashboard)
if ($authRule === 'user') {
    if ($userRole === 'guest') {
        header("Location: /login"); // Belum login, tendang ke login
        exit();
    }
    if ($userRole === 'admin') {
        header("Location: /admin/dashboard"); // Admin nyasar, tendang
        exit();
    }
}
// Admin (admin/usermanage)
if ($authRule === 'admin' && $userRole !== 'admin') {
    // Harusnya admin, tapi dia user atau tamu
    header("Location: /login"); // Tendang ke login
    exit();
}
// User Admin
if ($authRule === 'user_or_admin' && $userRole === 'guest') {
    // Tamu mau logout? Suruh login dulu
    header("Location: /login");
    exit();
}

// Path Path Penting
$includesDir = __DIR__ . "/../includes/";
$viewsDir = __DIR__ . "/../Views/";
$viewsDirAdmin = __DIR__ . "/../Views/admin/";
$uploadDir = __DIR__ . "/../../uploads";

if ($page === 'logout_handler') {
    require __DIR__ . "/../Process/destroyer.php";
    exit();
}

// Yang Akan Ditampilkan Nantinya
$viewFile = $viewsDir . $page . ".php";

// Kalau Misal Mau ngakses halaman admin, Soalnya dia ada di folder tersendiri dengan format admin_namaFile.php
if ($isAdmin && str_starts_with($page, 'admin_')) {
    $adminViewFile = $viewsDirAdmin . $page . ".php";
    if (file_exists($adminViewFile)) {
        $viewFile = $adminViewFile;
    }
}

// Cek terakhir kalo file view-nya beneran ada
if (!file_exists($viewFile)) {
    $viewFile = $viewsDir . "404.php";
    $pageTitle = $routes['404']['title']; // Ambil title 404
}
