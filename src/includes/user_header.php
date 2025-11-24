<?php
$pageTitle = "Laman User";
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=dashboard,edit,storefront,supervised_user_circle" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:FILL@0;wght@400;GRAD@0;opsz@24" />

<header class="dashboard-header">
    <div class="container-header">
        <a href="/dashboard" style="color: white; text-decoration:none;">
            <div class="page-title">
                <span class="material-symbols-outlined" style="font-size: 3rem;">
                    map_search
                </span>
                <h1>The Map</h1>
            </div>
        </a>
        <div class="hero-logo">
            <svg class="hero-logo-icons" viewBox="0 0 138 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="CoFi Logo">
                    <g id="I">
                        <path id="I_2" d="M131.104 61.2045V14.6546H138V61.2045H131.104Z" />
                        <path id="dotForI" d="M131.104 6.03425V0H138V6.03425H131.104Z" />
                    </g>
                    <path id="F" d="M94.0217 61.3998V0H119.897V5.96462H101.741V27.0161H115.424V32.9805H101.741V61.3998H94.0217Z" />
                    <g id="Beans">
                        <path id="LeftBeans" d="M45.6671 51.2792C57.4643 66.1672 61.0039 55.801 62.1173 43.2085C61.8834 32.8548 53.3801 23.6656 55.3532 13.0776C55.2829 10.7182 58.7821 4.85867 56.7226 3.51971C44.8185 6.70294 39.5695 20.0898 39.7165 31.7117C39.8039 39.0832 41.9173 46.0327 45.6671 51.2792Z" />
                        <path id="RightBeans" d="M65.0697 3.44814C63.1923 3.55343 62.0421 4.515 61.3225 5.83773C54.8968 19.1026 66.2199 30.9439 66.1426 44.0609C66.0752 48.9274 63.3395 53.356 63.5986 58.2161C69.2351 60.1108 73.9582 54.9394 77.3662 50.4946C86.8406 37.1183 84.7001 7.3931 65.0697 3.44814Z" />
                    </g>
                    <path id="C" d="M16.7534 61.3999C11.4322 61.3999 7.30955 60.0549 4.38571 57.365C1.46187 54.6751 0 50.728 0 45.5235V15.8763C0 10.672 1.46187 6.72476 4.38571 4.03499C7.30955 1.34513 11.4322 0 16.7534 0H28.5071V5.78926H16.7534C14.0636 5.78926 11.8853 6.56406 10.2187 8.11366C8.5521 9.66326 7.71888 11.7246 7.71888 14.2974V47.1025C7.71888 49.6755 8.5521 51.7366 10.2187 53.2862C11.8853 54.8358 14.0636 55.6108 16.7534 55.6108H28.5071V61.3999H16.7534Z" />
                </g>
            </svg>
        </div>
        <div class="admin-greet">
            <a href="/logout">Welcome Back, <?php
                                            echo htmlspecialchars($_SESSION['username']);
                                            ?></a>
        </div>
    </div>
    <nav class="navbar-tabs user">
        <a href="/calagoue">
            <div class="navbar-tab" id="navbar-dashboard">
                <span class="material-symbols-outlined">
                    dashboard
                </span>Catalouge
            </div>
        </a>
        <a href="#">
            <div class="navbar-tab" id="navbar-update-cafe">
                <span class="material-symbols-outlined">
                    edit
                </span> Coming Soon
            </div>
        </a>
    </nav>
</header>