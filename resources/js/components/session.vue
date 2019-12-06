<template></template>

<script>
    export default {
        mame: "session",
        props: [
            'user',
            'message',
            'error_message'
        ],
        mounted () {
            window.session = {
                user: this.user,
                message: this.message,
                error_message: this.error_message,
            }

            if (window.session.message) {
                window.Snotify.success(window.session.message);
            }

            if (window.session.error_message) {
                window.Snotify.warning(window.session.error_message);
            }
        }
    }
</script>
