<script setup lang="ts">
import SiteLogoMark from '@/components/site/SiteLogoMark.vue';

/** Mark plus wordmark. Set `onInk` when it sits on the dark footer. */
defineProps<{ onInk?: boolean }>();
</script>

<template>
    <span :class="['inline-flex items-center gap-3', onInk ? 'text-white' : 'text-ink']">
        <SiteLogoMark class="h-11 w-auto shrink-0 sm:h-12" />
        <span class="flex flex-col leading-none">
            <span class="text-[1.625rem] font-extrabold tracking-tight">MBSC</span>
            <span :class="['mt-1 text-[0.625rem] font-bold tracking-[0.08em] whitespace-nowrap uppercase', onInk ? 'text-white/75' : 'text-ink-soft']">
                RJSC | Tax | Legal compliance
            </span>
        </span>
    </span>
</template>
