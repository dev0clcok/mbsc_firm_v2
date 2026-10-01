import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
    roles?: string[];
    permissions?: string[];
    is_super_admin?: boolean;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    /** Small count shown after the label, hidden when zero. */
    badge?: number;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    site: SiteSettings;
    sidebarOpen: boolean;
};

export interface SiteImageData {
    url: string;
    width: number | null;
    height: number | null;
    alt?: string | null;
}

export interface SiteSettings {
    name: string;
    phone: string | null;
    phone_href: string | null;
    whatsapp_url: string | null;
    whatsapp_base_url: string | null;
    email: string | null;
    address: string | null;
    maps_url: string | null;
    map_embed_url: string | null;
    office_hours: string | null;
    privacy_published: boolean;
    socials: Array<{ platform: string; url: string }>;
    services: Array<{ slug: string; title: string }>;
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

export type BreadcrumbItemType = BreadcrumbItem;
