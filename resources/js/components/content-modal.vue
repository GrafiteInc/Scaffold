<template>
    <div id="appContentModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="m-0">{{ title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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

            // Custom window content modal
            window.modal = (_title, _message) => {
                this.title = _title;
                this.content = _message;

                $('#appContentModal').modal('show');

                return false;
            }
        }
    }
</script>
