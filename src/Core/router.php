<?php

// Setiap Post akan melewati ini jadi apapun postnya akan langsung di handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // Kalau POstnya Buat Login Attempt maka langsung dikasi ke php login Handler
    if ($_POST['action'] === 'login_attempt') {
        // Manggil Login Handler
        require __DIR__ . "/../Process/handler_login.php";
        // handler.php harus diakhiri dengan redirect dan exit()
    } else if ($_POST['action'] === 'registration_attempt') {
        require __DIR__ . "/../Process/handler_regis.php";
    }
    // Tambahin logic buat handle post form lain di sini (register, add_cafe, dan kawan kawan)

    // Wajib ada exit() setelah redirect di handler
    exit();
}

// Ngambil Routing dari browser kemudian ditampilin
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
// Kalau request Uri itu kosong balikin ke index
$page = $requestUri ?: 'index';
$pageTitle = "";

// Buat Button Logout
if ($page === 'logout') {
    // Manggil destroyer of the session
    require __DIR__ . "/../Process/destroyer.php";
    // destroyer.php nge redirect ke /login dan exit()
    // Lalu Keluar
}

// Path Ke Views dan Include
$viewsDir = __DIR__ . "/../Views/";
$includesDir = __DIR__ . "/../includes/";

// Ngambil variabel is logged in di handle_login
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
// LOGIC AUTH 
if (!$isLoggedIn) {
    // TODO - Buat nanti guest dikasi kemari
    if ($page !== 'login' && $page !== 'register') {
        header("Location: /login"); // Tendang ke /login
        exit();
    }
    $viewFile = $viewsDir . $page . ".php";
} else {
    // Sudah Login
    if ($page === 'index') {
        $page = 'dashboard';
    }
    if ($page === 'login') {
        // Kita turunin dulu boy
        // || $page === 'register'
        header("Location: /dashboard");
        exit();
    }
    $viewFile = $viewsDir . $page . ".php";
}
// Tentukan file yang akan di-load (final check)
if (!file_exists($viewFile)) {
    $viewFile = $viewsDir . "404.php";
    $page = "404";
}

// Buat Nentuin Title Tab
switch ($page) {
    case 'dashboard':
        $pageTitle = "Dashboard |";
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
