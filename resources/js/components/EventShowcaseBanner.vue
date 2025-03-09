<script setup lang="ts">
import type {Event} from "@/types";
import { router } from '@inertiajs/vue3';

const props = defineProps<{ event: Event }>();

function onClick() : void {
    router.visit(route('dashboard.events.show', props.event.id));
}
</script>

<template>
    <div class="eventshowcasecontainer"
         @click="onClick"
         :style="{ '--event-color': event.color || 'hsl(150 55% 45%)' }">
        <img :src="event.image" alt="Imagen de evento">
        <div class="z-20 absolute w-[90%] bottom-0 text-ellipsis whitespace-nowrap p-4">
            <h1 class="title">{{ event.name }}</h1>
        </div>
    </div>
</template>

<style scoped>
.title {
    font-weight: 1000;
    font-size: 1.5rem;
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
}
.eventshowcasecontainer:hover{
    cursor: pointer;
    scale: 105%;
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
    background-image: linear-gradient(180deg,white, transparent 50%, var(--event-color));
    border-radius: 1rem;
    transition: opacity 0.3s ease-out;
}

.eventshowcasecontainer:hover::after {
    opacity: 0.6;
}
</style>
