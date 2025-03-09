<script setup lang="ts">
    import type {BreadcrumbItem} from "@/types";
    import PlaceholderPattern from "@/components/PlaceholderPattern.vue";
    import AppLayout from "@/layouts/AppLayout.vue";
    import { Head } from '@inertiajs/vue3';

    // Define the props to receive the events from the controller
    defineProps<{
        events: Array<{
            id: number;
            title: string;
            description: string;
            // Add other fields your events have
        }>;
    }>();

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
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
            <div v-if="events.length > 0">
                <!-- Display events here -->
                <div v-for="event in events" :key="event.id" class="mb-4 p-4 border rounded">
                    <h2 class="text-lg font-bold">{{ event.title }}</h2>
                    <p>{{ event.description }}</p>
                    <!-- Add more event details as needed -->
                </div>
            </div>
            <div v-else>
                <p>No tienes eventos creados.</p>
                <PlaceholderPattern />
            </div>
        </AppLayout>
    </template>
