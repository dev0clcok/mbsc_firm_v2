<template>
    <PublicLayout>
        <Head title="About Us - MBSC" />

        <div class="bg-muted/50 py-12">
            <div class="container mx-auto px-4">
                <!-- Hero Section -->
                <div class="mb-12 text-center">
                    <h1 class="mb-4 text-4xl font-bold">About MODERN BUSINESS SOLUTION & CONSULTANCY</h1>
                    <p class="mx-auto max-w-3xl text-lg text-muted-foreground">
                        Your trusted partner for business consulting services in Bangladesh
                    </p>
                </div>

                <!-- Statistics -->
                <div v-if="statistics.length > 0" class="mb-16">
                    <div class="grid gap-8 md:grid-cols-4">
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

                <!-- Mission & Vision -->
                <div class="mb-16 grid gap-8 md:grid-cols-2">
                    <div class="rounded-lg border border-border bg-card p-8">
                        <h2 class="mb-4 text-2xl font-semibold">Our Mission</h2>
                        <p class="text-muted-foreground">
                            To provide premium consultation services that help businesses navigate the complex
                            regulatory and financial landscape of Bangladesh, ensuring compliance and fostering growth.
                        </p>
                    </div>
                    <div class="rounded-lg border border-border bg-card p-8">
                        <h2 class="mb-4 text-2xl font-semibold">Our Vision</h2>
                        <p class="text-muted-foreground">
                            To be the leading business consulting firm in Bangladesh, recognized for our commitment
                            to excellence, integrity, and delivering favorable outcomes for our clients.
                        </p>
                    </div>
                </div>

                <!-- Values -->
                <div class="mb-16">
                    <h2 class="mb-8 text-center text-3xl font-bold">We Believe In</h2>
                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="rounded-lg border border-border bg-card p-6 text-center">
                            <h3 class="mb-2 text-xl font-semibold">Premium Consultation</h3>
                            <p class="text-sm text-muted-foreground">
                                Expert advice tailored to your unique business needs
                            </p>
                        </div>
                        <div class="rounded-lg border border-border bg-card p-6 text-center">
                            <h3 class="mb-2 text-xl font-semibold">Powerful Commitment</h3>
                            <p class="text-sm text-muted-foreground">
                                Dedicated to your success with unwavering support
                            </p>
                        </div>
                        <div class="rounded-lg border border-border bg-card p-6 text-center">
                            <h3 class="mb-2 text-xl font-semibold">Favorable Outcome</h3>
                            <p class="text-sm text-muted-foreground">
                                Results-driven approach ensuring positive results
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Testimonials -->
                <div v-if="testimonials.length > 0" class="mb-16">
                    <h2 class="mb-8 text-center text-3xl font-bold">What Our Clients Say</h2>
                    <div class="grid gap-6 md:grid-cols-3">
                        <TestimonialCard
                            v-for="testimonial in testimonials"
                            :key="testimonial.id"
                            :testimonial="testimonial"
                        />
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="rounded-lg border border-border bg-primary/10 p-12 text-center">
                    <h2 class="mb-4 text-3xl font-bold">Ready to Get Started?</h2>
                    <p class="mb-8 text-muted-foreground">
                        Contact us today for expert guidance in business consultancy
                    </p>
                    <Link
                        :href="contact.index().url"
                        class="inline-block rounded-md bg-primary px-6 py-3 text-primary-foreground hover:bg-primary/90"
                    >
                        Contact Us
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import TestimonialCard from '@/components/public/TestimonialCard.vue';
import * as contact from '@/routes/contact';

interface Props {
    statistics: Array<{
        id: number;
        label: string;
        value: string;
    }>;
    testimonials: Array<{
        id: number;
        client_name: string;
        client_company?: string | null;
        testimonial: string;
        rating: number;
    }>;
}

defineProps<Props>();
</script>
