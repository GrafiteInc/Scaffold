window.Livewire.on('loadMore', () => {
    setTimeout(() => {
        window.FormsJS();
        window.turnOnTooltips();
        window.turnOnPopovers();
    }, 250);
});

window.Livewire.on('refresh', () => {
    setTimeout(() => {
        window.FormsJS();
        window.turnOnTooltips();
        window.turnOnPopovers();
    }, 250);
});
