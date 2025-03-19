<script setup lang="ts">
import type {BreadcrumbItem, Event} from "@/types";
import AppLayout from "@/layouts/AppLayout.vue";
import {Head} from '@inertiajs/vue3';
import EventShowcaseBanner from "@/components/EventShowcaseBanner.vue";
import {computed} from "vue";
import {Button} from "@/components/ui/button";
import {Calendar, CalendarPlus, PlusCircle} from "lucide-vue-next";

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
        href: '/events/explore',
    },
    {
        title: 'Mis Eventos',
        href: '/events/owned',
    }
];
</script>

<template>
    <Head title="Mis Eventos"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <!-- Header section with title and create button -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">Mis Eventos</h1>
                    <p class="text-gray-500 dark:text-gray-400">Administra los eventos que has creado</p>
                </div>
                <Button
                    as="a"
                    class="hover:scale-105 transition-all shadow-md"
                    href="/events/create"
                >
                    <CalendarPlus class="mr-2"/>
                    Crear Nuevo Evento
                </Button>
            </div>

            <!-- Events grid -->
            <TransitionGroup
                v-if="events.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                name="stagger-fade"
                tag="div"
            >
                <div
                    v-for="event in eventsWithDelay"
                    :key="event.id"
                    :style="{ animationDelay: event.delay }"
                    class="popup-item"
                >
                    <EventShowcaseBanner :event="event"/>
                </div>
            </TransitionGroup>

            <!-- Empty state with better styling -->
            <div v-else class="text-center py-16 px-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 backdrop-blur-sm">
                <div class="flex justify-center mb-4">
                    <Calendar class="w-16 h-16 text-gray-400"/>
                </div>
                <h2 class="text-xl font-semibold mb-2">No tienes eventos creados</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Comienza a organizar tus propios eventos y podrás administrarlos desde esta pantalla.
                </p>
                <Button
                    as="a"
                    class="hover:scale-105 transition-all shadow-md"
                    href="/events/create"
                    size="lg"
                >
                    <PlusCircle class="mr-2"/>
                    Crear mi primer evento
                </Button>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.popup-item {
    animation: popup 0.5s ease both;
    @apply hover:shadow-lg transition-shadow rounded-lg;
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
