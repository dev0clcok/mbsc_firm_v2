<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import { useSite } from '@/composables/useSite';
import { onBeforeUnmount, onMounted, ref } from 'vue';

/**
 * Corner buttons: WhatsApp (desktop only, phones have it in the bottom bar)
 * and back to top once the visitor has scrolled a screen or so.
 */
const site = useSite();
const scrolled = ref(false);

const onScroll = () => {
    scrolled.value = window.scrollY > 700;
};

const toTop = () => {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
    document.getElementById('main')?.focus({ preventScroll: true });
};

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
});

onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));
</script>

<template>
    <div class="fixed right-4 bottom-20 z-40 flex flex-col items-center gap-3 lg:right-6 lg:bottom-6">
        <button
            v-show="scrolled"
            type="button"
            aria-label="Back to top"
            class="flex h-12 w-12 items-center justify-center rounded-full border border-rule bg-paper text-ink shadow-raised transition-colors hover:bg-mist"
            @click="toTop"
        >
            <span class="-rotate-90"><SiteIcon name="chevron" /></span>
        </button>
        <a
            v-if="site.whatsapp_url"
            :href="site.whatsapp_url"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Message us on WhatsApp"
            class="hidden h-14 w-14 items-center justify-center rounded-full bg-whatsapp text-white shadow-raised transition-transform hover:scale-105 lg:flex [&>svg]:h-7 [&>svg]:w-7"
        >
            <SiteIcon name="whatsapp" />
        </a>
    </div>
</template>
