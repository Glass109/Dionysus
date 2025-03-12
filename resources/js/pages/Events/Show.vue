<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import {Head} from '@inertiajs/vue3'
import type {BreadcrumbItem, Event} from "@/types";
import {computed, ref} from "vue";
import PlaceholderPattern from "@/components/PlaceholderPattern.vue";
import {Button} from "@/components/ui/button";
import Icon from "@/components/Icon.vue";
import {ageGroupToRGBAColor, spanishMapping} from "../../lib/utils";

const props = defineProps<{ event: Event }>()
const imageLoaded = ref(false);
const formatDate = (date: Date) => {
    const options: Intl.DateTimeFormatOptions = {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    };

    const currentYear = new Date().getFullYear();
    const dateYear = date.getFullYear();

    if (dateYear === currentYear) {
        delete options.year;
    }

    return date.toLocaleDateString('es-ES', options)
        .replace(/^\w/, c => c.toUpperCase());
};

const formattedStartDate = computed(() => formatDate(new Date(props.event.start)));
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
        <div class="grid grid-cols-1 p-4" style="background-image: linear-gradient(180deg, var(--event-color), transparent)" :style="{'--event-color': event.color}">
            <div class="relative h-[30em] shadow">
                <PlaceholderPattern v-show="!imageLoaded"
                                    class="absolute inset-0 animate-pulse  rounded-2xl"></PlaceholderPattern>
                <img
                    :src="event.image"
                    alt="Imagen del evento"
                    @load="imageLoaded = true"
                    :class="{ 'opacity-0': !imageLoaded }"
                    class="transition-opacity duration-300"
                >
            </div>
            <div class="min-h-16 -mt-6 rounded-[1rem] p-4">
                <h1 class="text-3xl font-bold mt-4">{{ event.name }}</h1>
                <p class="text-lg">{{ event.description }}</p>
                <hr class="opacity-20 my-4">
                <div class="grid grid-cols-2">
                    <div class="sl">
                        <span>Organizado por:</span>
                        <Button variant="outline" class="text-md font-bold underline">{{ event.owner?.name }}</Button>
                    </div>
                    <div class="sl">
                        <Icon name="calendar" class="event-color"/>
                        <span>Fecha:</span>
                        <span class="text-lg">{{ formattedStartDate }}</span>
                    </div>
                    <div class="sl">
                        <Icon name="user" class="event-color"/>
                        <span>Dirigido a:</span>
                        <span class="italic"> {{spanishMapping[event.age_group]}}</span>
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
/*Straight Line*/
.sl{
    @apply flex justify-center items-center gap-2
}

.event-color{
    color: var(--event-color);
}
</style>
