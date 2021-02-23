<template>
    <b-modal ref="confirmation-modal" hide-footer title="Confirmation">
        <p class="mb-4" v-html="message"></p>
        <b-button @click="submitForm" class="float-right" variant="outline-primary">Confirm</b-button>
    </b-modal>
</template>

<script>
    export default {
        mame: "confirmation-modal",
        data () {
            return {
                target: null,
                message: ''
            }
        },
        mounted () {
            window.confirmation = (_event, _message) => {
                _event.preventDefault();

                this.message = _message;
                this.$refs['confirmation-modal'].show();
                this.target = _event.target;

                return false;
            }
        },
        methods: {
            submitForm () {
                this.target.form.submit();
            }
        }
    }
</script>
