<script setup lang="ts">
/** A titled card that groups related fields on an admin form. */
defineProps<{ title: string; description?: string }>();
</script>

<template>
    <section class="rounded-lg border border-border bg-card p-5 sm:p-6">
        <h2 class="text-lg font-semibold">{{ title }}</h2>
        <p v-if="description" class="mt-1 text-sm text-muted-foreground">{{ description }}</p>
        <div class="mt-5 space-y-5">
            <slot />
        </div>
    </section>
</template>
