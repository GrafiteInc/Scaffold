<template>
    <div>
        <div class="modal fade" role="dialog" ref="token-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-4">Are you sure you want to revoke this API token? It will invalidate any uses of it currently.</p>
                        <button @click="deleteToken" class="float-end btn btn-outline-primary">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-2" v-for="token in api_tokens" :key="token.id">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="col-md-3 col-sm-6">
                        <b class="mt-1 d-block">{{ token.name }}</b>
                    </div>
                    <div class="col-md-3 d-none d-md-block">
                        <span class="mt-1 d-block" v-html="tokenDate(token)"></span>
                    </div>
                    <div class="col-md-3 col-sm-6 d-flex justify-content-end">
                        <button
                            class="btn btn-sm btn-outline-danger"
                            @click="revokeToken(token)"
                        >
                            Revoke Token
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        tokens: Array
    },
    created () {
        this.api_tokens = this.tokens;
        window.app.$events.listen('new-api-token', this.getTokens);
    },
    methods: {
        tokenDate (token) {
            let _parsed = Date.parse(token.created_at);
            let _date = new Date(_parsed);

            return _date.toDateString();
        },
        getTokens () {
            axios.get(route('ajax.tokens'), {
                name: this.name,
                permissions: []
            })
                .then((results) => {
                    this.api_tokens = results.data.data.tokens;
                });
        },
        revokeToken (token) {
            this.tokenToRevoke = token;
            let _modal = bootstrap.Modal.getOrCreateInstance(this.$refs['token-modal']);
            _modal.toggle();
        },
        deleteToken (event) {
            let _processing = '<i class="fas fa-circle-notch fa-spin mr-2"></i>';
            let _originalHTML = event.target.innerHTML
            event.target.innerHTML = _processing + _originalHTML;
            event.target.disabled = true;

            axios.delete(route('ajax.destroy-token', this.tokenToRevoke.id))
                .then((results) => {
                    let _modal = bootstrap.Modal.getOrCreateInstance(this.$refs['token-modal']);
                    _modal.hide();
                    window.notify.success('Revoked!');
                    event.target.disabled = false;
                    event.target.innerHTML = _originalHTML;
                    this.getTokens();
                });
        }
    },
    data () {
        return {
            api_tokens: [],
            tokenToRevoke: null
        };
    }
};
</script>
