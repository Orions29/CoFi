<?php
require_once __DIR__ . "/../Core/database.php";

// MASTER KATEGORI
$availableCategories = [
    "productivity"      => ["icon" => "<i class=\"bi bi-laptop\"></i>", "label" => "Productivity"],
    "social space"      => ["icon" => "<i class=\"bi bi-people\"></i>", "label" => "Social Space"],
    "roastery"          => ["icon" => "<i class=\"bi bi-cup\"></i>", "label" => "Roastery"],
    "live music"        => ["icon" => "<i class=\"bi bi-music-note\"></i>", "label" => "Live Music"],
    "outdoor seating"   => ["icon" => "<i class=\"bi bi-tree-fill\"></i>", "label" => "Outdoor Seating"],
    "pet friendly"      => ["icon" => "<span class=\"material-symbols-outlined\">pets</span>", "label" => "Pet Friendly"],
];

$query = "SELECT * FROM cafes ORDER BY cafe_id DESC";
$stmt = $sqlConn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$cafes = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$photoStmt = $sqlConn->prepare("SELECT file_path FROM cafe_photos WHERE cafe_id = ? ORDER BY photo_id DESC");
$catStmt = $sqlConn->prepare("SELECT category_name FROM cafe_categories WHERE cafe_id = ?");

// HELPER FUNCTION 
function renderCoffeeRating($rating, $max = 5)
{
    $html = "<div class='coffee-rating' style='color: #fcb53b;'>";
    for ($i = 1; $i <= $max; $i++) {
        $icon = $i <= $rating ? "coffee" : "coffee";
        $style = $i <= $rating ? "font-variation-settings: 'FILL' 1;" : "opacity: 0.4;";
        $html .= "<span class='material-symbols-outlined' style='$style'>$icon</span>";
    }
    $html .= "</div>";
    return $html;
}
?>

<div class="title-container text-center mb-3">
    <h1 class="brand-title" style="color: var(--ca-primary);">Cafe Catalogue</h1>
    <hr class="divider-soft">
</div>
<div class="main-container user">
    <div class="row pb-3">
        <div class="col-md-3 filter-container-wrapper">
            <div class="filter-container card p-4  border-0 shadow-sm">
                <h3 style="font-family: 'Homenaje'; color: var(--ca-primary);"><i class="bi bi-search"></i> Find Cafe</h3>
                <div class="mb-4">
                    <!-- Ini Searchnya Dikendaliin di Javascript -->
                    <input type="text" id="searchInput" class="form-control" placeholder="Type cafe name..." style="font-family: 'Segoe UI';" value="<?= isset($_GET['searchKey']) ? $_GET['searchKey'] : '' ?>">
                </div>

                <h3 style="font-family: 'Homenaje'; color: var(--ca-primary);"><i class="bi bi-tags"></i> Filter by Category</h3>
                <div class="d-flex flex-column gap-2" id="filterContainer">
                    <button class="btn filter-btn active text-start" data-filter="all" style="background-color: var(--ca-primary); color: white;border: 1px solid var(--ca-primary);">
                        All Categories
                    </button>

                    <?php foreach ($availableCategories as $key => $data): ?>
                        <button class="btn filter-btn text-start btn-outline-secondary" data-filter="<?= $key ?>" style="border: 1px solid var(--ca-primary);">
                            <?= $data['icon'] . " " . $data['label'] ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-9 ">
            <div class=" cafe-recent-wrapper w-100 ">
                <div class="cafe-table-wrapper add-cafe-table-wrapper">

                    <?php foreach ($cafes as $cafe):
                        $id = (int)$cafe['cafe_id'];

                        // Photos
                        $photoStmt->bind_param("i", $id);
                        $photoStmt->execute();
                        $photoRes = $photoStmt->get_result();
                        $photos = [];
                        while ($p = $photoRes->fetch_assoc()) $photos[] = str_replace(['"', '\\'], '', $p['file_path']);
                        $encodedPhotos = htmlspecialchars(json_encode($photos), ENT_QUOTES, 'UTF-8');

                        // Categories
                        $catStmt->bind_param("i", $id);
                        $catStmt->execute();
                        $catRes = $catStmt->get_result();
                        $kategori = [];
                        while ($row = $catRes->fetch_assoc()) $kategori[] = $row['category_name'];

                        // JSON untuk JS populate modal
                        $encodedKategori = htmlspecialchars(json_encode($kategori), ENT_QUOTES, 'UTF-8');
                        // String untuk filter JS (lowercase)
                        $catString = strtolower(implode(" ", $kategori));

                        $nama = htmlspecialchars($cafe['cafe_name']);
                        $deskripsi = htmlspecialchars($cafe['cafe_description']);
                        $min = number_format($cafe['price_min'], 0, ',', '.');
                        $max = number_format($cafe['price_max'], 0, ',', '.');
                    ?>

                        <div class="cafe-card cafe-item mb-4" data-name="<?= strtolower($nama) ?>" data-category="<?= $catString ?>">
                            <div id="carouselCafe<?= $id ?>" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <?php foreach ($photos as $idx => $p): ?>
                                        <button type="button" data-bs-target="#carouselCafe<?= $id ?>" data-bs-slide-to="<?= $idx ?>" class="<?= $idx === 0 ? 'active' : '' ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="carousel-inner">
                                    <?php if (!empty($photos)): ?>
                                        <?php foreach ($photos as $idx => $p): ?>
                                            <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                                                <img src="<?= $p ?>" class="d-block  cafeCarouselImg" alt="Cafe Photo">
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="carousel-item active">
                                            <img src="/assets/img/placeholder.png" class="d-block w-100 cafeCarouselImg">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (count($photos) > 1): ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCafe<?= $id ?>" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselCafe<?= $id ?>" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="card-details">
                                <div class="top-row">
                                    <h2><?= $nama ?></h2>
                                    <div class="price-rating-wrapper">
                                        <?= renderCoffeeRating($cafe['rating']) ?>
                                        <div class="price-range">Rp<?= $min ?> - Rp<?= $max ?></div>
                                    </div>
                                </div>

                                <div class="cafe-location">
                                    <i class="bi bi-geo-alt-fill"></i> <?= $cafe['latitude'] ?>, <?= $cafe['longitude'] ?>
                                </div>
                                <div class="cafe-desc-container">
                                    <p class="cafe-desc"><?= nl2br($deskripsi) ?></p>
                                </div>

                                <?php if (!empty($kategori)): ?>
                                    <div class="tags">
                                        <?php foreach ($kategori as $k):
                                            $key = strtolower($k);
                                            $icon = $availableCategories[$key]['icon'] ?? "•";

                                            $bgClass = match ($key) {
                                                'productivity' => 'cat-productivity',
                                                'social space' => 'cat-social',
                                                'roastery' => 'cat-roastery',
                                                'pet friendly' => 'red',
                                                default => 'cat-default'
                                            };
                                        ?>
                                            <span class="badge <?= $bgClass ?>">
                                                <?= $icon ?> <?= htmlspecialchars($k) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="action-buttons">
                                    <!-- Kalau Misal ada Button nanti disini  -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div id="noResults" class="text-center mt-5" style="display: none;">
                        <h3 class="text-muted" style="font-family: 'Homenaje';">No cafes found cah... ☕</h3>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // FILTER & SEARCH (Realtime)
        const filterBtns = document.querySelectorAll(".filter-btn");
        const searchInput = document.getElementById("searchInput");
        const cafeItems = document.querySelectorAll(".cafe-item");
        const noResults = document.getElementById("noResults");

        function filterCafes() {
            const searchTerm = searchInput.value.toLowerCase();
            const activeBtn = document.querySelector(".filter-btn.active");
            const categoryFilter = activeBtn ? activeBtn.dataset.filter : 'all';

            let visibleCount = 0;

            cafeItems.forEach(item => {
                const name = item.dataset.name;
                const categories = item.dataset.category; // string space-separated

                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = categoryFilter === 'all' || categories.includes(categoryFilter);

                if (matchesSearch && matchesCategory) {
                    item.style.display = "flex";
                    visibleCount++;
                } else {
                    item.style.display = "none";
                }
            });
            noResults.style.display = visibleCount === 0 ? "block" : "none";
        }

        filterBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                filterBtns.forEach(b => {
                    b.classList.remove("active");
                    b.style.backgroundColor = "transparent";
                    b.style.color = "var(--ca-black)";
                });
                this.classList.add("active");
                this.style.backgroundColor = "var(--ca-primary)";
                this.style.color = "white";
                filterCafes();
            });
        });
        // Untuk Otomatis Nyari Cafenya
        if (searchInput.value.trim() !== "") {
            filterCafes();
        }
        searchInput.addEventListener("input", filterCafes);

    });
</script>