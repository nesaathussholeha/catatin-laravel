document.addEventListener("DOMContentLoaded", function () {
    // =========================
    // Report Modal
    // =========================

    const reportModal = document.getElementById("reportModal");
    const reportReason = document.getElementById("reportReason");

    function toggleModal(show) {
        if (!reportModal) return;

        reportModal.classList.toggle("active", show);

        if (show && reportReason) {
            reportReason.focus();
        }
    }

    // Membuat fungsi bisa dipanggil dari onclick di Blade
    window.toggleModal = toggleModal;

    // Tutup modal ketika area luar diklik
    if (reportModal) {
        reportModal.addEventListener("click", function (event) {
            if (event.target === reportModal) {
                toggleModal(false);
            }
        });
    }

    // Tutup modal dengan tombol Escape
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            toggleModal(false);
        }
    });

    // =========================
    // Search, Filter, and Sort
    // =========================

    // Bagian ini tetap gunakan kode milikmu sebelumnya.
});
