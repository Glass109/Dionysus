<script setup lang="ts">

import {Head} from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import type {BreadcrumbItem} from "@/types";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>
<template>
    <Head title="Explore"/>
    <AppLayout :breadcrumbs="breadcrumbs">
    </AppLayout>
</template>
