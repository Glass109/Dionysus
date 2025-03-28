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

// Función para formatear la fecha
function formatDate(dateString: string | null): string {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
}

// Create a reactive copy of the filters
const localFilters = ref<EventFilters>({
    age_group: props.filters.age_group,
    min_price: props.filters.min_price,
    max_price: props.filters.max_price,
    start_date: formatDate(props.filters.start_date),
    end_date: formatDate(props.filters.end_date),
    sort_by: props.filters.sort_by,
    sort_direction: props.filters.sort_direction,
    min_tier: props.filters.min_tier,
    status: props.filters.status || 'ACTIVE'
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
        sort_direction: 'desc',
        min_tier: '',
        status: 'ACTIVE'
    };

    // Reset slider values
    priceRange.value = [0, 1000];

    applyFilters();
}
</script>

<template>
    <div class="bg-card shadow rounded-lg p-4 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-medium text-muted-foreground">Filtrar eventos</h2>
            <div class="flex items-center space-x-2">
                <label class="text-sm text-muted-foreground">Ordenar por:</label>
                <select
                    v-model="localFilters.sort_by   "
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

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
            <!-- Age Group Filter -->
            <div>
                <Label class="mb-1">Grupo de edad</Label>
                <select
                    v-model="localFilters.age_group"
                >
                    <option :value="null">Seleccionar grupo de edad</option>
                    <option v-for="option in ageGroupOptions" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
            </div>
            <!-- Tier Filter -->
            <div>
                <Label class="mb-1">Tier mínimo</Label>
                <select
                    v-model="localFilters.min_tier"
                    @change="applyFilters"
                >
                    <option :value="null">Cualquier tier</option>
                    <option value="BRONZE">Bronze</option>
                    <option value="SILVER">Silver</option>
                    <option value="GOLD">Gold</option>
                    <option value="PLATINUM">Platinum</option>
                </select>
            </div>


            <!-- Date Range Filter -->
            <div>
                <Label class="mb-1">A partir de</Label>
                <Input
                    v-model="localFilters.start_date!"
                    type="date"
                />
            </div>
            <div>
                <Label class="mb-1">Hasta</Label>
                <Input
                    v-model="localFilters.end_date!"
                    type="date">
                    ></Input>
            </div>
            <!-- Price Range Filter with Slider -->
            <div class="col-span-1">
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

        <!-- Reset and Apply Filters Buttons -->
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

.PLATINUM {
    --tier-color: linear-gradient(135deg, #e5e4e2 0%, #b4b4b4 50%, #e5e4e2 100%);
}

.GOLD {
    --tier-color: linear-gradient(135deg, #ffd700 0%, #b8860b 50%, #ffd700 100%);
}

.SILVER {
    --tier-color: linear-gradient(135deg, #c0c0c0 0%, #808080 50%, #c0c0c0 100%);
}

.BRONZE {
    --tier-color: linear-gradient(135deg, #cd7f32 0%, #a0522d 50%, #cd7f32 100%);
}

.STANDARD {
    --tier-color: linear-gradient(135deg, #ffffff 0%, #f0f0f0 50%, #ffffff 100%);
}
</style>
