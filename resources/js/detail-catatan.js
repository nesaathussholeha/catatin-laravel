function tampilkanToast(pesan) {
    const wadah = document.querySelector('.detail-catatan');
    if (!wadah) return;

    let toast = wadah.querySelector('.toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.className = 'toast';
        wadah.appendChild(toast);
    }

    toast.textContent = pesan;
    toast.hidden = false;

    setTimeout(() => {
        toast.hidden = true;
    }, 3000);
}

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalLaporan');
    const form = document.getElementById('formLaporan');
    if (!modal || !form) return;

    const inputJudul = document.getElementById('inputJudul');
    const inputDeskripsi = document.getElementById('inputDeskripsi');
    const errorJudul = document.querySelector('[data-error="judul"]');
    const errorJenis = document.querySelector('[data-error="jenis"]');
    let jenisTerpilih = '';

    function bukaModal() {
        modal.hidden = false;
        inputJudul.focus();
    }

    function tutupModal() {
        modal.hidden = true;
    }

    document.getElementById('btnLaporkan')?.addEventListener('click', bukaModal);
    document.getElementById('btnLaporkanBawah')?.addEventListener('click', bukaModal);
    document.getElementById('modalClose')?.addEventListener('click', tutupModal);
    document.getElementById('btnBatal')?.addEventListener('click', tutupModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) tutupModal();
    });

    function perbaruiCounter() {
        document.getElementById('counterJudul').textContent = `${inputJudul.value.length}/100`;
        document.getElementById('counterDeskripsi').textContent = `${inputDeskripsi.value.length}/500`;
    }

    inputJudul.addEventListener('input', () => {
        perbaruiCounter();
        errorJudul.textContent = '';
    });
    inputDeskripsi.addEventListener('input', perbaruiCounter);

    document.getElementById('chipGroup')?.addEventListener('click', (e) => {
        const chip = e.target.closest('.chip');
        if (!chip) return;

        jenisTerpilih = chip.dataset.value;
        document.querySelectorAll('#chipGroup .chip').forEach((item) => {
            item.classList.toggle('aktif', item === chip);
        });
        errorJenis.textContent = '';
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let valid = true;

        if (!inputJudul.value.trim()) {
            errorJudul.textContent = 'Judul laporan wajib diisi.';
            valid = false;
        }
        if (!jenisTerpilih) {
            errorJenis.textContent = 'Pilih jenis laporan.';
            valid = false;
        }
        if (!valid) return;

        form.reset();
        jenisTerpilih = '';
        document.querySelectorAll('#chipGroup .chip').forEach((c) => c.classList.remove('aktif'));
        perbaruiCounter();
        tutupModal();
        tampilkanToast('Laporan berhasil dikirim!');
    });
});