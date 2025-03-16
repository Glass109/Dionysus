<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import {Head, useForm} from '@inertiajs/vue3' // Added useForm
import type {BreadcrumbItem, Event} from "@/types";
import {computed, ref} from "vue";
import PlaceholderPattern from "@/components/PlaceholderPattern.vue";
import {Button} from "@/components/ui/button";
import {spanishMapping} from "@/lib/utils";
import {
    Calendar,
    Clock1Icon,
    ExternalLinkIcon,
    MapIcon,
    StarIcon,
    User2,
    UserRoundCheckIcon,
    UserRoundPen,
    UserRoundPlusIcon,
    UsersIcon
} from "lucide-vue-next";
import axios from 'axios'; // Import axios for API requests

const props = defineProps<{
    event: Event
    participantsCount: number
    isSubscribed: boolean
}>()
const imageLoaded = ref(false);
const subscribed = ref(props.isSubscribed);
const participantsCounter = ref(props.participantsCount);
const isLoading = ref(false);

// Form for subscription
const subscribeForm = useForm({
    evento_id: props.event.id
});

// Handle subscription toggle
const toggleSubscription = async () => {
    if (isLoading.value) return;
    isLoading.value = true;

    try {
        const endpoint = subscribed.value ? '/events/unsubscribe' : '/events/subscribe';

        await axios.post(endpoint, {
            evento_id: props.event.id
        });

        // Update local state
        subscribed.value = !subscribed.value;
        // Update participant count based on action
        if (subscribed.value) {
            participantsCounter.value++;
        } else {
            participantsCounter.value = Math.max(0, participantsCounter.value - 1);
        }
    } catch (error) {
        console.error('Error updating subscription:', error);
    } finally {
        isLoading.value = false;
    }
}

const timeOptions = new Intl.DateTimeFormat('es-MX', {
    hour: 'numeric',
    minute: 'numeric',
});

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

    return date.toLocaleDateString('es-MX', options)
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
        <div class="grid grid-cols-1 p-4 relative"
             :style="{'--event-color': event.color}">
            <!-- Decorative elements -->
            <div class="absolute -z-10 top-20 right-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
            <div class="absolute -z-10 bottom-20 left-20 w-72 h-72 bg-secondary/10 rounded-full blur-3xl"></div>

            <div class="relative h-[30em] shadow image-container">
                <PlaceholderPattern v-show="!imageLoaded"
                                    class="absolute inset-0 animate-pulse rounded-2xl"></PlaceholderPattern>
                <img
                    :src="event.image"
                    alt="Imagen del evento"
                    @load="imageLoaded = true"
                    :class="{ 'opacity-0': !imageLoaded }"
                    class="transition-opacity duration-300"
                >
            </div>

            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm -mt-6 rounded-[1rem] p-8 shadow-lg">
                <h1 class="text-3xl font-bold mt-4">{{ event.name }}</h1>
                <p class="text-lg">{{ event.description }}</p>
                <hr class="opacity-20 my-4">

                <div class="details-grid">
                    <div class="sl">
                        <UserRoundPen class="event-color"/>
                        <span>Organizado por:</span>
                        <Button variant="ghost" class="text-md font-bold underline">{{ event.owner?.name }}</Button>
                    </div>
                    <div class="sl">
                        <Calendar class="event-color"/>
                        <span>Fecha:</span>
                        <span class="font-bold">{{ formattedStartDate }}</span>
                    </div>
                    <div class="sl">
                        <User2 class="event-color"/>
                        <span>Dirigido a:</span>
                        <span class="font-bold">{{ spanishMapping[event.age_group] }}</span>
                    </div>
                    <div class="sl">
                        <MapIcon class="event-color"/>
                        <span>Lugar:</span>
                      <Button :href="event.location_url" as="a" class="hover:scale-105 transition-transform"
                              variant="link"
                                style="color: var(--event-color)">
                            {{ event.location_name }}
                            <ExternalLinkIcon/>
                        </Button>
                    </div>
                    <div class="sl">
                        <Clock1Icon class="event-color"/>
                        <span>Horario:</span>
                        <span class="font-bold">
                            {{ timeOptions.format(new Date(event.start)) }}
                        </span>
                    </div>
                    <div class="sl">
                        <UsersIcon class="event-color"/>
                        <span>Participantes:</span>
                        <span class="font-bold">{{
                                participantsCounter > 0 ? participantsCounter : '-'
                            }} / {{ event.capacity }}</span>
                    </div>
                    <div class="col-span-2 flex justify-end items-center gap-4 mt-8">
                        <span>Precio: ${{ event.price }}</span>
                        <Button class="group shadow-lg hover:scale-110 hover:brightness-105 transition-all w-48"
                                size="lg" variant="secondary">
                            <StarIcon class="group-hover:text-yellow-500 group-hover:animate-bounce"/>
                            Me interesa
                        </Button>
                        <!-- Participation button that changes based on subscription status -->
                        <Button
                            class="group shadow-lg hover:scale-110 hover:brightness-105 transition-all w-48"
                            size="lg"
                            :variant="subscribed ? 'outline' : 'default'"
                            @click="toggleSubscription"
                            :disabled="isLoading"
                        >
                            <UserRoundCheckIcon v-if="subscribed" class="text-green-500"/>
                            <UserRoundPlusIcon v-else class="group-hover:animate-bounce"/>
                            <span class="ml-2">{{ subscribed ? 'Cancelar participación' : 'Participar' }}</span>
                            <span v-if="isLoading" class="ml-2 animate-spin">⟳</span>
                        </Button>
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

.image-container::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, rgba(0, 0, 0, 0.2) 0%, transparent 50%);
    border-radius: 1rem;
    z-index: 11;
}

.details-grid {
    @apply grid grid-cols-1 lg:grid-cols-2 gap-8 place-content-center mt-8;
}

.sl {
    @apply flex justify-start items-center gap-3 p-4 rounded-lg hover:bg-black/5 dark:hover:bg-white/5 transition-colors;
}

.event-color {
    color: var(--event-color);
}
</style>
