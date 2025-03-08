<script setup lang="ts">
import type {BreadcrumbItem} from "@/types";
import PlaceholderPattern from "@/components/PlaceholderPattern.vue";
import AppLayout from "@/layouts/AppLayout.vue";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Mis Eventos',
        href: 'dashboard/events/owned',
    }
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <PlaceholderPattern></PlaceholderPattern>
    </AppLayout>
</template>

<style scoped>

</style>
