/**
 * Location for handling events from Echo.
 */
document.addEventListener('DOMContentLoaded', (event) => {
    var generalChannel = Echo.private('general');
    generalChannel.listen('.general-event', function(data) {
        window.notify.info(data.data.message);
    });

    var userId = window.session.user.id;
    var userChannel = Echo.private('App.Models.User.' + userId);
    userChannel.listen('.user-event', function(data) {
        window.notify.info(data.data.message);
    });
});
