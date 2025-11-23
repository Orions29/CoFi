<div class="main-container themap">
    <!-- Container Buat Map Sangat Penting -->
    <div id="map"></div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cafeSidebar" aria-labelledby="cafeSidebarLabel" style="width: 400px;">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" id="sidebarName">Loading...</h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <img id="sidebarImage" src="" alt="Cafe Image" style="width: 100%; height: 250px; object-fit: cover;">

            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="badge bg-warning text-dark fs-6 d-flex align-items-center">
                        <span class="material-symbols-outlined" style="margin-right: 3px;">coffee</span>
                        <span id="sidebarRating">0.0</span>
                    </div>
                    <span class="text-muted small" id="sidebarCoords">-</span>
                </div>
                <div id="sidebarCategories" class="cafe-category mb-3 d-flex flex-wrap gap-1"></div>
                <h6 class="fw-bold">Tentang Cafe</h6>
                <p class="text-muted" id="sidebarDesc">...</p>
                <hr>
                <div class="d-grid gap-2">
                    <a id="btnFullDetail" href="#" class="btn">Lihat Halaman Full</a>
                    <a id="btnMaps" href="#" target="_blank" class="btn">
                        <i class="bi bi-map"></i> Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // TOKEN MAPBOX
        mapboxgl.accessToken = '<?= $_ENV['MAPBOX_API'] ?>';
        // INIT Map dari Mapbox
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/orions175/cmhlpivkl002e01pg3mns63q9',
            center: [110.366857, -7.783231], // Koordinat [Lng, Lat] / [Longitude, Latitude]
            zoom: 16.84,
            bearing: -140.30,
            pitch: 70.59,
            antialias: true
        });
        map.addControl(new mapboxgl.NavigationControl());

        // Global Store Data
        window.cafesData = {};
        // SIDEBAR (Global)
        window.showSidebar = function(id) {
            const cafe = window.cafesData[id];
            if (!cafe) return;

            // Pengisi Data
            document.getElementById('sidebarName').innerText = cafe.name;
            const imgEl = document.getElementById('sidebarImage');
            if (imgEl) imgEl.src = cafe.image;
            document.getElementById('sidebarRating').innerText = cafe.rating;
            document.getElementById('sidebarCoords').innerText = `${cafe.lat.toFixed(6)}, ${cafe.lng.toFixed(6)}`;
            document.getElementById('sidebarDesc').innerText = cafe.desc;

            // Updater Link
            document.getElementById('btnFullDetail').href = `/cafe/detail?id=${cafe.id}`;
            document.getElementById('btnMaps').href = `http://googleusercontent.com/maps.google.com/maps?q=${cafe.lat},${cafe.lng}`;

            // Category Generators
            const catContainer = document.getElementById('sidebarCategories');
            catContainer.innerHTML = '';
            if (cafe.categories && cafe.categories.length > 0) {
                cafe.categories.forEach(cat => {
                    const span = document.createElement('span');
                    let slug = cat.toLowerCase().trim().replace(/\s+/g, '-');
                    span.className = `badge map-cafe-detail cat-${slug}`;
                    span.innerText = cat;
                    span.style.marginRight = '4px';
                    catContainer.appendChild(span);
                });
            }

            // Ngambil Alamat dari Geocode
            const descEl = document.getElementById('sidebarDesc');
            // Reset dulu ke deskripsi asli
            descEl.innerHTML = `<div class="mb-2">Loading alamat...</div>${cafe.desc}`;

            const url = `https://api.mapbox.com/search/geocode/v6/reverse?longitude=${cafe.lng}&latitude=${cafe.lat}&access_token=${mapboxgl.accessToken}&limit=1`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    let alamat = "Alamat tidak ditemukan";
                    if (data.features?.length > 0) {
                        const f = data.features[0];
                        alamat = f.properties.full_address || f.properties.place_formatted;
                    }
                    // Update HTML Deskripsi sama Nambah Alamat
                    descEl.innerHTML = `
                        <div style="margin-bottom: 15px; padding: 10px; background: #fff; border-radius: 8px; border: 1px solid #eee;">
                            <strong style="color: #305669;">Lokasi :</strong><br>
                            <span style="font-size: 0.9rem;">${alamat}</span>
                        </div>
                        ${cafe.desc}
                    `;
                })
                .catch(() => {
                    descEl.innerText = cafe.desc;
                });

            // Show Sidebar
            new bootstrap.Offcanvas('#cafeSidebar').show();
        };

        // FETCH DATA MAP
        fetch('/get_cafes.php')
            .then(res => res.json())
            .then(data => {
                console.log("Cafes:", data.length);

                data.forEach(cafe => {
                    if (!cafe.lat || !cafe.lng) return;

                    // SIMPAN DATA
                    window.cafesData[cafe.id] = cafe;
                    // INJEK HTML DARI PHP
                    const popupHTML = `<?php
                                        // Ambil isi file popup
                                        $content = file_get_contents(__DIR__ . "/../includes/map_popup.php");
                                        // Bersihin enter/newline biar JS gak error
                                        echo str_replace(["\r", "\n"], '', $content);
                                        ?>`;
                    new mapboxgl.Marker({
                            color: '#305669'
                        })
                        .setLngLat([cafe.lng, cafe.lat])
                        .setPopup(new mapboxgl.Popup({
                            offset: 25
                        }).setHTML(popupHTML))
                        .addTo(map);
                });
            })
            .catch(err => console.error(err));
    </script>
</div>