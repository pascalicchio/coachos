<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Reports & Analytics</h1>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-green-50 p-6 rounded-lg">
        <div class="text-green-600 text-sm">Revenue This Month</div>
        <div class="text-3xl font-bold text-green-700">${{ overview.revenue?.this_month || 0 }}</div>
      </div>
      <div class="bg-blue-50 p-6 rounded-lg">
        <div class="text-blue-600 text-sm">Active Members</div>
        <div class="text-3xl font-bold text-blue-700">{{ overview.members?.active || 0 }}</div>
      </div>
      <div class="bg-purple-50 p-6 rounded-lg">
        <div class="text-purple-600 text-sm">New Members This Month</div>
        <div class="text-3xl font-bold text-purple-700">{{ overview.members?.new_this_month || 0 }}</div>
      </div>
      <div class="bg-orange-50 p-6 rounded-lg">
        <div class="text-orange-600 text-sm">Leads Converted</div>
        <div class="text-3xl font-bold text-orange-700">{{ overview.leads?.converted || 0 }}</div>
      </div>
    </div>

    <!-- Charts Placeholders -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Revenue Trend</h3>
        <div class="h-64 bg-gray-50 rounded flex items-center justify-center">
          <span class="text-gray-400">Chart coming soon</span>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Member Growth</h3>
        <div class="h-64 bg-gray-50 rounded flex items-center justify-center">
          <span class="text-gray-400">Chart coming soon</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const overview = ref({
  revenue: {},
  members: {},
  leads: {},
})
</script>
