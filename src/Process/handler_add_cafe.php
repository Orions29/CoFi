<?php
require_once __DIR__ . "/../Core/database.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Ngecek Session
if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /login");
    exit();
}

// Flag & Path Default
$redirectPath = '/cafe/add'; // Balik ke form kalo gagal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_cafe_attempt') {
    // TOKEN CHECK
    if (!hash_equals($_POST['add_cafe_token_attempt'], $_SESSION['add_cafe_token'])) { // Pastikan nama session token konsisten
        $_SESSION['alert'][] = ['type' => 'Invalid_Token', 'message' => 'Token keamanan tidak valid.'];
        header("Location: " . $redirectPath);
        exit();
    }
    // Validasi Input Wajib (Nama, Lat, Long)
    if (empty($_POST['namaCafeAdd']) || empty($_POST['latitudeAdd']) || empty($_POST['longitudeAdd'])) {
        $_SESSION['alert'][] = ['type' => 'error', 'message' => 'Nama Cafe dan Koordinat wajib diisi.'];
        header("Location: " . $redirectPath);
        exit();
    }
    // Inisialisasi Statement biar finally block aman
    $stmtCafe = null;
    $stmtCat = null;
    $stmtPhoto = null;
    $sqlConn->begin_transaction();
    try {
        // Insert Utama Cafe
        // NOTE - Kolom disesuaikan dengan update Lat/Long
        $sqlCafe = "INSERT INTO cafes (cafe_name, cafe_email, latitude, longitude, cafe_description, price_min, price_max, price_avg, rating) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtCafe = $sqlConn->prepare($sqlCafe);
        // NOTE - Tipe data: s=string, d=double/decimal
        // NOTE - Urutan: Name(s), Email(s), Lat(d), Long(d), Desc(s), Min(d), Max(d), Avg(d), rating(d)
        $stmtCafe->bind_param(
            "ssddsdddd", // Perhatikan jumlah hurufnya (s=string, d=double)
            $_POST['namaCafeAdd'],
            $_POST['cafeEmailAdd'],
            $_POST['latitudeAdd'],
            $_POST['longitudeAdd'],
            $_POST['cafeDescriptionAdd'],
            $_POST['minPriceAdd'],
            $_POST['maxPriceAdd'],
            $_POST['avgPriceAdd'],
            $_POST['ratingCafeAdd']
        );

        // EXECUTION SECTION
        $stmtCafe->execute();
        $newCafeId = $sqlConn->insert_id;
        // Jangan di-close di sini biar rapi di finally
        // Insert Looping buat semua Gambar Yang diup
        if (isset($_POST['category']) && is_array($_POST['category'])) {
            $stmtCat = $sqlConn->prepare("INSERT INTO cafe_categories (cafe_id, category_name) VALUES (?, ?)");
            foreach ($_POST['category'] as $catName) {
                $cleanCat = htmlspecialchars($catName);
                $stmtCat->bind_param("is", $newCafeId, $cleanCat);
                $stmtCat->execute();
            }
            // Jangan DI CLOSE STMT BELUM SELESAI
        }
        if (isset($_FILES['cafePicture']) && $_FILES['cafePicture']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            $stmtPhoto = $sqlConn->prepare("INSERT INTO cafe_photos (cafe_id, file_path) VALUES (?, ?)");
            // NOTE - Uploads sementara berada di Public.
            // FIXME - Nanti kita benerin jadi keluar dan bikin jembatan buat ngimport ke Public
            $uploadDir = __DIR__ . "/../../public/uploads/cafe_image/";
            // Bikin folder kalo belum ada Folder Uploads
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $totalFiles = count($_FILES['cafePicture']['name']);

            for ($i = 0; $i < $totalFiles; $i++) {
                $fileName = $_FILES['cafePicture']['name'][$i];
                $fileTmp  = $_FILES['cafePicture']['tmp_name'][$i];
                $fileSize = $_FILES['cafePicture']['size'][$i];
                $fileError = $_FILES['cafePicture']['error'][$i];
                // Kalau File Tidak Error
                if ($fileError === 0) {
                    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (in_array($fileExt, $allowedExt)) {
                        // Validasi size 7MB
                        if ($fileSize < 7000000) {
                            $newFileName = uniqid('cafe_', true) . "." . $fileExt;
                            $fileDestination = $uploadDir . $newFileName;
                            if (move_uploaded_file($fileTmp, $fileDestination)) {
                                $dbPath = '/uploads/cafe_image/' . $newFileName;

                                $stmtPhoto->bind_param("is", $newCafeId, $dbPath);
                                $stmtPhoto->execute();
                            }
                        }
                    }
                }
            }
        }
        // kalau Semua Sukses Commit
        $sqlConn->commit();
        $_SESSION['alert_success'][] = ['type' => 'success_add_cafe', 'message' => 'Cafe berhasil ditambahkan!'];
        $redirectPath = '/cafe/update';
    } catch (Exception $e) {
        // Kalau Gagal Di Rollback
        $sqlConn->rollback();
        error_log("Add Cafe Error: " . $e->getMessage());
        $_SESSION['alert'][] = ['type' => 'error', 'message' => 'Gagal menambahkan cafe. Cek log sistem.'];
    } finally {
        // Bersihkan Statement (Cek isset dulu biar gak error object)
        if (isset($stmtCafe) && $stmtCafe) $stmtCafe->close();
        if (isset($stmtCat) && $stmtCat) $stmtCat->close();
        if (isset($stmtPhoto) && $stmtPhoto) $stmtPhoto->close();

        // Selalu TUtup 
        if (isset($sqlConn) && $sqlConn) $sqlConn->close();
    }
} else {
    $_SESSION['alert'][] = ['type' => 'error', 'message' => 'Akses ilegal.'];
}
header("Location: " . $redirectPath);
exit();
