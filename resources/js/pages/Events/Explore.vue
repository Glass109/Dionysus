<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head} from '@inertiajs/vue3';
import type {Event} from '@/types';
import EventShowcaseBanner from "@/components/EventShowcaseBanner.vue";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps<{
    events: Array<Event>;
}>();
</script>

<template>
    <Head title="Explore"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Transition appear name="fade" class="flex justify-center items-center">
            <h1 class="title">Descubre nuevas aventuras</h1>
        </Transition>
        <Transition appear>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 items-center">
            <EventShowcaseBanner v-for="event in events" :event="event" />
        </div>
        </Transition>
    </AppLayout>
</template>

<style scoped>
.title {
    font-size: 4rem;
    font-weight: 600;
    background-clip: text;
    color: transparent;
    background-image: linear-gradient(180deg, white 0%, hsl(150 55% 45%), darkgreen 100%);
    text-align: center;

}
.v-enter-active,
.v-leave-active {
    @apply transition-all duration-500
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: scale(90%);
    filter: blur(30px);
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 1s;
    opacity: 100%;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    filter: blur(30px);
}

</style>
