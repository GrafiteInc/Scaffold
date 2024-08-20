window.copyToClipboard = function (_message) {
    window.navigator.clipboard.writeText(_message).then(() => {
        window.app.notify.success("Copied!");
    });
};
