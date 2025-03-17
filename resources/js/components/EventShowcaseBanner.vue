<script setup lang="ts">
import type { Event } from '@/types';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { ageGroupToRGBAColor } from "@/lib/utils";
import { spanishMapping } from "@/lib/utils";

const props = defineProps<{
    event: Event,
}>();

const badgeColorBasedOnAgeGroup = ageGroupToRGBAColor(props.event.age_group);

function onClick(): void {
    router.visit(route('events.show', props.event.id));
}

const isHovered = ref(false)

</script>

<template>
    <div @click="onClick" @mouseenter="isHovered = true" @mouseleave="isHovered = false" class="eventshowcasecontainer"
        :style="{
            '--event-color': event.color || 'hsl(150 55% 45%)',
        }">
        <div :style="{ '--age-color': badgeColorBasedOnAgeGroup }" class="badge">{{ spanishMapping[event.age_group] }}</div>
        <div class="tier-badge" :class="event.tier.tier">{{ event.tier.tier }}</div>
        <img :src="event.image" alt="Imagen de evento" />
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
    overflow: hidden;
    text-overflow: ellipsis;
}

h2 {
    color: white;
    font-weight: 500;
    font-size: 1rem;
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    width: 100%;
    text-wrap: balance;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
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

.tier-badge {
    transition: all 0.3s ease-out;
    position: absolute;
    opacity: 0;
    top: 0;
    left: 0; 
    backdrop-filter: blur(10px);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0 1rem 1rem 0;
    font-weight: 700;
    font-size: 1rem;
    z-index: 10;
}
.eventshowcasecontainer:hover .tier-badge {
    opacity: 1;
    transition: all 0.3s ease-out;
    animation: pulse 2s infinite;
}

.BRONZE{
    background: linear-gradient(45deg, #dd954d, #ad5700);
}
.SILVER{
    background: linear-gradient(45deg, #C0C0C0, #C0C0C0);
}
.GOLD{
    background: linear-gradient(45deg, #FFD700, #FFD700);
}
.PLATINUM{
    background: linear-gradient(45deg, #E5E4E2, #E5E4E2);
}
.DIAMOND{
    background: linear-gradient(45deg, #0077FF, #0077FF);
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
    overflow: hidden;
    transition: all 0.3s ease-out;

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
    background-image: linear-gradient(180deg, rgba(255, 255, 255, 0.8), transparent 2% 70%, var(--event-color) 98%, rgba(211, 211, 211, 0.8) 100%);
    border-radius: 1rem;
    transition: opacity 0.3s ease-out;
    z-index: 11;
}


.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: translateY(1rem);
}

.v-enter-active,
.v-leave-active {
    transition: all 0.3s ease-out;
}   
</style>
