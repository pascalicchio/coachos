<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Locations</h1>
      <button
        @click="showAddModal = true"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
      >
        Add Location
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="location in locations"
        :key="location.id"
        class="bg-white rounded-lg shadow p-6"
      >
        <div class="flex justify-between items-start mb-4">
          <div>
            <h3 class="text-lg font-semibold">{{ location.name }}</h3>
            <p class="text-gray-500 text-sm">{{ location.phone }}</p>
          </div>
          <span
            :class="location.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
            class="px-2 py-1 rounded text-xs font-medium"
          >
            {{ location.is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
        
        <div class="text-gray-600 text-sm mb-4">
          <p v-if="location.address">{{ location.address }}</p>
          <p v-if="location.city || location.state">
            {{ location.city }}{{ location.city && location.state ? ', ' : '' }} {{ location.state }} {{ location.zip }}
          </p>
          <p v-if="location.email">{{ location.email }}</p>
        </div>

        <div v-if="location.opening_time || location.closing_time" class="text-sm text-gray-500 mb-4">
          <span v-if="location.opening_time">{{ location.opening_time }} - {{ location.closing_time }}</span>
        </div>

        <div class="flex gap-2">
          <button @click="editLocation(location)" class="text-blue-600 hover:text-blue-900 text-sm">
            Edit
          </button>
          <button @click="deleteLocation(location.id)" class="text-red-600 hover:text-red-900 text-sm">
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ editingLocation ? 'Edit Location' : 'Add Location' }}</h2>
        <form @submit.prevent="saveLocation">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" required />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
            <input v-model="form.address" type="text" class="w-full border rounded px-3 py-2" />
          </div>
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">City</label>
              <input v-model="form.city" type="text" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">State</label>
              <input v-model="form.state" type="text" class="w-full border rounded px-3 py-2" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">ZIP</label>
              <input v-model="form.zip" type="text" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
              <input v-model="form.phone" type="text" class="w-full border rounded px-3 py-2" />
            </div>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
          </div>
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">Opening Time</label>
              <input v-model="form.opening_time" type="time" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">Closing Time</label>
              <input v-model="form.closing_time" type="time" class="w-full border rounded px-3 py-2" />
            </div>
          </div>
          <div class="flex justify-end gap-3">
            <button type="button" @click="closeModal" class="px-4 py-2 text-gray-600">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()
const locations = props.locations || []

const showAddModal = ref(false)
const editingLocation = ref(null)

const form = ref({
  name: '',
  address: '',
  city: '',
  state: '',
  zip: '',
  phone: '',
  email: '',
  opening_time: '',
  closing_time: '',
})

const editLocation = (location) => {
  editingLocation.value = location
  form.value = { ...location }
  showAddModal.value = true
}

const closeModal = () => {
  showAddModal.value = false
  editingLocation.value = null
  form.value = {
    name: '',
    address: '',
    city: '',
    state: '',
    zip: '',
    phone: '',
    email: '',
    opening_time: '',
    closing_time: '',
  }
}

const saveLocation = () => {
  closeModal()
}

const deleteLocation = (id) => {
  if (confirm('Are you sure you want to delete this location?')) {
    // Would use Inertia.delete(`/locations/${id}`)
  }
}
</script>
