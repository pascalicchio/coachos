<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Today's Attendance</h1>
      <div class="text-lg">Check-ins today: <span class="font-bold text-blue-600">{{ checkIns.length }}</span></div>
    </div>

    <!-- QR Scanner Placeholder -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white mb-6">
      <h2 class="text-xl font-bold mb-2">📱 QR Check-in</h2>
      <p class="opacity-90">Scan member QR code to check in instantly</p>
      <button class="mt-4 bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold">
        Launch Scanner
      </button>
    </div>

    <!-- Manual Check-in -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h3 class="text-lg font-bold mb-4">Manual Check-in</h3>
      <div class="flex gap-4">
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Search member name..."
          class="flex-1 border rounded-lg px-4 py-2"
        />
        <button @click="manualCheckIn" class="bg-green-600 text-white px-6 py-2 rounded-lg">
          Check In
        </button>
      </div>
    </div>

    <!-- Today's List -->
    <div class="bg-white rounded-lg shadow">
      <div class="p-4 border-b">
        <h3 class="font-bold">Checked In Today</h3>
      </div>
      <div v-if="checkIns.length === 0" class="p-8 text-center text-gray-500">
        No check-ins yet today
      </div>
      <div v-else class="divide-y">
        <div v-for="checkIn in checkIns" :key="checkIn.id" class="p-4 flex justify-between items-center">
          <div>
            <div class="font-medium">{{ checkIn.member?.name }}</div>
            <div class="text-sm text-gray-500">{{ checkIn.member?.membership?.name }}</div>
          </div>
          <div class="text-right">
            <div class="text-sm text-gray-500">{{ formatTime(checkIn.check_in_time) }}</div>
            <div class="text-xs text-gray-400">{{ checkIn.method }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const searchQuery = ref('')
const checkIns = ref([])

const manualCheckIn = () => {
  if (!searchQuery.value) return
  // Would call API to check in
  alert(`Checking in: ${searchQuery.value}`)
  searchQuery.value = ''
}

const formatTime = (time) => {
  return new Date(time).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
}
</script>
