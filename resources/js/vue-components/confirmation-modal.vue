<template>
    <div class="modal fade" role="dialog" ref="confirmation-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span v-html="message"></span>
                    <button @click="submitForm" class="float-end btn btn-outline-primary">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mame: 'confirmation-modal',
    data () {
        return {
            target: null,
            message: ''
        };
    },
    created () {
        window.confirmation = (_event, _message) => {
            _event.preventDefault();

            this.message = _message;
            let _modal = bootstrap.Modal.getOrCreateInstance(this.$refs['confirmation-modal']);
                _modal.toggle();
            this.target = _event.target;

            return false;
        };
    },
    methods: {
        submitForm (event) {
            let _processing = '<i class="fas fa-circle-notch fa-spin mr-2"></i>';
            event.target.innerHTML = _processing + event.target.innerHTML;
            event.target.disabled = true;

            this.target.form.submit();
        }
    }
};
</script>
