window.onload = () => {
    if (document.querySelector('.sidebar-toggle')) {
        document.querySelector('.sidebar-toggle').onclick = () => {
            document.querySelector('.sidebar').classList.toggle("toggled");
        }

        window.addEventListener("resize", () => {
            document.querySelector('.sidebar').classList.remove('toggled');
        });
    }

    if (window.innerWidth < 990) {
        document.querySelector('.main').onclick = () => {
            document.querySelector('.sidebar').classList.remove("toggled");
        };
    }
}
