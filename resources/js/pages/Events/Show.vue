<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import {Head} from '@inertiajs/vue3'
import type {BreadcrumbItem, Event} from "@/types";
import {AvatarImage} from "@/components/ui/avatar";
import {computed, ref} from "vue";
import PlaceholderPattern from "@/components/PlaceholderPattern.vue";

const props = defineProps<{event: Event}>()
const ownerInitials = computed(() => props.event.owner?.name ? props.event.owner?.name.split(' ').map(word => word[0]).join('') : '??')
const imageLoaded = ref(false);
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Eventos',
        href: '/events',
    },
    {
        title: props.event.name,
        href: `/events/${props.event.id}`,
    }
];

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Show"/>
        <div class="grid grid-cols-1 p-4 text-white">
            <div class="relative h-[30em]">
                <PlaceholderPattern v-show="!imageLoaded" class="absolute inset-0 animate-pulse  rounded-2xl"></PlaceholderPattern>
                <img
                    :src="event.image"
                    alt="Imagen del evento"
                    @load="imageLoaded = true"
                    :class="{ 'opacity-0': !imageLoaded }"
                    class="transition-opacity duration-300"
                >
            </div>
            <div :style="{'background-color': event.color}" class="min-h-16 -mt-6 rounded-[1rem] p-4">
                <h1 class="text-3xl font-bold mt-4">{{ event.name }}</h1>
                <p class="text-lg">{{ event.description }}</p>
                <hr class="opacity-20 my-4">
                <div class="flex flex-col">
                    <span class="text-gray-300">Organizado por:</span>
                    <div class="flex items-center rounded-md bg-black/50 pl-2 pr-4 py-2 gap-2 border w-fit">
                        <span class="text-sm rounded-sm bg-white text-black p-2">
                            {{ownerInitials}}
                        </span>
                        <span class="text-md">{{ event.owner?.name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
img {
    width: 100%;
    height: 30em;
    object-fit: cover;
    border-radius: 1rem;
    z-index: 10;
    position: absolute;
    top: 0;
    left: 0;
}
</style>
