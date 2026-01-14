<template>
    <div class="overflow-hidden rounded-lg border border-border bg-card transition-all hover:shadow-lg">
        <div v-if="caseStudy.featured_image" class="aspect-video w-full bg-muted">
            <img
                :src="caseStudy.featured_image"
                :alt="caseStudy.title"
                class="h-full w-full object-cover"
            />
        </div>
        <div class="p-6">
            <div v-if="caseStudy.service" class="mb-2">
                <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary">
                    {{ caseStudy.service.title }}
                </span>
            </div>
            <h3 class="mb-2 text-xl font-semibold">
                <Link
                    :href="caseStudies.show({ caseStudy: caseStudy.slug }).url"
                    class="hover:text-primary"
                >
                    {{ caseStudy.title }}
                </Link>
            </h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="font-medium">Challenge:</span>
                    <p class="mt-1 text-muted-foreground line-clamp-2">
                        {{ caseStudy.challenge }}
                    </p>
                </div>
            </div>
            <Link
                :href="caseStudies.show({ caseStudy: caseStudy.slug }).url"
                class="mt-4 inline-block text-sm font-medium text-primary hover:underline"
            >
                Read Case Study →
            </Link>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import * as caseStudies from '@/routes/case-studies';

interface Props {
    caseStudy: {
        id: number;
        slug: string;
        title: string;
        challenge: string;
        featured_image: string | null;
        service?: {
            title: string;
        } | null;
    };
}

defineProps<Props>();
</script>
