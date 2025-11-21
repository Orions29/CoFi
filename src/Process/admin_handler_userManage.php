<?php
require_once __DIR__ . "/../Core/database.php";

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'delete_user':
            $stmt_deleteUsers = "DELETE FROM users WHERE user_id =?";
            try {
                $stmt_stmt_deleteUsers = $sqlConn->prepare($stmt_deleteUsers);
                $user_id = (int) $_POST['user_id'];
                $stmt_stmt_deleteUsers->bind_param("i", $user_id);
                $stmt_stmt_deleteUsers->execute();
            } catch (\Throwable $th) {
                throw $th;
                $stmt_stmt_deleteUsers->close();
                header("Location: /admin/usermanage");
                $sqlConn->close();
                exit();
            }
            break;
        case 'update_user':
            // $selectUsers = "UPDATE users u WHERE user_id = ?";
            break;
        default:
            break;
    }
    // Janlup Close
    $sqlConn->close();
    header("Location: /admin/usermanage");
    exit();
}
