<template><div></div></template>

<script>
export default {
    mame: 'session',
    props: {
        user: String,
        message: String,
        info: String,
        warning: String,
        errorMessage: String,
    },
    created () {
        window.session = {
            user: JSON.parse(this.user),
            message: this.message,
            info: this.info,
            warning: this.warning,
            error_message: this.errorMessage,
        };

        if (window.session.message) {
            window.notify.success(window.session.message);
        }

        if (window.session.info) {
            window.notify.info(window.session.info);
        }

        if (window.session.warning) {
            window.notify.warning(window.session.warning);
        }

        if (window.session.error_message) {
            window.notify.error(window.session.error_message);
        }
    }
};
</script>
