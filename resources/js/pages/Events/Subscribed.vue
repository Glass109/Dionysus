<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {Head} from '@inertiajs/vue3'
import type {Event} from '@/types'
import EventShowcaseBanner from "@/components/EventShowcaseBanner.vue";
import {computed} from 'vue';

const props = defineProps<{
    events: Array<Event>
}>()

// Create a computed property to add staggered delay for each event
const eventsWithDelay = computed(() => {
    return props.events.map((event, index) => ({
        ...event,
        delay: `${index * 150}ms` // 150ms delay between each item
    }));
});
</script>

<template>
    <AppLayout>
        <Head title="Subscribed"/>
        <TransitionGroup
            tag="div"
            class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-4"
            name="stagger-fade"
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
