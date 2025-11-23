
<?php
require __DIR__ . "/../../Core/database.php";


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

    
function renderCoffeeRating($rating, $max = 5) {
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
            }  else {
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
                        Rp<?= number_format($min,0,',','.') ?> - Rp<?= number_format($max,0,',','.') ?>
                    </div>
                </div>
            </div>
            <div class="cafe-location"><i class="bi bi-geo-alt-fill"></i> <?= $cafe['latitude'] ?>, <?= $cafe['longitude'] ?></div>
            <p class="cafe-desc"><?= nl2br($deskripsi) ?></p>
            <?php if (!empty($kategori)): ?>
            <div class="cafe-categories">
                <?php foreach ($kategori as $k): ?>
                    <?php 
                        // convert category name → class-friendly
                        $class = "cat-" . str_replace(" ", "-", strtolower($k)); 
                    ?>
                    <span class="cat-badge <?= $class ?>">
                        <span class="cat-icon"><?= $icons[strtolower($k)] ?? "•" ?></span>
                        <?= htmlspecialchars($k) ?>
                    </span>
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
                    data-photos="<?= $encodedPhotos ?>"
                >Update</button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- UPDATE MODAL -->
<div class="modal fade" id="updateCafeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content updateCafeModal">

            <div class="updateModal-header">
                <h1>Update Cafe</h1>
                <button type="button" class="closeModalBtn" data-bs-dismiss="modal">✕</button>
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_cafe_attempt">
                <input type="hidden" name="id" id="upd-id">

                <div class="updateModal-body">

                    <div class="updateLeft">

                        <label>Edit Cafe Name</label>
                        <input type="text" id="upd-nama" name="nama" class="updateInput">

                        <label>Longitude</label>
                        <input type="text" id="upd-long" name="longitude" class="updateInput" placeholder="110.xxxx">

                        <label>Latitude</label>
                        <input type="text" id="upd-lat" name="latitude" class="updateInput" placeholder="-7.xxxx">

                        <label>Rating</label>
                        <input type="text" id="upd-rating" name="rating" class="updateInput" placeholder="0-5">

                        <div class="priceRow">
                            <div>
                                <label>Min Price</label>
                                <input type="text" id="upd-min" name="min_price" class="updateInput">
                            </div>
                            <div>
                                <label>Max Price</label>
                                <input type="text" id="upd-max" name="max_price" class="updateInput">
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Average Price</label>
                                    <input type="text" id="upd-avg" name="avg_price" 
                                        class="form-control mb-3 updateInput">
                                </div>
                            </div>
                        <!-- CATEGORY BUTTON -->
                        <button type="button" class="btn btn-sm categoryBtn" 
                            style="background:#c97847; color:white; margin-top:10px;">
                            ⚙ Category
                        </button>
                        <!-- CATEGORY DROPDOWN -->
                        <div id="category-panel" 
                            style="display:none; background:#fff1e3; padding:15px; border-radius:10px; margin-top:15px;">                           
                            <label style="font-weight:600;">Categories</label>
                            <div class="category-grid">
                                <label><input type="checkbox" class="cat-check" value="productivity"> Productivity</label>
                                <label><input type="checkbox" class="cat-check" value="social space"> Social Space</label>
                                <label><input type="checkbox" class="cat-check" value="roastery"> Roastery</label>
                                <label><input type="checkbox" class="cat-check" value="live music"> Live Music</label>
                                <label><input type="checkbox" class="cat-check" value="outdoor seating"> Outdoor Seating</label>
                                <label><input type="checkbox" class="cat-check" value="pet friendly"> Pet Friendly</label>
                            </div>

                            <input type="hidden" id="upd-categories" name="categories">
                        </div>
                    </div>
                    <div class="updateRight">
                        <label>Upload New Image</label>
                        <input type="file" id="upd-img-file" name="foto" class="form-control mb-3" style="background-color: white;">
                        
                        <div id="modal-carousel-wrapper">
                            <label>Current Images</label>
                            <div id="modalPhotoCarousel" class="carousel slide">
                                <div class="carousel-inner" id="modal-carousel-inner">
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#modalPhotoCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#modalPhotoCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>
                            <label style="margin-top:10px;">Preview (Selected)</label>
                            <img id="upd-img-preview" class="previewImg" src="/assets/img/placeholder.png">
                        </div>

                        <label>Edit Description</label>
                        <textarea id="upd-desc" name="deskripsi" class="updateTextarea"></textarea>
                    </div>
                </div>
                <div class="updateModal-footer">
                    <button type="submit" class="updateBtnFinal">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const modal = document.getElementById("updateCafeModal");
    if (!modal) return;

    modal.addEventListener("show.bs.modal", function (event) {

        const btn = event.relatedTarget;
        if (!btn) return;

        document.getElementById("upd-id").value   = btn.dataset.id;
        document.getElementById("upd-nama").value = btn.dataset.nama;
        document.getElementById("upd-rating").value = btn.dataset.rating;
        document.getElementById("upd-min").value  = btn.dataset.min;
        document.getElementById("upd-max").value  = btn.dataset.max;
        document.getElementById("upd-avg").value  = btn.dataset.avg;
        document.getElementById("upd-lat").value  = btn.dataset.lat;
        document.getElementById("upd-long").value = btn.dataset.long;
        document.getElementById("upd-desc").value = btn.dataset.desc;


        let photos = [];
        try { photos = JSON.parse(btn.dataset.photos); }
        catch(e) { photos = []; }

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
document.addEventListener("DOMContentLoaded", function () {
    const btn = document.querySelector(".categoryBtn");
    const panel = document.getElementById("category-panel");

    if (!btn || !panel) return;

    btn.addEventListener("click", () => {
        panel.style.display = panel.style.display === "none" ? "block" : "none";
    });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
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
document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById("upd-img-file");
    const preview = document.getElementById("upd-img-preview");

    if (!fileInput || !preview) return;

    fileInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) preview.src = URL.createObjectURL(file);
    });
});
</script>

<script>

    document.getElementById("updateCafeModal").addEventListener("show.bs.modal", function (event) {
    const btn = event.relatedTarget;

    let kategoriList = [];
    try {
        kategoriList = JSON.parse(btn.dataset.kategori);
    } catch(e) {}

    document.querySelectorAll(".cat-check").forEach(chk => {
        chk.checked = kategoriList.includes(chk.value);
    });
});


</script>

