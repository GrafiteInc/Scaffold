<template>
    <div>
        <b-modal ref="token-modal" hide-footer title="Confirmation">
            <p class="mb-4">
                Are you sure you want to revoke this API token? It will invalidate any uses of it currently.
            </p>
            <b-button @click="deleteToken" class="float-right" variant="outline-primary">
                Confirm
            </b-button>
        </b-modal>

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
        this.$event.listen('new-api-token', this.getTokens);
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
            this.$refs['token-modal'].show();
        },
        deleteToken (event) {
            let _processing = '<i class="fas fa-circle-notch fa-spin mr-2"></i>';
            event.target.innerHTML = _processing + event.target.innerHTML;
            event.target.disabled = true;

            axios.delete(route('ajax.destroy-token', this.tokenToRevoke.id))
                .then((results) => {
                    this.$refs['token-modal'].hide();
                    window.notify.success('Revoked!');
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
