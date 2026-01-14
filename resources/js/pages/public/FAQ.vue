<template>
    <PublicLayout>
        <Head title="Frequently Asked Questions - MBSC" />

        <div class="bg-muted/50 py-12">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <h1 class="mb-4 text-4xl font-bold">Frequently Asked Questions</h1>
                    <p class="text-muted-foreground">
                        Find answers to common questions about our services
                    </p>
                </div>

                <!-- Filters -->
                <div class="mb-8 flex flex-wrap gap-4">
                    <div class="flex-1">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search FAQs..."
                            class="w-full rounded-md border border-input bg-background px-4 py-2"
                            @input="handleSearch"
                        />
                    </div>
                    <select
                        v-model="selectedCategory"
                        class="rounded-md border border-input bg-background px-4 py-2"
                        @change="handleCategoryChange"
                    >
                        <option value="">All Categories</option>
                        <option
                            v-for="category in categories"
                            :key="category"
                            :value="category"
                        >
                            {{ category }}
                        </option>
                    </select>
                    <select
                        v-model="selectedService"
                        class="rounded-md border border-input bg-background px-4 py-2"
                        @change="handleServiceChange"
                    >
                        <option value="">All Services</option>
                        <option
                            v-for="service in services"
                            :key="service.id"
                            :value="service.id"
                        >
                            {{ service.title }}
                        </option>
                    </select>
                </div>

                <!-- FAQs by Category -->
                <div v-if="Object.keys(faqsByCategory).length > 0" class="space-y-8">
                    <div
                        v-for="(faqs, category) in faqsByCategory"
                        :key="category"
                    >
                        <h2 class="mb-4 text-2xl font-semibold">
                            {{ category || 'General' }}
                        </h2>
                        <FAQAccordion :faqs="faqs" />
                    </div>
                </div>
                <div v-else class="rounded-lg border border-border bg-card p-12 text-center">
                    <p class="text-muted-foreground">No FAQs found matching your criteria.</p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import FAQAccordion from '@/components/public/FAQAccordion.vue';
import * as faq from '@/routes/faq';

interface Props {
    faqsByCategory: Record<string, Array<{
        id: number;
        question: string;
        answer: string;
    }>>;
    services: Array<{
        id: number;
        title: string;
    }>;
    categories: string[];
    filters: {
        category?: string;
        service?: number;
        search?: string;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const selectedService = ref(props.filters.service?.toString() || '');

const handleSearch = () => {
    router.get(faq.index().url, {
        search: search.value,
        category: selectedCategory.value || null,
        service: selectedService.value || null,
    }, {
        preserveState: true,
        replace: true,
    });
};

const handleCategoryChange = () => {
    router.get(faq.index().url, {
        search: search.value,
        category: selectedCategory.value || null,
        service: selectedService.value || null,
    }, {
        preserveState: true,
        replace: true,
    });
};

const handleServiceChange = () => {
    router.get(faq.index().url, {
        search: search.value,
        category: selectedCategory.value || null,
        service: selectedService.value || null,
    }, {
        preserveState: true,
        replace: true,
    });
};
</script>
