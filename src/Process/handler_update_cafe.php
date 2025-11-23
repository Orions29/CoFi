<?php
require __DIR__ . "/../Core/database.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
die("Forbidden Knowledge");
}


$cafe_id = $_POST['id'] ?? null;
$nama = $_POST['nama'] ?? '';
$rating = $_POST['rating'] ?? 0;
$min_price = $_POST['min_price'] ?? 0;
$max_price = $_POST['max_price'] ?? 0;
$avg_price = $_POST['avg_price'] ?? 0;
$desc = $_POST['deskripsi'] ?? '';
$lat = $_POST['latitude'] ?? null;
$long = $_POST['longitude'] ?? null;


if (!$cafe_id) {
die("Invalid ID");
}


$sqlConn->begin_transaction();

try {

    $stmt = $sqlConn->prepare("SELECT file_path FROM cafe_photos WHERE cafe_id = ? ORDER BY photo_id DESC LIMIT 1");
    $stmt->bind_param("i", $cafe_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $oldPhoto = $res && $res->num_rows ? $res->fetch_assoc()['file_path'] : null;


    $photoToSave = $oldPhoto; 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

    $tmp = $_FILES['foto']['tmp_name'];
    $origName = basename($_FILES['foto']['name']);

    $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $origName);
    $newName = "cafe_" . uniqid() . "_" . $safeName;

    $uploadDir = __DIR__ . "/../public/uploads/cafe_image/";


    if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
    }


    $dest = $uploadDir . $newName;


    if (move_uploaded_file($tmp, $dest)) {

    $photoToSave = "/uploads/cafe_image/" . $newName;

    if ($oldPhoto && file_exists(__DIR__ . "/../public" . $oldPhoto)) {
        @unlink(__DIR__ . "/../public" . $oldPhoto);
    }

    $del = $sqlConn->prepare("DELETE FROM cafe_photos WHERE cafe_id = ?");
    $del->bind_param("i", $cafe_id);
    $del->execute();


    $ins = $sqlConn->prepare("INSERT INTO cafe_photos (cafe_id, file_path) VALUES (?, ?)");
    $ins->bind_param("is", $cafe_id, $photoToSave);
    $ins->execute();


    } else {
    error_log("GAGAL UPLOAD FOTO BARU untuk cafe_id=$cafe_id");

    }
    } 

    $updateCafeSql = "UPDATE cafes SET cafe_name=?, rating=?, price_min=?, price_max=?, price_avg=?, cafe_description=?, latitude=?, longitude=? WHERE cafe_id=?";


    $stmt = $sqlConn->prepare($updateCafeSql);
    if (!$stmt) throw new Exception('Prepare failed: ' . $sqlConn->error);
    $stmt->bind_param(
        "sddddsssi",
        $nama,
        $rating,
        $min_price,
        $max_price,
        $avg_price,
        $desc,
        $lat,
        $long,
        $cafe_id
        );
        $stmt->execute();

$sqlConn->commit();

header("Location: /cafe/update");
exit();

} catch (Exception $e) {
$sqlConn->rollback();
error_log('handler_update_cafe error: ' . $e->getMessage());
die("UPDATE FAILED: " . $e->getMessage());
}