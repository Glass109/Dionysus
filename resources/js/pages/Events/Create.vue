<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import type { Event } from '@/types';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Card from '@/components/ui/card/Card.vue';

const currentStep = ref(1);
const totalSteps = 4;
const imagePreview = ref<string | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

// Imágenes para cada paso
const stepImages: Record<number, string> = {
    1: '/images/Events/Create/create-event-1.jpg',
    2: '/images/Events/Create/create-event-2.jpg',
    3: '/images/Events/Create/create-event-3.jpg',
    4: '/images/Events/Create/create-event-4.jpg'
};

interface FormData {
    name: string;
    description: string;
    start: string;
    price: number;
    capacity: number;
    location_name: string;
    location_address: string;
    location_url: string;
    image: File | null;
    age_group: 'all' | 'kids' | 'teens' | 'young' | 'adults' | 'middle' | 'seniors' | 'other';
    color: string;
    [key: string]: string | number | File | null | undefined;
}

const form = useForm<FormData>({
    name: '',
    description: '',
    start: '',
    price: 0,
    capacity: 1,
    location_name: '',
    location_address: '',
    location_url: '',
    image: null,
    age_group: 'all',
    color: 'hsl(var(--primary))', // Color por defecto usando el tema
});

const steps = [
    {
        title: 'Información Básica',
        description: 'Nombre y detalles principales del evento',
        fields: ['name', 'description'] as const
    },
    {
        title: 'Fecha y Capacidad',
        description: 'Cuándo será el evento y cuántas personas pueden asistir',
        fields: ['start', 'price', 'capacity'] as const
    },
    {
        title: 'Ubicación',
        description: 'Dónde se realizará el evento',
        fields: ['location_name', 'location_address', 'location_url'] as const
    },
    {
        title: 'Detalles Finales',
        description: 'Imagen y configuración adicional',
        fields: ['image', 'age_group', 'color'] as const
    }
] as const;

const progress = computed(() => (currentStep.value / totalSteps) * 100);

const validateCurrentStep = () => {
    const currentFields = steps[currentStep.value - 1].fields;
    const requiredFields = currentFields.filter(field => field !== 'location_url') as Array<keyof FormData>;
    return requiredFields.every(field => {
        const value = form[field];
        if (field === 'price' || field === 'capacity') {
            return typeof value === 'number' && value > 0;
        }
        return value !== '' && value !== null && value !== undefined;
    });
};

const nextStep = () => {
    if (currentStep.value < totalSteps && validateCurrentStep()) {
        currentStep.value++;
    }
};

const previousStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const handleSubmit = () => {
    if (currentStep.value === totalSteps) {
        submit();
    } else {
        nextStep();
    }
};

const handleImageUpload = (event: InputEvent) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const openFileInput = () => {
    fileInput.value?.click();
};

const submit = () => {
    const formData = new FormData();
    
    Object.entries(form.data()).forEach(([key, value]) => {
        if (value !== null && value !== undefined) {
            if (key === 'price') {
                formData.append(key, String(Number(value) || 0));
            } else if (key === 'capacity') {
                formData.append(key, String(Number(value) || 1));
            } else if (value instanceof File) {
                formData.append(key, value);
            } else {
                formData.append(key, String(value));
            }
        }
    });

    // Enviar el formulario
    form.post(route('events.store'), {
        onSuccess: () => {
            // Manejar éxito
        },
    });
};
</script>

<template>
    <Head title="Crear Evento" />
    <AppLayout class="relative">
        <!-- Fondo con imagen -->
        <div class="absolute inset-0">
            <img 
                :src="stepImages[currentStep]"
                :alt="`Fondo paso ${currentStep}`"
                class="w-full h-full object-cover"
            />
        </div>

        <div class="relative h-full flex items-center justify-center p-6">
            <Card class="w-full max-w-4xl backdrop-blur p-6 shadow-xl">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-foreground">Crear Nuevo Evento</h1>
                    <p class="text-muted-foreground">Complete los siguientes pasos para crear su evento</p>
                </div>

                <!-- Progreso -->
                <div class="mb-8">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-foreground">Progreso</span>
                        <span class="text-sm font-medium text-muted-foreground">{{ currentStep }}/{{ totalSteps }}</span>
                    </div>
                    <div class="w-full bg-muted rounded-full h-2.5">
                        <div class="bg-primary h-2.5 rounded-full transition-all duration-300"
                             :style="{ width: `${progress}%` }"></div>
                    </div>
                </div>

                <!-- Pasos -->
                <div class="flex mb-8">
                    <template v-for="(step, index) in steps" :key="index">
                        <div class="flex items-center" :class="{ 'flex-1': index !== steps.length - 1 }">
                            <div class="relative flex items-center justify-center">
                                <div :class="[
                                    'w-10 h-10 rounded-full flex items-center justify-center border-2 transition-colors',
                                    currentStep > index + 1 ? 'bg-primary border-primary text-primary-foreground' :
                                    currentStep === index + 1 ? 'border-primary text-primary' :
                                    'border-muted text-muted-foreground'
                                ]">
                                    <span v-if="currentStep <= index + 1">{{ index + 1 }}</span>
                                    <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div v-if="index !== steps.length - 1" :class="[
                                'flex-1 h-0.5 mx-4',
                                currentStep > index + 1 ? 'bg-primary' : 'bg-muted'
                            ]"></div>
                        </div>
                    </template>
                </div>

                <!-- Formulario -->
                <form @submit.prevent="handleSubmit">
                    <!-- Paso 1: Información Básica -->
                    <div v-if="currentStep === 1" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Nombre del Evento</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="description">Descripción</Label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                required
                            ></textarea>
                        </div>
                    </div>

                    <!-- Paso 2: Fecha y Capacidad -->
                    <div v-if="currentStep === 2" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="start">Fecha y Hora de Inicio</Label>
                            <Input
                                id="start"
                                v-model="form.start"
                                type="datetime-local"
                                required
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="price">Precio</Label>
                            <Input
                                id="price"
                                v-model.number="form.price"
                                type="number"
                                min="0"
                                step="0.01"
                                required
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="capacity">Capacidad</Label>
                            <Input
                                id="capacity"
                                v-model.number="form.capacity"
                                type="number"
                                min="1"
                                required
                            />
                        </div>
                    </div>

                    <!-- Paso 3: Ubicación -->
                    <div v-if="currentStep === 3" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="location_name">Nombre del Lugar</Label>
                            <Input
                                id="location_name"
                                v-model="form.location_name"
                                type="text"
                                required
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="location_address">Dirección</Label>
                            <Input
                                id="location_address"
                                v-model="form.location_address"
                                type="text"
                                required
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="location_url">URL de la Ubicación (opcional)</Label>
                            <Input
                                id="location_url"
                                v-model="form.location_url"
                                type="url"
                            />
                        </div>
                    </div>

                    <!-- Paso 4: Detalles Finales -->
                    <div v-if="currentStep === 4" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="image">Imagen del Evento</Label>
                            <div class="flex flex-col items-center gap-4">
                                <div 
                                    class="w-full h-48 border-2 border-dashed border-muted rounded-lg flex items-center justify-center cursor-pointer hover:border-primary transition-colors"
                                    @click="openFileInput"
                                >
                                    <div v-if="!imagePreview" class="text-center p-4">
                                        <svg class="mx-auto h-12 w-12 text-muted-foreground" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="mt-1 text-sm text-muted-foreground">Haz clic para subir una imagen o arrastra y suelta aquí</p>
                                        <p class="text-xs text-muted-foreground">PNG, JPG, GIF hasta 2MB</p>
                                    </div>
                                    <img 
                                        v-else 
                                        :src="imagePreview" 
                                        class="w-full h-full object-contain"
                                        alt="Vista previa de la imagen"
                                    />
                                </div>
                                <input
                                    ref="fileInput"
                                    type="file"
                                    :class="{ hidden: true }"
                                    accept="image/*"
                                    @change="handleImageUpload"
                                />
                                <Button 
                                    v-if="imagePreview"
                                    type="button"
                                    variant="outline"
                                    @click="() => { form.image = null; imagePreview = null; }"
                                >
                                    Eliminar imagen
                                </Button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label for="age_group">Grupo de Edad</Label>
                            <select
                                id="age_group"
                                v-model="form.age_group"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                required
                            >
                                <option value="kids">Niños</option>
                                <option value="teens">Adolescentes</option>
                                <option value="young">Jóvenes</option>
                                <option value="adults">Adultos</option>
                                <option value="middle">Mediana Edad</option>
                                <option value="seniors">Adultos Mayores</option>
                                <option value="all">Todas las Edades</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label for="color">Color del Evento</Label>
                            <Input
                                id="color"
                                v-model="form.color"
                                type="color"
                                class="h-10 cursor-pointer"
                                required
                            />
                        </div>
                    </div>

                    <!-- Botones de navegación -->
                    <div class="flex justify-between mt-8">
                        <Button
                            type="button"
                            variant="outline"
                            @click="previousStep"
                            :disabled="currentStep === 1"
                        >
                            Anterior
                        </Button>
                        <Button
                            type="submit"
                            :disabled="!validateCurrentStep()"
                        >
                            {{ currentStep === totalSteps ? 'Crear Evento' : 'Siguiente' }}
                        </Button>
                    </div>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style> 