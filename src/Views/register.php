<?php
// Salting Procedure
$regisSalt = $_ENV['REGIS_SALT'];
$_SESSION['regis_token'] = $regisSalt . bin2hex(random_bytes(20));
unset($_SESSION['login_token']);
?>
<div class="form-contents-wrapper regis">
    <div class="form-wrapper regis">
        <header class="login-header regis">
            <svg class="logo-form register" viewBox="0 0 138 62" fill="none" xmlns="http://www.w3.org/2000/svg">
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
        <div class="form-title register">
            <h1>Register Page</h1>
        </div>
        <div class="form-main-container">
            <form action="/" method="post">
                <input type="hidden" name="action" value="regis_attempt">
                <input type="hidden" name="regis_token_attempt" value="<?php
                                                                        echo $_SESSION['regis_token'];
                                                                        ?>">
                <div class="mb-2" id="emailRegisContainer">
                    <label for="userEmailRegis" class="form-label regis">Your Email</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" id="userEmailRegis" name="userEmailRegis" placeholder="coffe@maker" required>
                </div>
                <div class="mb-2 name-birth-container d-flex justify-content-between">
                    <div class="full-name-regis" id="fullNameRegisContainer">
                        <label for="fullNameRegis" class="form-label regis">Your Full Name</label>
                        <input type="text" class="form-control" id="fullNameRegis" name="fullNameRegis" placeholder="Coffe Maker" required oninvalid="this.setCustomValidity('Woi, namanya')" oninput="this.setCustomValidity('')">
                    </div>
                    <div class="birth-datepicker-regis" id="bithRegisContainer">
                        <label for="birthRegis" class="form-label regis">Your Birtday</label>
                        <input type="date" id="birthRegis" name="birthRegis" class="form-control" required>
                    </div>

                </div>
                <label for="jobRegis" class="form-label regis">Select Your Job</label>
                <div class="form-floating mb-2" id="jobRegisContainer">
                    <select class="form-select" id="jobRegis" name="jobRegis" aria-label="Floating label select example">
                        <option selected value="Jobless">Jobless</option>
                        <option value="College">College Student</option>
                        <option value="Salarry Man">Salarry Man</option>
                        <option value="Investor">Investor</option>
                    </select>
                    <label for="jobRegis">Select Your Job</label>
                </div>
                <div class="mb-2" id="usernameRegisContainer">
                    <label for="usernameRegis" class="form-label regis">Create Username</label>
                    <input type="text" class="form-control" id="usernameRegis" name="usernameRegis" placeholder="yourUsername29" required oninvalid="this.setCustomValidity('Woi, PASSWORD ISI DULU')" oninput="this.setCustomValidity('')">
                </div>
                <label for="inputPassword" class="form-label regis">Create Password</label>
                <div class="input-group mb-3" id="passwordRegisContainer">
                    <input type="password" class="form-control" id="inputPassword" placeholder="password" name="passwordRegis" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                    </button>
                </div>
                <div class="button-container d-flex justify-contents-start flex-column regis">
                    <a href="/login">
                        I Have Account Sir
                    </a>
                    <button class="btn btn-success btn-submit regis" type="submit">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>