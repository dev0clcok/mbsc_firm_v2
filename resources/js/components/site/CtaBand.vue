<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import { useSite } from '@/composables/useSite';
import { Link } from '@inertiajs/vue3';

/** Closing call to action: a tinted panel that stands apart from the dark footer below it. */
defineProps<{ title: string; text?: string }>();

const site = useSite();
</script>

<template>
    <section class="pb-section">
        <div class="site-container">
            <div class="flex flex-wrap items-center justify-between gap-8 rounded-card border border-rose/15 bg-rose-tint px-6 py-10 sm:px-12 sm:py-14">
                <div>
                    <h2 class="site-title">{{ title }}</h2>
                    <p v-if="text" class="mt-3 max-w-[56ch] text-lead text-ink-soft">{{ text }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <Link href="/contact#enquiry" class="site-btn site-btn-primary">Send an enquiry</Link>
                    <a v-if="site.phone && site.phone_href" :href="site.phone_href" class="site-btn site-btn-secondary">
                        <SiteIcon name="phone" />
                        Call {{ site.phone }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
