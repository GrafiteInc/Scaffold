/**
 * Location for handling events from Echo.
 */
document.addEventListener('DOMContentLoaded', (event) => {
    let generalChannel = window.Echo.private('general');
    generalChannel.listen('.general-event', (data) => {
        window.app.notify.info(data.data.message);
    });

    let userId = window.app.session.user.id;
    let userChannel = window.Echo.private(`App.Models.User.${userId}`);
    userChannel.listen('.user-event', (data) => {
        window.app.notify.info(data.data.message);
    });
});
