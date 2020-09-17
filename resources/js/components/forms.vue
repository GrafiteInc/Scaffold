<template>
    <div>
        <form
            @submit.prevent="submit"
        >
            <form-input
                v-for="field in fields"
                :field="field"
                :key="field.id"
                v-model="data[field.key]"
            ></form-input>

            <button :class="settings.buttonClasses.submit">
                {{ settings.buttons.submit }}
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        mame: "forms",
        props: [
            'settings',
            'data',
            'fields',
        ],
        methods: {
            submit () {
                console.log(this.data)
                // axios.post(route('ajax.accept-cookie-policy'), {
                //     version: this.version,
                // })
                // .then(results => {
                //     // Will be a statement that its logged
                //     // results.data.data
                // })
                // .catch(err => {
                //     //
                // });
            }
        }
    }
</script>
