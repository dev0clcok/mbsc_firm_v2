<template>
    <PublicLayout>
        <Head title="Our Services - MBSC" />

        <div class="bg-muted/50 py-12">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <h1 class="mb-4 text-4xl font-bold">Our Services</h1>
                    <p class="mx-auto max-w-3xl text-lg text-muted-foreground">
                        Comprehensive business solutions designed to support your business at every stage of its growth
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <ServiceCard
                        v-for="service in services"
                        :key="service.id"
                        :service="service"
                    />
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import ServiceCard from '@/components/public/ServiceCard.vue';

interface Props {
    services: Array<{
        id: number;
        slug: string;
        title: string;
        description: string | null;
        icon: string | null;
        children?: Array<{ id: number; title: string }>;
    }>;
}

defineProps<Props>();
</script>
