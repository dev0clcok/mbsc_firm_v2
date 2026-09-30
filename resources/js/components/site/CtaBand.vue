<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import { useSite } from '@/composables/useSite';
import { Link } from '@inertiajs/vue3';

/** Closing call to action used at the foot of content pages. */
defineProps<{ title: string; text?: string }>();

const site = useSite();
</script>

<template>
    <section class="site-on-ink bg-ink py-section text-white">
        <div class="site-container flex flex-wrap items-end justify-between gap-8">
            <div>
                <h2 class="font-display text-title font-medium tracking-tight text-balance">{{ title }}</h2>
                <p v-if="text" class="mt-3 max-w-[60ch] text-lead text-white/80">{{ text }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <Link href="/contact#enquiry" class="site-btn site-btn-primary">Send an enquiry</Link>
                <a v-if="site.phone && site.phone_href" :href="site.phone_href" class="site-btn site-btn-on-ink">
                    <SiteIcon name="phone" />
                    Call {{ site.phone }}
                </a>
            </div>
        </div>
    </section>
</template>
