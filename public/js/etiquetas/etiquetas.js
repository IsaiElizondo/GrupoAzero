document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.etiqueta-checkbox').forEach(checkbox => {
        const label = document.querySelector('label[for="' + checkbox.id + '"]');

        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                label.classList.add('active');
            } else {
                label.classList.remove('active');
            }
        });
    });
});