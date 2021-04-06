<template></template>

<script>
    export default {
        mame: "notifications",
        created () {
            let _toast = this.$bvToast;

            window.notify = {
                success: function (message) {
                    this.notify(message, 'success', 'Success');
                },
                info: function (message) {
                    this.notify(message, 'info', 'Info');
                },
                warning: function (message) {
                    this.notify(message, 'warning', 'Warning');
                },
                error: function (message) {
                    this.notify(message, 'danger', 'Error');
                },
                notify: function (message, variant, title) {
                    _toast.toast(message, {
                        // title: title,
                        toaster: 'b-toaster-bottom-left',
                        autoHideDelay: 2000,
                        variant: variant,
                        solid: true,
                        noCloseButton: true
                    });
                }
            }
        }
    }
</script>
