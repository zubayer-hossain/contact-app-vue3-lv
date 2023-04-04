import { createRouter, createWebHistory } from "vue-router";

import ContactsIndex  from '@/components/contacts/ContactsIndex.vue'
import ContactsCreate from '@/components/contacts/ContactsCreate.vue'
import ContactsEdit   from '@/components/contacts/ContactsEdit.vue'

const routes = [
    {
        path: '/dashboard',
        name: 'contacts.index',
        component: ContactsIndex
    },
    {
        path: '/contacts/create',
        name: 'contacts.create',
        component: ContactsCreate
    },
    {
        path: '/contacts/:id/edit',
        name: 'contacts.edit',
        component: ContactsEdit,
        props: true
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
})
