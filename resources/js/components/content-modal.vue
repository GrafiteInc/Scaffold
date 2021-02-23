<template>
    <b-modal ref="content-modal" size="lg" hide-footer :title="title">
        <span v-html="content"></span>
    </b-modal>
</template>

<script>
    export default {
        mame: "content-modal",
        props: [
            //
        ],
        data () {
            return {
                title: '',
                content: ''
            }
        },
        mounted () {
            window.modal = (_title, _message) => {
                this.title = _title;
                this.content = _message;

                this.$refs['content-modal'].show();

                return false;
            }
        }
    }
</script>
