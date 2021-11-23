/**
 * Location for handling events from Echo.
 */
document.addEventListener('DOMContentLoaded', (event) => {
    let generalChannel = window.Echo.private('general');
    generalChannel.listen('.general-event', (data) => {
        window.notify.info(data.data.message);
    });

    let userId = window.session.user.id;
    let userChannel = window.Echo.private(`App.Models.User.${userId}`);
    userChannel.listen('.user-event', (data) => {
        window.notify.info(data.data.message);
    });
});
