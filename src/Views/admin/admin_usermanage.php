<?php
require_once __DIR__ . "/../../Core/database.php";

// Logic Searching
$searchKey = $_GET['searchKey'] ?? '';
$query = "SELECT * FROM users u";
if (!empty($searchKey)) {
    $query .= " WHERE username LIKE ?";
}
try {
    if ($searchKey == 'upload Dummy Data') {
        include __DIR__ . "/../../Process/dummyData.php";
        exit();
    }
    $stmt = $sqlConn->prepare($query);
    if (!empty($searchKey)) {
        $searchTerm = "%{$searchKey}%";
        $stmt->bind_param("s", $searchTerm);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $userRows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (\Throwable $th) {
    throw $th;
}

$sqlConn->close();
?>
<div class="main-container user-manage d-flex">
    <div class="form-container" id="user-manage-wrapper">
        <h1 class="homenaje-regular form-title">User Manage Page</h1>
        <div class="main-form-wrapper user-manage-table-wrapper">
            <div class="form-wrapper d-flex justify-content-center">
                <form action="/admin/usermanage" method="get">
                    <div class="mb-3 form-floating d-flex justify-content-center align-items-center">
                        <input type="search" class="form-control" id="searchKey" placeholder="Cari User" name="searchKey">
                        <label for="searchKey">Cari User</label>
                        <button class="btn " type="submit">Cari</button>
                    </div>
                </form>
            </div>
            <!-- header Tabel User -->
            <div class="user-manage-table header">
                <table>
                    <colgroup>
                        <col style="width: 10%">
                        <col style="width: 30%">
                        <col style="width: 30%">
                        <col style="width: 30%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>
                                Id
                            </th>
                            <th>
                                Username
                            </th>
                            <th>
                                Email
                            </th>
                            <th style="text-align: center;">
                                Action
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- Tabel User -->
            <div class="user-manage-table contents">
                <table>
                    <colgroup>
                        <col style="width: 10%">
                        <col style="width: 30%">
                        <col style="width: 30%">
                        <col style="width: 30%">
                    </colgroup>
                    <?php
                    foreach ($userRows as $row):
                    ?>
                        <tr style="background-color:<?= ($row['admin_stat'] == true) ? '#B7E5CD' : '#f7f1de' ?> ;">
                            <td>
                                <?php
                                echo $row['user_id'];
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $row['username'];
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $row['user_email'];
                                ?>
                            </td>
                            <td>
                                <div class="action-container d-flex justify-content-center" style="gap: 5px;">
                                    <form action="" method="post" class="">
                                        <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                        <button
                                            type="submit"
                                            name="action"
                                            value="delete_user"
                                            class="btn btn-danger"
                                            onclick="return confirm('Yakin mau hapus?')">
                                            Delete
                                        </button>
                                    </form>
                                    <form action="/" method="post">
                                        <button
                                            type="submit"
                                            name="action"
                                            value="update"
                                            class="btn btn-primary">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>