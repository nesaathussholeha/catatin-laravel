
document.addEventListener('DOMContentLoaded', function () {

    const $ = (id) => document.getElementById(id);


    /* =====================================
       NOTIFIKASI TOAST
    ===================================== */

    const toast = $('toastNotification');

    $('btnTutupToast')?.addEventListener('click', function () {
        if (toast) {
            toast.remove();
        }
    });


    /* =====================================
       MODAL BUAT FOLDER
    ===================================== */

    const modalBuat = $('plModal');

    $('plOpenModal')?.addEventListener('click', function () {
        if (!modalBuat) return;

        modalBuat.hidden = false;
        $('plNama')?.focus();
    });

    $('plCloseModal')?.addEventListener('click', function () {
        if (modalBuat) {
            modalBuat.hidden = true;
        }
    });


    /* =====================================
       MODAL UBAH FOLDER
    ===================================== */

    const modalUbah = $('plEditModal');
    const formUbah = $('plEditForm');

    document.querySelectorAll('.pl-edit').forEach(function (btn) {

        btn.addEventListener('click', function () {

            if (!formUbah || !modalUbah) return;

            formUbah.action = btn.dataset.url;

            $('plEditId').value = btn.dataset.id;
            $('plEditNama').value = btn.dataset.name;
            $('plEditDeskripsi').value =
                btn.dataset.description || '';

            tutupSemuaMenu();

            modalUbah.hidden = false;
            $('plEditNama')?.focus();

        });

    });

    $('plEditClose')?.addEventListener('click', function () {
        if (modalUbah) {
            modalUbah.hidden = true;
        }
    });


    /* =====================================
       MENU TITIK TIGA
    ===================================== */

    function tutupSemuaMenu() {

        document.querySelectorAll('.pl-menu-list').forEach(function (menu) {

            menu.hidden = true;

            menu.previousElementSibling?.setAttribute(
                'aria-expanded',
                'false'
            );

        });

    }

    document.querySelectorAll('.pl-menu-btn').forEach(function (btn) {

        btn.addEventListener('click', function (e) {

            e.stopPropagation();

            const menu = btn.nextElementSibling;
            if (!menu) return;

            const buka = menu.hidden;

            tutupSemuaMenu();

            menu.hidden = !buka;

            btn.setAttribute(
                'aria-expanded',
                buka ? 'true' : 'false'
            );

        });

    });

    document.addEventListener('click', tutupSemuaMenu);


    /* =====================================
       MODAL KONFIRMASI HAPUS FOLDER
    ===================================== */

    const modalHapus = $('plConfirm');

    let formHapusTarget = null;

    function tutupHapus() {

        if (modalHapus) {
            modalHapus.hidden = true;
        }

        formHapusTarget = null;

    }

    document.querySelectorAll('.pl-delete-form').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();

            formHapusTarget = form;

            $('plConfirmNama').textContent =
                '"' + form.dataset.nama + '"';

            tutupSemuaMenu();

            if (modalHapus) {
                modalHapus.hidden = false;
            }

        });

    });

    $('plConfirmBatal')?.addEventListener('click', tutupHapus);

    $('plConfirmYa')?.addEventListener('click', function () {

        if (formHapusTarget) {
            formHapusTarget.submit();
        }

    });


    /* =====================================
       MODAL KELUARKAN CATATAN
    ===================================== */

    const modalDetach = $('plDetachModal');
        /* =====================================
       MODAL DETAIL CATATAN
    ===================================== */

    const modalDetail = $('plDetailModal');

document.querySelectorAll('.pl-open-detail').forEach(function (btn) {
    btn.addEventListener('click', function () {
        if (!modalDetail) return;

        const judul = btn.dataset.judul || '-';
        const tautanMentah = btn.dataset.tautan || '';
        const tautan = /^https?:\/\//i.test(tautanMentah) ? tautanMentah : '';
        $('plDetailJudul').textContent = judul;
        $('plDetailJurusan').textContent = btn.dataset.jurusan || '-';
        $('plDetailMatkul').textContent = btn.dataset.matkul || '-';

        const linkJudul = $('plDetailLink');

        if (linkJudul) {
            linkJudul.href = tautan || '#';
            linkJudul.style.pointerEvents = tautan ? 'auto' : 'none';
            linkJudul.style.opacity = tautan ? '1' : '0.5';
            linkJudul.title = tautan
                ? 'Buka dokumen catatan'
                : 'Dokumen belum tersedia';
        }

        modalDetail.hidden = false;
    });
});

$('plDetailClose')?.addEventListener('click', function () {
    if (modalDetail) modalDetail.hidden = true;
});


    $('plDetailClose')?.addEventListener('click', function () {
        if (modalDetail) {
            modalDetail.hidden = true;
        }
    });
    const namaDetach = $('plDetachNama');

    let formDetachTarget = null;

    // Ketika tombol Keluarkan ditekan
    document.querySelectorAll('.pl-detach-form').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();

            formDetachTarget = form;

            if (namaDetach) {
                namaDetach.textContent = form.dataset.judul;
            }

            if (modalDetach) {
                modalDetach.hidden = false;
            }

        });

    });


    // Tombol Batal
    $('plDetachBatal')?.addEventListener('click', function () {

        if (modalDetach) {
            modalDetach.hidden = true;
        }

        formDetachTarget = null;

    });


    // Tombol Ya, keluarkan
    $('plDetachYa')?.addEventListener('click', function () {

        if (!formDetachTarget) return;

        const form = formDetachTarget;
        const kartu = form.closest('.pl-catatan-card');

        // Tutup modal
        if (modalDetach) {
            modalDetach.hidden = true;
        }

        // Tambahkan animasi pada baris catatan
        if (kartu) {
            kartu.classList.add('pl-card-keluar');
        }

        // Kirim form setelah animasi
        setTimeout(function () {

            form.submit();

        }, 400);

        formDetachTarget = null;

    });


    /* =====================================
       TUTUP MODAL DENGAN KLIK AREA GELAP
    ===================================== */

    [modalBuat, modalUbah, modalHapus, modalDetach, modalDetail].forEach(function (modal) {

        modal?.addEventListener('click', function (e) {

            if (e.target === modal) {

                modal.hidden = true;

                if (modal === modalHapus) {
                    formHapusTarget = null;
                }

                if (modal === modalDetach) {
                    formDetachTarget = null;
                }

            }

        });

    });


    /* =====================================
       TUTUP MODAL DENGAN TOMBOL ESC
    ===================================== */

    document.addEventListener('keydown', function (e) {

        if (e.key !== 'Escape') return;

        [modalBuat, modalUbah, modalHapus, modalDetach, modalDetail].forEach(function (modal) {

            if (modal) {
                modal.hidden = true;
            }

        });

        formHapusTarget = null;
        formDetachTarget = null;

        tutupSemuaMenu();

    });

});
