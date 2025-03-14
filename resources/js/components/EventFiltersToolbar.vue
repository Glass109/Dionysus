<script lang="ts" setup>
import {computed, ref, watch} from 'vue';
import {router} from '@inertiajs/vue3';
import {type EventFilters} from '@/types';
import {Button} from "@/components/ui/button";
import {Input} from "@/components/ui/input";
import {Label} from "@/components/ui/label";
import {Slider} from "@/components/ui/slider";

const props = defineProps<{
    filters: EventFilters;
    route: string; // Current route to refresh with filters
}>();

// Create a reactive copy of the filters
const localFilters = ref<EventFilters>({
    age_group: props.filters.age_group,
    min_price: props.filters.min_price,
    max_price: props.filters.max_price,
    start_date: props.filters.start_date,
    end_date: props.filters.end_date,
    sort_by: props.filters.sort_by,
    sort_direction: props.filters.sort_direction
});

// For the price slider
const priceRange = ref([
    localFilters.value.min_price ?? 0,
    localFilters.value.max_price ?? 1000
]);

// Format price for display
const formattedPriceRange = computed(() => {
    return `$${priceRange.value[0]} - $${priceRange.value[1]}`;
});

// Watch price range changes and update filters
watch(priceRange, (newRange) => {
    localFilters.value.min_price = newRange[0];
    localFilters.value.max_price = newRange[1];
}, {deep: true});

// Age group options
const ageGroupOptions = [
    {value: '', label: 'Todos'},
    {value: 'kids', label: 'Niños'},
    {value: 'teens', label: 'Adolescentes'},
    {value: 'young', label: 'Adultos Jóvenes'},
    {value: 'adults', label: 'Adultos'},
    {value: 'middle', label: 'Adultos de mediana edad'},
    {value: 'seniors', label: 'Adultos mayores'},
    {value: 'all', label: 'Para todas las edades'},
    {value: 'other', label: 'Otros'}
];

// Sorting options
const sortOptions = [
    {value: 'created_at', label: 'Fecha de Creación'},
    {value: 'start', label: 'Fecha de inicio'},
    {value: 'name', label: 'Nombre del evento'},
    {value: 'price', label: 'Precio'}
];

// Watch for changes from parent
watch(() => props.filters, (newFilters) => {
    localFilters.value = {...newFilters};

    // Update slider when filters change from parent
    priceRange.value = [
        newFilters.min_price ?? 0,
        newFilters.max_price ?? 1000
    ];
}, {deep: true});

function applyFilters() {
    router.get(props.route, localFilters.value, {
        preserveState: false,
        replace: true
    });
}

function resetFilters() {
    localFilters.value = {
        age_group: null,
        min_price: null,
        max_price: null,
        start_date: null,
        end_date: null,
        sort_by: 'created_at',
        sort_direction: 'desc'
    };

    // Reset slider values
    priceRange.value = [0, 1000];

    applyFilters();
}
</script>

<template>
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-medium text-gray-700">Filter Events</h2>
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Ordenar por:</label>
                <select
                    v-model="localFilters.sort_by"
                    class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    @change="applyFilters"
                >
                    <option v-for="option in sortOptions" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
                <button
                    :title="localFilters.sort_direction === 'asc' ? 'Sort Descending' : 'Sort Ascending'"
                    class="p-1.5 rounded-md hover:bg-gray-100 focus:outline-none"
                    @click="localFilters.sort_direction = localFilters.sort_direction === 'asc' ? 'desc' : 'asc'; applyFilters()"
                >
                    <svg :class="{'rotate-180': localFilters.sort_direction === 'desc'}" class="h-5 w-5"
                         fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd"
                              d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z"
                              fill-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Age Group Filter -->
            <div>
                <Label class="mb-1">Grupo de edad</Label>
                <select
                    v-model="localFilters.age_group"
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                >
                    <option v-for="option in ageGroupOptions" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
            </div>


            <!-- Date Range Filter -->
            <div>
                <Label class="mb-1">Fecha</Label>
                <Input
                    v-model="localFilters.start_date!"
                    type="date"
                />
            </div>
            <!-- Price Range Filter with Slider -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex justify-between items-center mb-1">
                    <Label>Rango de precio</Label>
                    <span class="text-sm text-gray-500">{{ formattedPriceRange }}</span>
                </div>
                <Slider
                    v-model="priceRange"
                    :max="1000"
                    :min="0"
                    :step="10"
                    class="my-4"
                />
            </div>
        </div>


        <div class="mt-4 flex justify-end space-x-2">
            <Button
                variant="secondary"
                @click="resetFilters"
            >
                Reiniciar filtros
            </Button>
            <Button @click="applyFilters">
                Aplicar filtros
            </Button>
        </div>
    </div>
</template>

<style scoped>
/* Optional: Add transition effects */
.filter-transition {
    transition: all 0.3s ease;
}
</style>
