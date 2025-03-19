<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Pencil } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';


defineProps<{
    auth: {
        user: {
            name: string;
            email: string;
            created_at: string;
            profile_photo_url?: string;
        };
    };  
}>();
</script>

<template>
    <Head title="Mi Perfil" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mi Perfil
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Información del Perfil -->
                    <Card class="md:col-span-2">
                        <CardHeader class="flex flex-row items-center justify-between">
                            <CardTitle>Información Personal</CardTitle>
                            <Button variant="outline" size="sm">
                                <Pencil class="h-4 w-4 mr-2" />
                                Editar
                            </Button>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nombre</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ auth.user.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Email</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ auth.user.email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Fecha de Registro</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ new Date(auth.user.created_at).toLocaleDateString() }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Avatar y Configuración -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Foto de Perfil</CardTitle>
                        </CardHeader>
                        <CardContent class="flex flex-col items-center space-y-4">
                            <Avatar class="h-32 w-32">
                                <AvatarImage :src="auth.user.profile_photo_url" />
                                <AvatarFallback>
                                    {{ auth.user.name.split(' ').map(n => n[0]).join('') }}
                                </AvatarFallback>
                            </Avatar>
                            <Button variant="outline" size="sm">
                                Cambiar Foto
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 