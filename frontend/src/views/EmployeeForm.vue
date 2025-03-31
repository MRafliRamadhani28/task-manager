<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';
import { useRouter, useRoute } from 'vue-router';
import AdminLayout from '../components/AdminLayout.vue'

const router = useRouter();
const route = useRoute();
const isEdit = ref(false);
const errors = ref({});

const employeeForm = ref({name: ''});

const fetchEmployees = async () => {
    try {
        if (route.params.id) {
            isEdit.value = true;

            const response = await api.get(`/employees/${route.params.id}`);
            employeeForm.value = response.data.data;
        }
    } catch (error) {
        console.error("Error fetching employee data:", error);
    }
};

const saveEmployee = async () => {
    try {
        errors.value = {};

        if (isEdit.value) {
            await api.put(`/employees/${route.params.id}`, employeeForm.value);
        } else {
            await api.post('/employees', employeeForm.value);
        }

        router.push('/employees');
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error("Error saving employee:", error);
        }
    }
};

onMounted(fetchEmployees);
</script>

<template>
    <AdminLayout>
        <div class="p-6 rounded-lg shadow-md">
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-6">List Employee</h2>

            <div class='rounded-2xl w-full sm:w-1/2 border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]'>
                <div class="px-6 py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ isEdit ? 'Edit Employee' : 'Add Employee' }}</h3>
                </div>

                <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <div class="space-y-5">
                        <div class="space-y-6">
                            <form @submit.prevent="saveEmployee" class="space-y-5 grid grid-cols-1 gap-4">
                                <div>
                                    <div class="mb-3">
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Name:</label>
                                        <input v-model="employeeForm.name" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" rows="3"></input>
                                        <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name[0] }}</span>
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">{{ isEdit ? "Save Changes" : "Save" }}</button>
                                    <button type="button" @click="router.push('/employees')" class="flex-1 bg-error-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
