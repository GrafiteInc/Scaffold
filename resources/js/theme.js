/**
 * This lets us follow the Operating systems dark/light mode preference.
 * Requires loading dark-app.css and light-app.css in the main.blade.php file.
 * Some forms would require dark mode adjustments as well.
 */
window.dark_mode = false;

if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    window.dark_mode = true;
    document.querySelector('#lightStylesheet').remove();
} else {
    document.querySelector('#darkStylesheet').remove();
}

window.matchMedia('(prefers-color-scheme: dark)')
    .addEventListener('change', ({matches:isDark}) => {
        window.dark_mode = false;

        let _head = document.getElementsByTagName('head')[0];
        let _themeNode = document.createElement('link');
        let _stylesheetID = '#darkStylesheet';

        _themeNode.id = 'lightStylesheet';
        _themeNode.type = 'text/css';
        _themeNode.rel = 'stylesheet';
        _themeNode.href = '/css/light-app.css';

        if (isDark) {
            window.dark_mode = true;
            _themeNode.id = 'darkStylesheet';
            _themeNode.type = 'text/css';
            _themeNode.rel = 'stylesheet';
            _themeNode.href = '/css/dark-app.css';
            _stylesheetID = '#lightStylesheet';
        }

        new Promise(function(resolve, reject) {
            _head.appendChild(_themeNode);

            _themeNode.onload = function () {
                document.querySelector(_stylesheetID).remove();
                resolve();
            }
        });
    });
