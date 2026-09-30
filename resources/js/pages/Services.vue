<script setup lang="ts">
import CtaBand from '@/components/site/CtaBand.vue';
import ServiceGrid, { type ServiceSummary } from '@/components/site/ServiceGrid.vue';
import SiteBreadcrumb from '@/components/site/SiteBreadcrumb.vue';
import SiteImage from '@/components/site/SiteImage.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { SiteImageData } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps<{
    hero: SiteImageData | null;
    services: ServiceSummary[];
}>();

// Older links pointed at /services#slug; send them to the service's own page.
onMounted(() => {
    const slug = window.location.hash.slice(1);

    if (slug && props.services.some((s) => s.slug === slug)) {
        router.visit(`/services/${slug}`, { replace: true });
    }
});
</script>

<template>
    <Head title="Services" />

    <PublicLayout current-page="services">
        <section class="pt-8 pb-12">
            <div class="site-container"><SiteBreadcrumb :items="[{ label: 'Services' }]" /></div>
            <div :class="['site-container mt-6 grid items-center gap-x-14 gap-y-10', hero && 'lg:grid-cols-2']">
                <div>
                    <h1 class="site-display">Services</h1>
                    <p class="site-lead mt-6">
                        Registration and annual compliance with RJSC, income tax, VAT, and audit support. Each service has its own page
                        listing what is covered.
                    </p>
                </div>
                <div v-if="hero" class="site-figure aspect-[3/2]">
                    <SiteImage :image="hero" eager sizes="(min-width: 1024px) 50vw, 72vw" />
                </div>
            </div>
        </section>

        <section class="pb-section">
            <div class="site-container">
                <ServiceGrid v-if="services.length" :services="services" heading-level="h2" />
                <p v-else class="site-prose">Our service list is being updated. Contact us to ask about a specific matter.</p>
            </div>
        </section>

        <CtaBand title="Not sure which service applies?" text="Describe your situation and we will tell you what is needed. The first consultation is free." />
    </PublicLayout>
</template>
