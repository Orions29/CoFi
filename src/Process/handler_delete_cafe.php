<?php
require __DIR__ . "/../Core/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Forbidden");
}

$cafe_id = $_POST['id'] ?? null;
if (!$cafe_id) die("Invalid cafe ID");

$stmt = $sqlConn->prepare("SELECT file_path FROM cafe_photos WHERE cafe_id = ?");
$stmt->bind_param("i", $cafe_id);
$stmt->execute();
$photos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

foreach ($photos as $p) {
    $file = __DIR__ . "/../public" . $p['file_path'];
    if (file_exists($file)) unlink($file);
}

$delPhotos = $sqlConn->prepare("DELETE FROM cafe_photos WHERE cafe_id = ?");
$delPhotos->bind_param("i", $cafe_id);
$delPhotos->execute();

$delCafe = $sqlConn->prepare("DELETE FROM cafes WHERE cafe_id = ?");
$delCafe->bind_param("i", $cafe_id);
$delCafe->execute();

header("Location: /cafe/update");
exit();
