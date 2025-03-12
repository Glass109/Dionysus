<script setup lang="ts">
    import type {BreadcrumbItem} from "@/types";
    import PlaceholderPattern from "@/components/PlaceholderPattern.vue";
    import AppLayout from "@/layouts/AppLayout.vue";
    import { Head } from '@inertiajs/vue3';
    import type { Event } from "@/types";
    import EventShowcaseBanner from "@/components/EventShowcaseBanner.vue";
    // Define the props to receive the events from the controller
    defineProps<{
        events: Array<Event>;
    }>();

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
        <Head title="Mis Eventos" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-4" v-if="events.length > 0">
                <EventShowcaseBanner :event="event" v-for="event in events"></EventShowcaseBanner>
            </div>
            <div v-else>
                <p>No tienes eventos creados.</p>
                <PlaceholderPattern />
            </div>
        </AppLayout>
    </template>
