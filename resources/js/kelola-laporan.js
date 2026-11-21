document.addEventListener('DOMContentLoaded', () => {
    const STORAGE_KEY = 'catatin_laporan_preview_v1';

    const STATUS_LABEL = {
        menunggu: 'Menunggu',
        diproses: 'Diproses',
        selesai: 'Selesai',
    };

    const state = {
        loading: true,
        filter: 'semua',
        reports: [],
        menuForId: null,
        detailId: null,
        deleteId: null,
    };

    const tbody = document.getElementById('report-body');
    const tabsWrap = document.getElementById('filter-tabs');
    const tabButtons = tabsWrap ? tabsWrap.querySelectorAll('.tab-btn') : [];
    const statusMenu = document.getElementById('status-menu');

    const detailModal = document.getElementById('detail-modal');
    const detailBody = document.getElementById('detail-body');
    const deleteModal = document.getElementById('delete-modal');
    const deleteText = document.getElementById('delete-text');
    const confirmDeleteBtn = document.getElementById('confirm-delete');

    /* ========================================
       DATA & PENYIMPANAN LOKAL
    ======================================== */

    const loadReports = () => {
        const fromServer = Array.isArray(window.LAPORAN_DATA)
            ? window.LAPORAN_DATA
            : [];

        if (fromServer.length > 0) {
            return fromServer.slice();
        }

        try {
            const saved = JSON.parse(localStorage.getItem(STORAGE_KEY));
            if (saved && Array.isArray(saved.reports)) {
                return saved.reports;
            }
        } catch (error) {
            console.warn('Data laporan lokal tidak dapat dibaca:', error);
        }

        return [];
    };

    const persist = () => {
        try {
            localStorage.setItem(
                STORAGE_KEY,
                JSON.stringify({ reports: state.reports })
            );
        } catch (error) {
            console.warn('Data laporan gagal disimpan:', error);
        }
    };

    /* ========================================
       HELPER
    ======================================== */

    const escapeHtml = (value) =>
        String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    const formatTanggal = (iso) => {
        if (!iso) return '-';
        const date = new Date(`${iso}T00:00:00`);
        if (isNaN(date.getTime())) return iso;

        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    const findReport = (id) =>
        state.reports.find((report) => Number(report.id) === Number(id));

    const getStatus = (status) => (STATUS_LABEL[status] ? status : 'menunggu');

    /* ========================================
       TOAST (mengikuti .toast-notification layout admin)
    ======================================== */

    const showToast = (message, type = 'success') => {
        document.getElementById('reportDynamicToast')?.remove();

        const toast = document.createElement('div');
        toast.id = 'reportDynamicToast';
        toast.className = `toast-notification toast-${type}`;

        const iconName =
            type === 'success' ? 'lucide:check-circle-2' : 'lucide:alert-circle';

        toast.innerHTML = `
            <iconify-icon icon="${iconName}"></iconify-icon>
            <span>${escapeHtml(message)}</span>
            <button type="button" class="toast-close" aria-label="Tutup">&times;</button>
        `;

        document.body.appendChild(toast);

        const tutup = () => {
            toast.classList.add('toast-hide');
            setTimeout(() => toast.remove(), 300);
        };

        toast.querySelector('.toast-close')?.addEventListener('click', tutup);
        setTimeout(tutup, 4000);
    };

    /* ========================================
       RENDER TABEL
    ======================================== */

    const getFiltered = () => {
        if (state.filter === 'semua') return state.reports;
        return state.reports.filter(
            (report) => getStatus(report.status) === state.filter
        );
    };

    const renderLoading = () => {
        if (!tbody) return;

        tbody.innerHTML = Array.from({ length: 3 })
            .map(
                () => `
                <tr class="skeleton-row">
                    <td><span class="skeleton w-60"></span></td>
                    <td><span class="skeleton w-50"></span></td>
                    <td><span class="skeleton w-50"></span></td>
                    <td><span class="skeleton w-pill"></span></td>
                    <td><span class="skeleton w-50"></span></td>
                </tr>`
            )
            .join('');
    };

    const renderEmpty = () => {
        if (!tbody) return;

        const isAll = state.filter === 'semua';
        const title = isAll
            ? 'Belum ada laporan masuk'
            : `Tidak ada laporan "${STATUS_LABEL[state.filter] || state.filter}"`;

        const description = isAll
            ? 'Laporan dari pengguna akan tampil di sini.'
            : 'Coba pilih tab status yang lain.';

        tbody.innerHTML = `
            <tr class="state-row">
                <td colspan="5">
                    <div class="empty-state">
                        <iconify-icon icon="lucide:inbox" width="44"></iconify-icon>
                        <strong>${escapeHtml(title)}</strong>
                        <span>${escapeHtml(description)}</span>
                    </div>
                </td>
            </tr>`;
    };

    const renderRows = (animate) => {
        if (!tbody) return;

        const rows = getFiltered();
        if (rows.length === 0) {
            renderEmpty();
            return;
        }

        tbody.innerHTML = rows
            .map((report) => {
                const status = getStatus(report.status);
                return `
                <tr data-id="${Number(report.id)}"${animate ? ' class="row-enter"' : ''}>
                    <td class="cell-catatan">${escapeHtml(report.catatan)}</td>
                    <td class="col-center cell-muted">${escapeHtml(report.pelapor)}</td>
                    <td class="col-center cell-muted">${escapeHtml(report.alasan)}</td>
                    <td class="col-center">
                        <button type="button"
                            class="status-pill status-${status}"
                            data-action="status"
                            aria-haspopup="menu">
                            ${STATUS_LABEL[status]}
                        </button>
                    </td>
                    <td class="col-center">
                        <div class="action-cell">
                            <button type="button" class="link-detail" data-action="detail">
                                Detail
                            </button>
                            <button type="button" class="link-delete" data-action="delete">
                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>`;
            })
            .join('');
    };

    const renderTabs = () => {
        tabButtons.forEach((button) => {
            const active = button.dataset.filter === state.filter;
            button.classList.toggle('active', active);
            button.setAttribute('aria-selected', active ? 'true' : 'false');
        });
    };

    const render = (animate = false) => {
        renderTabs();
        if (state.loading) {
            renderLoading();
        } else {
            renderRows(animate);
        }
    };

    /* ========================================
       FILTER TAB & DROPDOWN STATUS
    ======================================== */

    const closeStatusMenu = () => {
        if (!statusMenu) return;
        state.menuForId = null;
        statusMenu.hidden = true;
    };

    const openStatusMenu = (id, anchor) => {
        if (!statusMenu) return;
        const report = findReport(id);
        if (!report) return;

        state.menuForId = id;

        statusMenu.querySelectorAll('.status-option').forEach((option) => {
            option.classList.toggle(
                'selected',
                option.dataset.status === getStatus(report.status)
            );
        });

        statusMenu.hidden = false;

        const rect = anchor.getBoundingClientRect();
        const menuW = statusMenu.offsetWidth;
        const menuH = statusMenu.offsetHeight;

        let left = rect.left + rect.width / 2 - menuW / 2;
        left = Math.max(8, Math.min(left, window.innerWidth - menuW - 8));

        let top = rect.bottom + 6;
        if (top + menuH > window.innerHeight - 8) {
            top = rect.top - menuH - 6;
        }

        statusMenu.style.left = `${left}px`;
        statusMenu.style.top = `${top}px`;
    };

    tabsWrap?.addEventListener('click', (event) => {
        const button = event.target.closest('.tab-btn');
        if (!button || state.loading) return;

        state.filter = button.dataset.filter;
        closeStatusMenu();
        render(true);
    });

    statusMenu?.addEventListener('click', (event) => {
        const option = event.target.closest('.status-option');
        if (!option || state.menuForId === null) return;

        const report = findReport(state.menuForId);
        const newStatus = option.dataset.status;
        closeStatusMenu();

        if (!report || !STATUS_LABEL[newStatus] || report.status === newStatus) {
            return;
        }

        report.status = newStatus;
        persist();
        render(false);
        showToast(
            `Status "${report.catatan}" diubah menjadi ${STATUS_LABEL[newStatus]}`
        );
    });

    document.addEventListener('click', (event) => {
        if (state.menuForId === null) return;
        if (
            event.target.closest('#status-menu') ||
            event.target.closest('[data-action="status"]')
        ) {
            return;
        }
        closeStatusMenu();
    });

    window.addEventListener('resize', closeStatusMenu);
    window.addEventListener('scroll', closeStatusMenu, true);

    /* ========================================
       MODAL DETAIL & HAPUS
    ======================================== */

    const openModal = (modal) => {
        if (!modal) return;
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
    };

    const closeModal = (modal) => {
        if (!modal) return;
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
    };

    const closeAllModals = () => {
        closeModal(detailModal);
        closeModal(deleteModal);
        state.detailId = null;
        state.deleteId = null;
    };

    const openDetail = (id) => {
        const report = findReport(id);
        if (!report || !detailBody) return;

        state.detailId = id;
        const status = getStatus(report.status);

        detailBody.innerHTML = `
            <dl class="detail-list">
                <dt>Catatan</dt>
                <dd>${escapeHtml(report.catatan)}</dd>

                <dt>Pelapor</dt>
                <dd>${escapeHtml(report.pelapor)}</dd>

                <dt>Alasan</dt>
                <dd>${escapeHtml(report.alasan)}</dd>

                <dt>Tanggal</dt>
                <dd>${escapeHtml(formatTanggal(report.tanggal))}</dd>

                <dt>Status</dt>
                <dd>
                    <span class="status-pill status-${status}" style="pointer-events:none">
                        ${escapeHtml(STATUS_LABEL[status])}
                    </span>
                </dd>

                <dt>Keterangan</dt>
                <dd class="desc">${escapeHtml(report.deskripsi || '-')}</dd>
            </dl>`;

        openModal(detailModal);
    };

    const openDelete = (id) => {
        const report = findReport(id);
        if (!report || !deleteText) return;

        state.deleteId = id;
        deleteText.textContent = `Laporan untuk "${report.catatan}" dari ${report.pelapor} akan dihapus dan tidak dapat dikembalikan.`;
        openModal(deleteModal);
    };

    tbody?.addEventListener('click', (event) => {
        const actionEl = event.target.closest('[data-action]');
        if (!actionEl) return;

        const tr = actionEl.closest('tr');
        const id = Number(tr?.dataset.id);
        if (!Number.isFinite(id)) return;

        const action = actionEl.dataset.action;

        if (action === 'status') {
            event.stopPropagation();
            state.menuForId === id
                ? closeStatusMenu()
                : openStatusMenu(id, actionEl);
        } else if (action === 'detail') {
            openDetail(id);
        } else if (action === 'delete') {
            openDelete(id);
        }
    });

    [detailModal, deleteModal].forEach((modal) => {
        modal?.addEventListener('click', (event) => {
            if (event.target === modal || event.target.closest('[data-close]')) {
                closeAllModals();
            }
        });
    });

    confirmDeleteBtn?.addEventListener('click', () => {
        if (state.deleteId === null) return;

        const id = state.deleteId;
        const report = findReport(id);
        closeModal(deleteModal);
        state.deleteId = null;

        const row = tbody?.querySelector(`tr[data-id="${id}"]`);

        const finalizeDelete = () => {
            state.reports = state.reports.filter(
                (item) => Number(item.id) !== Number(id)
            );
            persist();
            render(false);
            if (report) {
                showToast(`Laporan "${report.catatan}" berhasil dihapus`);
            }
        };

        if (row) {
            row.classList.add('row-leave');
            setTimeout(finalizeDelete, 250);
        } else {
            finalizeDelete();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAllModals();
            closeStatusMenu();
        }
    });

    /* ========================================
       INISIALISASI
    ======================================== */

    state.reports = loadReports();
    render(false);

    setTimeout(() => {
        state.loading = false;
        render(true);
    }, 600);
});