<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue'

const props = defineProps({
  auth: Object,
})

const stats = computed(() => ({
  members: 24,
  leads: 8,
  coaches: 5,
  classesThisWeek: 18,
  revenue: 4250,
}))
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Welcome -->
                <div class="mb-8 overflow-hidden bg-gradient-to-r from-blue-600 to-blue-700 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-white">
                        <h1 class="text-2xl font-bold">Welcome back, {{ auth.user?.name || 'Coach' }}!</h1>
                        <p class="text-blue-100 mt-1">Here's what's happening at your gym today.</p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg shadow-sm">
                        <div class="text-neutral-500 text-sm">Active Members</div>
                        <div class="text-3xl font-bold text-blue-600">{{ stats.members }}</div>
                    </div>
                    <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg shadow-sm">
                        <div class="text-neutral-500 text-sm">New Leads</div>
                        <div class="text-3xl font-bold text-orange-600">8</div>
                    </div>
                    <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg shadow-sm">
                        <div class="text-neutral-500 text-sm">Coaches</div>
                        <div class="text-3xl font-bold text-emerald-600">{{ stats.coaches }}</div>
                    </div>
                    <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg shadow-sm">
                        <div class="text-neutral-500 text-sm">Classes This Week</div>
                        <div class="text-3xl font-bold text-purple-600">{{ stats.classesThisWeek }}</div>
                    </div>
                    <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg shadow-sm">
                        <div class="text-neutral-500 text-sm">Monthly Revenue</div>
                        <div class="text-3xl font-bold text-amber-600">${{ stats.revenue }}</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <a href="/schedule" class="block p-6 bg-white dark:bg-neutral-800 rounded-lg shadow-sm hover:shadow-md transition">
                        <div class="text-lg font-semibold mb-2">📅 Schedule</div>
                        <div class="text-neutral-500 text-sm">Class templates</div>
                    </a>
                    <a href="/locations" class="block p-6 bg-white dark:bg-neutral-800 rounded-lg shadow-sm hover:shadow-md transition">
                        <div class="text-lg font-semibold mb-2">📍 Locations</div>
                        <div class="text-neutral-500 text-sm">Manage gyms</div>
                    </a>
                    <a href="/leads" class="block p-6 bg-white dark:bg-neutral-800 rounded-lg shadow-sm hover:shadow-md transition">
                        <div class="text-lg font-semibold mb-2">🎯 Leads</div>
                        <div class="text-neutral-500 text-sm">Convert leads</div>
                    </a>
                    <a href="/payments" class="block p-6 bg-white dark:bg-neutral-800 rounded-lg shadow-sm hover:shadow-md transition">
                        <div class="text-lg font-semibold mb-2">💰 Payments</div>
                        <div class="text-neutral-500 text-sm">Revenue</div>
                    </a>
                    <a href="/private-classes" class="block p-6 bg-white dark:bg-neutral-800 rounded-lg shadow-sm hover:shadow-md transition">
                        <div class="text-lg font-semibold mb-2">🥋 Privates</div>
                        <div class="text-neutral-500 text-sm">1-on-1 training</div>
                    </a>
                    <a href="/members" class="block p-6 bg-white dark:bg-neutral-800 rounded-lg shadow-sm hover:shadow-md transition">
                        <div class="text-lg font-semibold mb-2">👥 Members</div>
                        <div class="text-neutral-500 text-sm">Manage members</div>
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
