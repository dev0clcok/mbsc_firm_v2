<template>
    <PublicLayout>
        <Head :title="`${caseStudy.title} - Case Study - MBSC`" />

        <div class="bg-muted/50 py-12">
            <div class="container mx-auto px-4">
                <article class="mx-auto max-w-4xl">
                    <!-- Breadcrumb -->
                    <nav class="mb-8 text-sm text-muted-foreground">
                        <Link :href="home().url" class="hover:text-primary">Home</Link>
                        <span class="mx-2">/</span>
                        <Link :href="caseStudies.index().url" class="hover:text-primary">Case Studies</Link>
                        <span class="mx-2">/</span>
                        <span class="text-foreground">{{ caseStudy.title }}</span>
                    </nav>

                    <!-- Featured Image -->
                    <div v-if="caseStudy.featured_image" class="mb-8 aspect-video w-full overflow-hidden rounded-lg bg-muted">
                        <img
                            :src="caseStudy.featured_image"
                            :alt="caseStudy.title"
                            class="h-full w-full object-cover"
                        />
                    </div>

                    <!-- Case Study Header -->
                    <header class="mb-8">
                        <div v-if="caseStudy.service" class="mb-4">
                            <span class="rounded-full bg-primary/10 px-3 py-1 text-sm font-medium text-primary">
                                {{ caseStudy.service.title }}
                            </span>
                        </div>
                        <h1 class="mb-4 text-4xl font-bold">{{ caseStudy.title }}</h1>
                    </header>

                    <!-- Challenge Section -->
                    <section class="mb-8 rounded-lg border border-border bg-card p-8">
                        <h2 class="mb-4 text-2xl font-semibold">Challenge</h2>
                        <p class="text-muted-foreground whitespace-pre-line">{{ caseStudy.challenge }}</p>
                    </section>

                    <!-- Solution Section -->
                    <section class="mb-8 rounded-lg border border-border bg-card p-8">
                        <h2 class="mb-4 text-2xl font-semibold">Solution</h2>
                        <p class="text-muted-foreground whitespace-pre-line">{{ caseStudy.solution }}</p>
                    </section>

                    <!-- Outcome Section -->
                    <section class="mb-8 rounded-lg border border-border bg-card p-8">
                        <h2 class="mb-4 text-2xl font-semibold">Outcome</h2>
                        <p class="text-muted-foreground whitespace-pre-line">{{ caseStudy.outcome }}</p>
                    </section>

                    <!-- Client Testimonial -->
                    <div v-if="caseStudy.client_testimonial" class="mb-8 rounded-lg border border-primary/20 bg-primary/5 p-8">
                        <h3 class="mb-4 text-xl font-semibold">Client Testimonial</h3>
                        <p class="italic text-muted-foreground">"{{ caseStudy.client_testimonial }}"</p>
                        <p v-if="caseStudy.client_name" class="mt-4 font-medium">
                            — {{ caseStudy.client_name }}
                        </p>
                    </div>

                    <!-- Related Case Studies -->
                    <div v-if="relatedCaseStudies.length > 0" class="mt-12">
                        <h2 class="mb-6 text-2xl font-semibold">Related Case Studies</h2>
                        <div class="grid gap-6 md:grid-cols-3">
                            <CaseStudyCard
                                v-for="related in relatedCaseStudies"
                                :key="related.id"
                                :case-study="related"
                            />
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import CaseStudyCard from '@/components/public/CaseStudyCard.vue';
import { home } from '@/routes';
import * as caseStudies from '@/routes/case-studies';

interface Props {
    caseStudy: {
        id: number;
        slug: string;
        title: string;
        challenge: string;
        solution: string;
        outcome: string;
        client_name: string | null;
        client_testimonial: string | null;
        featured_image: string | null;
        service?: {
            title: string;
        } | null;
    };
    relatedCaseStudies: Array<{
        id: number;
        slug: string;
        title: string;
        challenge: string;
        featured_image: string | null;
        service?: {
            title: string;
        } | null;
    }>;
}

defineProps<Props>();
</script>
