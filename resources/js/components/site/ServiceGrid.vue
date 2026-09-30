<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import SiteImage from '@/components/site/SiteImage.vue';
import { vReveal } from '@/directives/reveal';
import type { SiteImageData } from '@/types';
import { Link } from '@inertiajs/vue3';

export interface ServiceSummary {
    slug: string;
    title: string;
    summary: string | null;
    /** Trusted SVG markup entered in the admin panel. */
    icon: string | null;
    image: SiteImageData | null;
}

/** The firm's services as cards, each linking to the service's own page. */
withDefaults(
    defineProps<{
        services: ServiceSummary[];
        /** Heading level for each service title, so the grid fits the page outline. */
        headingLevel?: 'h2' | 'h3';
    }>(),
    { headingLevel: 'h3' },
);
</script>

<template>
    <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <li v-for="service in services" :key="service.slug" v-reveal>
            <Link :href="`/services/${service.slug}`" class="site-card site-card-link group flex h-full flex-col overflow-hidden">
                <div v-if="service.image" class="aspect-[3/2] overflow-hidden bg-mist">
                    <!-- 44vw keeps phones on the 480px file: card pictures are thumbnails, and lighter files leave bandwidth for the page's main image. -->
                    <SiteImage :image="service.image" sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 44vw" />
                </div>
                <div class="flex flex-1 flex-col p-6">
                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <span v-if="service.icon" class="site-icon-tile mb-4" v-html="service.icon"></span>
                    <component :is="headingLevel" class="site-heading">{{ service.title }}</component>
                    <p v-if="service.summary" class="mt-2 text-ink-soft">{{ service.summary }}</p>
                    <span class="mt-auto flex items-center gap-1 pt-5 font-semibold text-rose">
                        View service
                        <span class="transition-transform group-hover:translate-x-1"><SiteIcon name="chevron" /></span>
                    </span>
                </div>
            </Link>
        </li>
    </ul>
</template>
