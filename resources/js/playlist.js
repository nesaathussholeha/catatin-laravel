document.addEventListener('DOMContentLoaded', function () {

    const $ = (id) => document.getElementById(id);

    /* ---------- Modal Buat folder ---------- */
    const modalBuat = $('plModal');
    $('plOpenModal')?.addEventListener('click', () => {
        modalBuat.hidden = false;
        $('plNama')?.focus();
    });
    $('plCloseModal')?.addEventListener('click', () => { modalBuat.hidden = true; });

    /* ---------- Modal Ubah folder ---------- */
    const modalUbah = $('plEditModal');
    const formUbah  = $('plEditForm');

    document.querySelectorAll('.pl-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            formUbah.action        = btn.dataset.url;
            $('plEditId').value    = btn.dataset.id;
            $('plEditNama').value  = btn.dataset.name;
            $('plEditDeskripsi').value = btn.dataset.description || '';

            tutupSemuaMenu();
            modalUbah.hidden = false;
            $('plEditNama').focus();
        });
    });
    $('plEditClose')?.addEventListener('click', () => { modalUbah.hidden = true; });

    /* ---------- Menu titik tiga ---------- */
    function tutupSemuaMenu() {
        document.querySelectorAll('.pl-menu-list').forEach(function (m) {
            m.hidden = true;
            m.previousElementSibling?.setAttribute('aria-expanded', 'false');
        });
    }

    document.querySelectorAll('.pl-menu-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const menu = btn.nextElementSibling;
            const buka = menu.hidden;

            tutupSemuaMenu();
            menu.hidden = !buka;
            btn.setAttribute('aria-expanded', buka ? 'true' : 'false');
        });
    });

    document.addEventListener('click', tutupSemuaMenu);

    /* ---------- Konfirmasi hapus ---------- */
    const modalHapus = $('plConfirm');
    let formTarget = null;

    const tutupHapus = () => { modalHapus.hidden = true; formTarget = null; };

    document.querySelectorAll('.pl-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            formTarget = form;
            $('plConfirmNama').textContent = '"' + form.dataset.nama + '"';
            tutupSemuaMenu();
            modalHapus.hidden = false;
        });
    });

    $('plConfirmBatal')?.addEventListener('click', tutupHapus);
    $('plConfirmYa')?.addEventListener('click', () => { if (formTarget) formTarget.submit(); });

    /* ---------- Tutup modal: klik area gelap & Esc ---------- */
    [modalBuat, modalUbah, modalHapus].forEach(function (m) {
        m?.addEventListener('click', function (e) {
            if (e.target === m) {
                m.hidden = true;
                if (m === modalHapus) formTarget = null;
            }
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        [modalBuat, modalUbah, modalHapus].forEach((m) => { if (m) m.hidden = true; });
        formTarget = null;
        tutupSemuaMenu();
    });
});