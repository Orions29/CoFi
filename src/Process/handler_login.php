<?php
$_SESSION['testingUser'] = $_POST['usernameLogin'];
// Ini Buat Debug Boy belum ada conn dengan db
$_SESSION['is_logged_in'] = true;

// Section Wajib di dsetiap Handler
// Ngeredirect ke Dashboard
header("Location: /dashboard");
exit();
