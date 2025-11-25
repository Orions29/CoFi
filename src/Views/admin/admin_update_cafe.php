<?php
require_once __DIR__ . "/../../Core/database.php";

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
    <h1 class="brand-title" style="color: var(--ca-primary);">Update Catalogue Cafe</h1>
    <hr class="divider-soft">
</div>
<div class="main-container update">
    <div class="row  pb-3">
        <div class="col-md-3 filter-container-wrapper">
            <div class="filter-container card p-4  border-0 shadow-sm">
                <h3 style="font-family: 'Homenaje'; color: var(--ca-primary);"><i class="bi bi-search"></i> Find Cafe</h3>
                <div class="mb-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Type cafe name..." style="font-family: 'Segoe UI';">
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
                                    <form method="POST" onsubmit="return confirm('Yakin hapus <?= $nama ?>?');" style="margin:0;">
                                        <input type="hidden" name="action" value="delete_cafe_attempt">
                                        <input type="hidden" name="id" value="<?= $id ?>">
                                        <button class="btn btn-danger text-white"><i class="bi bi-trash"></i> Delete</button>
                                    </form>

                                    <button class="btn text-white update-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateCafeModal"
                                        data-id="<?= $id ?>"
                                        data-nama="<?= $nama ?>"
                                        data-desc="<?= $deskripsi ?>"
                                        data-rating="<?= $cafe['rating'] ?>"
                                        data-min="<?= $cafe['price_min'] ?>"
                                        data-max="<?= $cafe['price_max'] ?>"
                                        data-avg="<?= $cafe['price_avg'] ?>"
                                        data-lat="<?= $cafe['latitude'] ?>"
                                        data-long="<?= $cafe['longitude'] ?>"
                                        data-kategori='<?= $encodedKategori ?>'
                                        data-photos="<?= $encodedPhotos ?>">
                                        <i class="bi bi-pencil-square"></i> Update
                                    </button>
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

<div class="modal fade" id="updateCafeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--ca-bg-card); border-radius: 15px; border: none;">

            <div class="modal-header" style="background-color: var(--ca-primary); color: white;">
                <h1 class="modal-title fs-3" style="font-family: 'Homenaje';">Update Cafe Details</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_cafe_attempt">
                <input type="hidden" name="id" id="upd-id">

                <div class="modal-body p-0">
                    <div class="form-container" style="width: 100%; margin: 0; box-shadow: none; background: transparent;">

                        <div class="main-form-wrapper p-3">
                            <div class="left-wrapper">
                                <div class="mb-3">
                                    <label class="form-label">Cafe Name</label>
                                    <input type="text" id="upd-nama" name="nama" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Longitude</label>
                                        <input type="number" step="any" id="upd-long" name="longitude" class="form-control" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Latitude</label>
                                        <input type="number" step="any" id="upd-lat" name="latitude" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Rating (0-5)</label>
                                    <input type="number" step="0.1" max="5" id="upd-rating" name="rating" class="form-control" required>
                                </div>

                                <label class="form-label">Pricing Range</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Min</span>
                                    <input type="number" step="500" id="upd-min" name="min_price" class="form-control" required>
                                    <span class="input-group-text">Max</span>
                                    <input type="number" step="500" id="upd-max" name="max_price" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Average Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" id="upd-avg" name="avg_price" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn w-100 categoryBtn"
                                        style="background-color: var(--ca-secondary); color: white; font-family: 'Homenaje'; font-size: 1.2rem;">
                                        ⚙ Manage Categories
                                    </button>

                                    <div id="category-panel" style="display:none; margin-top: 10px; padding: 15px; background: white; border-radius: 8px; border: 1px solid rgba(193, 120, 90, 0.3);">
                                        <div id="selectedCategoriesDisplay" class="mb-3 text-muted"></div>

                                        <div class="category-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                            <?php foreach ($availableCategories as $val => $data): ?>
                                                <label>
                                                    <input type="checkbox" class="cat-check form-check-input"
                                                        id="cat-<?= str_replace(' ', '-', $val) ?>"
                                                        value="<?= $val ?>"> <?= $data['label'] ?>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>

                                        <input type="hidden" id="upd-categories" name="categories">
                                    </div>
                                </div>
                            </div>

                            <div class="right-wrapper">
                                <div class="mb-3">
                                    <label class="form-label">Upload New Image</label>
                                    <input type="file" id="upd-img-file" name="foto" class="form-control bg-white">
                                </div>

                                <div class="card p-2 mb-3 border-0 shadow-sm">
                                    <label class="small text-muted mb-2">Image Preview</label>
                                    <img id="upd-img-preview" src="/assets/img/placeholder.png"
                                        style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; border: 1px solid #ddd;">
                                    <div id="modalPhotoCarousel" class="carousel slide">
                                        <div class="carousel-inner" id="modal-carousel-inner" style="height: 80px;"></div>
                                    </div>
                                </div>

                                <div class="mt-3 cafe-description">
                                    <label class="form-label">Description</label>
                                    <textarea id="upd-desc" name="deskripsi" class="form-control" rows="8"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background-color: var(--ca-bg-card); border-top: 1px dashed var(--ca-primary);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: var(--ca-primary); color: white; font-family: 'Homenaje'; font-size: 1.3rem;">Save Changes</button>
                </div>
            </form>
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
        searchInput.addEventListener("input", filterCafes);


        // BADGE KATEGORI MODAL
        function updateSelectedCategories() {
            const displayElement = document.getElementById("selectedCategoriesDisplay");
            const checkboxes = document.querySelectorAll(".cat-check");
            const hiddenInput = document.getElementById("upd-categories");

            displayElement.innerHTML = "";
            let selectedValues = [];

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    const labelText = checkbox.parentElement.textContent.trim();
                    const checkboxId = checkbox.id;
                    selectedValues.push(checkbox.value);

                    let badgeClass = "cat-productivity"; // default
                    if (checkbox.value.includes("social")) badgeClass = "cat-social";
                    if (checkbox.value.includes("roastery")) badgeClass = "cat-roastery";
                    if (checkbox.value.includes("pet")) badgeClass = "red";

                    const badgeHtml = `
                    <span class="badge ${badgeClass} d-inline-flex align-items-center me-2 mt-1" data-checkbox-id="${checkboxId}" style="font-family: 'Caveat'; font-size: 1.3rem;">
                        ${labelText}
                        <button type="button" class="btn-close btn-close-white ms-2" aria-label="Remove ${labelText}"></button>
                    </span>`;
                    displayElement.insertAdjacentHTML("beforeend", badgeHtml);
                }
            });
            hiddenInput.value = selectedValues.join(","); // Kirim ke backend sebagai "val1,val2"

            displayElement.querySelectorAll(".btn-close").forEach((btn) => {
                btn.addEventListener("click", function() {
                    const badgeSpan = this.closest(".badge");
                    const targetId = badgeSpan.dataset.checkboxId;
                    const originalCheckbox = document.getElementById(targetId);
                    if (originalCheckbox) {
                        originalCheckbox.checked = false;
                        updateSelectedCategories();
                    }
                });
            });
            if (selectedValues.length === 0) {
                displayElement.innerHTML = '<small class="text-muted">No category selected.</small>';
            }
        }
        document.querySelectorAll(".cat-check").forEach(chk => {
            chk.addEventListener("change", updateSelectedCategories);
        });

        // POPULATE MODAL (Isi data dari tombol Update)
        const updateModal = document.getElementById("updateCafeModal");
        if (updateModal) {
            updateModal.addEventListener("show.bs.modal", function(event) {
                const btn = event.relatedTarget;
                if (!btn) return;

                document.getElementById("upd-id").value = btn.dataset.id;
                document.getElementById("upd-nama").value = btn.dataset.nama;
                document.getElementById("upd-rating").value = btn.dataset.rating;
                document.getElementById("upd-min").value = btn.dataset.min;
                document.getElementById("upd-max").value = btn.dataset.max;
                document.getElementById("upd-avg").value = btn.dataset.avg;
                document.getElementById("upd-lat").value = btn.dataset.lat;
                document.getElementById("upd-long").value = btn.dataset.long;
                document.getElementById("upd-desc").value = btn.dataset.desc;

                // Reset Checkbox
                document.querySelectorAll(".cat-check").forEach(chk => chk.checked = false);

                // Parse Data Kategori dari Button
                let catList = [];
                try {
                    catList = JSON.parse(btn.dataset.kategori);
                } catch (e) {
                    catList = [];
                }

                // Loop untuk nyentang yang sesuai
                catList.forEach(catName => {
                    // Cari checkbox dengan value yang sama persis plek ketiplek dengan DB
                    // .cat-check[value="social space"]
                    const targetBox = document.querySelector(`.cat-check[value="${catName}"]`);
                    if (targetBox) targetBox.checked = true;
                });
                updateSelectedCategories();

                // Photos
                let photos = [];
                try {
                    photos = JSON.parse(btn.dataset.photos);
                } catch (e) {
                    photos = [];
                }
                const carouselInner = document.getElementById("modal-carousel-inner");
                const preview = document.getElementById("upd-img-preview");
                carouselInner.innerHTML = "";

                if (photos.length > 0) {
                    preview.src = photos[0];
                    photos.forEach((src, idx) => {
                        const item = document.createElement("div");
                        item.className = "carousel-item" + (idx === 0 ? " active" : "");
                        item.innerHTML = `<img src="${src}" class="d-block w-100" data-src="${src}" style="height: 80px; object-fit: cover; cursor:pointer;">`;
                        carouselInner.appendChild(item);
                        item.querySelector("img").addEventListener("click", function() {
                            preview.src = this.dataset.src;
                        });
                    });
                } else {
                    carouselInner.innerHTML = `<div class="carousel-item active"><img src="/assets/img/placeholder.png" class="d-block w-100" style="height: 80px; object-fit: cover;"></div>`;
                    preview.src = "/assets/img/placeholder.png";
                }
            });
        }

        // UI TOGGLES buat Modal
        const catBtn = document.querySelector(".categoryBtn");
        const catPanel = document.getElementById("category-panel");
        if (catBtn && catPanel) {
            catBtn.addEventListener("click", () => {
                catPanel.style.display = catPanel.style.display === "none" ? "block" : "none";
            });
        }

        const fileInput = document.getElementById("upd-img-file");
        const preview = document.getElementById("upd-img-preview");
        if (fileInput && preview) {
            fileInput.addEventListener("change", function() {
                const file = this.files[0];
                if (file) preview.src = URL.createObjectURL(file);
            });
        }
    });
</script>