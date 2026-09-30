<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import { Link } from '@inertiajs/vue3';

/** The firm's services as a ruled index: one row per service, each linking to its page. */
withDefaults(
    defineProps<{
        services: Array<{ slug: string; title: string; summary: string | null }>;
        /** Heading level for each service title, so the list fits the page outline. */
        headingLevel?: 'h2' | 'h3';
    }>(),
    { headingLevel: 'h3' },
);
</script>

<template>
    <ul class="divide-y divide-rule border-y border-rule">
        <li v-for="service in services" :key="service.slug">
            <Link
                :href="`/services/${service.slug}`"
                class="group flex items-center gap-4 py-5 transition-colors hover:bg-mist sm:gap-8 sm:px-4"
            >
                <div class="min-w-0 flex-1 sm:grid sm:grid-cols-[minmax(0,5fr)_minmax(0,6fr)] sm:items-baseline sm:gap-8">
                    <component :is="headingLevel" class="site-heading group-hover:text-rose">{{ service.title }}</component>
                    <p v-if="service.summary" class="mt-1 text-ink-soft sm:mt-0">{{ service.summary }}</p>
                </div>
                <span class="text-ink-soft transition-transform group-hover:translate-x-1 group-hover:text-rose"><SiteIcon name="chevron" /></span>
            </Link>
        </li>
    </ul>
</template>
