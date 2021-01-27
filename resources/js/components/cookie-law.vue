<template>
    <div>
        <cookie-law
            theme="basic"
            message="This website uses cookies to ensure you get the best experience on our website."
            v-on:accept="accept"
            :storageName="cookieName"
        ></cookie-law>
    </div>
</template>

<script>
    import CookieLaw from 'vue-cookie-law'
    export default {
        mame: "cookielaw",
        props: [
            'version'
        ],
        components: {
            CookieLaw
        },
        methods: {
            accept () {
                // If they have not accepted it yet, lets log it
                if (! window.localStorage.getItem(this.cookieName)) {
                    axios.post(route('ajax.accept-cookie-policy'), {
                        version: this.version,
                    })
                    .then(results => {
                        // Will be a statement that its logged
                        // results.data.data
                    })
                    .catch(err => {
                        //
                    });
                }
            }
        },
        data () {
            return {
                cookieName: 'cookie:accepted:'+this.version
            }
        }
    }
</script>

<style lang="scss">
    @import './../../sass/light/variables';

    .Cookie--basic {
        background-color: $light;
        color: $dark;
        padding: 1.25em;
    }

    .Cookie--basic .Cookie__button {
        background-color: $dark;
        color: $light;
        padding: 0.625em 3.125em;
        border-radius: 0;
        border: 0;
        font-size: 1em;
        &:hover {
            background-color: $primary;
            color: $white;
        }
    }
</style>
