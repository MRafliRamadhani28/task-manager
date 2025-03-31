import { createRouter, createWebHistory } from 'vue-router';
import TaskList from '../views/TaskList.vue';
import TaskForm from '../views/TaskForm.vue';
import EmployeeList from '../views/EmployeeList.vue';
import EmployeeForm from '../views/EmployeeForm.vue';

const routes = [
    { path: '/', component: TaskList },
    { path: '/tasks/new', component: TaskForm },
    { path: '/tasks/edit/:id', component: TaskForm },
    { path: '/employees', component: EmployeeList },
    { path: '/employees/new', component: EmployeeForm },
    { path: '/employees/edit/:id', component: EmployeeForm },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
