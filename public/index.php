<?php
require __DIR__ . "/../src/Core/init.php";
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/styles/styles.css">
    <title>
        <?php
        echo $pageTitle;
        ?>
    </title>
    <script src="/assets/js/script.js" defer></script>
    <!-- Icon Untuk Title -->
    <link rel="icon" href="/assets/img/Cofi.png">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@200..700&family=Homenaje&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php
    // Ngecek SQL Connector
    // var_dump($sqlConn);

    // halaman-halaman yang GAK boleh nampil header
    $noHeaderPages = ['404', 'login', 'register'];
    // Cek apakah user sudah login DAN halaman BUKAN halaman yang dikecualikan
    if ($isLoggedIn && !in_array($page, $noHeaderPages)) {
        // Asumsi $includesDir sudah didefinisikan
        include $includesDir . "/header.php";
    }
    // kalau Ada Alert
    if (isset($_SESSION['alert'])) {
        include $includesDir . "/alert.php";
    }
    var_dump($_SESSION);

    // Nampilin Konten di Views
    include $viewFile;
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>