<?php
require_once __DIR__ . "/../Core/database.php";
// Nambah User
$insertNewUser = "INSERT INTO USERS (USERNAME ,USER_PASSWORD ,USER_EMAIL) 
                  VALUES (?,?,?)";
// Details nya  
$insertNewUserDetails = "INSERT INTO USER_DETAILS (USER_BIRTHDAY, USER_FULL_NAME ,JOB, USER_ID) 
                        VALUES (?,?,?,?)";
if (isset($_POST['action']) && $_POST['action'] == 'regis_attempt') {
    if (hash_equals($_POST['regis_token_attempt'], $_SESSION['regis_token'])) {
        $sqlConn->begin_transaction();
        try {
            // nambah User
            $stmt_insertNewUser = $sqlConn->prepare($insertNewUser);
            $stmt_insertNewUser->bind_param("sss", $_POST['usernameRegis'], password_hash($_POST['passwordRegis'], PASSWORD_DEFAULT), $_POST['userEmailRegis']);
            $stmt_insertNewUser->execute();
            $stmt_insertNewUser->close();
            // Nambah Details User
            $stmt_insertNewUserDetails = $sqlConn->prepare($insertNewUserDetails);
            $last_insert_id = $sqlConn->insert_id;
            $stmt_insertNewUserDetails->bind_param("sssi", $_POST['birthRegis'], $_POST['fullNameRegis'], $_POST['jobRegis'], $last_insert_id);
            $stmt_insertNewUserDetails->execute();
            $stmt_insertNewUserDetails->close();
            $sqlConn->commit();
        } catch (\Throwable $th) {
            $sqlConn->rollback();
            $_SESSION['alert'][] = [
                'type' => 'regis_failed',
                'message' => $th
            ];
            error_log("regisFailed - " . $th . date('d-m-Y H:i:s'));
        }
    } else {
        error_log("TokenViolation_regis- " . date('d-m-Y H:i:s'));
    }
} else {
    $_SESSION['alert'][] = [
        'type' => 'ngapain_ini',
        'messege' => 'Iso Mbobol Post cah!!'
    ];
}
// Janlup Untuk ditutup
$sqlConn->close();

header("Location: /login");
exit();
