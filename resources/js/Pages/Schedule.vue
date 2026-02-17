<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Schedule Management</h1>
      <div class="flex gap-2">
        <button @click="showGenerateModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
          Generate Schedule
        </button>
        <button @click="showTemplateModal = true" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">
          Add Class Template
        </button>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-neutral-800 p-4 rounded-lg shadow">
        <div class="text-neutral-500 text-sm">Class Templates</div>
        <div class="text-2xl font-bold">{{ templates.length }}</div>
      </div>
      <div class="bg-white dark:bg-neutral-800 p-4 rounded-lg shadow">
        <div class="text-neutral-500 text-sm">Scheduled This Week</div>
        <div class="text-2xl font-bold">{{ scheduledClasses.length }}</div>
      </div>
      <div class="bg-white dark:bg-neutral-800 p-4 rounded-lg shadow">
        <div class="text-neutral-500 text-sm">Active Coaches</div>
        <div class="text-2xl font-bold">{{ coachesCount }}</div>
      </div>
      <div class="bg-white dark:bg-neutral-800 p-4 rounded-lg shadow">
        <div class="text-neutral-500 text-sm">Total Classes</div>
        <div class="text-2xl font-bold">{{ totalClasses }}</div>
      </div>
    </div>

    <!-- Templates -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow mb-6">
      <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
        <h2 class="text-lg font-semibold">Class Templates</h2>
      </div>
      <div class="p-4">
        <table class="w-full">
          <thead>
            <tr class="text-left text-neutral-500 text-sm">
              <th class="pb-2">Class</th>
              <th class="pb-2">Day</th>
              <th class="pb-2">Time</th>
              <th class="pb-2">Coach</th>
              <th class="pb-2">Style</th>
              <th class="pb-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="template in templates" :key="template.id" class="border-t border-neutral-100 dark:border-neutral-700">
              <td class="py-3">{{ template.class_name }}</td>
              <td class="py-3">{{ dayName(template.day_of_week) }}</td>
              <td class="py-3">{{ template.start_time }} - {{ template.end_time }}</td>
              <td class="py-3">{{ template.coach?.name || 'Unassigned' }}</td>
              <td class="py-3">{{ template.martial_art || '-' }}</td>
              <td class="py-3">
                <button @click="deleteTemplate(template.id)" class="text-red-600 hover:text-red-800">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="templates.length === 0" class="text-center py-8 text-neutral-500">
          No class templates yet. Create one to get started!
        </div>
      </div>
    </div>

    <!-- Scheduled Classes -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow">
      <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
        <h2 class="text-lg font-semibold">Upcoming Classes</h2>
      </div>
      <div class="p-4">
        <div v-for="cls in scheduledClasses.slice(0, 10)" :key="cls.id" class="flex justify-between items-center py-3 border-b border-neutral-100 dark:border-neutral-700">
          <div>
            <div class="font-medium">{{ cls.class_name }}</div>
            <div class="text-sm text-neutral-500">
              {{ formatDate(cls.start_time) }} • {{ cls.coach?.name || 'TBD' }}
            </div>
          </div>
          <div class="text-sm text-neutral-500">
            {{ cls.attendance_count }} students
          </div>
        </div>
        <div v-if="scheduledClasses.length === 0" class="text-center py-8 text-neutral-500">
          No classes scheduled. Generate a schedule!
        </div>
      </div>
    </div>

    <!-- Generate Modal -->
    <div v-if="showGenerateModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg w-96">
        <h3 class="text-lg font-bold mb-4">Generate Schedule</h3>
        <label class="block mb-4">
          <span class="text-sm text-neutral-600">Number of weeks</span>
          <input v-model="generateWeeks" type="number" min="1" max="12" class="w-full mt-1 p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600">
        </label>
        <div class="flex gap-2 justify-end">
          <button @click="showGenerateModal = false" class="px-4 py-2 text-neutral-600">Cancel</button>
          <button @click="generateSchedule" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Generate
          </button>
        </div>
      </div>
    </div>

    <!-- Template Modal -->
    <div v-if="showTemplateModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg w-96">
        <h3 class="text-lg font-bold mb-4">Add Class Template</h3>
        <form @submit.prevent="createTemplate" class="space-y-4">
          <input v-model="newTemplate.class_name" placeholder="Class Name" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
          <select v-model="newTemplate.day_of_week" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
            <option value="">Select Day</option>
            <option v-for="(day, idx) in days" :key="idx" :value="idx">{{ day }}</option>
          </select>
          <div class="flex gap-2">
            <input v-model="newTemplate.start_time" type="time" class="w-1/2 p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
            <input v-model="newTemplate.end_time" type="time" class="w-1/2 p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
          </div>
          <select v-model="newTemplate.coach_id" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600">
            <option value="">Select Coach (optional)</option>
            <option v-for="coach in coaches" :key="coach.id" :value="coach.id">{{ coach.name }}</option>
          </select>
          <input v-model="newTemplate.martial_art" placeholder="Style (Jiu-Jitsu, MMA, etc.)" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600">
          <div class="flex gap-2 justify-end">
            <button type="button" @click="showTemplateModal = false" class="px-4 py-2 text-neutral-600">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  templates: Array,
  scheduledClasses: Array,
})

const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

const showGenerateModal = ref(false)
const showTemplateModal = ref(false)
const generateWeeks = ref(4)

const newTemplate = ref({
  class_name: '',
  day_of_week: '',
  start_time: '',
  end_time: '',
  coach_id: '',
  martial_art: '',
})

const coaches = computed(() => {
  const c = new Set()
  props.templates.forEach(t => { if (t.coach) c.add(JSON.stringify(t.coach)) })
  return [...c].map(s => JSON.parse(s))
})

const coachesCount = computed(() => coaches.value.length)
const totalClasses = computed(() => props.templates.length * 7)

const dayName = (idx) => days[idx] || 'Unknown'

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleString('en-US', { 
    weekday: 'short', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit'
  })
}

const generateSchedule = () => {
  Inertia.post('/schedule/generate', { weeks: generateWeeks.value }, {
    onSuccess: () => { showGenerateModal.value = false }
  })
}

const createTemplate = () => {
  Inertia.post('/schedule/template', newTemplate.value, {
    onSuccess: () => {
      showTemplateModal.value = false
      newTemplate.value = { class_name: '', day_of_week: '', start_time: '', end_time: '', coach_id: '', martial_art: '' }
    }
  })
}

const deleteTemplate = (id) => {
  if (confirm('Delete this template?')) {
    Inertia.delete(`/schedule/template/${id}`)
  }
}
</script>
