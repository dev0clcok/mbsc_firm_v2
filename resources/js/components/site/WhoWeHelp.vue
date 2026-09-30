<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import { useSite } from '@/composables/useSite';
import { vReveal } from '@/directives/reveal';
import { Link } from '@inertiajs/vue3';
import { Building2, FilePlus2, UserRound } from 'lucide-vue-next';
import { computed } from 'vue';

/**
 * Three starting points for visitors who know their situation but not the
 * name of the service. Links are limited to services that are published.
 */
const site = useSite();

const paths = [
    {
        title: 'Starting a business',
        description: 'Register a company, partnership, foundation or society with RJSC.',
        icon: FilePlus2,
        slugs: ['rjsc-limited-company', 'rjsc-partnership-firm', 'foundation-trust-society'],
    },
    {
        title: 'Running a company',
        description: 'Keep VAT, corporate tax, annual returns and audits in order.',
        icon: Building2,
        slugs: ['vat', 'income-tax', 'audit-support'],
    },
    {
        title: 'Individual taxpayer',
        description: 'e-TIN, yearly returns, tax certificates and help with notices.',
        icon: UserRound,
        slugs: ['income-tax'],
    },
];

const visiblePaths = computed(() =>
    paths
        .map((path) => ({ ...path, services: site.value.services.filter((s) => path.slugs.includes(s.slug)) }))
        .filter((path) => path.services.length),
);
</script>

<template>
    <section v-if="visiblePaths.length" class="py-section" aria-labelledby="who-we-help">
        <div class="site-container">
            <h2 id="who-we-help" class="site-title">Who we help</h2>
            <ul class="mt-10 grid gap-x-10 gap-y-10 md:grid-cols-3">
                <li v-for="path in visiblePaths" :key="path.title" v-reveal class="border-t-2 border-ink pt-6">
                    <span class="site-icon-tile"><component :is="path.icon" :stroke-width="1.75" /></span>
                    <h3 class="site-heading mt-4">{{ path.title }}</h3>
                    <p class="mt-1 text-ink-soft">{{ path.description }}</p>
                    <ul class="mt-4 divide-y divide-rule border-y border-rule">
                        <li v-for="service in path.services" :key="service.slug">
                            <Link
                                :href="`/services/${service.slug}`"
                                class="group flex min-h-12 items-center justify-between gap-3 py-2 font-medium text-ink transition-colors hover:text-rose"
                            >
                                {{ service.title }}
                                <span class="text-ink-soft transition-transform group-hover:translate-x-1 group-hover:text-rose"><SiteIcon name="chevron" /></span>
                            </Link>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </section>
</template>
