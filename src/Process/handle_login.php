<?php
require __DIR__ . "/../Core/init.php";
$_SESSION['testingUser'] = $_POST['usernameLogin'];
$_SESSION['is_logged_in'] = true;
header("Location: /dashboard");

exit;
// exit
