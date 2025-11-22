<div class="main-container">
    <div class="form-container dashboard-page-container" id="edit-cafe-wrapper">
        <div class="title-container d-flex align-items-center flex-column" style="width: 100%;">
            <h1>Admin Dashboard</h1>
            <hr class="divider-soft">
        </div>
        <div class="dashboard-container">
            <div class="counter-wrapper">
                <div class="counter-contents" id="cafe-counter">
                    <h2><span class="material-symbols-outlined">
                            storefront
                        </span> Cafe Counter</h2>
                    <h1>20</h1>
                </div>
                <div class="counter-contents" id="user-counter">
                    <h2><span class="material-symbols-outlined">
                            supervised_user_circle
                        </span> User Counter</h2>
                    <h1>20</h1>
                </div>
            </div>
            <div class="cafe-recent-wrapper">
                <div class="title-recent-cafe-wrapper d-flex justify-content-start align-items-center mb-1 ms-2">

                    <span class="material-symbols-outlined" style="margin-right: 6px; font-size:2rem;">
                        history
                    </span>
                    <h2>Recent Cafe Activity</h2>
                </div>
                <div class="cafe-table-wrapper">
                    <?php
                    for ($i = 0; $i < 10; $i++):
                    ?>
                        <div class="card cafe-recent-card mb-2">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Nama Cafenya</h5>
                                <p class="card-text">Deskripsi Cafenya</p>
                                <p class="card-text"><small class="text-body-secondary">Last updated Kapan? di SQL</small></p>
                            </div>
                        </div>
                    <?php
                    endfor;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>