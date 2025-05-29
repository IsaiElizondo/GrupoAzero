document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.dropdown-checkbox').forEach(function (dropdown) {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu-checkboxes');

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            
            document.querySelectorAll('.dropdown-checkbox').forEach(function (dd) {
                if (dd !== dropdown) dd.classList.remove('open');
            });
            
            dropdown.classList.toggle('open');
        });
    });

    //CERRAR DROPDOWN
    document.addEventListener('click', function (e) {
        document.querySelectorAll('.dropdown-checkbox').forEach(function (dropdown) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });
    });
});