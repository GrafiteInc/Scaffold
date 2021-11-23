<template>
    <div class="overlay bg-dark" v-if="show">
        <div class="spinner-container">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mame: 'pending-overlay',
    data () {
        return {
            show: false,
            mode: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
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

<style lang="scss" scoped>
.overlay {
    width: 100%;
    height: 100vh;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 30000;
    opacity: .8;
}
.spinner-container {
    position: absolute;
    top: 49%;
    left: calc(50% - 15px);
}
</style>
