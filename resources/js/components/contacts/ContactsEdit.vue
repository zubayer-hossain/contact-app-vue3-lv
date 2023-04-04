<template>
    <div class="mb-5">
      <h4>Edit Contact Form</h4>
    </div>

    <div v-if="errors">
        <div v-for="(v, k) in errors" :key="k" class="bg-red-400 text-white rounded font-bold mb-4 shadow-lg py-2 px-4 pr-0">
            <p v-for="error in v" :key="error" class="text-sm">
                {{ error }}
            </p>
        </div>
    </div>

    <form class="space-y-6" v-on:submit.prevent="saveContact">
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <div class="mt-1">
            <input type="text" name="name" id="name"
                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   v-model="contact.name">
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <div class="mt-1">
            <input type="text" name="email" id="email"
                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   v-model="contact.email">
          </div>
        </div>
      </div>

      <hr>

      <div class="float-left">
        <span>Add Phone Numbers</span>
      </div>
      <div class="flex place-content-end mb-4">
        <div class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-md cursor-pointer">
          <button type="button" class="text-sm font-medium" @click="addPhoneNumber()">Add</button>
        </div>
      </div>
      <template v-for="(phone_number, index) in contact.phone_numbers" :key="phone_number.id">
        <div class="grid grid-cols-3 gap-3">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Type</label>
            <div class="mt-1">
              <select v-model="phone_number.phone_type_id" class="form-select block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                <option v-for="option in phoneTypes" :value="option.id">
                  {{ option.type }}
                </option>
              </select>
            </div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <div class="mt-1">
              <input type="text" name="phone_number" id="phone_number"
                     class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                     v-model="phone_number.phone_number">
            </div>
          </div>

          <div class="">
            <label for="">&nbsp;</label>
            <div class="cursor-pointer">
              <a type="button" class="text-sm font-medium text-red-700"  @click="deletePhone(index)">Remove</a>
            </div>
          </div>
        </div>
      </template>

      <hr>

      <div class="float-left">
        <span>Add Address</span>
      </div>
      <div class="flex place-content-end mb-4">
        <div class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-md cursor-pointer">
          <button type="button" class="text-sm font-medium" @click="addAddress()">Add</button>
        </div>
      </div>
      <template v-for="(address, index) in contact.addresses" :key="address.id">
        <div class="grid grid-cols-3 gap-3">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Address Line</label>
            <div class="mt-1">
              <input type="text" name="address_line" id="address_line"
                     class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                     v-model="address.address_line">
            </div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Pincode</label>
            <div class="mt-1">
              <input type="text" name="pincode" id="pincode"
                     class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                     v-model="address.pincode">
            </div>
          </div>

          <div class="">
            <label for="">&nbsp;</label>
            <div class="cursor-pointer">
              <a type="button" class="text-sm font-medium text-red-700"  @click="deleteAddress(index)">Remove</a>
            </div>
          </div>
        </div>
      </template>

      <button type="submit"
              class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 rounded-md border border-transparent ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring disabled:opacity-25">
        Save Contact
      </button>
    </form>
</template>

<script setup>
import useContacts  from "@/composables/contacts";
import { onMounted } from "vue";

const { errors, contact, phoneTypes, getContact, updateContact, getPhoneTypes } = useContacts()
const props = defineProps({
    id: {
        required: true,
        type: String
    }
})

onMounted(() => {
  getPhoneTypes();
  getContact(props.id);
})

const saveContact = async () => {
    await updateContact(props.id)
}

const addPhoneNumber = async () => {
  contact.value.phone_numbers.push({
    phone_type_id : '',
    phone_number : ''
  })
}

const deletePhone = async (index) => {
  contact.value.phone_numbers.splice(index, 1)
}

const addAddress = async () => {
  contact.value.addresses.push({
    address_line : '',
    pincode : ''
  })
}

const deleteAddress = async (index) => {
  contact.value.addresses.splice(index, 1)
}
</script>
