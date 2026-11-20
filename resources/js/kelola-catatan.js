document.addEventListener('DOMContentLoaded', function () {
    // Membuka dan menutup modal
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add('show');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.remove('show');
    }

    document.getElementById('open-add-modal')?.addEventListener('click', function () {
        openModal('add-modal');
    });

    document.querySelectorAll('.modal-close').forEach(function (button) {
        button.addEventListener('click', function () {
            closeModal(button.dataset.close);
        });
    });

    document.querySelectorAll('.catatan-modal-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (event) {
            if (event.target === overlay) {
                closeModal(overlay.id);
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.catatan-modal-overlay.show').forEach(function (modal) {
                closeModal(modal.id);
            });
        }
    });

    // Pencarian tabel
    const searchInput = document.getElementById('search-input');

    searchInput?.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        document.querySelectorAll('#catatan-table tbody tr').forEach(function (row) {
            row.style.display = row.textContent.toLowerCase().includes(keyword)
                ? ''
                : 'none';
        });
    });

    // Modal edit
    document.querySelectorAll('.edit-button').forEach(function (button) {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const form = document.getElementById('edit-catatan-form');

            form.action = `/admin/kelola-catatan/${id}`;

            document.getElementById('edit-user').value = this.dataset.user;
            document.getElementById('edit-judul').value = this.dataset.judul;
            document.getElementById('edit-matkul').value = this.dataset.matkul;
            document.getElementById('edit-jurusan').value = this.dataset.jurusan;
            document.getElementById('edit-category').value = this.dataset.category;
            document.getElementById('edit-deskripsi').value = this.dataset.deskripsi;
            document.getElementById('edit-tautan').value = this.dataset.tautan;
            document.getElementById('edit-visibilitas').value = this.dataset.visibilitas;
            document.getElementById('edit-anonim').checked = this.dataset.anonim === '1';

            openModal('edit-modal');
        });
    });

    // Modal lihat
    document.querySelectorAll('.view-button').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('view-judul').textContent = this.dataset.judul;
            document.getElementById('view-penulis').textContent = this.dataset.penulis;
            document.getElementById('view-jurusan').textContent = this.dataset.jurusan;
            document.getElementById('view-matkul').textContent = this.dataset.matkul;
            document.getElementById('view-kategori').textContent = this.dataset.kategori;
            document.getElementById('view-status').textContent = this.dataset.status;
            document.getElementById('view-deskripsi').textContent = this.dataset.deskripsi;

            const link = document.getElementById('view-tautan');
            link.href = this.dataset.tautan;
            link.textContent = this.dataset.tautan;

            openModal('view-modal');
        });
    });
});
