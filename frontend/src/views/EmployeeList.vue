<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';
import { useRouter } from 'vue-router';
import AdminLayout from '../components/AdminLayout.vue'

const router = useRouter();
const employees = ref([]);
const selectedEmployee = ref(null);

const fetchEmployees = async () => {
    try {
        const response = await api.get("/employees");
        employees.value = response.data.data;
    } catch (error) {
        console.error("Error fetching employees:", error);
    }
};

const deleteEmployee = async (id) => {
    if (!confirm("Apakah Anda yakin ingin menghapus pegawai ini?")) return;

    try {
        await api.delete(`/employees/${id}`);
        fetchEmployees();
    } catch (error) {
        console.error("Error deleting employee:", error);
    }
};

onMounted(fetchEmployees);
</script>

<template>
    <AdminLayout>
        <div class="p-6 rounded-lg shadow-md">
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-6">List Employees</h2>
            <button @click="router.push('/employees/new')" class="bg-blue-600 text-white px-4 py-2 rounded-md mb-4 hover:bg-blue-700 transition">+ Add Employee</button>
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Name</p>
                                </th>
                                <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Action</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="employee in employees" :key="employee.id" class="border-t border-gray-100 dark:border-gray-800">
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ employee.name }}</p>
                                </td>
                                <td class="p-3 space-x-2">
                                    <button @click="router.push(`/employees/edit/${employee.id}`)" class="bg-yellow-500 w-full sm:w-auto mb-1 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition">Edit</button>
                                    <button @click="deleteEmployee(employee.id)" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
