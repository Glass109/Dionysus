<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {Head} from '@inertiajs/vue3'
import type {Event} from '@/types'

const props = defineProps<{
    event: Event
}>()
</script>

<template>
    <AppLayout>
        <Head title="Subscribed"/>
        <h1>AQUI DEBERÍAN IR LOS EVENTOS DONDE TE SUBSCRIBES</h1>
    </AppLayout>
</template>
