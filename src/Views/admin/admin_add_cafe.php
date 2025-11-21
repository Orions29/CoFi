<?php
// Salting Procedure
?>
<div class="main-container cafe-editor d-flex">
    <div class="form-container" id="edit-cafe-wrapper">
        <h1 class="homenaje-regular form-title">Add Cafe</h1>
        <form action="/" method="post">
            <div class="main-form-wrapper cafe-form-wrapper">
                <div class="left-wrapper wrapper">
                    <input type="hidden" name="action" value="add_cafe">
                    <input type="hidden" name="" value="">
                    <div class="mb-2" id="cafeNameContainer">
                        <label for="cafeEmail" class="form-label regis">Cafe Email</label>
                        <input type="email" class="form-control" aria-describedby="emailHelp" id="userEmailRegis" name="cafeEmailAdd" placeholder="yourCafe@maker" required>
                    </div>
                    <div class="cafe-name mb-2" id="namaCafeContainer">
                        <label for="fullNameRegis" class="form-label regis">Nama Cafe</label>
                        <input type="text" class="form-control" id="namaCafeAdd" name="namaCafe" placeholder="Coffe Maker" required oninvalid="this.setCustomValidity('Woi, namanya')" oninput="this.setCustomValidity('')">
                    </div>
                    <div class="cafe-name mb-2" id="koordinatCafeContainer">
                        <label for="fullNameRegis" class="form-label regis">Alamat Cafe (url)</label>
                        <input type="url" class=" form-control" id="namaCafeAdd" name="namaCafe" placeholder="Coffe Maker" required oninvalid="this.setCustomValidity('Woi, Alamatnya dimana???')" oninput="this.setCustomValidity('')">
                    </div>
                    <div class="cafe-rating mb-3" id="ratingContainerAdd">
                        <label for="fullNameRegis" class="form-label regis">Rating</label>
                        <input type="number" step="0.1" class="form-control" id="ratingCafeAdd" name="ratingCafe" placeholder="0" required ">
                    </div>
                    
                    <div class=" mb-2" id="categoryCafeContainer">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle kategori"
                                type="button"
                                data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"
                                aria-expanded="false">
                                Pilih Kategori
                            </button>

                            <ul class="dropdown-menu p-3" aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category[]" value="productivity" id="cat-productivity">
                                        <label class="form-check-label" for="cat-productivity">
                                            Productivity
                                        </label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category[]" value="social space" id="cat-social">
                                        <label class="form-check-label" for="cat-social">
                                            Social Space
                                        </label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category[]" value="roastery" id="cat-roastery">
                                        <label class="form-check-label" for="cat-roastery">
                                            Roastery
                                        </label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category[]" value="live music" id="cat-livemusic">
                                        <label class="form-check-label" for="cat-livemusic">
                                            Live Music
                                        </label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category[]" value="outdoor seating" id="cat-outdoor">
                                        <label class="form-check-label" for="cat-outdoor">
                                            Outdoor Seating
                                        </label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category[]" value="pet friendly" id="cat-pet">
                                        <label class="form-check-label" for="cat-pet">
                                            Pet Friendly
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <span id="selectedCategoriesDisplay" class="text-muted small"></span>
                </div>
                <div class="right-wrapper wrapper">
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Gambar Suasana Cafe</label>
                        <input class="form-control" type="file" id="multipleCafePicture" name="cafePicture" multiple>
                    </div>
                    <div class=" price-wrapper d-flex justify-content-between">
                        <div class="max-price-container price-container ">
                            <label for="fullNameRegis" class="form-label regis">Max Pricing</label>
                            <div class="input-group cafe-rating mb-3" id="maxPriceContainer">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="number" step="500" class="form-control" id="maxPrice" name="ratingCafe" placeholder="0" required ">
                            </div>
                        </div>
                        <div class=" min-price-container price-container">
                                <label for="fullNameRegis" class="form-label regis">Min Pricing</label>
                                <div class="input-group cafe-rating mb-3" id="minPriceContainer">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="number" step="500" class="form-control" id="minPrice" name="ratingCafe" placeholder="0" required ">
                                </div>
                        </div>
                        <div class=" avg-price-container price-container">
                                    <label for="fullNameRegis" class="form-label regis">Average Pricing</label>
                                    <div class="input-group cafe-rating mb-3" id="avgPriceContainer">
                                        <span class="input-group-text" id="basic-addon1">Rp</span>
                                        <input type="number" step="500" max="5" class="form-control" id="avgPrice" name="ratingCafe" placeholder="0" required ">
                                    </div>
                        </div>
                    </div>
                                    <div class=" mb-3">
                                        <label for="cafeDescription" class="form-label">Cafe Description</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                            name="cafeDescription" placeholder="Cafe Deskription Here"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="add-cafe-container">
                                <button class="btn btn-submit add-cafe mt-2 mb-2" type=" submit">
                                    Add Cafe
                                </button>
                            </div>
        </form>
    </div>
</div>