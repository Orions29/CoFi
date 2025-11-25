<?php
require_once __DIR__ . "/../Core/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Forbidden Knowledge");
}

// 1. TANGKAP SEMUA INPUT
$cafe_id    = $_POST['id'] ?? null;
$nama       = $_POST['nama'] ?? '';
$rating     = $_POST['rating'] ?? 0;
$min_price  = $_POST['min_price'] ?? 0;
$max_price  = $_POST['max_price'] ?? 0;
$avg_price  = $_POST['avg_price'] ?? 0;
$desc       = $_POST['deskripsi'] ?? '';
$lat        = $_POST['latitude'] ?? null;
$long       = $_POST['longitude'] ?? null;
// Tangkap string kategori (contoh: "productivity,roastery")
$categories_str = $_POST['categories'] ?? '';

if (!$cafe_id) {
    die("Invalid ID");
}

$sqlConn->begin_transaction();

try {
    // --- 2. LOGIC FOTO (APPEND MODE) ---
    // Kita cek kalo user upload foto baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $tmp = $_FILES['foto']['tmp_name'];
        $origName = basename($_FILES['foto']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $origName);
        $newName = "cafe_" . uniqid() . "_" . $safeName; // Nama unik

        $uploadDir = __DIR__ . "/../public/uploads/cafe_image/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $dest = $uploadDir . $newName;

        if (move_uploaded_file($tmp, $dest)) {
            // Simpan path relatif
            $photoToSave = "/uploads/cafe_image/" . $newName;

            // ⚠️ PERBAIKAN PENTING:
            // Jangan DELETE foto lama kalau mau jadi Carousel!
            // Cukup INSERT foto baru sebagai tambahan.

            $ins = $sqlConn->prepare("INSERT INTO cafe_photos (cafe_id, file_path) VALUES (?, ?)");
            $ins->bind_param("is", $cafe_id, $photoToSave);
            $ins->execute();
        } else {
            error_log("GAGAL UPLOAD FOTO BARU untuk cafe_id=$cafe_id");
        }
    }

    // --- 3. UPDATE DATA UTAMA CAFE ---
    $updateCafeSql = "UPDATE cafes SET cafe_name=?, rating=?, price_min=?, price_max=?, price_avg=?, cafe_description=?, latitude=?, longitude=? WHERE cafe_id=?";

    $stmt = $sqlConn->prepare($updateCafeSql);
    if (!$stmt) throw new Exception('Prepare failed: ' . $sqlConn->error);

    // Tipe data: s=string, d=double/decimal, i=integer
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

    // --- 4. UPDATE KATEGORI (YANG TADI HILANG) ---
    // Konsep: Hapus semua kategori lama user ini -> Masukin kategori baru yang dipilih

    // A. Hapus kategori lama
    $delCat = $sqlConn->prepare("DELETE FROM cafe_categories WHERE cafe_id = ?");
    $delCat->bind_param("i", $cafe_id);
    $delCat->execute();

    // B. Insert kategori baru (jika ada)
    if (!empty($categories_str)) {
        // Pecah string "productivity,social space" jadi array
        $catArray = explode(",", $categories_str);

        $insCat = $sqlConn->prepare("INSERT INTO cafe_categories (cafe_id, category_name) VALUES (?, ?)");

        foreach ($catArray as $catName) {
            $catName = trim($catName); // Bersihkan spasi
            if (!empty($catName)) {
                $insCat->bind_param("is", $cafe_id, $catName);
                $insCat->execute();
            }
        }
    }

    // --- 5. COMMIT & SELESAI ---
    $sqlConn->commit();

    // Redirect balik ke halaman list dengan pesan sukses (opsional)
    header("Location: /cafe/update");
    $_SESSION['alert_success'][] = ['type' => 'success_edit_cafe', 'message' => 'Cafe berhasil Diedit!'];
    exit();
} catch (Exception $e) {
    $sqlConn->rollback();
    error_log('handler_update_cafe error: ' . $e->getMessage());
    die("UPDATE FAILED: " . $e->getMessage());
}
