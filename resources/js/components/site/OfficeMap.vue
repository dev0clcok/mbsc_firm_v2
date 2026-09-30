<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import { useSite } from '@/composables/useSite';
import { ref } from 'vue';

/**
 * Google map of the office. The map is only requested from Google when
 * the visitor asks for it, which keeps the page fast and sends nothing to
 * a third party until then.
 */
const site = useSite();
const shown = ref(false);
</script>

<template>
    <div v-if="site.map_embed_url" :class="['site-figure', shown ? 'aspect-[4/3] sm:aspect-[16/9]' : 'min-h-64']">
        <iframe
            v-if="shown"
            :src="site.map_embed_url"
            title="Map showing the office location"
            class="h-full w-full border-0"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
        ></iframe>
        <div v-else class="flex min-h-64 flex-col items-center justify-center gap-4 p-6 text-center">
            <span class="site-icon-tile"><SiteIcon name="pin" /></span>
            <p v-if="site.address" class="max-w-[40ch] text-ink-soft">{{ site.address }}</p>
            <div class="flex flex-wrap justify-center gap-3">
                <button type="button" class="site-btn site-btn-secondary" @click="shown = true">Show map</button>
                <a v-if="site.maps_url" :href="site.maps_url" target="_blank" rel="noopener noreferrer" class="site-btn site-btn-secondary">Open in Google Maps</a>
            </div>
            <p class="text-sm text-ink-soft">The map is loaded from Google.</p>
        </div>
    </div>
</template>
