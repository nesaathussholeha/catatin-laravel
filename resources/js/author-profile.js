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

    window.toggleModal = toggleModal;

    if (reportModal) {
        reportModal.addEventListener("click", function (event) {
            if (event.target === reportModal) {
                toggleModal(false);
            }
        });
    }

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            toggleModal(false);
        }
    });

    // =========================
    // Search, Filter, and Sort
    // =========================
    const searchInput = document.getElementById("searchInput");
    const categorySelect = document.getElementById("categorySelect");
    const sortSelect = document.getElementById("sortSelect");
    const noteGrid = document.getElementById("noteGrid");
    const emptyMessage = document.getElementById("emptyMessage");
    const displayCount = document.getElementById("displayCount");

    if (
        searchInput &&
        categorySelect &&
        sortSelect &&
        noteGrid &&
        emptyMessage &&
        displayCount
    ) {
        const cards = Array.from(noteGrid.querySelectorAll(".note-card"));

        function filterAndSortNotes() {
            const keyword = searchInput.value.trim().toLowerCase();
            const selectedCategory = categorySelect.value;

            const filteredCards = cards.filter((card) => {
                const title = (card.dataset.title || "").toLowerCase();
                const matkul = (card.dataset.matkul || "").toLowerCase();
                const description = (
                    card.dataset.deskripsi || ""
                ).toLowerCase();
                const category = card.dataset.category || "Umum";

                const cocokPencarian =
                    title.includes(keyword) ||
                    matkul.includes(keyword) ||
                    description.includes(keyword);

                const cocokKategori =
                    selectedCategory === "all" || category === selectedCategory;

                return cocokPencarian && cocokKategori;
            });

            // Urutkan catatan
            const sortType = sortSelect.value;

            filteredCards.sort((a, b) => {
                if (sortType === "terbaru") {
                    return Number(b.dataset.date) - Number(a.dataset.date);
                }

                if (sortType === "terlama") {
                    return Number(a.dataset.date) - Number(b.dataset.date);
                }

                if (sortType === "upvote") {
                    return Number(b.dataset.upvote) - Number(a.dataset.upvote);
                }

                return 0;
            });

            // Sembunyikan SEMUA kartu terlebih dahulu
            cards.forEach((card) => {
                card.hidden = true;
            });

            // Tampilkan hanya kartu yang cocok
            filteredCards.forEach((card) => {
                card.hidden = false;
                noteGrid.appendChild(card);
            });

            // Tampilkan pesan jika tidak ada hasil
            emptyMessage.hidden = filteredCards.length !== 0;

            // Perbarui jumlah catatan
            displayCount.textContent = `Menampilkan ${filteredCards.length} dari ${cards.length} catatan`;
        }

        searchInput.addEventListener("input", filterAndSortNotes);
        categorySelect.addEventListener("change", filterAndSortNotes);
        sortSelect.addEventListener("change", filterAndSortNotes);

        filterAndSortNotes();
    }
});
