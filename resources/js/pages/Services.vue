<script setup lang="ts">
import CtaBand from '@/components/site/CtaBand.vue';
import ServiceRegister from '@/components/site/ServiceRegister.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps<{
    services: Array<{ slug: string; title: string; summary: string | null }>;
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
        <section class="py-section">
            <div class="site-container">
                <h1 class="site-display">Services</h1>
                <p class="site-lead mt-6">
                    Registration and annual compliance with RJSC, income tax, VAT, and audit support. Each service has its own page
                    listing what is covered.
                </p>

                <ServiceRegister v-if="services.length" :services="services" heading-level="h2" class="mt-12" />
                <p v-else class="site-prose mt-12">Our service list is being updated. Contact us to ask about a specific matter.</p>
            </div>
        </section>

        <CtaBand title="Not sure which service applies?" text="Describe your situation and we will tell you what is needed. The first consultation is free." />
    </PublicLayout>
</template>
