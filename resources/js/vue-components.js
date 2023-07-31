import { createApp } from 'vue';

// External Components

// Internal Components
// import CopyButton from './vue-components/CopyButton.vue';

// Define App
const vueApp = createApp({});

// Add Components
vueApp.component('CopyButton', require('./vue-components/CopyButton.vue').default);

// Mount App
vueApp.mount('#app');
window.app.vue = vueApp;
