<template>
    <div class="inline-notification-marker">
        <span v-if="markerIsVisible" class="badge badge-pill badge-primary">&nbsp;</span>
    </div>
</template>

<script>
    export default {
        props: {},
        created () {
            this.$event.listen('notifications-counted', this.setMarker)
        },
        methods: {
            setMarker (value) {
                if (value > 0) {
                    this.markerIsVisible = true;
                }
            }
        },
        data () {
            return {
                markerIsVisible: false,
            }
        }
    }
</script>

<style scoped>
    .inline-notification-marker {
        display: inline-block;
    }
</style>