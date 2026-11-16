document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const closeSidebar = document.getElementById("closeSidebar");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");

    if (!sidebar || !overlay) return;

    function openSidebar() {
        sidebar.classList.add("active");
        overlay.classList.add("active");
        document.body.style.overflow = "hidden";
    }

    function closeMenu() {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
        document.body.style.overflow = "";
    }

    menuToggle?.addEventListener("click", openSidebar);
    closeSidebar?.addEventListener("click", closeMenu);
    overlay.addEventListener("click", closeMenu);

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeMenu();
        }
    });
});

const logoutTrigger = document.getElementById("logout-trigger");
const logoutModal = document.getElementById("logout-modal");
const cancelLogout = document.getElementById("cancel-logout");
const confirmLogout = document.getElementById("confirm-logout");
const logoutForm = document.getElementById("logout-form");

// Buka modal
logoutTrigger?.addEventListener("click", () => {
    logoutModal.classList.add("active");
});

// Tutup modal saat klik Batal
cancelLogout?.addEventListener("click", () => {
    logoutModal.classList.remove("active");
});

// Tutup modal saat klik area luar
logoutModal?.addEventListener("click", (e) => {
    if (e.target === logoutModal) {
        logoutModal.classList.remove("active");
    }
});

// Konfirmasi logout
confirmLogout?.addEventListener("click", () => {
    logoutForm.submit();
});
