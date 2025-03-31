<script setup>
import Layout from './components/Layout.vue'
import SidebarProvider from './components/SidebarProvider.vue'
</script>

<template>
	<Layout>
		<SidebarProvider>
			<RouterView />
		</SidebarProvider>
	</Layout>
	<!-- <div class="min-h-screen bg-gray-50">
        <nav class="bg-gradient-to-r from-blue-900 to-blue-600 p-4 shadow-lg">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-white text-2xl font-semibold">Task Manager</h1>
                <div class="space-x-4">
                    <router-link to="/" class="text-white text-lg font-medium hover:opacity-80 transition">Home</router-link>
                    <router-link to="/tasks/new" class="text-white text-lg font-medium hover:opacity-80 transition">Tambah Tugas</router-link>
                </div>
            </div>
        </nav>

        <div class="container mx-auto p-8">
            <RouterView />
        </div>
    </div> -->
</template>
