<?php
require_once __DIR__ . "/../../Core/database.php";

$query = "SELECT * FROM cafes ORDER BY cafe_id DESC";
$stmt = $sqlConn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$cafes = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$photoStmt = $sqlConn->prepare("
        SELECT file_path 
        FROM cafe_photos 
        WHERE cafe_id = ?
        ORDER BY photo_id DESC
    ");


function renderCoffeeRating($rating, $max = 5)
{
    $html = "<div class='coffee-rating'>";

    for ($i = 1; $i <= $max; $i++) {
        $cls = $i <= $rating ? "coffee-active" : "coffee-inactive";

        $html .= "<span class='material-symbols-outlined $cls'>coffee</span>";
    }

    $html .= "</div>";
    return $html;
}

$icons = [
    "productivity"      => "💻",
    "social space"      => "👥",
    "roastery"          => "☕",
    "live music"        => "🎵",
    "outdoor seating"   => "🌿",
    "pet friendly"      => "🐾"
];

?>

<div class="page-container">
    <h1 class="brand-title" style="margin-bottom: 20px; color:black; text-align:center;">Update Catalogue Cafe</h1>
    <?php foreach ($cafes as $cafe):
        $id = (int)$cafe['cafe_id'];
        $photoStmt->bind_param("i", $id);
        $photoStmt->execute();
        $photoRes = $photoStmt->get_result();

        $photos = [];
        while ($p = $photoRes->fetch_assoc()) {
            $clean = str_replace(['"', '\\'], '', $p['file_path']);
            $photos[] = $clean;
        }
        $encodedPhotos = htmlspecialchars(json_encode($photos), ENT_QUOTES, 'UTF-8');
        $thumb = $photos[0] ?? null;
        if ($thumb) {
            if (str_starts_with($thumb, '/uploads/')) {
                $displayPhoto = $thumb;
            } else {
                $displayPhoto = '/assets/img/placeholder.png';
            }
        }

        $catStmt = $sqlConn->prepare("SELECT category_name FROM cafe_categories WHERE cafe_id = ?");
        $catStmt->bind_param("i", $id);
        $catStmt->execute();
        $catRes = $catStmt->get_result();

        $kategori = [];
        while ($row = $catRes->fetch_assoc()) {
            $kategori[] = $row['category_name'];
        }

        $nama = htmlspecialchars($cafe['cafe_name']);
        $deskripsi = htmlspecialchars($cafe['cafe_description']);
        $lat = $cafe['latitude'];
        $long = $cafe['longitude'];
        $rating = $cafe['rating'];
        $min = $cafe['price_min'];
        $max = $cafe['price_max'];
        $avg = $cafe['price_avg'];
        // var_dump($thumb);

    ?>
        <div class="cafe-card">
            <div id="carouselCafe<?= $id ?>" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-indicators">
                    <?php foreach ($photos as $index => $p): ?>
                        <button type="button" data-bs-target="#carouselCafe<?= $id ?>"
                            data-bs-slide-to="<?= $index ?>"
                            class="<?= $index === 0 ? 'active' : '' ?>"
                            aria-current="<?= $index === 0 ? 'true' : 'false' ?>"></button>
                    <?php endforeach; ?>
                </div>

                <div class="carousel-inner">
                    <?php if (!empty($photos)): ?>
                        <?php foreach ($photos as $index => $p): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= $p ?>" class="d-block w-100 cafeCarouselImg" alt="Cafe Photo">
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
            <div style="flex:1; display:flex; flex-direction:column;">
                <div class="top-row" style="display:flex; justify-content:space-between; align-items:center;">
                    <h2><?= $nama ?></h2>
                    <div style="display:flex; flex-direction:column; align-items:flex-end;">
                        <?= renderCoffeeRating($rating) ?>
                        <div style="font-size:30px;">
                            Rp<?= number_format($min, 0, ',', '.') ?> - Rp<?= number_format($max, 0, ',', '.') ?>
                        </div>
                    </div>
                </div>
                <div class="cafe-location"><i class="bi bi-geo-alt-fill"></i> <?= $cafe['latitude'] ?>, <?= $cafe['longitude'] ?></div>
                <p class="cafe-desc"><?= nl2br($deskripsi) ?></p>
                <?php if (!empty($kategori)): ?>
                    <div class="cafe-categories">
                        <?php foreach ($kategori as $k):
                            // convert category name → class-friendly
                            $class = "badge " . str_replace(" ", "-", strtolower($k));
                        ?>
                            <div class=" <?= $class ?>">
                                <span class="cat-icon"><?= $icons[strtolower($k)] ?? "•" ?></span>
                                <?= htmlspecialchars($k) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:auto;">
                    <form method="POST" onsubmit="return confirm('Yakin hapus cafe ini?');">
                        <input type="hidden" name="action" value="delete_cafe_attempt">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button class="delete-btn">Delete</button>
                    </form>
                    <button class="update-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#updateCafeModal"
                        data-id="<?= $id ?>"
                        data-nama="<?= $nama ?>"
                        data-desc="<?= $deskripsi ?>"
                        data-rating="<?= $cafe['rating'] ?>"
                        data-min="<?= $cafe['price_min'] ?>"
                        data-max="<?= $cafe['price_max'] ?>"
                        data-lat="<?= $cafe['latitude'] ?>"
                        data-long="<?= $cafe['longitude'] ?>"
                        data-avg=<?= $cafe['price_avg'] ?>
                        data-kategori=<?= json_encode($kategori) ?>
                        data-photos="<?= $encodedPhotos ?>">Update</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- UPDATE MODAL -->
<div class="modal fade" id="updateCafeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none;">

            <div class="modal-header" style="background-color: #c1785a; color: white;">
                <h1 class="modal-title fs-4" style="font-family: 'Homenaje';">Update Cafe</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_cafe_attempt">
                <input type="hidden" name="id" id="upd-id">

                <div class="modal-body" style="background-color: #f7f1de;">
                    <div class="row">

                        <div class="col-md-6 p-4">
                            <div class="mb-3">
                                <label class="form-label" style="font-family: 'Homenaje'; font-size: 1.2rem; color: #c1785a;">Cafe Name</label>
                                <input type="text" id="upd-nama" name="nama" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" id="upd-long" name="longitude" class="form-control" placeholder="110.xxxx">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" id="upd-lat" name="latitude" class="form-control" placeholder="-7.xxxx">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <input type="number" step="0.1" max="5" id="upd-rating" name="rating" class="form-control" placeholder="0-5">
                            </div>

                            <label class="form-label">Pricing</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Min</span>
                                <input type="number" id="upd-min" name="min_price" class="form-control">
                                <span class="input-group-text">Max</span>
                                <input type="number" id="upd-max" name="max_price" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Average Price</label>
                                <input type="number" id="upd-avg" name="avg_price" class="form-control">
                            </div>

                            <div class="mt-4">
                                <button type="button" class="btn w-100 categoryBtn"
                                    style="background:#c97847; color:white; font-family: 'Homenaje'; font-size: 1.2rem;">
                                    ⚙ Manage Categories
                                </button>

                                <div id="category-panel" style="display:none; background:white; padding:15px; border-radius:10px; margin-top:10px; border: 1px solid #c1785a;">
                                    <label style="font-weight:600; margin-bottom: 10px; display:block;">Select Categories:</label>
                                    <div class="category-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                        <label><input type="checkbox" class="cat-check form-check-input" value="productivity"> Productivity</label>
                                        <label><input type="checkbox" class="cat-check form-check-input" value="social space"> Social Space</label>
                                        <label><input type="checkbox" class="cat-check form-check-input" value="roastery"> Roastery</label>
                                        <label><input type="checkbox" class="cat-check form-check-input" value="live music"> Live Music</label>
                                        <label><input type="checkbox" class="cat-check form-check-input" value="outdoor seating"> Outdoor Seating</label>
                                        <label><input type="checkbox" class="cat-check form-check-input" value="pet friendly"> Pet Friendly</label>
                                    </div>
                                    <input type="hidden" id="upd-categories" name="categories">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 p-4">
                            <div class="mb-3">
                                <label class="form-label" style="font-family: 'Homenaje'; font-size: 1.2rem; color: #c1785a;">Upload New Image</label>
                                <input type="file" id="upd-img-file" name="foto" class="form-control bg-white">
                            </div>

                            <div class="card p-2 mb-3">
                                <label class="small text-muted mb-2">Image Preview / Current Images</label>

                                <img id="upd-img-preview" src="/assets/img/placeholder.png"
                                    style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; border: 1px solid #ddd;">

                                <div id="modalPhotoCarousel" class="carousel slide">
                                    <div class="carousel-inner" id="modal-carousel-inner" style="height: 80px;">
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#modalPhotoCarousel" data-bs-slide="prev" style="width: 20px;">
                                        <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: gray; border-radius: 50%;"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#modalPhotoCarousel" data-bs-slide="next" style="width: 20px;">
                                        <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: gray; border-radius: 50%;"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-family: 'Homenaje'; font-size: 1.2rem; color: #c1785a;">Description</label>
                                <textarea id="upd-desc" name="deskripsi" class="form-control" rows="8"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer" style="background-color: #f7f1de; border-top: 1px solid #dcdcdc;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #c1785a; color: white;">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const modal = document.getElementById("updateCafeModal");
        if (!modal) return;

        modal.addEventListener("show.bs.modal", function(event) {

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


            let photos = [];
            try {
                photos = JSON.parse(btn.dataset.photos);
            } catch (e) {
                photos = [];
            }

            const preview = document.getElementById("upd-img-preview");
            const carouselInner = document.getElementById("modal-carousel-inner");

            if (carouselInner) carouselInner.innerHTML = "";
            if (!photos || photos.length === 0) {
                if (carouselInner) {
                    carouselInner.innerHTML = `
                    <div class="carousel-item active">
                        <img src="/assets/img/placeholder.png" class="modal-carousel-img">
                    </div>
                `;
                }
                if (preview) preview.src = "/assets/img/placeholder.png";
                return;
            }


            photos.forEach((src, index) => {
                const item = document.createElement("div");
                item.className = "carousel-item" + (index === 0 ? " active" : "");

                item.innerHTML = `
                <img src="${src}" class="modal-carousel-img" data-photo="${src}">
            `;

                carouselInner.appendChild(item);
            });

            if (preview) preview.src = photos[0];

            setTimeout(() => {
                document.querySelectorAll(".modal-carousel-img").forEach(img => {
                    img.addEventListener("click", () => {
                        preview.src = img.dataset.photo;
                    });
                });
            }, 50);
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btn = document.querySelector(".categoryBtn");
        const panel = document.getElementById("category-panel");

        if (!btn || !panel) return;

        btn.addEventListener("click", () => {
            panel.style.display = panel.style.display === "none" ? "block" : "none";
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checks = document.querySelectorAll(".cat-check");
        const output = document.getElementById("upd-categories");

        if (!output) return;

        checks.forEach(chk => {
            chk.addEventListener("change", () => {
                const selected = [...document.querySelectorAll(".cat-check:checked")]
                    .map(c => c.value);
                output.value = selected.join(",");
            });
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById("upd-img-file");
        const preview = document.getElementById("upd-img-preview");

        if (!fileInput || !preview) return;

        fileInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file) preview.src = URL.createObjectURL(file);
        });
    });
</script>

<script>
    document.getElementById("updateCafeModal").addEventListener("show.bs.modal", function(event) {
        const btn = event.relatedTarget;

        let kategoriList = [];
        try {
            kategoriList = JSON.parse(btn.dataset.kategori);
        } catch (e) {}

        document.querySelectorAll(".cat-check").forEach(chk => {
            chk.checked = kategoriList.includes(chk.value);
        });
    });
</script>