<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Private Classes</h1>
      <button @click="showModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Schedule Private Class
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-neutral-800 p-4 rounded-lg shadow">
        <div class="text-neutral-500 text-sm">Total</div>
        <div class="text-2xl font-bold">{{ stats.total }}</div>
      </div>
      <div class="bg-blue-50 dark:bg-blue-900/50 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
        <div class="text-blue-600 dark:text-blue-400 text-sm">Scheduled</div>
        <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ stats.scheduled }}</div>
      </div>
      <div class="bg-emerald-50 dark:bg-emerald-900/50 p-4 rounded-lg border border-emerald-200 dark:border-emerald-800">
        <div class="text-emerald-600 dark:text-emerald-400 text-sm">Completed</div>
        <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-300">{{ stats.completed }}</div>
      </div>
      <div class="bg-amber-50 dark:bg-amber-900/50 p-4 rounded-lg border border-amber-200 dark:border-amber-800">
        <div class="text-amber-600 dark:text-amber-400 text-sm">Revenue</div>
        <div class="text-2xl font-bold text-amber-700 dark:text-amber-300">${{ stats.revenue }}</div>
      </div>
    </div>

    <!-- Classes List -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow">
      <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
        <select v-model="filterStatus" class="p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600 text-sm">
          <option value="">All Status</option>
          <option value="scheduled">Scheduled</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
          <option value="no_show">No Show</option>
        </select>
      </div>
      <div class="p-4">
        <div v-for="cls in classes.data" :key="cls.id" class="flex justify-between items-center py-4 border-b border-neutral-100 dark:border-neutral-700">
          <div class="flex-1">
            <div class="font-medium">{{ cls.student?.name }} with {{ cls.coach?.name }}</div>
            <div class="text-sm text-neutral-500">
              {{ formatDate(cls.scheduled_at) }} • {{ cls.duration_minutes }} min
            </div>
            <div v-if="cls.notes" class="text-sm text-neutral-400 mt-1">{{ cls.notes }}</div>
          </div>
          <div class="text-right">
            <div class="font-bold text-lg">${{ cls.price }}</div>
            <span :class="statusClass(cls.status)" class="px-2 py-1 rounded text-xs">
              {{ cls.status }}
            </span>
            <div class="flex gap-2 mt-2 justify-end">
              <button v-if="cls.status === 'scheduled'" @click="completeClass(cls.id)" class="text-sm text-emerald-600 hover:text-emerald-800">Complete</button>
              <button v-if="cls.status === 'scheduled'" @click="cancelClass(cls.id)" class="text-sm text-red-600 hover:text-red-800">Cancel</button>
            </div>
          </div>
        </div>
        <div v-if="!classes.data?.length" class="text-center py-8 text-neutral-500">
          No private classes scheduled
        </div>
      </div>
    </div>

    <!-- Add Private Class Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg w-96">
        <h3 class="text-lg font-bold mb-4">Schedule Private Class</h3>
        <form @submit.prevent="createClass" class="space-y-4">
          <select v-model="newClass.student_id" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
            <option value="">Select Student</option>
            <option v-for="s in students" :key="s.id" :value="s.id">{{ s.name }}</option>
          </select>
          <select v-model="newClass.coach_id" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
            <option value="">Select Coach</option>
            <option v-for="c in coaches" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
          <input v-model="newClass.scheduled_at" type="datetime-local" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
          <div class="flex gap-2">
            <select v-model="newClass.duration_minutes" class="w-1/2 p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
              <option value="30">30 min</option>
              <option value="60">60 min</option>
              <option value="90">90 min</option>
            </select>
            <input v-model="newClass.price" type="number" step="0.01" placeholder="Price" class="w-1/2 p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
          </div>
          <textarea v-model="newClass.notes" placeholder="Notes (optional)" rows="2" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600"></textarea>
          <div class="flex gap-2 justify-end">
            <button type="button" @click="showModal = false" class="px-4 py-2 text-neutral-600">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Schedule</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  classes: Object,
  students: Array,
  coaches: Array,
  stats: Object,
})

const showModal = ref(false)
const filterStatus = ref('')

const students = props.students || []
const coaches = props.coaches || []

const newClass = ref({
  student_id: '',
  coach_id: '',
  scheduled_at: '',
  duration_minutes: 60,
  price: '',
  notes: '',
})

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleString('en-US', { 
    weekday: 'short', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit'
  })
}

const statusClass = (status) => {
  const classes = {
    scheduled: 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
    completed: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
    cancelled: 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
    no_show: 'bg-neutral-100 text-neutral-700 dark:bg-neutral-700 dark:text-neutral-300',
  }
  return classes[status] || ''
}

const createClass = () => {
  Inertia.post('/private-classes', newClass.value, {
    onSuccess: () => {
      showModal.value = false
      newClass.value = { student_id: '', coach_id: '', scheduled_at: '', duration_minutes: 60, price: '', notes: '' }
    }
  })
}

const completeClass = (id) => {
  Inertia.put(`/private-classes/${id}`, { status: 'completed' })
}

const cancelClass = (id) => {
  if (confirm('Cancel this class?')) {
    Inertia.put(`/private-classes/${id}`, { status: 'cancelled' })
  }
}

watch(filterStatus, () => {
  Inertia.get('/private-classes', { status: filterStatus.value }, { preserveState: true })
})
</script>
