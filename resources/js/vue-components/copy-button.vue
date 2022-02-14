<template>
    <div>
        <button
            :data-clipboard-text="copyData"
            @click="success"
            class="input-group-text copy-clipboard-btn"
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
    mounted () {
        new window.clipboard('.copy-clipboard-btn');
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
