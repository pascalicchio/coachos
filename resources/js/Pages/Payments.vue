<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Payments</h1>
      <button @click="showModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Record Payment
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-emerald-50 dark:bg-emerald-900/50 p-4 rounded-lg border border-emerald-200 dark:border-emerald-800">
        <div class="text-emerald-600 dark:text-emerald-400 text-sm">Total Revenue</div>
        <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-300">${{ stats.total_revenue }}</div>
      </div>
      <div class="bg-amber-50 dark:bg-amber-900/50 p-4 rounded-lg border border-amber-200 dark:border-amber-800">
        <div class="text-amber-600 dark:text-amber-400 text-sm">Pending</div>
        <div class="text-2xl font-bold text-amber-700 dark:text-amber-300">${{ stats.pending }}</div>
      </div>
      <div class="bg-blue-50 dark:bg-blue-900/50 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
        <div class="text-blue-600 dark:text-blue-400 text-sm">This Month</div>
        <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">${{ stats.this_month }}</div>
      </div>
    </div>

    <!-- Payments List -->
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow">
      <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
        <div class="flex gap-2">
          <select v-model="filterStatus" class="p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600 text-sm">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
          </select>
          <select v-model="filterType" class="p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600 text-sm">
            <option value="">All Types</option>
            <option value="membership">Membership</option>
            <option value="private_class">Private Class</option>
            <option value="drop_in">Drop-in</option>
            <option value="expense">Expense</option>
          </select>
        </div>
      </div>
      <div class="p-4">
        <table class="w-full">
          <thead>
            <tr class="text-left text-neutral-500 text-sm">
              <th class="pb-2">Date</th>
              <th class="pb-2">Student</th>
              <th class="pb-2">Type</th>
              <th class="pb-2">Amount</th>
              <th class="pb-2">Status</th>
              <th class="pb-2">Method</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="payment in payments.data" :key="payment.id" class="border-t border-neutral-100 dark:border-neutral-700">
              <td class="py-3 text-sm">{{ formatDate(payment.payment_date) }}</td>
              <td class="py-3">{{ payment.user?.name }}</td>
              <td class="py-3 capitalize">{{ payment.payment_type?.replace('_', ' ') }}</td>
              <td class="py-3 font-medium">${{ payment.amount }}</td>
              <td class="py-3">
                <span :class="statusClass(payment.status)" class="px-2 py-1 rounded text-xs">
                  {{ payment.status }}
                </span>
              </td>
              <td class="py-3 text-sm text-neutral-500">{{ payment.payment_method || '-' }}</td>
            </tr>
          </tbody>
        </table>
        <div v-if="payments.data?.length === 0" class="text-center py-8 text-neutral-500">
          No payments found
        </div>
      </div>
    </div>

    <!-- Add Payment Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg w-96">
        <h3 class="text-lg font-bold mb-4">Record Payment</h3>
        <form @submit.prevent="createPayment" class="space-y-4">
          <select v-model="newPayment.user_id" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
            <option value="">Select Student</option>
            <option v-for="s in students" :key="s.id" :value="s.id">{{ s.name }}</option>
          </select>
          <input v-model="newPayment.amount" type="number" step="0.01" placeholder="Amount" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
          <select v-model="newPayment.payment_type" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
            <option value="membership">Membership</option>
            <option value="private_class">Private Class</option>
            <option value="drop_in">Drop-in</option>
            <option value="expense">Expense</option>
          </select>
          <select v-model="newPayment.status" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
          </select>
          <input v-model="newPayment.payment_date" type="date" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600" required>
          <select v-model="newPayment.payment_method" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600">
            <option value="">Payment Method</option>
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="stripe">Stripe</option>
            <option value="other">Other</option>
          </select>
          <input v-model="newPayment.description" placeholder="Description (optional)" class="w-full p-2 border rounded dark:bg-neutral-700 dark:border-neutral-600">
          <div class="flex gap-2 justify-end">
            <button type="button" @click="showModal = false" class="px-4 py-2 text-neutral-600">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
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
  payments: Object,
  students: Array,
  stats: Object,
})

const showModal = ref(false)
const filterStatus = ref('')
const filterType = ref('')

const newPayment = ref({
  user_id: '',
  amount: '',
  payment_type: 'membership',
  status: 'completed',
  payment_date: new Date().toISOString().split('T')[0],
  payment_method: '',
  description: '',
})

const students = props.students || []

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

const statusClass = (status) => {
  const classes = {
    completed: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
    pending: 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
    failed: 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
    refunded: 'bg-neutral-100 text-neutral-700 dark:bg-neutral-700 dark:text-neutral-300',
  }
  return classes[status] || ''
}

const createPayment = () => {
  Inertia.post('/payments', newPayment.value, {
    onSuccess: () => {
      showModal.value = false
      newPayment.value = {
        user_id: '',
        amount: '',
        payment_type: 'membership',
        status: 'completed',
        payment_date: new Date().toISOString().split('T')[0],
        payment_method: '',
        description: '',
      }
    }
  })
}

watch([filterStatus, filterType], () => {
  Inertia.get('/payments', { status: filterStatus.value, type: filterType.value }, { preserveState: true })
})
</script>
