/**
 * Handle special mobile JS
 */
document.addEventListener('DOMContentLoaded', (event) => {
    // Detects if device is on iOS
    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test( userAgent );
    }

    // Detects if device is in standalone mode
    const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

    // Checks if should display install popup notification:
    if (isIos() && !isInStandaloneMode()) {
        this.setState({ showInstallMessage: true });
    }

    if (window.innerWidth <= 576) {
        window.pendingHide();

        document.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                window.pending();
                setTimeout(() => {
                    window.location = e.target.closest("*[href]").href;
                }, 50);
            });
        });
    }
});
