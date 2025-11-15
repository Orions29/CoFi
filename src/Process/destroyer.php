<?php
// Destroyer Session dan destroyer handler

$_SESSION = [];
session_destroy();
header("Location: /login");
exit();
