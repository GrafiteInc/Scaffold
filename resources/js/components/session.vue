<template></template>

<script>
    export default {
        mame: "session",
        props: [
            'user',
            'message',
            'info',
            'warning',
            'error_message'
        ],
        mounted () {
            window.session = {
                user: this.user,
                message: this.message,
                info: this.info,
                warning: this.warning,
                error_message: this.error_message,
            }

            if (window.session.message) {
                window.Snotify.success(window.session.message);
            }

            if (window.session.info) {
                window.Snotify.info(window.session.info);
            }

            if (window.session.warning) {
                window.Snotify.warning(window.session.warning);
            }

            if (window.session.error_message) {
                window.Snotify.warning(window.session.error_message);
            }
        }
    }
</script>
