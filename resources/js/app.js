import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from "vue";
import router         from '@/router/index.js'
import ContactsIndex from '@/components/contacts/ContactsIndex.vue'
import Notifications from '@kyvg/vue3-notification'

createApp({
    components: {
        ContactsIndex
    }
}).use(router).use(Notifications).mount('#app')
