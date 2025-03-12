<script setup lang="ts">
import type {Event} from '@/types';
import {router} from '@inertiajs/vue3';
import {computed, ref} from 'vue';

const props = defineProps<{
    event: Event,
}>();
const spanishMapping = {
    'all': 'Todos',
    'kids': 'Niños',
    'teens': 'Adolescentes',
    'young': 'Adultos jóvenes',
    'adults': 'Adultos',
    'middle': 'Adultos de mediana edad',
    'seniors': 'Adultos mayores',
    'other': 'Otros',
};

const badgeColorBasedOnAgeGroup = computed(() => {
    switch (props.event.age_group) {
        case 'kids':
            return 'rgba(255, 0, 0, 0.5)';      // red
        case 'teens':
            return 'rgba(0, 0, 255, 0.5)';      // blue
        case 'young':
            return 'rgba(0, 255, 0, 0.5)';      // green
        case 'adults':
            return 'rgba(255, 255, 0, 0.5)';    // yellow
        case 'middle':
            return 'rgba(255, 165, 0, 0.5)';    // orange
        case 'seniors':
            return 'rgba(128, 0, 128, 0.5)';    // purple
        case 'other':
            return 'rgba(128, 128, 128, 0.5)';  // gray
        default:
            return 'rgba(128, 128, 128, 0.5)';  // gray
    }
});
function onClick(): void {
    router.visit(route('events.show', props.event.id));
}
const isHovered = ref(false)

</script>

<template>
    <div @click="onClick" @mouseenter="isHovered = true" @mouseleave="isHovered = false" class="eventshowcasecontainer"
 :style="{ '--event-color': event.color || 'hsl(150 55% 45%)' }">
        <div :style="{ '--age-color': badgeColorBasedOnAgeGroup}"  class="badge">{{spanishMapping[event.age_group]}}</div>
        <img :src="event.image" alt="Imagen de evento"/>
        <Transition appear class="absolute bottom-0 z-20 w-[90%] text-ellipsis whitespace-nowrap p-4">
            <h1 v-if="!isHovered" class="title">{{ event.name }}</h1>
            <h2 v-else> {{ event.description }} </h2>
        </Transition>
    </div>
</template>

<style scoped>
h1 {
    color: white;
    font-weight: 700;
    font-size: 1.5rem;
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
}

h2 {
    color: white;
    font-weight: 500;
    font-size: 1rem;
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    width: 100%;
    text-wrap: balance;
}
.badge {
    opacity: 0;
    transition: all 0.3s ease-out;
    position: absolute;
    top: 0;
    right: 0;
    background-color: var(--age-color);
    backdrop-filter: blur(10px);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0 0 0 1rem;
    font-weight: 700;
    font-size: 1rem;
    z-index: 10;
}

.badge:hover {
    opacity: 1;
    transition: all 0.3s ease-out;
}

img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 1rem;
    position: absolute;
}

.eventshowcasecontainer {
    position: relative;
    aspect-ratio: 16/9;
    border-radius: 1rem;
    overflow: clip;
    transition: all 0.3s ease-out;
    border-top: 2px solid white;
    border-bottom: 2px solid black;
}

.eventshowcasecontainer:hover {
    cursor: pointer;
    scale: 105%;
    transition: all 0.3s ease-out;
}

/* Show the badge when main object is hovered */
.eventshowcasecontainer:hover .badge {
    opacity: 1;
    transition: all 0.3s ease-out;
}

/*noinspection ALL*/
.eventshowcasecontainer::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: linear-gradient(180deg, white, transparent 2% 70%, var(--event-color) 98%, lightgray 100%);
    border-radius: 1rem;
    transition: opacity 0.3s ease-out;
}


.v-enter-from, .v-leave-to {
    opacity: 0;
    transform: translateY(1rem);
}

.v-enter-active, .v-leave-active {
    transition: all 0.3s ease-out;
}
</style>
