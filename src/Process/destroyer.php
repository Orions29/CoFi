<?php
$_SESSION = [];
require __DIR__ . "/../Core/init.php";
session_destroy();
header("Location: /login");
exit();
