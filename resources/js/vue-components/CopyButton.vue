<template>
    <div>
        <button
            @click="success"
            class="btn btn-outline-primary"
        >
            <slot></slot>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type: String
        }
    },
    methods: {
        success: () => {
            window.app.notify.success('Copied!');
        }
    },
    data () {
        return {
            copyData: this.message
        };
    }
};
</script>
