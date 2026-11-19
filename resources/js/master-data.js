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
}
