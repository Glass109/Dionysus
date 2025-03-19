<script setup lang="ts">
import { ref } from 'vue';
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Label } from "@/components/ui/label";
import axios from 'axios';
import { XCircle } from 'lucide-vue-next';

const props = defineProps<{
    eventId: number;
    isOwner: boolean;
}>();

const emit = defineEmits(['eventCancelled']);

const isOpen = ref(false);
const isLoading = ref(false);
const selectedReason = ref('');

const cancelReasons = [
    {
        id: 'date_conflict',
        label: 'Conflicto de fecha',
        description: 'Tengo otro compromiso en la misma fecha',
        reputationImpact: 'bajo'
    },
    {
        id: 'location_change',
        label: 'Cambio de ubicación',
        description: 'La ubicación ya no está disponible o ha cambiado',
        reputationImpact: 'medio'
    },
    {
        id: 'low_attendance',
        label: 'Baja asistencia',
        description: 'No hay suficientes participantes',
        reputationImpact: 'bajo'
    },
    {
        id: 'emergency',
        label: 'Emergencia',
        description: 'Situación de emergencia o imprevisto',
        reputationImpact: 'ninguno'
    },
    {
        id: 'other',
        label: 'Otro motivo',
        description: 'Otro motivo no listado',
        reputationImpact: 'alto'
    }
];

const handleCancel = async () => {
    if (!selectedReason.value) return;
    
    isLoading.value = true;
    try {
        await axios.post('/events/cancel', {
            evento_id: props.eventId,
            reason: selectedReason.value
        });
        
        isOpen.value = false;
        emit('eventCancelled');
    } catch (error) {
        console.error('Error al cancelar el evento:', error);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <Button variant="destructive" class="group shadow-lg hover:scale-110 hover:brightness-105 transition-all">
                <XCircle class="mr-2" />
                {{ isOwner ? 'Cancelar evento' : 'Cancelar participación' }}
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>{{ isOwner ? '¿Seguro que quieres cancelar el evento?' : '¿Seguro que quieres cancelar tu participación?' }}</DialogTitle>
                <DialogDescription>
                    Esta acción no se puede deshacer. {{ isOwner ? 'Se notificará a todos los participantes.' : '' }}
                    Selecciona el motivo de la cancelación:
                </DialogDescription>
            </DialogHeader>
            <div class="py-6">
                <RadioGroup v-model="selectedReason" class="gap-6">
                    <div v-for="reason in cancelReasons" :key="reason.id" class="flex items-start space-x-4 space-y-0">
                        <RadioGroupItem :value="reason.id" :id="reason.id" />
                        <Label :for="reason.id" class="grid gap-1 font-normal">
                            <span class="font-semibold">{{ reason.label }}</span>
                            <span class="text-sm text-muted-foreground">{{ reason.description }}</span>
                            <span class="text-xs text-destructive">
                                Impacto en reputación: {{ reason.reputationImpact }}
                            </span>
                        </Label>
                    </div>
                </RadioGroup>
            </div>
            <DialogFooter>
                <Button variant="outline" @click="isOpen = false">
                    Cancelar
                </Button>
                <Button 
                    variant="destructive" 
                    :disabled="!selectedReason || isLoading"
                    @click="handleCancel"
                >
                    {{ isLoading ? 'Procesando...' : 'Confirmar cancelación' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template> 