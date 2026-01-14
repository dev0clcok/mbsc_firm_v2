<template>
    <PublicLayout>
        <Head title="Home - MBSC" />

        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-primary/10 to-primary/5 py-20">
            <div class="container mx-auto px-4">
                <div class="mx-auto max-w-3xl text-center">
                    <h1 class="mb-6 text-4xl font-bold md:text-5xl">
                        Top Business Consulting Firm In Bangladesh
                    </h1>
                    <p class="mb-8 text-lg text-muted-foreground">
                        At MODERN BUSINESS SOLUTION & CONSULTANCY's guidance, you are safe from any kind of legal and financial trouble!
                        We are committed to providing logistical services designed to support your business at every
                        stage of its growth.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <Link
                            :href="contact.index().url"
                            class="rounded-md bg-primary px-6 py-3 text-primary-foreground hover:bg-primary/90"
                        >
                            Contact Us
                        </Link>
                        <Link
                            :href="serviceRoutes.index().url"
                            class="rounded-md border border-border bg-background px-6 py-3 hover:bg-muted"
                        >
                            Our Services
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section v-if="statistics.length > 0" class="py-16">
            <div class="container mx-auto px-4">
                <div class="grid gap-8 md:grid-cols-3">
                    <div
                        v-for="stat in statistics"
                        :key="stat.id"
                        class="text-center"
                    >
                        <div class="mb-2 text-4xl font-bold text-primary">{{ stat.value }}</div>
                        <div class="text-muted-foreground">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="bg-muted/50 py-16">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <h2 class="mb-4 text-3xl font-bold">Our Top Services</h2>
                    <p class="text-muted-foreground">
                        Comprehensive business solutions tailored to your needs
                    </p>
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <ServiceCard
                        v-for="service in services"
                        :key="service.id"
                        :service="service"
                    />
                </div>
                <div class="mt-12 text-center">
                    <Link
                        :href="serviceRoutes.index().url"
                        class="inline-block rounded-md bg-primary px-6 py-3 text-primary-foreground hover:bg-primary/90"
                    >
                        View All Services
                    </Link>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section v-if="testimonials.length > 0" class="py-16">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <h2 class="mb-4 text-3xl font-bold">What Our Clients Say</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    <TestimonialCard
                        v-for="testimonial in testimonials"
                        :key="testimonial.id"
                        :testimonial="testimonial"
                    />
                </div>
            </div>
        </section>

        <!-- Recent Blog Posts -->
        <section v-if="recentPosts.length > 0" class="bg-muted/50 py-16">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <h2 class="mb-4 text-3xl font-bold">Latest Blog</h2>
                    <p class="text-muted-foreground">Stay updated with our latest news and insights</p>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    <div
                        v-for="post in recentPosts"
                        :key="post.id"
                        class="overflow-hidden rounded-lg border border-border bg-card"
                    >
                        <div v-if="post.featured_image" class="aspect-video w-full bg-muted">
                            <img
                                :src="post.featured_image"
                                :alt="post.title"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="p-6">
                            <div class="mb-2 text-sm text-muted-foreground">
                                {{ formatDate(post.published_at) }}
                            </div>
                            <h3 class="mb-2 text-xl font-semibold">
                                <Link
                                    :href="blog.show({ post: post.slug }).url"
                                    class="hover:text-primary"
                                >
                                    {{ post.title }}
                                </Link>
                            </h3>
                            <p class="mb-4 text-sm text-muted-foreground line-clamp-2">
                                {{ post.excerpt }}
                            </p>
                            <Link
                                :href="blog.show({ post: post.slug }).url"
                                class="text-sm font-medium text-primary hover:underline"
                            >
                                Read More →
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="mt-12 text-center">
                    <Link
                        :href="blog.index().url"
                        class="inline-block rounded-md border border-border bg-background px-6 py-3 hover:bg-muted"
                    >
                        View All Posts
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import ServiceCard from '@/components/public/ServiceCard.vue';
import TestimonialCard from '@/components/public/TestimonialCard.vue';
import { home } from '@/routes';
import * as serviceRoutes from '@/routes/services';
import * as blog from '@/routes/blog';
import * as contact from '@/routes/contact';

interface Props {
    services: Array<{
        id: number;
        slug: string;
        title: string;
        description: string | null;
        icon: string | null;
        children?: Array<{ id: number; title: string }>;
    }>;
    statistics: Array<{
        id: number;
        label: string;
        value: string;
    }>;
    testimonials: Array<{
        id: number;
        client_name: string;
        testimonial: string;
        rating: number;
    }>;
    recentPosts: Array<{
        id: number;
        slug: string;
        title: string;
        excerpt: string | null;
        featured_image: string | null;
        published_at: string;
    }>;
}

defineProps<Props>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>
