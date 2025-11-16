// Ambil elemen-elemennya
const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#inputPassword");
const icon = document.querySelector("#toggleIcon");

function updateSelectedCategories() {
	const selectedLabels = [];
	const displayElement = document.getElementById("selectedCategoriesDisplay");
	const checkboxes = document.querySelectorAll(
		"#categoryCafeContainer .form-check-input"
	);

	// Bersihin Semua Badge
	displayElement.innerHTML = "";

	// Looping untuk mencari item yang dicentang
	checkboxes.forEach((checkbox) => {
		const labelText = checkbox.nextElementSibling.textContent.trim();
		const checkboxId = checkbox.id; // Kita ambil ID untuk linking

		if (checkbox.checked) {
			// Buat HTML untuk Badge baru (Pill Look)
			const badgeHtml = `
				<span class="badge text-bg-secondary d-inline-flex align-items-center me-2 mt-1 ${checkboxId} " data-checkbox-id="${checkboxId}">
					${labelText}
					<button type="button" class="btn-close btn-close-white ms-2" aria-label="Remove ${labelText}"></button>
				</span>
			`;
			// Masukkan badge baru ke display area
			displayElement.insertAdjacentHTML("beforeend", badgeHtml);
		}
	});

	// Pasang Listener ke Tombol Close yang BARU DIBUAT
	// Kita harus pasang listener setiap kali badge dibuat
	document
		.querySelectorAll("#selectedCategoriesDisplay .btn-close")
		.forEach((closeButton) => {
			closeButton.addEventListener("click", function () {
				// Dapatkan ID checkbox asli dari attribute data-checkbox-id di tag <span>
				const badgeSpan = this.closest(".badge");
				const targetId = badgeSpan.dataset.checkboxId;

				// Uncheck checkbox aslinya
				const originalCheckbox = document.getElementById(targetId);
				if (originalCheckbox) {
					originalCheckbox.checked = false;

					// Trigger event 'change' agar fungsi updateSelectedCategories() berjalan lagi secara otomatis
					const event = new Event("change");
					originalCheckbox.dispatchEvent(event);
				}
			});
		});

	// 4. Kasih pesan default kalau tidak ada yang terpilih
	if (displayElement.innerHTML === "") {
		displayElement.textContent = "Belum ada kategori terpilih.";
	}
}

document.addEventListener("DOMContentLoaded", () => {
	// 1. Logic INI buat password toggle (yang sebelumnya error 'null')
	const togglePassword = document.querySelector("#togglePassword");
	if (togglePassword) {
		togglePassword.addEventListener("click", function (e) {
			// Ganti tipe inputnya
			const type =
				password.getAttribute("type") === "password" ? "text" : "password";
			password.setAttribute("type", type);

			// Ganti ikon matanya
			if (type === "password") {
				icon.classList.remove("bi-eye");
				icon.classList.add("bi-eye-slash");
			} else {
				icon.classList.remove("bi-eye-slash");
				icon.classList.add("bi-eye");
			}
		});
	}

	// 2. Logic INI yang PALING PENTING buat Category Badge
	document
		.querySelectorAll("#categoryCafeContainer .form-check-input")
		.forEach((checkbox) => {
			// wajib
			checkbox.addEventListener("change", updateSelectedCategories);
		});

	// 3. PANGGILAN AWAL (BIAR BADGE MUNCUL PAS PAGE LOAD)
	// PASTIKAN BARIS INI ADA!
	updateSelectedCategories();
});
