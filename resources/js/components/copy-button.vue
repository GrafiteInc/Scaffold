<template>
    <div>
        <button
            v-clipboard="copyData"
            @success="success"
            class="input-group-text"
        >Copy</button>
    </div>
</template>

<script>
    export default {
        props: {
            message: ''
        },
        methods: {
            success () {
                this.$snotify.success('Copied!');
            }
        },
        data() {
            return {
                copyData: this.message
            }
        }
    }
</script>
