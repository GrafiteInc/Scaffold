<template>
    <div>
        <div class="cookie-banner" v-if="showBanner">
            <span class="mx-4">This website uses cookies to ensure you get the best experience on our website.</span>
            <button
                class="btn btn-sm btn-outline-secondary mx-4"
                @click="accept"
            >Accept</button>
        </div>
    </div>
</template>

<script>
export default {
    mame: 'cookie-law',
    props: [
        'version'
    ],
    mounted () {
        this.showBanner = window.localStorage.getItem(this.cookieName) ? false : true;
    },
    methods: {
        accept () {
            // If they have not accepted it yet, lets log it
            if (! window.localStorage.getItem(this.cookieName)) {
                this.showBanner = false,
                window.localStorage.setItem(this.cookieName, true);

                axios.post(route('ajax.accept-cookie-policy'), {
                    version: this.version,
                })
                    .then((results) => {
                        // Will be a statement that its logged
                        // results.data.data
                    })
                    .catch((err) => {
                        //
                    });
            }
        }
    },
    data () {
        return {
            showBanner: true,
            cookieName: `cookie:accepted:${this.version}`
        };
    }
};
</script>

<style lang="scss">
    .cookie-banner {
        left: 0;
        bottom: 0;
        position: fixed;
        z-index: 10000000;
        background: #333;
        width: 100%;
        text-align: center;
        span {
            line-height: 56px;
        }
    }
</style>
