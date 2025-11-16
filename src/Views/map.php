<?php
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {

    // 2. KALO IYA: Tendang dia ke dashboard-nya sendiri!
    header("Location: /admin/dashboard"); // Arahkan ke dashboard admin
    exit(); // WAJIB MATIKAN SCRIPT!
}
?>
<h1>
    Ini MAp
</h1>