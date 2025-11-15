<?php
// kalau udah ada ga di require lagi
require_once __DIR__ . "/../Core/database.php";
// Querrying Section
$selectLoginUser = "SELECT * FROM USERS U WHERE U.USERNAME=? ";
// Execute Section
if (isset($_POST['action']) && $_POST['action'] == 'login_attempt') {
    // Ngecek Tokennya sama endak sama yang dikirim
    if (hash_equals($_POST['login_token_attempt'], $_SESSION['login_token'])) {
        $stmt_selectLoginUser = $sqlConn->prepare($selectLoginUser);
        $stmt_selectLoginUser->bind_param('s', $_POST['usernameLogin']);
        try {
            $stmt_selectLoginUser->execute();
            $result_selectLoginUser = $stmt_selectLoginUser->get_result();
            $rows_selectLoginUser = $result_selectLoginUser->fetch_assoc();
            // Login Logic
            // kalau ada usernya
            if (!empty($rows_selectLoginUser)) {
                if (password_verify($_POST['passwordLogin'], $rows_selectLoginUser['user_password'])) {
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['username'] = $rows_selectLoginUser['username'];
                    // Pengendali admin atau user
                    $_SESSION['user_role'] = ($rows_selectLoginUser['admin_stat'] == 1) ? 'admin' : 'user';
                } else {
                    $_SESSION['alert'][] = [
                        'type' => 'login_failed',
                        'message' => 'Password Wrong'
                    ];
                }
            } else {
                $_SESSION['alert'][] = [
                    'type' => 'login_failed',
                    'message' => 'Username Not Found'
                ];
            }
        } catch (\Throwable $th) {
            error_log("loginAttemptFailed - " . $th . ' ' . date('d-m-Y H:i:s'));
        }
        // Ngeclose Koneksi
        $stmt_selectLoginUser->close();
        $sqlConn->close();
    } else {
        $_SESSION['alert'][] = [
            'type' => 'login_failed',
            'message' => 'invalid_token'
        ];
    }
} else {
    $_SESSION['alert'][] = [
        'type' => 'ngapain_ini',
        'messege' => 'Iso Mbobol Post cah!!'
    ];
}
// Section Wajib di dsetiap Handler
// Ngeredirect ke Dashboard

// Harusnya ga sampe sini
header("Location: /");
exit();
