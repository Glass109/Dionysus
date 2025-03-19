export interface Event {
    id: number;
    name: string;
    description: string;
    start: string;
    price: number;
    capacity: number;
    location_name: string;
    location_address: string;
    location_url?: string;
    image: string;
    image_thumbnail: string | null;
    image_large: string | null;
    image_webp: string | null;
    color: string;
    age_group: string;
    owner_id: number;
    owner?: {
        id: number;
        name: string;
    };
    status: 'ACTIVE' | 'CANCELLED' | 'EXPIRED';
    cancellation_reason?: string;
    cancelled_by?: number;
    cancelled_at?: string;
    tier: {
        tier: string;
    };
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface PageProps {
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
        };
    };
    [key: string]: any;
}

export interface EventFilters {
    age_group: string | null;
    min_price: number | null;
    max_price: number | null;
    start_date: string | null;
    end_date: string | null;
    sort_by: string;
    sort_direction: 'asc' | 'desc';
    min_tier: string;
} 