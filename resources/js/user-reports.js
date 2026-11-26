
document.addEventListener("DOMContentLoaded", () => {
    // TOAST NOTIFICATION
    const toastNotification = document.getElementById("toastNotification");
    const btnTutupToast = document.getElementById("btnTutupToast");

    if (toastNotification) {
        btnTutupToast?.addEventListener("click", () => {
            toastNotification.remove();
        });

        setTimeout(() => {
            toastNotification.remove();
        }, 4000);
    }

    // TAB KELOLA PENGGUNA
    const tabButtons = document.querySelectorAll(".user-tab");
    const tabContents = document.querySelectorAll(".user-tab-content");

    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get("tab") || "daftar-pengguna";

    function activateTab(targetTab) {
        tabButtons.forEach((tab) => {
            tab.classList.toggle("active", tab.dataset.tab === targetTab);
        });

        tabContents.forEach((content) => {
            content.classList.toggle("active", content.id === targetTab);
        });
    }

    activateTab(activeTab);

    tabButtons.forEach((button) => {
        button.addEventListener("click", () => {
            activateTab(button.dataset.tab);
        });
    });

    // LIVE SEARCH DAFTAR PENGGUNA
    const searchPengguna = document.getElementById("searchPengguna");
    const filterStatusPengguna = document.getElementById("filterStatusPengguna");

    if (searchPengguna || filterStatusPengguna) {
        const barisPengguna = document.querySelectorAll(".baris-pengguna");
        const hasilKosong = document.getElementById("hasilPenggunaTidakDitemukan");

        function filterPengguna() {
            const keyword = (searchPengguna?.value || "").toLowerCase().trim();
            const status = filterStatusPengguna?.value || "";

            let jumlahDitemukan = 0;

            barisPengguna.forEach((baris) => {
                const nama = baris.cells[1].textContent.toLowerCase();
                const username = baris.cells[2].textContent.toLowerCase();
                const email = baris.cells[3].textContent.toLowerCase();

                const cocokKeyword =
                    nama.includes(keyword) ||
                    username.includes(keyword) ||
                    email.includes(keyword);

                const cocokStatus =
                    status === "" || baris.dataset.status === status;

                const cocok = cocokKeyword && cocokStatus;

                baris.style.display = cocok ? "" : "none";

                if (cocok) {
                    jumlahDitemukan++;
                }
            });

            if (hasilKosong) {
                hasilKosong.style.display =
                    jumlahDitemukan === 0 && barisPengguna.length > 0
                        ? ""
                        : "none";
            }
        }

        searchPengguna?.addEventListener("input", filterPengguna);
        filterStatusPengguna?.addEventListener("change", filterPengguna);
    }

    // LIVE SEARCH LAPORAN PENGGUNA
    const searchLaporan = document.getElementById("searchLaporan");
    const filterStatusLaporan = document.getElementById("filterStatusLaporan");

    if (searchLaporan || filterStatusLaporan) {
        const barisLaporan = document.querySelectorAll(".baris-laporan");
        const hasilKosong = document.getElementById("hasilLaporanTidakDitemukan");

        function filterLaporan() {
            const keyword = (searchLaporan?.value || "").toLowerCase().trim();
            const status = filterStatusLaporan?.value || "";

            let jumlahDitemukan = 0;

            barisLaporan.forEach((baris) => {
                const pelapor = baris.cells[1].textContent.toLowerCase();
                const dilaporkan = baris.cells[2].textContent.toLowerCase();
                const alasan = baris.cells[3].textContent.toLowerCase();

                const cocokKeyword =
                    pelapor.includes(keyword) ||
                    dilaporkan.includes(keyword) ||
                    alasan.includes(keyword);

                const cocokStatus =
                    status === "" || baris.dataset.status === status;

                const cocok = cocokKeyword && cocokStatus;

                baris.style.display = cocok ? "" : "none";

                if (cocok) {
                    jumlahDitemukan++;
                }
            });

            if (hasilKosong) {
                hasilKosong.style.display =
                    jumlahDitemukan === 0 && barisLaporan.length > 0
                        ? ""
                        : "none";
            }
        }

        searchLaporan?.addEventListener("input", filterLaporan);
        filterStatusLaporan?.addEventListener("change", filterLaporan);
    }

    // MENCEGAH FORM PENCARIAN RELOAD
    document.querySelectorAll(".filter-form").forEach((form) => {
        form.addEventListener("submit", (event) => {
            event.preventDefault();
        });
    });

    // MODAL KONFIRMASI UBAH STATUS
    const statusModal = document.getElementById("statusConfirmModal");
    const statusModalMessage = document.getElementById("statusModalMessage");
    const btnBatalStatus = document.getElementById("btnBatalStatus");
    const btnKonfirmasiStatus = document.getElementById("btnKonfirmasiStatus");

    let selectedStatusForm = null;

    const statusLabels = {
        active: "Aktif",
        inactive: "Tidak Aktif",
        blocked: "Diblokir",
    };

    document.querySelectorAll(".btn-update-status").forEach((button) => {
        button.addEventListener("click", () => {
            const form = button.closest(".status-form");
            const select = form.querySelector(".status-select");

            const currentStatus = select.dataset.currentStatus;
            const newStatus = select.value;
            const userName = select.dataset.userName;

            if (currentStatus === newStatus) {
                return;
            }

            selectedStatusForm = form;

            statusModalMessage.textContent =
                `Yakin ingin mengubah status akun ${userName} dari ${statusLabels[currentStatus]} menjadi ${statusLabels[newStatus]}?`;

            statusModal?.classList.add("active");
        });
    });

    // Tombol batal
    btnBatalStatus?.addEventListener("click", () => {
        statusModal?.classList.remove("active");
        selectedStatusForm = null;
    });

    // Klik area luar modal
    statusModal?.addEventListener("click", (event) => {
        if (event.target === statusModal) {
            statusModal.classList.remove("active");
            selectedStatusForm = null;
        }
    });

    // Tombol konfirmasi
    btnKonfirmasiStatus?.addEventListener("click", () => {
        if (selectedStatusForm) {
            selectedStatusForm.submit();
        }
    });
});
