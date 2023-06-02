document.addEventListener('DOMContentLoaded', (event) => {
    let _sidebarPreventScroll = function (e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    };

    if (document.querySelector('.sidebar-toggle')) {
        document.querySelector('.sidebar-toggle').onclick = () => {
            document.querySelector('.sidebar').classList.toggle('toggled');
            document.querySelector('.sidebar-overlay').classList.toggle('toggled');
            document.querySelector('.sidebar-toggle i').classList.toggle('fa-times');

            if (document.querySelector('.sidebar').classList.contains('toggled')) {
                document.querySelector('main').addEventListener('wheel', _sidebarPreventScroll, { passive: false });
                window.app.destroyPullToRefresh();
            }

            if (! document.querySelector('.sidebar').classList.contains('toggled')) {
                document.querySelector('main').removeEventListener('wheel', _sidebarPreventScroll, { passive: false });
                window.app.pullToRefresh();
            }
        };

        window.addEventListener('resize', () => {
            document.querySelector('.sidebar').classList.remove('toggled');
            document.querySelector('.sidebar-overlay').classList.remove('toggled');
            document.querySelector('.sidebar-toggle i').classList.remove('fa-times');
            document.querySelector('main').removeEventListener('wheel', _sidebarPreventScroll, { passive: false });
        });
    }

    if (window.innerWidth < 990) {
        if (document.querySelector('.sidebar-overlay')) {
            document.querySelector('.sidebar-overlay').onclick = () => {
                document.querySelector('.sidebar').classList.remove('toggled');
                document.querySelector('.sidebar-overlay').classList.remove('toggled');
                document.querySelector('.sidebar-toggle i').classList.remove('fa-times');
                document.querySelector('main').removeEventListener('wheel', _sidebarPreventScroll, { passive: false });
                window.app.pullToRefresh();
            };
        }
    }
});
