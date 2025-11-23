<?php
// NOTE - FIle Ini Buat Nginjek Data Dummy yang cepet Ke Database

require_once __DIR__ . "/../Core/database.php";

// Aturannya Gini kalau mau pake maka pastikan semua data ini sudah kedelete semua

// Data Dummy Pejabat
$pejabatList = [
    [
        'username' => 'jokowi_widodo',
        'password' => 'sepeda123', // Password asli
        'email'    => 'jokowi@istana.go.id',
        'fullname' => 'Ir. H. Joko Widodo',
        'birth'    => '1961-06-21',
        'job'      => 'Presiden RI'
    ],
    [
        'username' => 'prabowo08',
        'password' => 'gemoy88',
        'email'    => 'prabowo@kemhan.go.id',
        'fullname' => 'H. Prabowo Subianto',
        'birth'    => '1951-10-17',
        'job'      => 'Menteri Pertahanan'
    ],
    [
        'username' => 'gibran_rakabuming',
        'password' => 'fufufafa',
        'email'    => 'gibran@solo.go.id',
        'fullname' => 'Gibran Rakabuming Raka',
        'birth'    => '1987-10-01',
        'job'      => 'Wapres Terpilih'
    ],
    [
        'username' => 'sri_mulyani',
        'password' => 'pajak2025',
        'email'    => 'sri.mulyani@kemenkeu.go.id',
        'fullname' => 'Sri Mulyani Indrawati',
        'birth'    => '1962-08-26',
        'job'      => 'Menteri Keuangan'
    ],
    [
        'username' => 'luhut_binsar',
        'password' => 'semuabisa',
        'email'    => 'luhut@maritim.go.id',
        'fullname' => 'Luhut Binsar Pandjaitan',
        'birth'    => '1947-09-28',
        'job'      => 'Menko Marves'
    ],
    [
        'username' => 'erick_thohir',
        'password' => 'intermilan',
        'email'    => 'erick@bumn.go.id',
        'fullname' => 'Erick Thohir',
        'birth'    => '1970-05-30',
        'job'      => 'Menteri BUMN'
    ],
    [
        'username' => 'basuki_hadimuljono',
        'password' => 'topi_balik',
        'email'    => 'pakbas@pupr.go.id',
        'fullname' => 'Basuki Hadimuljono',
        'birth'    => '1954-11-05',
        'job'      => 'Menteri PUPR'
    ],
    [
        'username' => 'megawati_soekarno',
        'password' => 'merah_total',
        'email'    => 'mega@pdip.id',
        'fullname' => 'Megawati Soekarnoputri',
        'birth'    => '1947-01-23',
        'job'      => 'Ketua Umum'
    ],
    [
        'username' => 'anies_baswedan',
        'password' => 'jakarta1',
        'email'    => 'anies@pendidikan.id',
        'fullname' => 'Anies Rasyid Baswedan',
        'birth'    => '1969-05-07',
        'job'      => 'Akademisi'
    ],
    [
        'username' => 'basuki_btp',
        'password' => 'pertamina1',
        'email'    => 'ahok@btp.id',
        'fullname' => 'Basuki Tjahaja Purnama',
        'birth'    => '1966-06-29',
        'job'      => 'Komisaris Utama'
    ]
];

echo "<h2>Injecting Dummy Data</h2>";

$sqlConn->begin_transaction();

try {

    $stmtUser = $sqlConn->prepare("INSERT INTO USERS (USERNAME, USER_PASSWORD, USER_EMAIL) VALUES (?,?,?)");
    $stmtDetail = $sqlConn->prepare("INSERT INTO USER_DETAILS (USER_BIRTHDAY, USER_FULL_NAME, JOB, USER_ID) VALUES (?,?,?,?)");

    foreach ($pejabatList as $p) {

        $hashedPass = password_hash($p['password'], PASSWORD_DEFAULT);
        $stmtUser->bind_param("sss", $p['username'], $hashedPass, $p['email']);
        $stmtUser->execute();

        $newUserId = $sqlConn->insert_id;

        $stmtDetail->bind_param("sssi", $p['birth'], $p['fullname'], $p['job'], $newUserId);
        $stmtDetail->execute();

        echo "Berhasil input: <b>" . $p['fullname'] . "</b> (Pass: " . $p['password'] . ")<br>";
    }

    $sqlConn->commit();
    echo "<hr><h3>Sukses Cah Semua data udah masuk</h3>";
} catch (Throwable $th) {
    $sqlConn->rollback();
    echo "<h1>GAGAL WOY! , TP GPP Punk Og</h1>";
    echo "Error: " . $th->getMessage();
}

$sqlConn->close();
