import Quill from 'quill';
import 'quill/dist/quill.snow.css';

(function () {
    'use strict';

    const form = document.getElementById('form-catatan');
    if (!form) { return; }

    const panelTeks    = document.getElementById('panel-teks');
    const panelTautan  = document.getElementById('panel-tautan');
    const inputTautan  = document.getElementById('tautan');
    const inputIsi     = document.getElementById('isi');
    const pesanIsi     = document.getElementById('pesan-isi');
    const grupIdentitas = document.getElementById('grup-identitas');

    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Mulai tuliskan materi catatanmu...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ header: [1, 2, 3, false] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['blockquote', 'code-block'],
                ['link'],
                ['clean'],
            ],
        },
    });

    if (inputIsi.value) {
        try {
            quill.setContents(JSON.parse(inputIsi.value));
        } catch (e) {
            
        }
    }

    function modeSekarang() {
        return form.querySelector('input[name="tipe_isi"]:checked').value;
    }

    function aturMode() {
        const mode = modeSekarang();
        panelTeks.hidden   = mode !== 'teks';
        panelTautan.hidden = mode !== 'tautan';
        inputTautan.required = mode === 'tautan';
    }

    function aturIdentitas() {
        const publik = form.querySelector('input[name="visibilitas"]:checked').value === 'publik';

        form.querySelectorAll('input[name="anonim"]').forEach(function (r) {
            r.disabled = !publik;
        });
        if (!publik) {
            form.querySelector('input[name="anonim"][value="0"]').checked = true;
        }
        grupIdentitas.classList.toggle('nonaktif', !publik);
    }

    form.querySelectorAll('input[name="tipe_isi"]').forEach(function (r) {
        r.addEventListener('change', aturMode);
    });
    form.querySelectorAll('input[name="visibilitas"]').forEach(function (r) {
        r.addEventListener('change', aturIdentitas);
    });

    form.addEventListener('submit', function (e) {
        if (modeSekarang() === 'teks') {
            if (quill.getText().trim().length === 0) {
                e.preventDefault();
                pesanIsi.hidden = false;
                quill.focus();
                return;
            }
            pesanIsi.hidden = true;
            inputIsi.value = JSON.stringify(quill.getContents()); // Delta -> JSON
        } else {
            inputIsi.value = '';
        }
    });

    aturMode();
    aturIdentitas();
})();