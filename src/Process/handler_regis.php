<?php
require_once __DIR__ . "/../Core/database.php";

$insertNewUser = "INSERT INTO USERS 
                 (USERNAME ,USER_PASSWORD ,USER_EMAIL) VALUES (?,?,?)";
$insertNewUserDetails = "INSERT INTO USER_DETAILS 
                        (USER_BIRTHDAY, USER_FULL_NAME ,JOB, USER_ID) VALUES (?,?,?,?)";

if (isset($_POST['action']) && $_POST['action'] == 'regis_attempt') {
    $selectUsers = "SELECT username FROM users WHERE username = ?";
    $stmt_selectUsers = $sqlConn->prepare($selectUsers);
    $stmt_selectUsers->bind_param("s", $_POST['usernameRegis']); // Bind dulu!
    $stmt_selectUsers->execute(); // Baru execute kosong
    $result = $stmt_selectUsers->get_result();

    if ($result->num_rows > 0) {
        $stmt_selectUsers->close();
        $_SESSION['alert'][] = [
            'type' => 'regis_failed',
            'message' => 'Username already taken, Cari yang lain Cah.'
        ];
        header("Location: /register");
        exit();
    }
    $stmt_selectUsers->close();

    if (hash_equals($_POST['regis_token_attempt'] ?? '', $_SESSION['regis_token'] ?? '')) { // Pake null coalescing biar gak warning

        $sqlConn->begin_transaction();

        try {
            // Insert User Utama
            $stmt_insertNewUser = $sqlConn->prepare($insertNewUser);
            $hashedPassword = password_hash($_POST['passwordRegis'], PASSWORD_DEFAULT);
            $stmt_insertNewUser->bind_param("sss", $_POST['usernameRegis'], $hashedPassword, $_POST['userEmailRegis']);
            $stmt_insertNewUser->execute();
            $stmt_insertNewUser->close();

            // Ambil ID terakhir
            $last_insert_id = $sqlConn->insert_id;

            // Insert Details
            $stmt_insertNewUserDetails = $sqlConn->prepare($insertNewUserDetails);
            $stmt_insertNewUserDetails->bind_param("sssi", $_POST['birthRegis'], $_POST['fullNameRegis'], $_POST['jobRegis'], $last_insert_id);
            $stmt_insertNewUserDetails->execute();
            $stmt_insertNewUserDetails->close();

            $sqlConn->commit();

            $_SESSION['alert_success'][] = [
                'type' => 'Registration Complete',
                'message' => 'Akun berhasil dibuat!'
            ];
            header("Location: /login");
            exit();
        } catch (\Throwable $th) {
            $sqlConn->rollback();
            error_log("Regis Error: " . $th->getMessage());
            $_SESSION['alert'][] = [
                'type' => 'regis_failed',
                'message' => 'System error. Coba lagi nanti ya.'
            ];
            header("Location: /register");
            exit();
        }
    } else {
        // Token salah
        error_log("TokenViolation_regis - " . date('d-m-Y H:i:s'));
        $_SESSION['alert'][] = [
            'type' => 'security_alert',
            'message' => 'Invalid Token.'
        ];
        header("Location: /register");
        exit();
    }
} else {
    $_SESSION['alert'][] = [
        'type' => 'ngapain_ini',
        'message' => 'Iso Mbobol Post cah!!'
    ];
    header("Location: /register");
    exit();
}
