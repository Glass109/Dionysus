<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head} from '@inertiajs/vue3';
import type {Event} from '@/types';
import EventShowcaseBanner from "@/components/EventShowcaseBanner.vue";
import {computed, onMounted, ref} from "vue";

defineProps<{
    events: Array<Event>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Eventos',
        href: '/events',
    },
];

const translations = [
    'Descubre nuevas aventuras',      // Spanish
    'Discover new adventures',         // English
    'Découvrez de nouvelles aventures', // French
    'Entdecke neue Abenteuer',        // German
    'Scopri nuove avventure',         // Italian
    'Ontdek nieuwe avonturen',        // Dutch
    '发现新的冒险',                    // Chinese Simplified
    '新しい冒険を見つけよう',           // Japanese
    'Descubra novas aventuras',       // Portuguese
    'Открой новые приключения',        // Russian
    'Odkryj nowe przygody',           // Polish
    'Upptäck nya äventyr',            // Swedish
    'Opdag nye eventyr',              // Danish
    'Löydä uusia seikkailuja',        // Finnish
    'Felfedez új kalandokat',         // Hungarian
    'Descopera noi aventuri',         // Romanian
    'Objav nové dobrodružstvá',       // Slovak
    'Keşfet yeni maceralar',          // Turkish
    'Objevte nová dobrodružství',     // Czech
    'Ανακαλύψτε νέες περιπέτειες',    // Greek
    'اكتشف مغامرات جديدة',           // Arabic
    'नई साहसिक यात्राएं खोजें',        // Hindi
    'ახალი თავგადასავლები აღმოაჩინე',  // Georgian
    'Khám phá cuộc phiêu lưu mới',    // Vietnamese
    'ค้นพบการผจญภัยใหม่',              // Thai
];
const currentTranslation = ref(0);

const displayedTranslation = computed(() => translations[currentTranslation.value]);

function cycleTranslation() {
    currentTranslation.value = (currentTranslation.value + 1) % translations.length;
}

// Start cycling every 3 seconds
onMounted(() => {
    setInterval(cycleTranslation, 3000);
});


</script>

<template>
    <Head title="Explore"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="relative">
            <Transition appear name="fade">
                <h1 class="title" :key="displayedTranslation">{{ displayedTranslation }}</h1>
            </Transition>
        </div>
        <Transition appear>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 items-center p-4">
                <EventShowcaseBanner v-for="event in events" :event="event"/>
            </div>
        </Transition>
    </AppLayout>
</template>

<style scoped>
.title {
    font-size: 4rem;
    font-weight: 600;
    background-clip: text;
    color: transparent;
    background-image: linear-gradient(90deg, #ff6b00, #ff9900, #ffcc00);
    background-size: 200% 200%;
    text-align: center;
    animation: fire 3s ease backwards infinite;
}

@keyframes fire {
    0% {
        background-position: 400%;
    }
    100% {
        background-position: 0;
    }
}

.v-enter-active,
.v-leave-active {
    @apply transition-all duration-500
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: scale(90%);
    filter: blur(30px);
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 1s;
    opacity: 100%;
}

.fade-enter-from, .fade-leave-to {
    position: absolute;
    opacity: 0;
    filter: blur(30px);
}

.fade-move {
    transition: transform 1s;
}

</style>
