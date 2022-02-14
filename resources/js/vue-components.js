import { createApp } from 'vue';

/**
 * Let's create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Notifications from './vue-components/notifications.vue';
import ContentModal from './vue-components/content-modal.vue';
import Offcanvas from './vue-components/offcanvas.vue';
import ConfirmationModal from './vue-components/confirmation-modal.vue';
import PendingOverlay from './vue-components/pending-overlay.vue';
import CookieLaw from './vue-components/cookie-law.vue';
import CopyButton from './vue-components/copy-button.vue';
import ApiTokens from './vue-components/api-tokens.vue';
import ApiTokenCreate from './vue-components/api-token-create.vue';
import NotificationBadge from './vue-components/notification-badge.vue';


const app = createApp({
    components: {
        Notifications,
        ApiTokens,
        ApiTokenCreate,
        CopyButton,
        NotificationBadge,
        ContentModal,
        Offcanvas,
        ConfirmationModal,
        PendingOverlay,
        CookieLaw
    }
}).mount('#app');
