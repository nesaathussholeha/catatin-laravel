
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
    // KEPUTUSAN ADMIN
    // ==============================

    const decisionSelect = document.getElementById('decision');
    const accountStatusGroup = document.getElementById('accountStatusGroup');
    const accountStatusSelect = document.getElementById('account_status');

    function toggleAccountStatus() {

        if (!decisionSelect || !accountStatusGroup || !accountStatusSelect) {
            return;
        }

        const isAccepted = decisionSelect.value === 'accepted';

        accountStatusGroup.style.display = isAccepted ? 'flex' : 'none';

        accountStatusSelect.required = isAccepted;

        if (!isAccepted) {
            accountStatusSelect.value = '';
        }
    }

    if (decisionSelect) {
        decisionSelect.addEventListener('change', toggleAccountStatus);

        toggleAccountStatus();
    }

});
