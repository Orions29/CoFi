<?php
// Pastikan $sqlConn (MySQLi) udah connect di sini
require __DIR__ . "/../../Core/database.php";
// 1. Hitung Total Cafe
$resCafe = $sqlConn->query("SELECT COUNT(*) as total FROM cafes");
$totalCafe = $resCafe->fetch_assoc()['total'] ?? 0;

// 2. Hitung Total User (Sesuaikan nama tabel 'users' lu)
$resUser = $sqlConn->query("SELECT COUNT(*) as total FROM users");
$totalUser = $resUser->fetch_assoc()['total'] ?? 0;

// 3. Ambil Data Cafe + 1 Foto Thumbnail
// Subquery ini ngambil path gambar yang tadi kita simpen (/uploads/...)
$sqlRecent = "SELECT 
                c.cafe_id, 
                c.cafe_name, 
                c.cafe_description,
                c.created_at,
                (SELECT file_path FROM cafe_photos cp WHERE cp.cafe_id = c.cafe_id LIMIT 1) as thumbnail
              FROM cafes c 
              ORDER BY c.cafe_id DESC 
              LIMIT 5"; // Nampilin 5 cafe terakhir

$recentCafes = $sqlConn->query($sqlRecent);
?>

<div class="main-container">
    <div class="form-container dashboard-page-container" id="edit-cafe-wrapper">

        <div class="title-container d-flex align-items-center flex-column" style="width: 100%;">
            <h1>Admin Dashboard</h1>
            <hr class="divider-soft">
        </div>

        <div class="dashboard-container">
            <div class="counter-wrapper">
                <div class="counter-contents" id="cafe-counter">
                    <h2><span class="material-symbols-outlined">storefront</span> Cafe Counter</h2>
                    <h1><?= number_format($totalCafe) ?></h1>
                </div>
                <div class="counter-contents" id="user-counter">
                    <h2><span class="material-symbols-outlined">supervised_user_circle</span> User Counter</h2>
                    <h1><?= number_format($totalUser) ?></h1>
                </div>
            </div>

            <div class="cafe-recent-wrapper">
                <div class="title-recent-cafe-wrapper d-flex justify-content-start align-items-center mb-1 ms-2">
                    <span class="material-symbols-outlined" style="margin-right: 6px; font-size:2rem;">history</span>
                    <h2>Recent Cafe Activity</h2>
                </div>

                <div class="cafe-table-wrapper">
                    <?php if ($recentCafes && $recentCafes->num_rows > 0): ?>
                        <?php while ($cafe = $recentCafes->fetch_assoc()): ?>
                            <?php
                            $imgSrc = !empty($cafe['thumbnail']) ? $cafe['thumbnail'] : '/assets/img/default-cafe.png';
                            $shortDesc = substr($cafe['cafe_description'], 0, 100) . '...';
                            ?>
                            <div class="card mb-3" style="max-width: 100%; max-height:150px;">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="<?= htmlspecialchars($imgSrc) ?>" class="img-fluid rounded-start" alt="Cafe Image" style="height: 150px; object-fit: cover; ">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($cafe['cafe_name']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($shortDesc) ?></p>
                                            <p class="card-text"><small class="text-body-secondary">Last Modified : <?= $cafe['created_at'] ?></small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-warning m-3">Belum ada data cafe nih, Bestie.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>