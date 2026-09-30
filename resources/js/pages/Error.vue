<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{ status: number }>();

const message = computed(
    () =>
        ({
            403: { title: 'You do not have access to this page', text: 'If you think this is a mistake, contact us.' },
            404: { title: 'This page could not be found', text: 'The link may be old or mistyped. The pages below are a good place to start again.' },
            503: { title: 'The site is being updated', text: 'Please try again in a few minutes.' },
        })[props.status] ?? { title: 'Something went wrong on our side', text: 'Please try again. If it keeps happening, call or message us.' },
);
</script>

<template>
    <Head :title="message.title" />

    <PublicLayout>
        <section class="py-section">
            <div class="site-container">
                <p class="font-display text-6xl font-medium text-rose">{{ status }}</p>
                <h1 class="site-display mt-4">{{ message.title }}</h1>
                <p class="site-lead mt-6">{{ message.text }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <Link href="/" class="site-btn site-btn-primary">Go to the home page</Link>
                    <Link href="/services" class="site-btn site-btn-secondary">See our services</Link>
                    <Link href="/contact" class="site-btn site-btn-secondary">Contact us</Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
