<template>
    <div>
        <span v-if="notificationCount > 0" class="badge badge-pill badge-primary notification-badge" v-html="notificationCount"></span>
    </div>
</template>

<script>
    export default {
        props: {},
        created () {
            this.getCount();
            this.$event.listen('get-notifications', this.getCount);
        },
        methods: {
            getCount () {
                axios.get(route('ajax.notifications-count'), {})
                .then(results => {
                    this.notificationCount = results.data.data;
                    this.$event.fire('notifications-counted', this.notificationCount);
                })
                .catch(err => {
                    //
                });
            }
        },
        data () {
            return {
                notificationCount: 9,
            }
        }
    }
</script>
