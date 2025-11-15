<?php
// Ambil status peran dari Session
// $userRole = $_SESSION['user_role'] ?? 'guest';

// Cek peran dan include file yang sesuai
if ($_SESSION['user_role'] === 'admin') {
    // ➡️ Jika Peran Admin
    include __DIR__ . '/admin_header.php';
} else {
    // ➡️ Jika Peran User Biasa (atau default lainnya)
    // Walaupun namanya user_header, ini bisa juga jadi header default yang non-admin
    include __DIR__ . '/user_header.php';
}
