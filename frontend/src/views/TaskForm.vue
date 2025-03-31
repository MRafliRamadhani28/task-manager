<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';
import { useRouter, useRoute } from 'vue-router';
import AdminLayout from '../components/AdminLayout.vue'

const router = useRouter();
const route = useRoute();
const isEdit = ref(false);
const allEmployees = ref([]);
const errors = ref({});

const task = ref({
    employee_name: '',
    task_description: '',
    hours_spent: 0,
    hourly_rate: 0,
    additional_charges: 0,
    employees: [],
});

const fetchTask = async () => {
    try {
        if (route.params.id) {
            isEdit.value = true;

            const taskResponse = await api.get(`/tasks/${route.params.id}`);
            task.value = taskResponse.data.data;
        }

        const employeeResponse = await api.get(`/employees`);
        allEmployees.value = employeeResponse.data.data;

    } catch (error) {
        console.error("Error fetching data:", error);
    }
};

const filteredEmployees = (index) => {
  const selectedIds = task.value.employees.map((emp) => emp.id);
  return allEmployees.value.filter((emp) => 
    !selectedIds.includes(emp.id) || task.value.employees[index].id === emp.id
  );
};

const addEmployee = () => {
    if (allEmployees.value.length > task.value.employees.length) {
    task.value.employees.push({ id: "", hours_spent: 0 });
  }
};

const removeEmployee = (index) => {
  task.value.employees.splice(index, 1);
};

const saveTask = async () => {
    try {
        errors.value = {};

        if (isEdit.value) {
            await api.put(`/tasks/${route.params.id}`, task.value);
        } else {
            await api.post('/tasks', task.value);
        }
        router.push('/');
    } catch (error) {
        if (error.response && error.response.status === 422) {
           errors.value = error.response.data.errors;
        } else {
            console.error("Error saving task:", error);
        }
    }
};

onMounted(fetchTask);
</script>

<template>
    <AdminLayout>
        <div class="p-6 rounded-lg shadow-md">
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-6">List Task</h2>

            <div class='rounded-2xl w-full sm:w-1/2 border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]'>
                <div class="px-6 py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ isEdit ? 'Edit Task' : 'Add Task' }}</h3>
                </div>

                <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <div class="space-y-5">
                        <div class="space-y-6">
                            <form @submit.prevent="saveTask" class="space-y-5 grid grid-cols-1 gap-4">
                                <div>
                                    <div class="mb-3">
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Description:</label>
                                        <textarea v-model="task.task_description" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" rows="3"></textarea>
                                        <span v-if="errors.task_description" class="text-red-500 text-sm">{{ errors.task_description[0] }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="mb-3">
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Hourly Rate:</label>
                                            <input type="number" v-model="task.hourly_rate" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" required/>
                                            <span v-if="errors.hourly_rate" class="text-red-500 text-sm">{{ errors.hourly_rate[0] }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Additional Charges:</label>
                                            <input type="number" v-model="task.additional_charges" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" required/>
                                            <span v-if="errors.additional_charges" class="text-red-500 text-sm">{{ errors.additional_charges[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-4 block text-sm font-medium text-gray-700 dark:text-gray-400">Employee Name</label>
                                    <div v-for="(employee, index) in task.employees" :key="index" class="flex space-x-2 mb-2">
                                        <div class="grid grid-cols-1 flex-1 relative z-20 bg-transparent">
                                            <select v-model="employee.id" class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                            :class="{ 'text-gray-800 dark:text-white/90': employee.id }">
                                                <option value="" disabled>Select Employee</option>
                                                <option v-for="emp in filteredEmployees(index)" :key="emp.id" :value="emp.id" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                    {{ emp.name }}
                                                </option>
                                            </select>
                                            <span class="absolute z-30 text-gray-700 -translate-y-1/2 pointer-events-none right-4 top-1/2 dark:text-gray-400">
                                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                            <span v-if="errors[`employees.${index}.id`]" class="text-red-500 text-sm">Please select an employee</span>
                                        </div>

                                        <div class="grid grid-cols-1 w-12 md:w-40">
                                            <input v-model="employee.hours_spent" type="number" placeholder="Hours Spent" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                            <span v-if="errors[`employees.${index}.hours_spent`]" class="text-red-500 text-sm">Please enter hours spent</span>
                                        </div>

                                        <button type="button" @click="removeEmployee(index)" class="px-3 py-1 bg-red-500 text-white rounded">Hapus</button>
                                    </div>

                                    <button type="button" v-if="task.employees.length < allEmployees.length" @click="addEmployee" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Add Employee</button>
                                </div>

                                <div class="flex space-x-3">
                                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">{{ isEdit ? "Save Changes" : "Save" }}</button>
                                    <button type="button" @click="router.push('/')" class="flex-1 bg-error-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>