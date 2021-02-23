<template></template>

<script>
    export default {
        mame: "online",
        mounted () {
            window.addEventListener('offline', (event) => {
                if (localStorage.getItem('network-state') && localStorage.getItem('network-state') !== 'offline') {
                    window.notify.info('The network connection has been lost.');
                    localStorage.setItem('network-state', 'offline');
                }
            });

            window.addEventListener('online', (event) => {
                if (localStorage.getItem('network-state') && localStorage.getItem('network-state') !== 'online') {
                    window.notify.success('The network connection has been restored.');
                    localStorage.setItem('network-state', 'online');
                }
            });
        }
    }
</script>
