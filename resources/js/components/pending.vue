<template>
    <div id="appPendingModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="text-center m-0">
                        <span class="fas fa-spinner fa-spin"></span>
                        Processing
                    </h5>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mame: "pending-modal",
        props: [
            //
        ],
        mounted () {

            // Custom window modal
            window.pending = () => {
                $('#appPendingModal').modal('show');

                return false;
            }
        }
    }
</script>
