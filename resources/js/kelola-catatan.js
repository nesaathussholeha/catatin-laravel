document.addEventListener('DOMContentLoaded', function () {
    // Konfirmasi sebelum memulihkan catatan
    document.querySelectorAll(
        'form[action*="/restore"]'
    ).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Apakah kamu yakin ingin memulihkan catatan ini?')) {
                event.preventDefault();
            }
        });
    });

    // Konfirmasi sebelum menghapus permanen
    document.querySelectorAll(
        'form[action*="/force-delete"]'
    ).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm(
                'Catatan akan dihapus permanen dan tidak dapat dipulihkan. Lanjutkan?'
            )) {
                event.preventDefault();
            }
        });
    });
});
