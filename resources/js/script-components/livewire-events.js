window.Livewire.on('loadMore', function () {
    setTimeout(function () {
        window.FormsJS();
        window.turnOnTooltips();
        window.turnOnPopovers();
    }, 250);
});

window.Livewire.on('refresh', function () {
    setTimeout(function () {
        window.FormsJS();
        window.turnOnTooltips();
        window.turnOnPopovers();
    }, 250);
});