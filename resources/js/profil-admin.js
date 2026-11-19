document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const icon = this.querySelector('iconify-icon');
            const tampil = input.type === 'password';

            input.type = tampil ? 'text' : 'password';
            icon.setAttribute('icon', tampil ? 'lucide:eye-off' : 'lucide:eye');
        });
    });
});