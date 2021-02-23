<template>
    <b-overlay
        ref="processing-overlay"
        :show="show"
        no-wrap
        z-index="19000"
    >
    </b-overlay>
</template>

<script>
    export default {
        mame: "pending-modal",
        data () {
            return {
                show: false,
            }
        },
        mounted () {
            window.pendingModal = (button) => {
                if (button && button.form.checkValidity()) {
                    button.form.submit();
                    button.disabled = true;
                    this.show = true;
                }

                if (! button) {
                    this.show = true;
                }

                return false;
            }
        }
    }
</script>
