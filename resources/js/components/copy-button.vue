<template>
    <div>
        <button
            v-clipboard="copyData"
            @success="success"
            class="input-group-text"
        >
            Copy
        </button>
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type: String,
            default: ''
        }
    },
    methods: {
        success () {
            window.notify.success('Copied!');
        }
    },
    data () {
        return {
            copyData: this.message
        };
    }
};
</script>
