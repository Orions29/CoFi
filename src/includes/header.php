<?php
// Ambil status peran dari Session

// Cek peran dan include file yang sesuai
if ($_SESSION['user_role'] === 'admin') {
    include __DIR__ . '/admin_header.php';
} else {
    // Walaupun namanya user_header, ini bisa juga jadi header default yang non-admin
    include __DIR__ . '/user_header.php';
}
