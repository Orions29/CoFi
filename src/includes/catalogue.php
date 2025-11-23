<?php 
// login / db
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Finder - Catalogue</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/assets/styles/style.map.catalogue.dashboard.css">
</head>
<body>
    
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-ca">
    <div class="container justify-content-center">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <span class="brand-title">Cafe Finder</span>
        </a>
    </div>
    <div class="profile-circle">R</div>
</nav>

<!-- Floating catalogue button -->
<div class="floating-catalogue-btn">
    <button class="catalog-btn">
        <span class="material-symbols-outlined">book</span>
        Catalogue
    </button>
</div>

<!-- Page container -->
<div class="page-container">

    <!-- Search & filter -->
    <div class="catalog-header">
        <div class="search-bar">
            <span class="material-symbols-outlined">search</span>
            <input type="text" placeholder="Search Cafe">
        </div>

        <div class="filter-buttons">
            <button><span class="material-symbols-outlined">filter_list</span> Filter</button>
            <button><span class="material-symbols-outlined">sort</span> Sort By Price</button>
        </div>
    </div>

    <!-- Tags -->
    <div class="tags">
        <div class="tag green"><span class="material-symbols-outlined">close_small</span> Productivity</div>
        <div class="tag orange"><span class="material-symbols-outlined">close_small</span> Social Space</div>
        <div class="tag red"><span class="material-symbols-outlined">close_small</span> Roastery</div>
    </div>

    <!-- Cafe card 1 -->
    <div class="cafe-card">
        <div id="cafeCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../img/blanco-coffee-jogja-cafe-shop-blanco-coffee-book.jpg" class="d-block w-100" alt="Blanco Cafe 1">
                </div>
                <div class="carousel-item">
                    <img src="../img/blanco2.jpeg" class="d-block w-100" alt="Blanco Cafe 2">
                </div>
                <div class="carousel-item">
                    <img src="../img/blanco3.jpeg" class="d-block w-100" alt="Blanco Cafe 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#cafeCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#cafeCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="cafe-details">
            <div class="cafe-title">
                <div class="top-row">
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:grey;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:grey;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:grey;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:grey;font-size:50px;">coffee</span>
                    <div class="price">Rp.20.000 - RP.50.000</div>
                </div>

                <div class="title-row">
                    <h2>Blanco Cafe and Lab</h2>
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:40px;">menu_book_2</span>
                </div>
            </div>

            <div class="tags" style="justify-content:flex-start;margin:5px 0;">
                <div class="tag green">Productivity</div>
                <div class="tag orange">Social Space</div>
            </div>

            <div class="cafe-location">
                <span class="material-symbols-outlined">directions</span>
                <span>
                    Jl. Kranggan No.30, Cokrodiningratan, Kec. Jetis, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55233
                </span>
            </div>

            <p class="cafe-desc">
                Cafe dengan konsep modern minimalis yang nyaman untuk bekerja atau hanya sekedar menghabiskan waktu bersama teman.
            </p>
        </div>
    </div>

    <!-- Cafe card 2 -->
    <div class="cafe-card">
        <div id="cafeCarousel2" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../img/klotok1.jpg" class="d-block w-100" alt="Epilogue 1">
                </div>
                <div class="carousel-item">
                    <img src="../img/klotok2.jpg" class="d-block w-100" alt="Epilogue 2">
                </div>
                <div class="carousel-item">
                    <img src="../img/klotok3.jpg" class="d-block w-100" alt="Epilogue 2">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#cafeCarousel2" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#cafeCarousel2" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="cafe-details">
            <div class="cafe-title">
                <div class="top-row">
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:grey;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:grey;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:grey;font-size:50px;">coffee</span>
                    <div class="price">Rp.25.000 - RP.60.000</div>
                </div>

                <div class="title-row">
                    <h2>Kopi Klotok</h2>
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:40px;">menu_book_2</span>
                </div>
            </div>

            <div class="tags" style="justify-content:flex-start;margin:5px 0;">
                <div class="tag orange">Social Space</div>
                <div class="tag red">Roastery</div>
            </div>

            <div class="cafe-location">
                <span class="material-symbols-outlined">directions</span>
                <span>
                    Jalan Kaliurang No.KM.16, Area Sawah, Pakembinangun, Kec. Pakem, Kabupaten Sleman, Daerah Istimewa Yogyakarta
                </span>
            </div>

            <p class="cafe-desc">
                Warung kopi dengan suasana pedesaan yang asri dan menenangkan, cocok untuk bersantai bersama teman atau keluarga.
            </p>
        </div>
    </div>

    <!-- Cafe card 3 -->
    <div class="cafe-card">
        <div id="cafeCarousel3" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../img/cosan1.jpeg" class="d-block w-100" alt="Cosan Reserve at Harper Malioboro 1">
                </div>
                <div class="carousel-item">
                    <img src="../img/cosan2.jpeg" class="d-block w-100" alt="Cosan Reserve at Harper Malioboro 2">
                </div>
                <div class="carousel-item">
                    <img src="../img/cosan3.jpeg" class="d-block w-100" alt="Cosan Reserve at Harper Malioboro 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#cafeCarousel3" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#cafeCarousel3" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="cafe-details">
            <div class="cafe-title">
                <div class="top-row">
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:50px;">coffee</span>
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:50px;">coffee</span>
                        <span class="material-symbols-outlined" style="color:grey;font-size:50px;">coffee</span>
                    <div class="price">Rp.30.000 - RP.90.000</div>
                </div>

                <div class="title-row">
                    <h2>Cosan Reserve at Harper Malioboro</h2>
                    <span class="material-symbols-outlined" style="color:#a65c42;font-size:40px;">menu_book_2</span>
                </div>
            </div>

            <div class="tags" style="justify-content:flex-start;margin:5px 0;">
                <div class="tag green">Productivity</div>
                <div class="tag red">Roastery</div>
            </div>

            <div class="cafe-location">
                <span class="material-symbols-outlined">directions</span>
                <span>
                    Jl. P. Mangkubumi No.52, Gowongan, Kec. Jetis, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55232
                </span>
            </div>

            <p class="cafe-desc">
                Cafe dan roastery dengan konsep elegan dan modern, menawarkan berbagai pilihan kopi spesial dan makanan lezat.
            </p>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
