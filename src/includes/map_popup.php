<div class="popup-card">
    <img src="${cafe.image}" class="popup-img" alt="${cafe.name}">
    <div class="popup-info">
        <h3>${cafe.name}</h3>
        <div class="popup-rating d-flex align-items-center">
            <span class="material-symbols-outlined" style="margin-right: 3px;">coffee</span>
            ${cafe.rating}
        </div>
        <button onclick="window.showSidebar('${cafe.id}')" class="btn-detail" style="border:none; cursor:pointer;">
            Lihat Detail
        </button>
    </div>
</div>