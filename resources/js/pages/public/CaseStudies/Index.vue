<template>
    <PublicLayout>
        <Head title="Case Studies - MBSC" />

        <div class="bg-muted/50 py-12">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <h1 class="mb-4 text-4xl font-bold">Case Studies</h1>
                    <p class="text-muted-foreground">
                        Discover how we've helped businesses achieve success
                    </p>
                </div>

                <!-- Filters -->
                <div class="mb-8 flex flex-wrap gap-4">
                    <div class="flex-1">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search case studies..."
                            class="w-full rounded-md border border-input bg-background px-4 py-2"
                            @input="handleSearch"
                        />
                    </div>
                    <select
                        v-model="selectedService"
                        class="rounded-md border border-input bg-background px-4 py-2"
                        @change="handleServiceChange"
                    >
                        <option value="">All Services</option>
                        <option
                            v-for="service in services || []"
                            :key="service.id"
                            :value="service.id"
                        >
                            {{ service.title }}
                        </option>
                    </select>
                </div>

                <!-- Case Studies Grid -->
                <div v-if="caseStudies?.data && caseStudies.data.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <CaseStudyCard
                        v-for="caseStudy in caseStudies.data"
                        :key="caseStudy.id"
                        :case-study="caseStudy"
                    />
                </div>
                <div v-else class="rounded-lg border border-border bg-card p-12 text-center">
                    <p class="text-muted-foreground">No case studies found.</p>
                </div>

                <!-- Pagination -->
                <div v-if="caseStudies.links && caseStudies.links.length > 3" class="mt-12 flex justify-center">
                    <div class="flex gap-2">
                        <Link
                            v-for="link in caseStudies.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            :class="[
                                'rounded-md px-4 py-2',
                                link.active
                                    ? 'bg-primary text-primary-foreground'
                                    : 'bg-background text-foreground hover:bg-muted',
                                !link.url && 'pointer-events-none opacity-50',
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import CaseStudyCard from '@/components/public/CaseStudyCard.vue';
import * as caseStudies from '@/routes/case-studies';

interface Props {
    caseStudies?: {
        data: Array<{
            id: number;
            slug: string;
            title: string;
            challenge: string;
            featured_image: string | null;
            service?: {
                title: string;
            } | null;
        }>;
        links?: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    services?: Array<{
        id: number;
        title: string;
    }>;
    filters?: {
        service?: number;
        search?: string;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters?.search || '');
const selectedService = ref(props.filters?.service?.toString() || '');

const handleSearch = () => {
    router.get(caseStudies.index().url, { search: search.value, service: selectedService.value || null }, {
        preserveState: true,
        replace: true,
    });
};

const handleServiceChange = () => {
    router.get(caseStudies.index().url, { search: search.value, service: selectedService.value || null }, {
        preserveState: true,
        replace: true,
    });
};
</script>
