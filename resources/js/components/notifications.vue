<template><div></div></template>

<script>
export default {
    mame: 'notifications',
    created () {
        let _toast = this.$bvToast;

        window.notify = {
            success: function (message, delay = 2000) {
                this.notify(message, 'success', 'Success', delay);
            },
            info: function (message, delay = 2000) {
                this.notify(message, 'info', 'Info', delay);
            },
            warning: function (message, delay = 2000) {
                this.notify(message, 'warning', 'Warning', delay);
            },
            error: function (message, delay = 2000) {
                this.notify(message, 'danger', 'Error', delay);
            },
            notify: function (message, variant, title, delay = 2000) {
                _toast.toast(message, {
                    toaster: 'b-toaster-bottom-left',
                    autoHideDelay: delay,
                    variant: variant,
                    solid: true,
                    noCloseButton: true
                });
            }
        };
    }
};
</script>
