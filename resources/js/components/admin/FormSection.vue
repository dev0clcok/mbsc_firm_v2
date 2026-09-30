<script setup lang="ts">
/**
 * A group of related fields on an admin form. On wide screens the title and
 * explanation sit to the left and the fields in a card to the right; on
 * narrow screens they stack.
 */
defineProps<{ title: string; description?: string }>();
</script>

<template>
    <section class="grid gap-x-10 gap-y-4 border-t border-border pt-8 first:border-t-0 first:pt-0 lg:grid-cols-[minmax(0,17rem)_minmax(0,1fr)]">
        <div>
            <h2 class="text-base font-semibold">{{ title }}</h2>
            <p v-if="description" class="mt-1 text-sm leading-relaxed text-muted-foreground">{{ description }}</p>
        </div>
        <div class="space-y-5 rounded-xl border border-border bg-card p-5 shadow-xs sm:p-6">
            <slot />
        </div>
    </section>
</template>
