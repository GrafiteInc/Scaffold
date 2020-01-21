<template>
    <div>
        <div class="form-row align-items-center">
            <div class="col-10">
                <input type="text" class="form-control mb-2" disabled v-model="currentToken">
            </div>
            <div class="col-2 d-flex justify-content-end">
                <button
                    v-clipboard="currentToken"
                    @success="success"
                    class="btn btn-primary mr-2 mb-2"
                >Copy</button>

                <button
                    class="btn btn-warning mb-2"
                    @click="reset"
                >
                    <span class="fas fa-fw fa-sync-alt"></span>
                    Reset
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            token: String,
        },
        created () {
            if (this.token == '') {
                this.currentToken = 'Please click reset to set your API token.';
            }
        },
        methods: {
            reset () {
                $('#appModalMessage').html('Are you sure you want to reset your API token? It will invalidate any uses of it currently.');

                $('#appModal').modal('show');

                $('#appModalConfirmBtn').unbind().click(() => {
                    axios.post(route('ajax.reset-token'), {})
                    .then(results => {
                        this.currentToken = results.data.data.token;
                        $('#appModal').modal('hide');
                        window.Snotify.success('Token reset!');
                    })
                    .catch(err => {
                        //
                    });
                });
            },
            success () {
                window.Snotify.success('Copied!');
            }
        },
        data () {
            return {
                currentToken: this.token,
            }
        }
    }
</script>
