const modalJurusan = document.getElementById("modalJurusan");

if (modalJurusan) {
    const btnTambah = document.getElementById("btnTambahJurusan");
    const btnTutup = document.getElementById("btnTutupModalJurusan");
    const btnBatal = document.getElementById("btnBatalJurusan");
    const form = document.getElementById("formJurusan");
    const judulModal = document.getElementById("judulModalJurusan");

    // Membuka kembali modal jika terdapat error validasi Laravel
    const memilikiError = modalJurusan.querySelector(".is-invalid");

    if (memilikiError) {
        modalJurusan.classList.add("active");
    }

    // Membuka modal tambah jurusan
    function bukaModalJurusan() {
        form.reset();
        judulModal.textContent = "Tambah Jurusan";
        modalJurusan.classList.add("active");
    }

    // Menutup modal
    function tutupModalJurusan() {
        modalJurusan.classList.remove("active");
    }

    // Tombol Tambah Jurusan
    btnTambah?.addEventListener("click", bukaModalJurusan);

    // Tombol silang
    btnTutup?.addEventListener("click", tutupModalJurusan);

    // Tombol Batal
    btnBatal?.addEventListener("click", tutupModalJurusan);

    // Menutup modal ketika area luar modal diklik
    modalJurusan.addEventListener("click", function (event) {
        if (event.target === modalJurusan) {
            tutupModalJurusan();
        }
    });

    // Menutup modal dengan tombol Escape
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            tutupModalJurusan();
        }
    });

    // Tombol Edit Jurusan
    const tombolEdit = document.querySelectorAll(".btn-edit-jurusan");

    tombolEdit.forEach((button) => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            const code = this.dataset.code;
            const name = this.dataset.name;

            form.reset();

            judulModal.textContent = "Edit Jurusan";

            form.action = `/admin/departments/${id}`;

            document.getElementById("methodJurusan").value = "PUT";

            document.getElementById("kodeJurusan").value = code;
            document.getElementById("namaJurusan").value = name;

            modalJurusan.classList.add("active");
        });
    });

    // MODAL KONFIRMASI HAPUS JURUSAN
    const modalHapusJurusan = document.getElementById("modalHapusJurusan");

    if (modalHapusJurusan) {
        const tombolHapus = document.querySelectorAll(".btn-hapus-jurusan");
        const btnBatal = document.getElementById("btnBatalHapusJurusan");
        const formHapus = document.getElementById("formHapusJurusan");
        const namaJurusan = document.getElementById("namaJurusanHapus");

        // Membuka modal konfirmasi
        tombolHapus.forEach((button) => {
            button.addEventListener("click", function () {
                const id = this.dataset.id;
                const name = this.dataset.name;

                namaJurusan.textContent = name;

                formHapus.action = `/admin/departments/${id}`;

                modalHapusJurusan.classList.add("active");
            });
        });

        // Menutup modal
        function tutupModalHapus() {
            modalHapusJurusan.classList.remove("active");
        }

        btnBatal?.addEventListener("click", tutupModalHapus);

        // Menutup modal ketika area luar diklik
        modalHapusJurusan.addEventListener("click", function (event) {
            if (event.target === modalHapusJurusan) {
                tutupModalHapus();
            }
        });

        // Menutup modal dengan tombol Escape
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                tutupModalHapus();
            }
        });
    }

    // SEARCH JURUSAN

    const searchJurusan = document.getElementById("searchJurusan");

    if (searchJurusan) {
        const barisJurusan = document.querySelectorAll(".baris-jurusan");
        const pesanTidakDitemukan = document.getElementById(
            "hasilJurusanTidakDitemukan",
        );
        const dataKosong = document.getElementById("dataJurusanKosong");

        searchJurusan.addEventListener("input", function () {
            const keyword = this.value.toLowerCase().trim();
            let jumlahDitemukan = 0;

            barisJurusan.forEach((baris) => {
                const kode = baris.cells[1].textContent.toLowerCase();
                const nama = baris.cells[2].textContent.toLowerCase();

                const cocok = kode.includes(keyword) || nama.includes(keyword);

                baris.style.display = cocok ? "" : "none";

                if (cocok) {
                    jumlahDitemukan++;
                }
            });

            if (dataKosong) {
                dataKosong.style.display = keyword ? "none" : "";
            }

            pesanTidakDitemukan.style.display =
                keyword && jumlahDitemukan === 0 ? "" : "none";
        });
    }


    // ==========================================
    // CRUD MATA KULIAH
    // ==========================================

    const modalMatakuliah = document.getElementById("modalMatakuliah");

    if (modalMatakuliah) {
        const btnTambah = document.getElementById("btnTambahMatakuliah");
        const btnTutup = document.getElementById("btnTutupModalMatakuliah");
        const btnBatal = document.getElementById("btnBatalMatakuliah");

        const form = document.getElementById("formMatakuliah");
        const judulModal = document.getElementById("judulModalMatakuliah");
        const method = document.getElementById("methodMatakuliah");

        // ==========================================
        // SEARCHABLE MULTI SELECT JURUSAN
        // ==========================================

        const jurusanMultiSelect = document.getElementById("jurusanMultiSelect");
        const selectedContainer = document.getElementById("jurusanSelected");
        const searchInput = document.getElementById("searchJurusanSelect");
        const optionsContainer = document.getElementById("jurusanOptions");

        const checkboxes = optionsContainer
            ? optionsContainer.querySelectorAll('input[name="departments[]"]')
            : [];

        // Menampilkan jurusan yang dipilih
        function renderJurusanSelected() {
            if (!selectedContainer) return;

            selectedContainer.replaceChildren();

            checkboxes.forEach((checkbox) => {
                const option = checkbox.closest(".jurusan-option");

                if (option) {
                    option.classList.toggle("selected", checkbox.checked);
                }
            });

            const checkedBoxes = [...checkboxes].filter(
                (checkbox) => checkbox.checked
            );

            if (checkedBoxes.length === 0) {
                const placeholder = document.createElement("span");

                placeholder.className = "placeholder";
                placeholder.textContent = "Pilih jurusan...";

                selectedContainer.appendChild(placeholder);
                return;
            }

            checkedBoxes.forEach((checkbox) => {
                const chip = document.createElement("span");
                chip.className = "jurusan-chip";

                const name = document.createElement("span");
                name.textContent = checkbox.dataset.name;

                const removeButton = document.createElement("button");
                removeButton.type = "button";
                removeButton.textContent = "×";

                removeButton.addEventListener("click", (event) => {
                    event.stopPropagation();

                    checkbox.checked = false;
                    renderJurusanSelected();
                });

                chip.append(name, removeButton);
                selectedContainer.appendChild(chip);
            });
        }

        // Membuka dan menutup dropdown
        selectedContainer?.addEventListener("click", () => {
            jurusanMultiSelect.classList.toggle("active");

            if (jurusanMultiSelect.classList.contains("active")) {
                searchInput?.focus();
            }
        });

        // Memperbarui tampilan ketika jurusan dipilih
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", renderJurusanSelected);
        });

        // Mencari jurusan
        searchInput?.addEventListener("input", () => {
            const keyword = searchInput.value.toLowerCase().trim();

            jurusanMultiSelect
                .querySelectorAll(".jurusan-option")
                .forEach((option) => {
                    const name = option.textContent.toLowerCase();

                    option.classList.toggle(
                        "hidden",
                        !name.includes(keyword)
                    );
                });
        });

        // Menutup dropdown ketika klik di luar
        document.addEventListener("click", (event) => {
            if (
                jurusanMultiSelect &&
                !jurusanMultiSelect.contains(event.target)
            ) {
                jurusanMultiSelect.classList.remove("active");
            }
        });

        // Tampilan awal
        renderJurusanSelected();

        // ==========================================
        // VALIDASI LARAVEL
        // ==========================================

        const memilikiError = modalMatakuliah.querySelector(".is-invalid");

        if (memilikiError) {
            modalMatakuliah.classList.add("active");
        }

        // ==========================================
        // MODAL TAMBAH MATA KULIAH
        // ==========================================

        function bukaModalTambah() {
            form.reset();

            form.action = "/admin/courses";
            method.value = "POST";

            judulModal.textContent = "Tambah Mata Kuliah";

            // Bersihkan pilihan jurusan sebelumnya
            checkboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });

            if (searchInput) {
                searchInput.value = "";
            }

            jurusanMultiSelect?.classList.remove("active");

            jurusanMultiSelect?.querySelectorAll(".jurusan-option")
                .forEach((option) => option.classList.remove("hidden"));

            renderJurusanSelected();

            modalMatakuliah.classList.add("active");
        }

        // ==========================================
        // MENUTUP MODAL
        // ==========================================

        function tutupModal() {
            modalMatakuliah.classList.remove("active");
            jurusanMultiSelect?.classList.remove("active");
        }

        btnTambah?.addEventListener("click", bukaModalTambah);
        btnTutup?.addEventListener("click", tutupModal);
        btnBatal?.addEventListener("click", tutupModal);

        modalMatakuliah.addEventListener("click", (event) => {
            if (event.target === modalMatakuliah) {
                tutupModal();
            }
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                tutupModal();
            }
        });

        // ==========================================
        // EDIT MATA KULIAH
        // ==========================================

        const tombolEdit = document.querySelectorAll(".btn-edit-matakuliah");

        tombolEdit.forEach((button) => {
            button.addEventListener("click", function () {
                const id = this.dataset.id;
                const code = this.dataset.code;
                const name = this.dataset.name;

                const departments = this.dataset.departments
                    ? this.dataset.departments.split(",").map((id) => id.trim())
                    : [];

                form.reset();

                judulModal.textContent = "Edit Mata Kuliah";

                form.action = `/admin/courses/${id}`;
                method.value = "PUT";

                document.getElementById("kodeMatakuliah").value = code;
                document.getElementById("namaMatakuliah").value = name;

                // Mengisi kembali jurusan yang sebelumnya dipilih
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = departments.includes(checkbox.value);
                });

                if (searchInput) {
                    searchInput.value = "";
                }

                jurusanMultiSelect?.classList.remove("active");

                jurusanMultiSelect?.querySelectorAll(".jurusan-option")
                    .forEach((option) => option.classList.remove("hidden"));

                // Perbarui chip dan tanda centang
                renderJurusanSelected();

                modalMatakuliah.classList.add("active");
            });
        });

        // ==========================================
        // MODAL KONFIRMASI HAPUS MATA KULIAH
        // ==========================================

        const modalHapusMatakuliah = document.getElementById(
            "modalHapusMatakuliah"
        );

        if (modalHapusMatakuliah) {
            const tombolHapus = document.querySelectorAll(
                ".btn-hapus-matakuliah"
            );

            const btnBatalHapus = document.getElementById(
                "btnBatalHapusMatakuliah"
            );

            const formHapus = document.getElementById("formHapusMatakuliah");

            const namaMatakuliah = document.getElementById(
                "namaMatakuliahHapus"
            );

            tombolHapus.forEach((button) => {
                button.addEventListener("click", function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;

                    namaMatakuliah.textContent = name;

                    formHapus.action = `/admin/courses/${id}`;

                    modalHapusMatakuliah.classList.add("active");
                });
            });

            function tutupModalHapus() {
                modalHapusMatakuliah.classList.remove("active");
            }

            btnBatalHapus?.addEventListener("click", tutupModalHapus);

            modalHapusMatakuliah.addEventListener("click", (event) => {
                if (event.target === modalHapusMatakuliah) {
                    tutupModalHapus();
                }
            });

            document.addEventListener("keydown", (event) => {
                if (event.key === "Escape") {
                    tutupModalHapus();
                }
            });
        }

        // ==========================================
        // SEARCH MATA KULIAH
        // ==========================================

        const searchMatakuliah = document.getElementById("searchMatakuliah");

        if (searchMatakuliah) {
            const barisMatakuliah = document.querySelectorAll(
                ".baris-matakuliah"
            );

            const pesanTidakDitemukan = document.getElementById(
                "hasilMatakuliahTidakDitemukan"
            );

            const dataKosong = document.getElementById("dataMatakuliahKosong");

            searchMatakuliah.addEventListener("input", function () {
                const keyword = this.value.toLowerCase().trim();
                let jumlahDitemukan = 0;

                barisMatakuliah.forEach((baris) => {
                    const kode = baris.cells[1].textContent.toLowerCase();
                    const nama = baris.cells[2].textContent.toLowerCase();

                    const cocok =
                        kode.includes(keyword) || nama.includes(keyword);

                    baris.style.display = cocok ? "" : "none";

                    if (cocok) {
                        jumlahDitemukan++;
                    }
                });

                if (dataKosong) {
                    dataKosong.style.display = keyword ? "none" : "";
                }

                if (pesanTidakDitemukan) {
                    pesanTidakDitemukan.style.display =
                        keyword && jumlahDitemukan === 0 ? "" : "none";
                }
            });
        }
    }
}
