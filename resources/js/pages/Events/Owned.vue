<script setup lang="ts">
import type {BreadcrumbItem} from "@/types";
import PlaceholderPattern from "@/components/PlaceholderPattern.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import {Head} from '@inertiajs/vue3';
import type {Event} from "@/types";
import EventShowcaseBanner from "@/components/EventShowcaseBanner.vue";
import {computed} from "vue";
// Define the props to receive the events from the controller
const props = defineProps<{
    events: Array<Event>;
}>();

const eventsWithDelay = computed(() => {
    return props.events.map((event, index) => ({
        ...event,
        delay: `${index * 150}ms` // 150ms delay between each item
    }));
});
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Eventos',
        href: '/dashboard/events/explore',
    },
    {
        title: 'Mis Eventos',
        href: 'dashboard/events/owned',
    }
];
</script>

<template>
    <Head title="Mis Eventos"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <TransitionGroup
            tag="div"
            class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-4"
            name="stagger-fade"
            v-if="events.length > 0"
        >
            <div
                v-for="event in eventsWithDelay"
                :key="event.id"
                class="popup-item"
                :style="{ animationDelay: event.delay }"
            >
                <EventShowcaseBanner :event="event"/>
            </div>
        </TransitionGroup>
        <div v-else>
            <p>No tienes eventos creados.</p>
            <PlaceholderPattern/>
        </div>
    </AppLayout>
</template>

<style scoped>
.popup-item {
    animation: popup 0.5s ease both;
}

@keyframes popup {
    0% {
        opacity: 0;
        transform: scale(0.8) translateY(30px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.stagger-fade-enter-active,
.stagger-fade-leave-active {
    transition: all 0.5s ease;
}

.stagger-fade-enter-from,
.stagger-fade-leave-to {
    opacity: 0;
    transform: scale(0.8) translateY(30px);
}

.stagger-fade-move {
    transition: transform 0.5s ease;
}
</style>
