/**
 * Handle special mobile JS
 */
document.addEventListener('DOMContentLoaded', (event) => {
    if (window.screen.width < 576) {
        document.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', () => {
                window.pending();
                return true;
            });
        });
    }
});
