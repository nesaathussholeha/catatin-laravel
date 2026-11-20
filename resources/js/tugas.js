
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('modal-tugas');

    if (!modal) return;

    const tombolTambah = document.getElementById('buka-modal');
    const tombolTutup = document.getElementById('tutup-modal');
    const tombolBatal = document.getElementById('batal-modal');

    const form = document.getElementById('form-tugas');
    const judulModal = document.getElementById('judul-modal');

    const inputMethod = document.getElementById('method-tugas');
    const inputId = document.getElementById('id-tugas');
    const inputJudul = document.getElementById('judul-tugas');
    const inputDeadline = document.getElementById('deadline-tugas');

    const actionTambah = form.action;

    function bukaModal() {
        modal.classList.add('aktif');
    }

    function tutupModal() {
        modal.classList.remove('aktif');
    }

    function resetForm() {

        form.reset();

        form.action = actionTambah;

        inputMethod.value = 'POST';
        inputId.value = '';

        judulModal.textContent = 'Tambah Tugas';
    }

    tombolTambah.addEventListener('click', function () {

        resetForm();

        bukaModal();

    });

    tombolTutup.addEventListener('click', tutupModal);

    tombolBatal.addEventListener('click', tutupModal);

    modal.addEventListener('click', function (event) {

        if (event.target === modal) {
            tutupModal();
        }

    });

    document.querySelectorAll('.tombol-edit').forEach(function (tombol) {

        tombol.addEventListener('click', function () {

            resetForm();

            form.action = this.dataset.updateUrl;

            inputMethod.value = 'PUT';

            inputId.value = this.dataset.id;

            inputJudul.value = this.dataset.judul;

            inputDeadline.value = this.dataset.deadline;

            judulModal.textContent = 'Edit Tugas';

            bukaModal();

        });

    });

    // Membuka kembali modal jika validasi Laravel gagal.
    if (modal.dataset.openOnError === '1') {
        bukaModal();
    }

});
