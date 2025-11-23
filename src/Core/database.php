<?php
// Ngestrict MYSQL Biar kalau ada Error langsung ngeretrieve
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// Error Handler SQL Conn
try {
    $sqlConn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_SELECTED']);
} catch (\Throwable $th) {
    $_SESSION['alert'][] = [
        'type' => 'db_connect_failed',
        'message' => '[Failed to Connect DB]' . $th
    ];
    error_log("SQLConnector - " . $th . ' ' . date('d-m-Y H:i:s'));
}
