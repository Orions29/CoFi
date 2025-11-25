<div id="preloader">
    <div class="loader-content">
        <header class="preloader-header">
            <svg class="logo-form" viewBox="0 0 138 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="CoFi Logo">
                    <g id="I">
                        <path id="I_2" d="M131.104 61.2045V14.6546H138V61.2045H131.104Z" />
                        <path id="dotForI" d="M131.104 6.03425V0H138V6.03425H131.104Z" />
                    </g>
                    <path id="F" d="M94.0217 61.3998V0H119.897V5.96462H101.741V27.0161H115.424V32.9805H101.741V61.3998H94.0217Z" />
                    <g id="Beans">
                        <path id="LeftBeans" d="M45.6671 51.2792C57.4643 66.1672 61.0039 55.801 62.1173 43.2085C61.8834 32.8548 53.3801 23.6656 55.3532 13.0776C55.2829 10.7182 58.7821 4.85867 56.7226 3.51971C44.8185 6.70294 39.5695 20.0898 39.7165 31.7117C39.8039 39.0832 41.9173 46.0327 45.6671 51.2792Z" />
                        <path id="RightBeans" d="M65.0697 3.44814C63.1923 3.55343 62.0421 4.515 61.3225 5.83773C54.8968 19.1026 66.2199 30.9439 66.1426 44.0609C66.0752 48.9274 63.3395 53.356 63.5986 58.2161C69.2351 60.1108 73.9582 54.9394 77.3662 50.4946C86.8406 37.1183 84.7001 7.3931 65.0697 3.44814Z" />
                    </g>
                    <path id="C" d="M16.7534 61.3999C11.4322 61.3999 7.30955 60.0549 4.38571 57.365C1.46187 54.6751 0 50.728 0 45.5235V15.8763C0 10.672 1.46187 6.72476 4.38571 4.03499C7.30955 1.34513 11.4322 0 16.7534 0H28.5071V5.78926H16.7534C14.0636 5.78926 11.8853 6.56406 10.2187 8.11366C8.5521 9.66326 7.71888 11.7246 7.71888 14.2974V47.1025C7.71888 49.6755 8.5521 51.7366 10.2187 53.2862C11.8853 54.8358 14.0636 55.6108 16.7534 55.6108H28.5071V61.3999H16.7534Z" />
                </g>
            </svg>
        </header>
        <h2 class="homenaje-regular">Sabar dulu ya Ngopinya...</h2>
        <h3 id="load-percent" class="homenaje-regular" style="margin-top: 10px;">0%</h3>

        <div class="loading-bar-container">
            <div id="loading-bar-fill" class="loading-bar-fill"></div>
        </div>
    </div>
</div>
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
            zoom: 15.55,
            bearing: -110.30,
            pitch: 45,
            antialias: true
        });
        map.addControl(new mapboxgl.NavigationControl());

        // Loading Simulation
        let loadProgress = 0;
        const percentText = document.getElementById('load-percent');
        const barFill = document.getElementById('loading-bar-fill');
        const preloader = document.getElementById('preloader');

        // Memulai Loading Gadungan
        const loadingInterval = setInterval(() => {
            // nambah angka acak tp stuck di 85 ketika belum load
            if (loadProgress < 85) {
                loadProgress += Math.floor(Math.random() * 5) + 1; // Nambah 1-5% acak
                updateLoaderUI();
            }
            // Updatenya tiap 200ms
        }, 200);

        // Fungsi update tampilan HTML 
        function updateLoaderUI() {
            if (percentText) percentText.innerText = `${loadProgress}%`;
            if (barFill) barFill.style.width = `${loadProgress}%`;
        }

        // Ketika Map sudah selesai di load
        map.on('load', () => {
            // Stop simulasi
            clearInterval(loadingInterval);

            // Paksa langsung ke 100%
            loadProgress = 100;
            updateLoaderUI();

            // Kasih waktu user liat angka 100% bentar
            setTimeout(() => {
                if (preloader) {
                    preloader.classList.add('loaded'); // Animasi slide up
                    // Hapus elemen setelah animasi selesai
                    setTimeout(() => {
                        preloader.remove();
                    }, 1000);
                }
            }, 500);
            // Animasi Awal Ketika nanti dia sudah ke load
            map.flyTo({
                center: [110.366857, -7.783231],
                zoom: 16.84,
                bearing: -140.30,
                pitch: 70.59,
                duration: 3000
            });
        });

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
            document.getElementById('btnFullDetail').href = `user/catalouge?searchKey=${cafe.name}`;
            document.getElementById('btnMaps').href = `https://www.google.com/maps/dir/?api=1&destination=${cafe.lat},${cafe.lng}`;

            // Category Generator
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

            // NOTE Ini Buat GeoCoding (Ngambil Alamat)
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