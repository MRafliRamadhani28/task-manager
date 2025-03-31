<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';
import { useRouter } from 'vue-router';
import AdminLayout from '../components/AdminLayout.vue'

const router = useRouter();
const tasks = ref([]);

const fetchTasks = async () => {
    try {
        const response = await api.get('/tasks');
        tasks.value = response.data.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
};

const deleteTask = async (id) => {
    if (confirm('Yakin ingin menghapus tugas ini?')) {
        try {
            await api.delete(`/tasks/${id}`);
            fetchTasks();
        } catch (error) {
            console.error('Error deleting task:', error);
        }
    }
};

onMounted(fetchTasks);
</script>

<template>
    <AdminLayout>
        <div class="p-6 rounded-lg shadow-md">
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-6">List Task</h2>
            <button @click="router.push('/tasks/new')" class="bg-blue-600 text-white px-4 py-2 rounded-md mb-4 hover:bg-blue-700 transition">+ Add Task</button>
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Task Description</p>
                                </th>
                                <th class="px-5 py-3 text-left w-2/20 sm:px-6">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Date</p>
                                </th>
                                <th class="px-5 py-3 text-left w-2/18 sm:px-6">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Hours Spent</p>
                                </th>
                                <th class="px-5 py-3 text-left w-2/18 sm:px-6">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Hourly Rate</p>
                                </th>
                                <th class="px-5 py-3 text-left w-2/15 sm:px-6">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Additional Charges</p>
                                </th>
                                <th class="px-5 py-3 text-left w-2/10 sm:px-6">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Total Remuneration</p>
                                </th>
                                <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Action</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="task in tasks" :key="task.id" class="border-t border-gray-100 dark:border-gray-800">
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ task.task_description }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ task.date }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ task.total_hours_spent }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $formatCurrency(task.hourly_rate) }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $formatCurrency(task.additional_charges) }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400 pb-3">({{ $formatCurrency(task.total_remuneration) }})</p>
                                    <div v-for="employee in task.employees" :key="employee.id" class="flex items-center gap-3" >
                                        <div>
                                            <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90"> - {{ employee.name }}</span>
                                            <span class="block text-gray-500 text-theme-xs dark:text-gray-400">{{ $formatCurrency(employee.remuneration) }} / {{ employee.hours_spent }} hr</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 space-x-2">
                                    <button @click="router.push(`/tasks/edit/${task.id}`)" class="bg-yellow-500 w-full sm:w-auto mb-1 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition">Edit</button>
                                    <button @click="deleteTask(task.id)" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>