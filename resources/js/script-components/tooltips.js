window.turnOnTooltips = function () {
    let tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map((tooltipTriggerEl) => {
        return new window.bootstrap.Tooltip(tooltipTriggerEl);
    });
};

window.addEventListener('DOMContentLoaded', () => {
    window.turnOnTooltips();
});
