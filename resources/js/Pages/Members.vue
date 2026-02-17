<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Members</h1>
      <div class="flex gap-2">
        <a href="/export/members" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
          Export CSV
        </a>
        <button @click="showAddModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
          Add Member
        </button>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-green-50 p-4 rounded-lg">
        <div class="text-2xl font-bold text-green-600">{{ activeCount }}</div>
        <div class="text-gray-600">Active</div>
      </div>
      <div class="bg-yellow-50 p-4 rounded-lg">
        <div class="text-2xl font-bold text-yellow-600">{{ frozenCount }}</div>
        <div class="text-gray-600">Frozen</div>
      </div>
      <div class="bg-red-50 p-4 rounded-lg">
        <div class="text-2xl font-bold text-red-600">{{ expiredCount }}</div>
        <div class="text-gray-600">Expired</div>
      </div>
      <div class="bg-gray-50 p-4 rounded-lg">
        <div class="text-2xl font-bold text-gray-600">{{ members.length }}</div>
        <div class="text-gray-600">Total</div>
      </div>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Membership</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="member in members" :key="member.id">
            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ member.name }}</td>
            <td class="px-6 py-4">
              <div>{{ member.email }}</div>
              <div class="text-gray-500 text-sm">{{ member.phone }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ member.membership?.name || '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="isExpired(member) ? 'text-red-600' : 'text-gray-600'">
                {{ member.expiry_date || 'No expiry' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="statusClass(member.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                {{ member.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button v-if="member.status === 'active'" @click="freezeMember(member.id)" class="text-yellow-600 hover:text-yellow-900 mr-3">Freeze</button>
              <button v-if="member.status !== 'cancelled'" @click="cancelMember(member.id)" class="text-red-600 hover:text-red-900">Cancel</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Add Member</h2>
        <form @submit.prevent="saveMember">
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
            <label class="block text-gray-700 text-sm font-bold mb-2">Expiry Date</label>
            <input v-model="form.expiry_date" type="date" class="w-full border rounded px-3 py-2" />
          </div>
          <div class="flex justify-end gap-3">
            <button type="button" @click="showAddModal = false" class="px-4 py-2 text-gray-600">Cancel</button>
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

const { props } = usePage()
const members = props.members || []

const showAddModal = ref(false)
const form = ref({ name: '', email: '', phone: '', expiry_date: '' })

const activeCount = computed(() => members.filter(m => m.status === 'active').length)
const frozenCount = computed(() => members.filter(m => m.status === 'frozen').length)
const expiredCount = computed(() => members.filter(m => m.status === 'expired').length)

const isExpired = (member) => member.expiry_date && new Date(member.expiry_date) < new Date()

const statusClass = (status) => ({
  'bg-green-100 text-green-800': status === 'active',
  'bg-yellow-100 text-yellow-800': status === 'frozen',
  'bg-red-100 text-red-800': status === 'expired' || status === 'cancelled',
})

const saveMember = () => { showAddModal.value = false }
const freezeMember = (id) => {}
const cancelMember = (id) => {}
</script>
