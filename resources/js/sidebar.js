document.addEventListener('DOMContentLoaded', (event) => {
    if (document.querySelector('.sidebar-toggle')) {
        document.querySelector('.sidebar-toggle').onclick = () => {
            document.querySelector('.sidebar').classList.toggle('toggled');
            document.querySelector('.sidebar-toggle i').classList.toggle('fa-times');
        };

        window.addEventListener('resize', () => {
            document.querySelector('.sidebar').classList.remove('toggled');
            document.querySelector('.sidebar-toggle i').classList.remove('fa-times');
        });
    }

    if (window.innerWidth < 990) {
        document.querySelector('.main .container').onclick = () => {
            document.querySelector('.sidebar').classList.remove('toggled');
            document.querySelector('.sidebar-toggle i').classList.remove('fa-times');
        };
    }
});
