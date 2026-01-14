<template>
    <PublicLayout>
        <Head :title="`${service.title} - MBSC`" />

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-primary/20 to-primary/10 py-20">
            <div class="container mx-auto px-4">
                <div class="mx-auto max-w-4xl text-center">
                    <h1 class="mb-4 text-4xl font-bold md:text-5xl">
                        {{ service.title }} in Bangladesh
                    </h1>
                    <p v-if="service.description" class="text-lg text-muted-foreground">
                        {{ service.description }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Service Overview Section -->
        <section v-if="service.content" class="bg-background py-16">
            <div class="container mx-auto px-4">
                <div class="grid gap-8 lg:grid-cols-2">
                    <div class="flex items-center gap-6">
                        <div class="flex-shrink-0">
                            <div class="flex h-24 w-24 items-center justify-center rounded-lg bg-primary text-4xl font-bold text-primary-foreground">
                                11
                            </div>
                            <p class="mt-2 text-center text-sm font-medium">Years of Experience</p>
                        </div>
                        <div>
                            <h2 class="mb-4 text-2xl font-bold">{{ service.title }} - Accurate, Compliant and Hassle-Free</h2>
                            <p class="text-muted-foreground">
                                Your trusted partner for business consulting services in Bangladesh.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="h-64 w-full max-w-md rounded-lg bg-muted"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sub-services Section -->
        <section v-if="service.children && service.children.length > 0" class="bg-muted/50 py-16">
            <div class="container mx-auto px-4">
                <h2 class="mb-8 text-center text-3xl font-bold">Our {{ service.title }}</h2>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="child in service.children"
                        :key="child.id"
                        class="group overflow-hidden rounded-lg border border-border bg-card transition-all hover:shadow-lg"
                    >
                        <div class="h-2 bg-primary"></div>
                        <div class="p-6">
                            <h3 class="mb-2 text-lg font-semibold">{{ child.title }}</h3>
                            <p v-if="child.description" class="mb-4 text-sm text-muted-foreground">
                                {{ child.description }}
                            </p>
                            <Link
                                :href="services.child({ parent: service.slug, child: child.slug }).url"
                                class="text-sm font-medium text-primary hover:underline"
                            >
                                Learn More →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Section -->
        <section v-if="service.content" class="bg-background py-16">
            <div class="container mx-auto px-4">
                <div class="grid gap-8 lg:grid-cols-4">
                    <!-- Table of Contents -->
                    <aside class="lg:col-span-1">
                        <div class="sticky top-8 rounded-lg border border-border bg-card p-6">
                            <h3 class="mb-4 font-semibold">Table of Contents</h3>
                            <nav class="space-y-2">
                                <a
                                    v-for="(item, index) in tableOfContents"
                                    :key="index"
                                    :href="`#${item.id}`"
                                    class="block text-sm text-muted-foreground hover:text-primary"
                                    :class="{ 'ml-4': item.level === 2, 'ml-8': item.level === 3 }"
                                >
                                    {{ item.number }}. {{ item.text }}
                                </a>
                            </nav>
                        </div>
                    </aside>

                    <!-- Main Content -->
                    <div class="lg:col-span-3">
                        <div
                            class="prose prose-lg max-w-none rounded-lg border border-border bg-card p-8"
                            v-html="service.content"
                            ref="contentRef"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-primary/10 py-16">
            <div class="container mx-auto px-4">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="mb-4 text-3xl font-bold">Ready to Get Started?</h2>
                    <p class="mb-8 text-muted-foreground">
                        Contact us today to learn more about how we can help your business.
                    </p>
                    <Link
                        :href="contact.index().url"
                        class="inline-block rounded-md bg-primary px-6 py-3 text-primary-foreground hover:bg-primary/90"
                    >
                        Contact Us
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import * as services from '@/routes/services';
import * as contact from '@/routes/contact';

interface Props {
    service: {
        id: number;
        slug: string;
        title: string;
        description: string | null;
        content: string | null;
        icon: string | null;
        children?: Array<{
            id: number;
            slug: string;
            title: string;
            description?: string | null;
        }>;
    };
    parent?: {
        slug: string;
        title: string;
    } | null;
    relatedServices?: Array<{
        id: number;
        slug: string;
        title: string;
        description: string | null;
    }>;
}

withDefaults(defineProps<Props>(), {
    parent: null,
    relatedServices: () => [],
});

const contentRef = ref<HTMLElement | null>(null);

interface TOCItem {
    id: string;
    text: string;
    level: number;
    number: string;
}

const tableOfContents = computed<TOCItem[]>(() => {
    if (!contentRef.value) return [];

    const headings = contentRef.value.querySelectorAll('h1, h2, h3');
    const toc: TOCItem[] = [];
    let h1Count = 0;
    let h2Count = 0;
    let h3Count = 0;

    headings.forEach((heading) => {
        const level = parseInt(heading.tagName.charAt(1));
        const text = heading.textContent || '';
        const id = `heading-${toc.length}`;
        
        heading.id = id;

        let number = '';
        if (level === 1) {
            h1Count++;
            h2Count = 0;
            h3Count = 0;
            number = `${h1Count}`;
        } else if (level === 2) {
            h2Count++;
            h3Count = 0;
            number = `${h1Count}.${h2Count}`;
        } else if (level === 3) {
            h3Count++;
            number = `${h1Count}.${h2Count}.${h3Count}`;
        }

        toc.push({ id, text, level, number });
    });

    return toc;
});

onMounted(() => {
    // Add smooth scroll behavior
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach((link) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href')?.substring(1);
            const target = document.getElementById(targetId || '');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>

<style scoped>
.prose h1,
.prose h2,
.prose h3 {
    scroll-margin-top: 2rem;
}

.prose h1 {
    font-size: 1.875rem;
    line-height: 2.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
    margin-top: 2rem;
}

.prose h2 {
    font-size: 1.5rem;
    line-height: 2rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    margin-top: 1.5rem;
}

.prose h3 {
    font-size: 1.25rem;
    line-height: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    margin-top: 1rem;
}

.prose p {
    margin-bottom: 1rem;
    color: hsl(var(--muted-foreground));
}

.prose ul,
.prose ol {
    margin-bottom: 1rem;
    margin-left: 1.5rem;
}

.prose ul {
    list-style-type: disc;
}

.prose ol {
    list-style-type: decimal;
}

.prose table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.prose table th,
.prose table td {
    border: 1px solid hsl(var(--border));
    padding: 0.5rem 1rem;
}

.prose table th {
    background-color: hsl(var(--muted));
    font-weight: 600;
}
</style>
