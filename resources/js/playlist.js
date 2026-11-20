document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('plModal');
    var open  = document.getElementById('plOpenModal');
    var close = document.getElementById('plCloseModal');
    if (!modal || !open) return;

    open.addEventListener('click', function () {
        modal.hidden = false;
        document.getElementById('plNama').focus();
    });
    close.addEventListener('click', function () { modal.hidden = true; });
    modal.addEventListener('click', function (e) {
        if (e.target === modal) modal.hidden = true;
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') modal.hidden = true;
    });
});

// Konfirmasi hapus playlist (pengganti confirm bawaan browser)
(function () {
    const modal = document.getElementById('plConfirm');
    if (!modal) return;

    const namaEl = document.getElementById('plConfirmNama');
    let formTarget = null;

    const tutup = () => {
        modal.hidden = true;
        formTarget = null;
    };

    document.querySelectorAll('.pl-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            formTarget = form;
            namaEl.textContent = '"' + form.dataset.nama + '"';
            modal.hidden = false;
        });
    });

    document.getElementById('plConfirmBatal').addEventListener('click', tutup);

    document.getElementById('plConfirmYa').addEventListener('click', function () {
        if (formTarget) formTarget.submit();
    });

    modal.addEventListener('click', function (e) {
        if (e.target === modal) tutup();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) tutup();
    });
})();