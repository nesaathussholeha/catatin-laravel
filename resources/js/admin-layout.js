
const adminSidebar = document.getElementById('admin-sidebar');
const adminMenuToggle = document.getElementById('admin-menu-toggle');
const adminOverlay = document.getElementById('admin-sidebar-overlay');

adminMenuToggle?.addEventListener('click', () => {
    adminSidebar?.classList.toggle('active');
    adminOverlay?.classList.toggle('active');
});

adminOverlay?.addEventListener('click', () => {
    adminSidebar?.classList.remove('active');
    adminOverlay?.classList.remove('active');
});

// LOGOUT MODAL

const logoutTrigger = document.getElementById('admin-logout-trigger');
const logoutModal = document.getElementById('admin-logout-modal');
const cancelLogout = document.getElementById('admin-cancel-logout');
const confirmLogout = document.getElementById('admin-confirm-logout');
const logoutForm = document.getElementById('admin-logout-form');

logoutTrigger?.addEventListener('click', () => {
    logoutModal?.classList.add('active');
});

cancelLogout?.addEventListener('click', () => {
    logoutModal?.classList.remove('active');
});

logoutModal?.addEventListener('click', (event) => {
    if (event.target === logoutModal) {
        logoutModal.classList.remove('active');
    }
});

confirmLogout?.addEventListener('click', () => {
    logoutForm?.submit();
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        adminSidebar?.classList.remove('active');
        adminOverlay?.classList.remove('active');
        logoutModal?.classList.remove('active');
    }
});
