import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import {computed} from "vue";

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function ageGroupToRGBAColor(age_group: string): string {
    switch (age_group) {
        case 'kids':
            return 'rgba(255, 0, 0, 0.5)';      // red
        case 'teens':
            return 'rgba(0, 0, 255, 0.5)';      // blue
        case 'young':
            return 'rgba(0, 255, 0, 0.5)';      // green
        case 'adults':
            return 'rgba(255, 255, 0, 0.5)';    // yellow
        case 'middle':
            return 'rgba(255, 165, 0, 0.5)';    // orange
        case 'seniors':
            return 'rgba(128, 0, 128, 0.5)';    // purple
        case 'other':
            return 'rgba(128, 128, 128, 0.5)';  // gray
        default:
            return 'rgba(128, 128, 128, 0.5)';  // gray
    }
}

export const spanishMapping: { [key: string]: string } = {
    all: 'Todos',
    kids: 'Niños',
    teens: 'Adolescentes',
    young: 'Jóvenes',
    adults: 'Adultos',
    middle: 'Mediana edad',
    seniors: 'Adultos mayores',
    other: 'Otro'
};
