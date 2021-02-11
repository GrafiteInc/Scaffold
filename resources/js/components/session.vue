<template></template>

<script>
    export default {
        mame: "session",
        props: {
            user: String,
            message: String,
            info: String,
            warning: String,
            error_message: String,
        },
        mounted () {
            window.session = {
                user: JSON.parse(this.user),
                message: this.message,
                info: this.info,
                warning: this.warning,
                error_message: this.error_message,
            }

            window.snotify = this.$snotify;

            window.addEventListener('offline', (event) => {
                if (localStorage.getItem('network-state') && localStorage.getItem('network-state') !== 'offline') {
                    this.$snotify.info("The network connection has been lost.");
                    localStorage.setItem('network-state', 'offline');
                }
            });

            window.addEventListener('online', (event) => {
                if (localStorage.getItem('network-state') && localStorage.getItem('network-state') !== 'online') {
                    this.$snotify.success("The network connection has been restored.");
                    localStorage.setItem('network-state', 'online');
                }
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
