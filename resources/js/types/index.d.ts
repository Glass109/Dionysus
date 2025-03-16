import type {PageProps} from '@inertiajs/core';
import type {LucideIcon} from 'lucide-vue-next';
import type {Config} from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}
export interface Event {
    id: number;
    owner_id: number
    name: string
    description: string
    start: Date
    price: number
    capacity: number
    location_name: string
    location_address: string
    location_url: string
    image: string
    age_group: | 'kids' | 'teens' | 'young' | 'adults'| 'middle' | 'seniors' | 'all' | 'other'
    color: string
    owner?: User
    participants?: User[]
}

export interface EventFilters {
    age_group: | 'kids' | 'teens' | 'young' | 'adults' | 'middle' | 'seniors' | 'all' | 'other' | '' | null;
    min_price: number | null;
    max_price: number | null;
    start_date: string | null;
    end_date: string | null;
    sort_by: string;
    sort_direction: 'asc' | 'desc';
}

export type BreadcrumbItemType = BreadcrumbItem;
