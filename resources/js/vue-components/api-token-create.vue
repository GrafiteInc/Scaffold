<template>
    <div>
        <div class="form-row align-items-center">
            <div class="col-md-12 d-flex justify-content-end">
                <input type="text" class="form-control mr-sm-2" id="tokenName" placeholder="Third-party webhook access" v-model="name">
                <button
                    class="btn btn-primary"
                    @click="create"
                >
                    Create
                </button>
            </div>
            <div class="col-md-12 d-flex justify-content-end mt-4 mb-2" v-if="currentToken">
                <input type="text" class="form-control me-sm-2" readonly v-model="currentToken">
                <button
                    :data-clipboard-text="currentToken"
                    @click="success"
                    class="btn btn-primary"
                >
                    Copy
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {},
    mounted () {
        new window.clipboard('.btn');
    },
    methods: {
        create () {
            axios.post(route('ajax.create-token'), {
                name: this.name,
                permissions: []
            })
                .then((results) => {
                    this.currentToken = results.data.data.token;
                    this.name = null;
                    window.notify.success('Token created!');
                    window.app.$events.fire('get-notifications');
                    window.app.$events.fire('new-api-token');
                })
                .catch((err) => {
                    //
                });
        },
        success () {
            window.notify.success('Copied!');
            this.currentToken = false;
        }
    },
    data () {
        return {
            currentToken: false,
            name: null
        };
    }
};
</script>
