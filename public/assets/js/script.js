// Ambil elemen-elemennya
const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#inputPassword");
const icon = document.querySelector("#toggleIcon");

togglePassword.addEventListener("click", function (e) {
	// Ganti tipe inputnya
	const type =
		password.getAttribute("type") === "password" ? "text" : "password";
	password.setAttribute("type", type);

	// Ganti ikon matanya
	if (type === "password") {
		// Kalo jadi password (disembunyiin)
		icon.classList.remove("bi-eye");
		icon.classList.add("bi-eye-slash");
	} else {
		// Kalo jadi teks (ditampilin)
		icon.classList.remove("bi-eye-slash");
		icon.classList.add("bi-eye");
	}
});
