<?php
ini_set('display_errors', 0);
error_reporting(E_ALL); // Tetap catat error di log server

header('Content-Type: application/json'); // Header wajib JSON Gatau Kenapa Nanti kita cari tau

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();

    // Manggil Database
    require_once __DIR__ . "/../src/Core/database.php";

    // Cek apakah $sqlConn (objek mysqli) berhasil dibuat?
    if (!isset($sqlConn) || $sqlConn->connect_error) {
        throw new Exception("Koneksi Database Gagal: " . ($sqlConn->connect_error ?? "Credentials Error"));
    }

    // Queerying
    $sql = "SELECT 
                c.cafe_id, 
                c.cafe_name, 
                c.latitude, 
                c.longitude, 
                c.rating,
                c.cafe_description,
                (SELECT file_path FROM cafe_photos cp WHERE cp.cafe_id = c.cafe_id LIMIT 1) as thumbnail,
                (SELECT GROUP_CONCAT(category_name) FROM cafe_categories cc WHERE cc.cafe_id = c.cafe_id) as categories
            FROM cafes c";

    $result = $sqlConn->query($sql);

    if (!$result) {
        throw new Exception("Query Error: " . $sqlConn->error);
    }

    $cafes = [];

    while ($row = $result->fetch_assoc()) {
        $img = !empty($row['thumbnail']) ? $row['thumbnail'] : '/assets/img/default-cafe.png';

        // 👇 LOGIC BARU: Pecah string kategori jadi Array
        // Kalau kosong, kasih array kosong []
        $cats = !empty($row['categories']) ? explode(',', $row['categories']) : [];

        $cafes[] = [
            'id' => $row['cafe_id'],
            'name' => $row['cafe_name'],
            'lat' => (float)$row['latitude'],
            'lng' => (float)$row['longitude'],
            'rating' => $row['rating'],
            'desc' => substr(strip_tags($row['cafe_description']), 0, 80) . '...',
            'image' => $img,
            'categories' => $cats // Kirim array kategori ke JSON
        ];
    }

    // KIRIM JSON BERSIH
    echo json_encode($cafes);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
