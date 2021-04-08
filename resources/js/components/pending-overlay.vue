<template>
    <b-overlay
        id="pendingOverlay"
        ref="processing-overlay"
        :show="show"
        no-wrap
        fixed
        z-index="19000"
        :variant="mode"
    >
    </b-overlay>
</template>

<script>
export default {
    mame: 'pending-overlay',
    props: {
        mode: {
            type: String,
            default: 'dark'
        }
    },
    data () {
        return {
            show: false,
        };
    },
    created () {
        window.pending = (button) => {
            if (button && button.form.checkValidity()) {
                button.form.submit();
                button.disabled = true;
                this.show = true;
            }

            if (! button) {
                this.show = true;
            }

            return false;
        };

        window.pendingHide = () => {
            this.show = false;
        };
    }
};
</script>
