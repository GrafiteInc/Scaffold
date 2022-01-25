<template>
    <div class="modal fade" role="dialog" ref="content-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-html="title"></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span v-html="content"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mame: 'content-modal',
    data () {
        return {
            title: '',
            content: ''
        };
    },
    created () {
        window.modal = (_title, _message) => {
            this.title = _title;
            this.content = _message;

            let _modal = bootstrap.Modal.getOrCreateInstance(this.$refs['content-modal']);
                _modal.toggle();
        };
    }
};
</script>
