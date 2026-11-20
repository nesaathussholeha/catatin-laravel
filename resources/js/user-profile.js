document.addEventListener("DOMContentLoaded", function () {
    // ========================================
    // PREVIEW FOTO PROFIL
    // ========================================

    const photoInput = document.getElementById("profile_photo");
    const photoPreview = document.getElementById("photoPreview");
    const photoPlaceholder = document.getElementById("photoPlaceholder");

    if (photoInput && photoPreview) {
        photoInput.addEventListener("change", function () {
            const file = this.files[0];

            if (!file) return;

            const allowedTypes = ["image/jpeg", "image/png", "image/webp"];

            if (!allowedTypes.includes(file.type)) {
                alert("Format foto harus JPG, JPEG, PNG, atau WEBP.");
                this.value = "";
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert("Ukuran foto maksimal 2 MB.");
                this.value = "";
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                photoPreview.src = event.target.result;
                photoPreview.style.display = "block";

                if (photoPlaceholder) {
                    photoPlaceholder.style.display = "none";
                }
            };

            reader.readAsDataURL(file);
        });
    }

    // ========================================
    // SHOW / HIDE PASSWORD
    // ========================================

    const toggleButtons = document.querySelectorAll(".toggle-password");

    toggleButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector("iconify-icon");

            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                icon.setAttribute("icon", "lucide:eye-off");
            } else {
                input.type = "password";
                icon.setAttribute("icon", "lucide:eye");
            }
        });
    });

    // ========================================
    // PASSWORD STRENGTH
    // ========================================

    const newPassword = document.getElementById("password");
    const strengthBar = document.getElementById("strengthBar");
    const strengthText = document.getElementById("strengthText");

    if (newPassword && strengthBar && strengthText) {
        newPassword.addEventListener("input", function () {
            const password = this.value;
            let score = 0;

            if (password.length >= 8) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^a-zA-Z0-9]/.test(password)) score++;

            const levels = [
                { width: "0%", text: "Kekuatan password", color: "#e03131" },
                { width: "20%", text: "Sangat lemah", color: "#e03131" },
                { width: "40%", text: "Lemah", color: "#f08c00" },
                { width: "60%", text: "Cukup", color: "#fab005" },
                { width: "80%", text: "Kuat", color: "#40a85a" },
                { width: "100%", text: "Sangat kuat", color: "#2f9e44" },
            ];

            const level = levels[score];

            strengthBar.style.width = level.width;
            strengthBar.style.backgroundColor = level.color;
            strengthText.textContent = level.text;
        });
    }

    // ========================================
    // VALIDASI KONFIRMASI PASSWORD
    // ========================================

    const passwordForm = document.getElementById("passwordForm");

    if (passwordForm) {
        passwordForm.addEventListener("submit", function (event) {
            const password = document.getElementById("password").value;
            const confirmation = document.getElementById(
                "password_confirmation",
            ).value;

            if (password !== confirmation) {
                event.preventDefault();
                alert("Konfirmasi password tidak sesuai.");
            }
        });
    }

    // ========================================
    // MODAL NONAKTIFKAN AKUN
    // ========================================

    const deactivateModal = document.getElementById("deactivateModal");
    const openModalButton = document.getElementById("openDeactivateModal");
    const closeModalButton = document.getElementById("closeDeactivateModal");
    const deactivateForm = document.getElementById("deactivateForm");

    function openDeactivateModal() {
        if (deactivateModal) {
            deactivateModal.classList.add("show");
        }
    }

    function closeDeactivateModal() {
        if (deactivateModal) {
            deactivateModal.classList.remove("show");
        }
    }

    if (openModalButton) {
        openModalButton.addEventListener("click", openDeactivateModal);
    }

    if (closeModalButton) {
        closeModalButton.addEventListener("click", closeDeactivateModal);
    }

    if (deactivateModal) {
        deactivateModal.addEventListener("click", function (event) {
            if (event.target === deactivateModal) {
                closeDeactivateModal();
            }
        });
    }

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeDeactivateModal();
        }
    });

    if (deactivateForm) {
        deactivateForm.addEventListener("submit", function (event) {
            const confirmed = confirm(
                "Apakah kamu yakin ingin menonaktifkan akun CatatIn?",
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });
    }
});

//Modal konfirmasi hapus foto profil
const deletePhotoModal = document.getElementById("deletePhotoModal");
const openDeletePhotoModal = document.getElementById("openDeletePhotoModal");
const closeDeletePhotoModal = document.getElementById("closeDeletePhotoModal");

if (openDeletePhotoModal && deletePhotoModal) {
    openDeletePhotoModal.addEventListener("click", () => {
        deletePhotoModal.classList.add("show");
    });
}

if (closeDeletePhotoModal && deletePhotoModal) {
    closeDeletePhotoModal.addEventListener("click", () => {
        deletePhotoModal.classList.remove("show");
    });
}

// Tutup modal jika pengguna klik area luar modal
if (deletePhotoModal) {
    deletePhotoModal.addEventListener("click", (event) => {
        if (event.target === deletePhotoModal) {
            deletePhotoModal.classList.remove("show");
        }
    });
}

// Modal konfirmasi ganti password
const passwordForm = document.getElementById("passwordForm");
const changePasswordModal = document.getElementById("changePasswordModal");
const closePasswordModal = document.getElementById("closePasswordModal");
const confirmPasswordChange = document.getElementById("confirmPasswordChange");

let passwordChangeConfirmed = false;

// Tampilkan modal sebelum form dikirim
if (passwordForm && changePasswordModal) {
    passwordForm.addEventListener("submit", (event) => {
        if (!passwordChangeConfirmed) {
            event.preventDefault();
            changePasswordModal.classList.add("show");
        }
    });
}

// Tutup modal ketika memilih Batal
if (closePasswordModal && changePasswordModal) {
    closePasswordModal.addEventListener("click", () => {
        changePasswordModal.classList.remove("show");
    });
}

// Lanjutkan pengiriman form setelah konfirmasi
if (confirmPasswordChange && passwordForm) {
    confirmPasswordChange.addEventListener("click", () => {
        passwordChangeConfirmed = true;
        passwordForm.requestSubmit();
    });
}

// Tutup modal jika klik area luar
if (changePasswordModal) {
    changePasswordModal.addEventListener("click", (event) => {
        if (event.target === changePasswordModal) {
            changePasswordModal.classList.remove("show");
        }
    });
}
