<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Settings</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Organization -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Organization</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" :value="organization?.name" class="mt-1 w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Plan</label>
            <select class="mt-1 w-full border rounded px-3 py-2">
              <option>Starter - $19/mo</option>
              <option>Pro - $39/mo</option>
              <option>Business - $79/mo</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Notifications -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Notifications</h2>
        <div class="space-y-3">
          <label class="flex items-center">
            <input type="checkbox" class="mr-2" checked /> Email reminders for expiring memberships
          </label>
          <label class="flex items-center">
            <input type="checkbox" class="mr-2" checked /> SMS notifications for new leads
          </label>
          <label class="flex items-center">
            <input type="checkbox" class="mr-2" /> Daily attendance summary
          </label>
        </div>
      </div>

      <!-- Billing -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Billing</h2>
        <div class="space-y-2">
          <div class="flex justify-between">
            <span>Current Plan</span>
            <span class="font-semibold">Pro - $39/mo</span>
          </div>
          <div class="flex justify-between">
            <span>Next Billing Date</span>
            <span>March 17, 2026</span>
          </div>
          <button class="mt-4 w-full bg-gray-100 text-gray-700 py-2 rounded hover:bg-gray-200">
            Manage Subscription
          </button>
        </div>
      </div>

      <!-- Integrations -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Integrations</h2>
        <div class="space-y-3">
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
            <span>Stripe</span>
            <span class="text-green-600 text-sm">Connected</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
            <span>Google Calendar</span>
            <span class="text-gray-500 text-sm">Not connected</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
            <span>WhatsApp</span>
            <span class="text-gray-500 text-sm">Not connected</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const organization = ref({ name: 'My Gym' })
</script>
