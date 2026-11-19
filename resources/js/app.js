import './master-data.js';

// TOAST NOTIFICATION
const toastNotification = document.getElementById('toastNotification');

if (toastNotification) {
    const btnTutupToast = document.getElementById('btnTutupToast');

    function tutupToast() {
        toastNotification.classList.add('toast-hide');

        setTimeout(() => {
            toastNotification.remove();
        }, 300);
    }

    btnTutupToast?.addEventListener('click', tutupToast);

    setTimeout(tutupToast, 4000);
}
