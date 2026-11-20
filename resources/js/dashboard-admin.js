document.addEventListener('DOMContentLoaded', function () {

    // DROPDOWN
    document.querySelectorAll('.dropdown-trigger').forEach(button => {
        button.addEventListener('click', function (event) {
            event.stopPropagation();

            const menu = this.nextElementSibling;

            document.querySelectorAll('.action-menu').forEach(item => {
                if (item !== menu) {
                    item.classList.remove('show');
                }
            });

            menu.classList.toggle('show');
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.action-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    });


    // MODAL DETAIL PENGGUNA
    const userModal = document.getElementById('user-modal');

    document.querySelectorAll('.view-user').forEach(button => {
        button.addEventListener('click', function () {

            document.getElementById('modal-user-name').textContent =
                this.dataset.name;

            document.getElementById('modal-user-nim').textContent =
                this.dataset.nim;

            document.getElementById('modal-user-jurusan').textContent =
                this.dataset.jurusan;

            document.getElementById('modal-user-status').textContent =
                this.dataset.status;

            userModal.classList.add('show');
        });
    });


    // TUTUP MODAL
    document.querySelectorAll('.modal-close').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.close;
            document.getElementById(id).classList.remove('show');
        });
    });

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function (event) {
            if (event.target === this) {
                this.classList.remove('show');
            }
        });
    });


    // TAMPILKAN / SEMBUNYIKAN LAPORAN
    const reportButton = document.getElementById('report-button');
    const reportList = document.getElementById('report-list');

    if (reportButton && reportList) {
        reportButton.addEventListener('click', function () {

            reportList.classList.toggle('show');

            this.textContent = reportList.classList.contains('show')
                ? 'Tutup laporan'
                : 'Periksa laporan';
        });
    }

});
