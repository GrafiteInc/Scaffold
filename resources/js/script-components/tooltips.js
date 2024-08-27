window.turnOnTooltips = function () {
    let tooltipTriggerList = document.querySelectorAll("[data-bs-toggle=\"tooltip\"]");
    [...tooltipTriggerList].map((tooltipTriggerEl) => {
        return new window.bootstrap.Tooltip(tooltipTriggerEl);
    });
};

window.turnOnPopovers = function () {
    let popoverTriggerList = document.querySelectorAll("[data-bs-toggle=\"popover\"]");
    [...popoverTriggerList].map((popoverTriggerEl) => {
        return new window.bootstrap.Popover(popoverTriggerEl, {
            html: true,
        });
    });
};

window.addEventListener("DOMContentLoaded", () => {
    window.turnOnTooltips();
    window.turnOnPopovers();
});
