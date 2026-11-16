document.addEventListener("DOMContentLoaded", function () {
    // ==========================================
    // 1. ELEMENT HTML
    // ==========================================

    const btnMasuk = document.getElementById("btn-masuk");
    const btnDaftar = document.getElementById("btn-daftar");

    const formMasuk = document.getElementById("form-masuk");
    const formDaftar = document.getElementById("form-daftar");

    const loginForm = formMasuk?.querySelector("form");
    const registerForm = formDaftar?.querySelector("form");

    // ==========================================
    // 2. TAB MASUK DAN DAFTAR
    // ==========================================

    function setActiveTab(tab, updateUrl = true) {
        const isDaftar = tab === "daftar";

        // Aktifkan tombol tab
        btnMasuk?.classList.toggle("active", !isDaftar);
        btnDaftar?.classList.toggle("active", isDaftar);

        // Tampilkan form sesuai tab
        if (formMasuk) {
            formMasuk.style.display = isDaftar ? "none" : "block";
        }

        if (formDaftar) {
            formDaftar.style.display = isDaftar ? "block" : "none";
        }

        // Ubah URL tanpa reload halaman
        if (updateUrl) {
            const activeButton = isDaftar ? btnDaftar : btnMasuk;
            const url = activeButton?.dataset.url;

            if (url) {
                history.replaceState({}, "", url);
            }
        }
    }

    // Ambil tab aktif dari Blade
    const activeTab = document.body.dataset.activeTab || "masuk";

    // Tampilkan tab sesuai halaman saat pertama dibuka
    setActiveTab(activeTab, false);

    // Event klik tab Masuk
    btnMasuk?.addEventListener("click", function (event) {
        event.preventDefault();
        setActiveTab("masuk");
    });

    // Event klik tab Daftar
    btnDaftar?.addEventListener("click", function (event) {
        event.preventDefault();
        setActiveTab("daftar");
    });

    // ==========================================
    // 3. TOGGLE PASSWORD
    // ==========================================

    const togglePasswordButtons = document.querySelectorAll(
        ".toggle-password-btn",
    );

    togglePasswordButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            const targetId = button.dataset.target;
            const passwordInput = document.getElementById(targetId);

            if (!passwordInput) return;

            const icon = button.querySelector("iconify-icon");

            const isPassword = passwordInput.type === "password";

            // Ubah tipe input
            passwordInput.type = isPassword ? "text" : "password";

            // Ubah ikon Iconify
            if (icon) {
                icon.setAttribute(
                    "icon",
                    isPassword ? "lucide:eye-off" : "lucide:eye",
                );
            }

            // Aksesibilitas
            button.setAttribute(
                "aria-label",
                isPassword ? "Sembunyikan password" : "Tampilkan password",
            );
        });
    });

    // ==========================================
    // 4. VALIDASI KONFIRMASI PASSWORD
    // ==========================================

    const registerPassword = document.getElementById("register-password");

    const passwordConfirmation = document.getElementById(
        "password_confirmation",
    );

    const passwordMatchMessage = document.getElementById(
        "password-match-message",
    );

    function checkPasswordMatch() {
        if (
            !registerPassword ||
            !passwordConfirmation ||
            !passwordMatchMessage
        ) {
            return true;
        }

        const password = registerPassword.value;
        const confirmation = passwordConfirmation.value;

        // Jika konfirmasi belum diisi,
        // serahkan validasi ke atribut required
        if (confirmation === "") {
            passwordMatchMessage.textContent = "";
            passwordConfirmation.setCustomValidity("");
            return true;
        }

        // Jika password cocok
        if (password === confirmation) {
            passwordMatchMessage.textContent = "Password cocok.";
            passwordMatchMessage.style.color = "#16a34a";

            passwordConfirmation.setCustomValidity("");

            return true;
        }

        // Jika password tidak cocok
        passwordMatchMessage.textContent = "Password tidak cocok.";
        passwordMatchMessage.style.color = "#dc2626";

        passwordConfirmation.setCustomValidity(
            "Konfirmasi password tidak cocok.",
        );

        return false;
    }

    // Periksa setiap kali password berubah
    registerPassword?.addEventListener("input", checkPasswordMatch);

    // Periksa setiap kali konfirmasi password berubah
    passwordConfirmation?.addEventListener("input", checkPasswordMatch);

    // ==========================================
    // 5. CEGAH SUBMIT JIKA PASSWORD TIDAK COCOK
    // ==========================================

    registerForm?.addEventListener("submit", function (event) {
        const isPasswordMatch = checkPasswordMatch();

        if (!isPasswordMatch) {
            event.preventDefault();

            passwordConfirmation?.focus();
        }
    });

    // ==========================================
    // 6. VALIDASI LOGIN
    // ==========================================

    loginForm?.addEventListener("submit", function () {
        // Biarkan validasi login ditangani Laravel
    });
});
