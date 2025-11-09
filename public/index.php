<?php
require __DIR__ . "/../src/Core/init.php";

// Ngambil Routing dari browser kemudian ditampilin
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$page = $requestUri ?: 'index';
// Ngambil variabel is logged in di handle_login
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
$viewsDir = __DIR__ . "/../src/Views/";
$includesDir = __DIR__ . "/../src/includes/";
// LOGIC AUTH 
if (!$isLoggedIn) {
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
    if ($page === 'login' || $page === 'register') {
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
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/styles/styles.css">
    <!-- Icon Untuk Title -->
    <link rel="icon" href="/assets/img/Cofi.png">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@200..700&family=Homenaje&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <?php
    // halaman-halaman yang GAK boleh nampil header
    $noHeaderPages = ['404', 'login', 'register'];
    // Cek apakah user sudah login DAN halaman BUKAN halaman yang dikecualikan
    if ($isLoggedIn && !in_array($page, $noHeaderPages)) {
        // Asumsi $includesDir sudah didefinisikan
        include $pathToHeader;
    }
    // Nampilin Konten di Views
    include $viewFile;

    // Buat Debugging
    // echo "Is Logged In: " . ($isLoggedIn ? 'TRUE' : 'FALSE') . "<br>";
    // echo "Current Page: " . $page . "<br>";
    // echo "User Role: " . $userRole . "<br>";
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>