(function () {
    var toggle = document.querySelector('[data-sidebar-toggle]');

    if (toggle) {
        toggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-open');
        });
    }
})();
