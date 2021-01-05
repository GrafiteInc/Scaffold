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

            window.snotify = this.$snotify;

            window.addEventListener('offline', (event) => {
                this.$snotify.info("The network connection has been lost.");
            });

            if (window.session.message) {
                this.$snotify.success(window.session.message);
            }

            if (window.session.info) {
                this.$snotify.info(window.session.info);
            }

            if (window.session.warning) {
                this.$snotify.warning(window.session.warning);
            }

            if (window.session.error_message) {
                this.$snotify.warning(window.session.error_message);
            }
        }
    }
</script>
