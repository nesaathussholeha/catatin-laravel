
document.addEventListener('DOMContentLoaded', () => {

    // ==============================
    // TOAST NOTIFICATION
    // ==============================

    const toastNotification = document.getElementById('toastNotification');
    const btnTutupToast = document.getElementById('btnTutupToast');

    if (toastNotification) {

        if (btnTutupToast) {
            btnTutupToast.addEventListener('click', () => {
                toastNotification.remove();
            });
        }

        setTimeout(() => {
            toastNotification.remove();
        }, 4000);
    }

    // ==============================
    // FILTER STATUS OTOMATIS
    // ==============================

    const statusFilter = document.querySelector('select[name="status"]');

    if (statusFilter) {
        statusFilter.addEventListener('change', () => {
            statusFilter.form.submit();
        });
    }

    // ==============================
    // PENCARIAN
    // ==============================

    const searchInput = document.querySelector('input[name="search"]');

    if (searchInput) {
        searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchInput.form.submit();
            }
        });
    }

});
