window.livewire.on('loadMore', function () {
    setTimeout(function () {
        window.FormsJS();
        window.turnOnTooltips();
        window.turnOnPopovers();
    }, 250);
});

window.livewire.on('refresh', function () {
    setTimeout(function () {
        window.FormsJS();
        window.turnOnTooltips();
        window.turnOnPopovers();
    }, 250);
});