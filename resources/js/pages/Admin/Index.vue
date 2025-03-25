<template>
    <Head title="Panel de Administración" />
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel de Administración
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Contenido del panel de administración -->
                        <h3 class="text-lg font-medium mb-4">Bienvenido al Panel de Administración</h3>
                        
                        <!-- Aquí irá el contenido específico del panel -->
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
</script> 