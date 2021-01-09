<template>
    <div id="appModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="appModalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button id="appModalConfirmBtn" type="button" class="btn btn-outline-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mame: "modal",
        props: [
            //
        ],
        mounted () {

            // Custom window modal
            window.confirmation = (_event, _message) => {
                _event.preventDefault();

                $('#appModalMessage').html(_message);

                $('#appModal').modal('show');

                $('#appModalConfirmBtn').unbind().click(() => {
                    $(_event.target).closest('form')[0].submit();
                });

                return false;
            }
        }
    }
</script>
