import PullToRefresh from 'pulltorefreshjs';

/**
 * Handle special mobile JS
 */
document.addEventListener('DOMContentLoaded', (event) => {
    // Detects if device is on iOS
    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(userAgent);
    };

    // Detects if device is in standalone mode
    const isInStandaloneMode = () => { return ('standalone' in window.navigator) && (window.navigator.standalone); };

    // Checks if should display install popup notification:
    if (isIos() && !isInStandaloneMode()) {
        window.notify.info('To install this app on your iPhone: tap the Share icon in the middle bar below and then Add to Home Screen.', 99000);
    }

    if (window.innerWidth <= 576) {
        // window.app.pendingHide();

        // document.querySelectorAll('a').forEach((link) => {
        //     link.addEventListener('click', (e) => {
        //         e.preventDefault();
        //         window.app.pending();
        //         setTimeout(() => {
        //             window.location = e.target.closest('*[href]').href;
        //         }, 50);
        //     });
        // });

        const ptr = PullToRefresh.init({
            mainElement: '#app',
            distThreshold: 75,
            distReload: 95,
            onRefresh () {
                window.location.reload();
            }
        });
    }
});
