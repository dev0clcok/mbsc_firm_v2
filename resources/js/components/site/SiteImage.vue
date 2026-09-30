<script setup lang="ts">
import type { SiteImageData } from '@/types';
import { computed } from 'vue';

/**
 * A stored picture with its smaller variant, fixed dimensions (no layout
 * shift) and lazy loading unless it is the page's first image.
 */
const props = withDefaults(
    defineProps<{
        image: SiteImageData;
        /** Overrides the description stored with the picture. */
        alt?: string;
        /**
         * How wide the picture is shown, as a `sizes` attribute. On phones use
         * about 72vw for a full-width picture: that keeps high-density screens on
         * the 800px file, which is already sharper than 2x at that size.
         */
        sizes?: string;
        /** Load immediately and with high priority: use for the image at the top of a page. */
        eager?: boolean;
    }>(),
    { alt: undefined, sizes: '100vw', eager: false },
);

// Pictures saved by the image store come in three widths: name-1280.webp, name-800.webp and name-480.webp.
const srcset = computed(() => {
    const { url, width } = props.image;

    if (!url.endsWith('-1280.webp')) return undefined;

    const variant = (w: number) => `${url.replace('-1280.webp', `-${w}.webp`)} ${w}w`;

    return `${variant(480)}, ${variant(800)}, ${url} ${width ?? 1280}w`;
});
</script>

<template>
    <img
        :src="image.url"
        :srcset="srcset"
        :sizes="srcset ? sizes : undefined"
        :width="image.width ?? undefined"
        :height="image.height ?? undefined"
        :alt="alt ?? image.alt ?? ''"
        :loading="eager ? 'eager' : 'lazy'"
        :fetchpriority="eager ? 'high' : undefined"
        decoding="async"
        class="h-full w-full object-cover"
    />
</template>
