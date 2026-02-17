<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Leads</h1>
      <button
        @click="showAddModal = true"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
      >
        Add Lead
      </button>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex gap-4">
      <select v-model="statusFilter" class="border rounded-lg px-4 py-2">
        <option value="">All Status</option>
        <option value="new">New</option>
        <option value="contacted">Contacted</option>
        <option value="scheduled">Scheduled</option>
        <option value="converted">Converted</option>
        <option value="lost">Lost</option>
      </select>
    </div>

    <!-- Leads Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="lead in filteredLeads" :key="lead.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ lead.name }}</td>
            <td class="px-6 py-4">
              <div>{{ lead.email }}</div>
              <div class="text-gray-500">{{ lead.phone }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ lead.source }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                :class="{
                  'bg-yellow-100 text-yellow-800': lead.status === 'new',
                  'bg-blue-100 text-blue-800': lead.status === 'contacted',
                  'bg-purple-100 text-purple-800': lead.status === 'scheduled',
                  'bg-green-100 text-green-800': lead.status === 'converted',
                  'bg-red-100 text-red-800': lead.status === 'lost',
                }"
                class="px-2 py-1 rounded-full text-xs font-medium"
              >
                {{ lead.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button
                v-if="lead.status !== 'converted'"
                @click="convertLead(lead.id)"
                class="text-green-600 hover:text-green-900 mr-3"
              >
                Convert
              </button>
              <button @click="editLead(lead)" class="text-blue-600 hover:text-blue-900 mr-3">
                Edit
              </button>
              <button @click="deleteLead(lead.id)" class="text-red-600 hover:text-red-900">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ editingLead ? 'Edit Lead' : 'Add Lead' }}</h2>
        <form @submit.prevent="saveLead">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" required />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
            <input v-model="form.phone" type="text" class="w-full border rounded px-3 py-2" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Source</label>
            <select v-model="form.source" class="w-full border rounded px-3 py-2">
              <option value="">Select source</option>
              <option value="instagram">Instagram</option>
              <option value="facebook">Facebook</option>
              <option value="referral">Referral</option>
              <option value="website">Website</option>
              <option value="walk-in">Walk-in</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
            <select v-model="form.status" class="w-full border rounded px-3 py-2">
              <option value="new">New</option>
              <option value="contacted">Contacted</option>
              <option value="scheduled">Scheduled</option>
              <option value="converted">Converted</option>
              <option value="lost">Lost</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
            <textarea v-model="form.notes" class="w-full border rounded px-3 py-2" rows="3"></textarea>
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
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const { leads } = usePage().props

const statusFilter = ref('')
const showAddModal = ref(false)
const editingLead = ref(null)

const form = ref({
  name: '',
  email: '',
  phone: '',
  source: '',
  status: 'new',
  notes: '',
})

const filteredLeads = computed(() => {
  if (!statusFilter.value) return leads
  return leads.filter(lead => lead.status === statusFilter.value)
})

const editLead = (lead) => {
  editingLead.value = lead
  form.value = { ...lead }
  showAddModal.value = true
}

const closeModal = () => {
  showAddModal.value = false
  editingLead.value = null
  form.value = {
    name: '',
    email: '',
    phone: '',
    source: '',
    status: 'new',
    notes: '',
  }
}

const saveLead = () => {
  const url = editingLead.value ? `/leads/${editingLead.value.id}` : '/leads'
  const method = editingLead.value ? 'put' : 'post'
  
  // Would use Inertia form submit here
  closeModal()
}

const convertLead = (id) => {
  if (confirm('Convert this lead to a member?')) {
    // Would use Inertia.post(`/leads/${id}/convert`)
  }
}

const deleteLead = (id) => {
  if (confirm('Are you sure you want to delete this lead?')) {
    // Would use Inertia.delete(`/leads/${id}`)
  }
}
</script>
