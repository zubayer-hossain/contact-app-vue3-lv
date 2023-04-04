import { ref } from 'vue'
import axios from "axios";
import { useRouter } from 'vue-router';
import { useNotification } from "@kyvg/vue3-notification";

export default function useContacts() {
    const contacts = ref([])
    const contact = ref([])
    const phoneTypes = ref([])
    const router = useRouter()
    const errors = ref('')
    const notification = useNotification()

    const getPhoneTypes = async () => {
        let response = await axios.get('/api/phone-types')
        phoneTypes.value = response.data.data;
    }

    const getContacts = async () => {
        let response = await axios.get('/api/contacts')
        contacts.value = response.data.data;
    }

    const getContact = async (id) => {
        let response = await axios.get('/api/contacts/' + id)
        contact.value = response.data.data;
        contact.value.addresses = contact.value.address;
        contact.value.phone_numbers = contact.value.phone_number;
        delete contact.value.address;
        delete contact.value.phone_number;
    }

    const storeContact = async (data) => {
        errors.value = ''
        axios.post('/api/contacts/', data)
            .then(function(res){
                if (res.data.status === "success"){
                    notification.notify({
                        title: "Saved",
                        text: res.data.message,
                    });

                    router.push({name: 'contacts.index'})
                }else{
                    notification.notify({
                        title: "Error",
                        text: res.data.message,
                    });
                }
            })
            .catch(function(error){
                console.log(error);
                errors.value = error.response.data.errors
            });
    }

    const updateContact = async (id) => {
        errors.value = ''
        axios.put('/api/contacts/' + id, contact.value)
            .then(function(res){
                if (res.data.status === "success"){
                    notification.notify({
                        title: "Updated",
                        text: res.data.message,
                    });

                    router.push({name: 'contacts.index'})
                }else{
                    notification.notify({
                        title: "Error",
                        text: res.data.message,
                    });
                }
            })
            .catch(function(error){
                console.log(error);
                errors.value = error.response.data.errors
            });
    }

    const destroyContact = async (id) => {
        await axios.delete('/api/contacts/' + id)
    }


    return {
        contacts,
        contact,
        errors,
        phoneTypes,
        getContacts,
        getContact,
        storeContact,
        updateContact,
        destroyContact,
        getPhoneTypes
    }
}
