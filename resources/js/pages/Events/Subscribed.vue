<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {Head} from '@inertiajs/vue3'
import type {Event} from '@/types'
import EventShowcaseBanner from "@/components/EventShowcaseBanner.vue";
import {computed} from 'vue';

const props = defineProps<{
    events: {
        data: Array<Event>
    }
}>()

// Create a computed property to add staggered delay for each event
const eventsWithDelay = computed(() => {
    return props.events.data.map((event, index) => ({
        ...event,
        delay: `${index * 150}ms` // 150ms delay between each item
    }));
});
</script>

<template>
    <Head title="Subscribed"/>
    <AppLayout>
        <div class="p-4  flex flex-col">
            <div>
                <h1 class="text-2xl font-bold">Eventos Subscritos</h1>
                <p class="text-gray-500 dark:text-gray-400">Estos son los eventos a los que te has subscrito</p>
            </div>
            <TransitionGroup
                class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-4"
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
            <div v-if="events.data.length === 0" class="flex-1 flex flex-col items-center justify-center">
                <p class="text-4xl font-bold mb-4">No tienes eventos subscritos!</p>
                <img src="/svg/preocupied_greek.svg" alt="No events subscribed" class="w-1/2 h-auto max-h-[30vh] floating-doodle dark:invert">
            </div>
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

.floating-doodle {
    animation: shake-violently 100ms ease-in-out infinite;
}

@keyframes shake-violently {
    0%, 100% {
        transform: translateY(0) rotate(0);
    }
    25% {
        transform: translateY(-15px) rotate(-5deg);
    }
    50% {
        transform: translateY(0) rotate(5deg); 
    }
    75% {
        transform: translateY(15px) rotate(-3deg);
    }
}

</style>
